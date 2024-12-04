<?php

namespace App\Services\Municipio;

use App\Providers\MunicipioProvider;
use Exception;

class MunicipioService
{

    protected $municipioProvider;

    public function __construct(MunicipioProvider $municipioProvider) {
        $this->municipioProvider = $municipioProvider;
    }

    public function getMunicipios($request, $uf)
    {
        try{
            $provider = env('MUNICIPIOS_PROVIDER', 'ibge');
 
            $data = match ($provider) {
                'ibge' => $this->municipioProvider->fetchFromIbge($uf),
                'brasilapi' => $this->municipioProvider->fetchFromBrasilApi($uf),
                default => throw new Exception("Provedor $provider não suportado."),
            };

            $result = $this->normalizeMunicipios($data, $provider);

            return [
                'status' => true,
                'data' => $result
            ];
        }catch(Exception $error){
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => $error->getCode()
            ];
        }
    }

    private function normalizeMunicipios(array $data, string $provider)
    {
        if ($provider === 'ibge') {
            return collect($data)->map(function ($municipio) {
                return [
                    'name' => $municipio['nome'],
                    'ibge_code' => $municipio['id'],
                ];
            })->toArray();
        }

        if ($provider === 'brasilapi') {
            return collect($data)->map(function ($municipio) {
                return [
                    'name' => mb_convert_case(strtolower($municipio['nome']), MB_CASE_TITLE, "UTF-8"),
                    'ibge_code' => $municipio['codigo_ibge'],
                ];
            })->toArray();
        }

        return [];
    }
}

