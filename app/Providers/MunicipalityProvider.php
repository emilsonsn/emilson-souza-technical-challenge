<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Http;

class MunicipalityProvider
{
    public function fetchFromIbge(string $uf)
    {
        $url = config('services.providers.ibge') . "/$uf/municipios";
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new Exception("Erro ao buscar municípios do IBGE", 400);
        }

        return $response->json();
    }

    public function fetchFromBrasilApi(string $uf)
    {
        $url = config('services.providers.brasilapi') . "/$uf";
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new Exception("Erro ao buscar municípios da Brasil API", 400);
        }

        return $response->json();
    }
}