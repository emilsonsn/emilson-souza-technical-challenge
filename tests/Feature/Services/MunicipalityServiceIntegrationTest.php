<?php

namespace Tests\Integration\Services;

use App\Services\Municipality\MunicipalityService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Tests\TestCase;

class MunicipalityServiceIntegrationTest extends TestCase
{
    protected MunicipalityService $municipalityService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->municipalityService = app(MunicipalityService::class);
    }

    public function test_common_request_returns_paginated_response()
    {
        Http::fake([
            'https://servicodados.ibge.gov.br/*' => Http::response([
                ['id' => 1200013, 'nome' => 'Acrelândia'],
                ['id' => 1200054, 'nome' => 'Assis Brasil'],
            ], 200),
        ]);

        $request = Request::create('/api/municipios', 'GET', ['take' => 2, 'page' => 1]);
        $uf = 'AC';

        $result = $this->municipalityService->getMunicipalities($request, $uf);

        $this->assertTrue($result['status']);
        $this->assertCount(2, $result['data']->items());
        $this->assertEquals('Acrelândia', $result['data']->items()[0]['name']);
    }

    public function test_request_with_filter_applies_correctly()
    {
        Http::fake([
            'https://servicodados.ibge.gov.br/*' => Http::response([
                ['id' => 1200013, 'nome' => 'Acrelândia'],
                ['id' => 1200054, 'nome' => 'Assis Brasil'],
                ['id' => 1200202, 'nome' => 'Rio Branco'],
            ], 200),
        ]);

        $request = Request::create('/api/municipios', 'GET', ['search_term' => 'Acrelâ', 'take' => 10, 'page' => 1]);
        $uf = 'AC';

        $result = $this->municipalityService->getMunicipalities($request, $uf);

        $this->assertTrue($result['status']);
        $this->assertCount(1, $result['data']->items());
        $this->assertEquals('Acrelândia', $result['data']->items()[0]['name']);
    }

    public function test_request_with_invalid_uf_returns_error()
    {
        $request = Request::create('/api/municipios', 'GET', []);
        $uf = 'XX';

        $result = $this->municipalityService->getMunicipalities($request, $uf);

        $this->assertFalse($result['status']);
        $this->assertEquals('UF inválida: XX', $result['error']);
        $this->assertEquals(400, $result['statusCode']);
    }
}
