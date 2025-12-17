<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest\ExpenseFilterRequest;
use App\Http\Requests\ExpenseRequest\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Responses\APIResponse;
use App\Services\ExpenseService\IExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected IExpenseService $expenseService;

    public function __construct(IExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(ExpenseFilterRequest $request)
    {
        $filters = $request->validated();
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));

        $expenses = $this->expenseService->getExpenseByFilters($filters, $perPage);

        return APIResponse::paginated(ExpenseResource::collection($expenses['expenses'])->additional([
            'summary' => [
                'total_paid' => (float)$expenses['summary']->paid,
                'total_expect' => (float)$expenses['summary']->total_expect,
            ]
        ]));
    }

    public function store(ExpenseRequest $request)
    {
        $data = $request->validated();
        $expense = $this->expenseService->createExpense($data);
        return APIResponse::success($expense);
    }

    public function update(ExpenseRequest $request, string $id)
    {
        $data = $request->validated();
        $expense = $this->expenseService->updateExpense($id, $data);
        return APIResponse::success(new ExpenseResource($expense));
    }

    public function delete(string $id)
    {
        $result = $this->expenseService->deleteExpense($id);
        return APIResponse::success('Xóa thành công');
    }

    public function approveExpense(Request $request)
    {
        $approvedBy = jwt_claim('sub');
        $listExpense = $request->input('listExpense');
        $expense = $this->expenseService->approveExpense($listExpense, $approvedBy);

        return APIResponse::success($expense);
    }

}
