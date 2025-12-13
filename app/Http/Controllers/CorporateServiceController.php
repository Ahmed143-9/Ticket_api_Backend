<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorporateService;
use App\Http\Requests\Modules\Configuration\CorporateService\CorporateServiceCreateRequest;
use App\Http\Requests\Modules\Configuration\CorporateService\CorporateServiceUpdateRequest;
use App\Contracts\Configuration\CorporateServiceInterface;

class CorporateServiceController extends Controller
{
    protected $corporateService;

    public function __construct(CorporateServiceInterface $corporateService)
    {
        $this->corporateService = $corporateService;
    }

    public function index(Request $request)
    {
        return response()->json($this->corporateService->paginate($request));
    }

    public function show($id)
    {
        return response()->json($this->corporateService->show($id));
    }

    public function store(CorporateServiceCreateRequest $request)
    {
        return response()->json($this->corporateService->store($request));
    }

    public function update(CorporateServiceUpdateRequest $request)
    {
        return response()->json($this->corporateService->update($request));
    }

    public function destroy($id)
    {
        return response()->json($this->corporateService->delete($id));
    }
}