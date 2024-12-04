<?php

namespace App\Services\Municipality;

use App\Providers\MunicipalityProvider;
use App\Utils\StateValidator;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class MunicipalityService
{

    protected $municipalityProvider;

    public function __construct(MunicipalityProvider $municipalityProvider) {
        $this->municipalityProvider = $municipalityProvider;
    }

    public function getMunicipalities($request, $uf)
    {
        try{
            StateValidator::validate($uf);

            $provider = env('MUNICIPALITIES_PROVIDER', 'ibge');

            $searchTerm = $request->search_term ?? '';
            $take = (int) ($request->take ?? 10);
            $page = (int) ($request->page ?? 1);
 
            $data = match ($provider) {
                'ibge' => $this->municipalityProvider->fetchFromIbge($uf),
                'brasilapi' => $this->municipalityProvider->fetchFromBrasilApi($uf),
                default => throw new Exception("Provedor $provider não suportado.", 400),
            };

            $normalizedMunicipalitie = $this->normalizeMunicipalities($data, $provider);
            
            $filteredMunicipalities = $this->filterMunicipalities($normalizedMunicipalitie, $searchTerm);

            $paginatedMunicipalities = $this->paginate($filteredMunicipalities, $take, $page);

            return [
                'status' => true,
                'data' => $paginatedMunicipalities
            ];
        }catch(Exception $error){
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }

    private function normalizeMunicipalities(array $data, string $provider)
    {
        $normalizedMunicipalitie = match ($provider) {
            'ibge' => 
                collect($data)->map(function ($municipio) {
                    return [
                        'name' => $municipio['nome'],
                        'ibge_code' => $municipio['id'],
                    ];
                })->toArray(),

            'brasilapi' =>
                collect($data)->map(function ($municipio) {
                    return [
                        'name' => mb_convert_case(strtolower($municipio['nome']), MB_CASE_TITLE, "UTF-8"),
                        'ibge_code' => $municipio['codigo_ibge'],
                    ];
                })->toArray(),

            default => [],
        };

        return $normalizedMunicipalitie;
    }

    private function filterMunicipalities(array $municipalities, string $searchTerm){
        if ($municipalities) {
            $Municipalities = array_filter($municipalities, function ($municipio) use ($searchTerm) {
                return stripos($municipio['name'], $searchTerm) !== false;
            });
        }

        return $Municipalities;
    }

    private function paginate(array $municipalities, int $perPage, int $currentPage)
    {
        $currentPage = max(1, $currentPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedMunicipalities = array_slice($municipalities, $offset, $perPage);

        return new LengthAwarePaginator(
            $paginatedMunicipalities,
            count($paginatedMunicipalities),
            $perPage,    
            $currentPage,
            ['path' => url()->current()]
        );
    }
}

