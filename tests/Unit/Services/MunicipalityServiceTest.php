<?php

namespace Tests\Unit\Services;

use App\Providers\MunicipalityProvider;
use App\Services\Municipality\MunicipalityService;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Http\Request;

class MunicipalityServiceTest extends TestCase
{
    protected $municipalityService;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \PHPUnit\Framework\MockObject\MockObject|MunicipalityProvider $mockProvider */
        $mockProvider = $this->createMock(MunicipalityProvider::class);

        $mockProvider->method('fetchFromIbge')->willReturn([
            ['id' => 1200013, 'nome' => 'Acrelândia'],
            ['id' => 1200054, 'nome' => 'Assis Brasil'],
        ]);
    
        $mockProvider->method('fetchFromBrasilApi')->willReturn([
            ['codigo_ibge' => '1200013', 'nome' => 'Acrelândia'],
            ['codigo_ibge' => '1200054', 'nome' => 'Assis Brasil'],
        ]);
        
        $this->municipalityService = new MunicipalityService($mockProvider);
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
        $this->assertInstanceOf(LengthAwarePaginator::class, $result['data']);
        $this->assertCount(2, $result['data']->items());
        $this->assertEquals('Acrelândia', $result['data']->items()[0]['name']);
    }

    public function test_request_with_filter()
    {
        Http::fake([
            'https://servicodados.ibge.gov.br/*' => Http::response([
                ['id' => 1200013, 'nome' => 'Acrelândia'],
                ['id' => 1200054, 'nome' => 'Assis Brasil'],
                ['id' => 1200202, 'nome' => 'Rio Branco'],
            ], 200),
        ]);

        $request = Request::create('/api/municipios', 'GET', ['search_term' => 'Acre', 'take' => 10, 'page' => 1]);
        $uf = 'AC';

        $result = $this->municipalityService->getMunicipalities($request, $uf);

        $this->assertTrue($result['status']);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result['data']);
        $this->assertCount(1, $result['data']->items());
        $this->assertEquals('Acrelândia', $result['data']->items()[0]['name']);
    }

    public function test_request_with_invalid_uf_returns_error_response()
    {
        $request = \Illuminate\Http\Request::create('/api/municipios', 'GET', []);
        $uf = 'XX';
    
        $result = $this->municipalityService->getMunicipalities($request, $uf);
    
        $this->assertFalse($result['status']);
        $this->assertEquals('UF inválida: XX', $result['error']);
        $this->assertEquals(400, $result['statusCode']);
    }
}
