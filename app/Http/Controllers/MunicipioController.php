<?php

namespace App\Http\Controllers;

use App\Services\Municipio\MunicipioService;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    protected $municipioService;

    public function __construct(MunicipioService $municipioService) {
        $this->municipioService = $municipioService;
    }

    public function index(Request $request, string $uf)
    {
        return response()->json($this->municipioService->getMunicipios($request, $uf));
    }
}
