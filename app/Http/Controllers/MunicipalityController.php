<?php

namespace App\Http\Controllers;

use App\Services\Municipalitie\MunicipalitieService;
use App\Services\Municipality\MunicipalityService;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    protected $municipalityService;

    public function __construct(MunicipalityService $municipalityService) {
        $this->municipalityService = $municipalityService;
    }

    public function index(Request $request, string $uf)
    {
        return response()->json($this->municipalityService->getMunicipalities($request, $uf));
    }
}
