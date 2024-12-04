<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;

class MunicipioProvider
{
    public function fetchFromIbge(string $uf)
    {
        $url = config('services.providers.ibge') . "/$uf/municipios";
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception("Erro ao buscar municípios do IBGE");
        }

        return $response->json();
    }

    public function fetchFromBrasilApi(string $uf)
    {
        $url = config('services.providers.brasilapi') . "/$uf";
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception("Erro ao buscar municípios da Brasil API");
        }

        return $response->json();
    }
}
