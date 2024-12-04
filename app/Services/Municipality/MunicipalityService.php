<?php

namespace App\Services\Municipality;

use App\Providers\MunicipalityProvider;
use Exception;

class MunicipalityService
{

    protected $municipalityProvider;

    public function __construct(MunicipalityProvider $municipalityProvider) {
        $this->municipalityProvider = $municipalityProvider;
    }

    public function getMunicipalities($request, $uf)
    {
        try{
            $provider = env('MUNICIPALITIES_PROVIDER', 'ibge');
 
            $data = match ($provider) {
                'ibge' => $this->municipalityProvider->fetchFromIbge($uf),
                'brasilapi' => $this->municipalityProvider->fetchFromBrasilApi($uf),
                default => throw new Exception("Provedor $provider não suportado."),
            };

            $result = $this->normalizeMunicipalities($data, $provider);
            
            $municipalities = $this->filterMunicipalities($result, $request->search_term ?? null);            

            return [
                'status' => true,
                'data' => $municipalities
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

    private function filterMunicipalities(array $municipalities, string|null $searchTerm){
        if ($municipalities) {
            $Municipalities = array_filter($municipalities, function ($municipio) use ($searchTerm) {
                return stripos($municipio['name'], $searchTerm) !== false;
            });
        }

        return $Municipalities;
    }

}

