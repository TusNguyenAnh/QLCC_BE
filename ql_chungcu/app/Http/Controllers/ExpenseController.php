<?php

namespace App\Http\Controllers;

use App\Factory\ExpenseFactory;
use App\Http\Requests\ExpenseRequest\ExpenseFilterRequest;
use App\Http\Requests\ExpenseRequest\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Responses\APIResponse;
use App\Services\ComplexService\IComplexService;
use App\Services\ExpenseService\IExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseFactory $factory;
    protected IComplexService $complexService;

    public function __construct(ExpenseFactory $factory, IComplexService $complexService)
    {
        $this->factory = $factory;
        $this->complexService = $complexService;
    }

    public function index(ExpenseFilterRequest $request)
    {
        $filters = $request->validated();
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));

        $complexId = jwt_claim('complex_id');
        //lay model
        $finanModel = $this->complexService->findById($complexId)->financial_model;
        //tao doi tuong sd factory
        $expense = $this->factory->make($finanModel);

        $expenses = $expense->getExpenseByFilters($filters, $perPage, $complexId);

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
        //lay model
        $finanModel = $this->complexService->findById(jwt_claim('complex_id'))->financial_model;
        //tao doi tuong sd factory
        $expense = $this->factory->make($finanModel);

        $expenses = $expense->createExpense($data);
        return APIResponse::success($expenses);
    }

    public function update(ExpenseRequest $request, string $id)
    {
        $data = $request->validated();
        //lay model
        $finanModel = $this->complexService->findById(jwt_claim('complex_id'))->financial_model;
        //tao doi tuong sd factory
        $expense = $this->factory->make($finanModel);

        $expense = $expense->updateExpense($id, $data);
        return APIResponse::success(new ExpenseResource($expense));
    }

    public function delete(string $id)
    {
        //lay model
        $finanModel = $this->complexService->findById(jwt_claim('complex_id'))->financial_model;
        //tao doi tuong sd factory
        $expense = $this->factory->make($finanModel);

        $result = $expense->deleteExpense($id);
        return APIResponse::success('Xóa thành công');
    }

    public function approveExpense(Request $request)
    {
        $approvedBy = jwt_claim('sub');
        $listExpense = $request->input('listExpense');
        //lay model
        $finanModel = $this->complexService->findById(jwt_claim('complex_id'))->financial_model;
        //tao doi tuong sd factory
        $expense = $this->factory->make($finanModel);

        $expense = $expense->approveExpense($listExpense, $approvedBy);

        return APIResponse::success($expense);
    }
}
