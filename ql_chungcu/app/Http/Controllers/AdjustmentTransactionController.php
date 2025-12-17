<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdjustmentRequest\AdjustmentRequest;
use App\Http\Resources\AdjustmentTransactionResource;
use App\Models\AdjustmentTransaction;
use App\Responses\APIResponse;
use App\Services\AdjustmentTransactionService\IAdjustmentTransactionService;
use Illuminate\Http\Request;

class AdjustmentTransactionController extends Controller
{
    protected IAdjustmentTransactionService $adjustmentService;

    public function __construct(IAdjustmentTransactionService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    public function getByReference(string $referenceId)
    {
        $adjustments = $this->adjustmentService->getAdjustmentsByLedger($referenceId);
        return APIResponse::success(AdjustmentTransactionResource::collection($adjustments));
    }

    public function store(AdjustmentRequest $request, string $ledgerId)
    {
        $data = $request->validated();
        $data['created_by'] = jwt_claim('sub');
        $data['ledger_id'] = $ledgerId;
        $data['complex_id'] =  jwt_claim('complex_id');

        $adjustment = $this->adjustmentService->createAdjustment($data);
        return APIResponse::success(new AdjustmentTransactionResource($adjustment));
    }

}
