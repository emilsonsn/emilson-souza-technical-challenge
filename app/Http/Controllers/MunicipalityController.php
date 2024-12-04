<?php

namespace App\Http\Controllers;

use App\Services\Municipality\MunicipalityService;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    protected $municipalityService;

    public function __construct(MunicipalityService $municipalityService) {
        $this->municipalityService = $municipalityService;
    }

    /**
     * Retorna a lista de municípios.
     *
     * @group Municípios
     * 
     * @urlParam uf string required A sigla da UF (estado). Example: pb
     * @queryParam search_term string optional O termo para buscar municípios. Example: João
     * @queryParam take int optional O número de itens por página. Default: 10. Example: 10
     * @queryParam page int optional A página atual. Default: 1. Example: 1
     *
     * @response 200 {
     *    "status": true,
     *    "data": {
     *        "current_page": 1,
     *        "data": [
     *            {
     *                "name": "São João Do Rio Do Peixe",
     *                "ibge_code": "2500700"
     *            },
     *        ],
     *        "first_page_url": "http://127.0.0.1:8000/api/municipios/pb?page=1",
     *        "from": 1,
     *        "last_page": 1,
     *        "last_page_url": "http://127.0.0.1:8000/api/municipios/pb?page=1",
     *        "links": [
     *            {
     *                "url": null,
     *                "label": "&laquo; Previous",
     *                "active": false
     *            },
     *            {
     *                "url": "http://127.0.0.1:8000/api/municipios/pb?page=1",
     *                "label": "1",
     *                "active": true
     *            },
     *            {
     *                "url": null,
     *                "label": "Next &raquo;",
     *                "active": false
     *            }
     *        ],
     *        "next_page_url": null,
     *        "path": "http://127.0.0.1:8000/api/municipios/pb",
     *        "per_page": 4,
     *        "prev_page_url": null,
     *        "to": 3,
     *        "total": 3
     *    }
     * }
     */

    public function index(Request $request, string $uf)
    {
        return response()->json($this->municipalityService->getMunicipalities($request, $uf));
    }
}
