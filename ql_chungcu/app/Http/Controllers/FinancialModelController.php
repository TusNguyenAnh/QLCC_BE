<?php

namespace App\Http\Controllers;

use App\Factory\FinancialModelFactory;
use App\Repositories\FinancialModelRepository\FinancialModelRepository;
use App\Responses\APIResponse;
use Illuminate\Http\Request;

class FinancialModelController extends Controller
{
    protected FinancialModelFactory $factory;
    public function __construct(FinancialModelFactory $factory)
    {
        $this->factory = $factory;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['complex_id'] = jwt_claim('complex_id');
        if (isset($data['type'])) {
            $model = $this->factory->make($data['type']);
            // Xử lý nghiệp vụ
            $model->setupFinancialModel($data);
        }
        return APIResponse::success('Cấu hình mô hình tài chính thành công!');
    }
}
