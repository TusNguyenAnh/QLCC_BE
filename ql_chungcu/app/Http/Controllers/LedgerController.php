<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerRequest\LedgerFilterRequest;
use App\Http\Requests\LedgerRequest\LedgerRequest;
use App\Http\Resources\LedgerResource;
use App\Responses\APIResponse;
use App\Services\LedgerService\ILedgerService;
use App\Services\LedgerService\ILedgerSummaryService;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    protected ILedgerService $ledgerService;
    protected ILedgerSummaryService $ledgerSummaryService;

    public function __construct(ILedgerService $ledgerService, ILedgerSummaryService $ledgerSummaryService)
    {
        $this->ledgerService = $ledgerService;
        $this->ledgerSummaryService = $ledgerSummaryService;
    }

    public function index(LedgerFilterRequest $request)
    {
        $filters = $request->validated();
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $complexId = jwt_claim('complex_id');

        $ledgers = $this->ledgerService->getLedgerByFilters($filters, $perPage,$complexId);
        return APIResponse::paginated(LedgerResource::collection($ledgers));
    }

    public function storeRevenue(string $revenueId, LedgerRequest $request)
    {
        $data = $request->validated();
        $data['complex_id'] = jwt_claim('complex_id');
        $revenue = $this->ledgerService->storeRevenue($revenueId, $data);
        return APIResponse::success(new LedgerResource($revenue));
    }

    public function storeExpense(string $expenseId, LedgerRequest $request)
    {
        $data = $request->validated();
        $data['complex_id'] = jwt_claim('complex_id');
        $expense = $this->ledgerService->storeExpense($expenseId, $data);
        return APIResponse::success(new LedgerResource($expense));
    }

    public function createLedgerSummary(Request $request)
    {
        $data['year'] = $request->input('year');
        $data['month'] = $request->input('month');
        $data['complex_id'] = jwt_claim('complex_id');
        $ledgerSummary = $this->ledgerSummaryService->createLedgerSummary($data);
        return APIResponse::success($ledgerSummary);
    }

    public function updateLedgerSummary(Request $request)
    {
        $data['year'] = $request->input('year');
        $data['month'] = $request->input('month');
        $data['complex_id'] = jwt_claim('complex_id');
        $ledgerSummary = $this->ledgerSummaryService->updateManyLedgerSummary($data);
        return APIResponse::success($ledgerSummary);
    }

}
