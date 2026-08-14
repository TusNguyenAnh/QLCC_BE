<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest\ExcelFileRequest;
use App\Http\Requests\MoneyAccountRequest\MoneyAccountRequest;
use App\Http\Resources\MoneyAccountResource;
use App\Responses\APIResponse;
use App\Services\MoneyAccountService\IMoneyAccountService;
use Illuminate\Http\Request;

class MoneyAccountController extends Controller
{
    protected IMoneyAccountService $moneyAccountService;

    public function __construct(IMoneyAccountService $moneyAccountService)
    {
        $this->moneyAccountService = $moneyAccountService;
    }

    public function findByBuildingId(string $bdId)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(MoneyAccountResource::collection($this->moneyAccountService->findByBuildingId($bdId, $perPage)));
    }

    public function store(MoneyAccountRequest $moneyAccountRequest)
    {
        $data = $moneyAccountRequest->validated();
        $data["complex_id"] = jwt_claim('complex_id');
        $moneyAccount = $this->moneyAccountService->add($data);
        return APIResponse::success(new MoneyAccountResource($moneyAccount));
    }

    public function importMoneyAccExcel(ExcelFileRequest $request)
    {
        $file = $request->file('files');

        $result = $this->moneyAccountService->importMoneyAccountFromExcel($file);

        if ($result['success']) {
            return APIResponse::success($result);
        } else {
            return APIResponse::error($result['message'], 400);
        }
    }
}
