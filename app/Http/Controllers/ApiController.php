<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected ApiService $apiService;
    protected array $validationRules = [];
    protected array $validationMessages = [];

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    protected function buildIndexQuery(Request $request)
    {
        $params = [];
        if ($request->has('per_page')) {
            $params['per_page'] = $request->get('per_page');
        }
        if ($request->has('page')) {
            $params['page'] = $request->get('page');
        }
        if ($request->has('search')) {
            $params['search'] = $request->get('search');
        }
        return $params;
    }

    protected function getCreateViewData(): array
    {
        return [];
    }

    protected function getEditViewData($id): array
    {
        return $this->getCreateViewData();
    }

    protected function validateRequest(Request $request)
    {
        return $this->validate($request, $this->validationRules, $this->validationMessages);
    }
}