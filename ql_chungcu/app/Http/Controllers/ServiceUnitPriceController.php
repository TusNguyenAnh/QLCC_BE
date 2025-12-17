<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceUnitPriceRequest\ServiceUnitPriceRequest;
use App\Http\Resources\ServiceUnitPriceResource;
use App\Responses\APIResponse;
use App\Services\ServiceUnitPriceService\IServiceUnitPriceService;
use Illuminate\Http\Request;

class ServiceUnitPriceController extends Controller
{
    protected IServiceUnitPriceService $serviceUnitPriceService;

    public function __construct(IServiceUnitPriceService $serviceUnitPriceService)
    {
        $this->serviceUnitPriceService = $serviceUnitPriceService;
    }

    /**
     * Lấy tất cả đơn giá dịch vụ
     */
    public function index()
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $complexId = jwt_claim('complex_id');
        $prices = $this->serviceUnitPriceService->getAll($perPage, $complexId);
        return APIResponse::success(ServiceUnitPriceResource::collection($prices));
    }

    /**
     * Lấy đơn giá theo năm
     */
    public function getByYear(int $year)
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        $complexId = jwt_claim('complex_id');
        $price = $this->serviceUnitPriceService->getByYear($year, $perPage, $complexId);
        return APIResponse::success(ServiceUnitPriceResource::collection($price));
    }

    /**
     * Tạo đơn giá dịch vụ mới
     */
    public function store(ServiceUnitPriceRequest $request)
    {
        $data = $request->validated();
        $data['complex_id'] = jwt_claim('complex_id');
        $price = $this->serviceUnitPriceService->create($data);
        return APIResponse::success(new ServiceUnitPriceResource($price));
    }

    /**
     * Cập nhật đơn giá dịch vụ
     */
    public function update(ServiceUnitPriceRequest $request, string $id)
    {
        $data = $request->validated();
        $price = $this->serviceUnitPriceService->update($id, $data);
        return APIResponse::success(new ServiceUnitPriceResource($price));
    }

    /**
     * Xóa đơn giá dịch vụ
     */
    public function delete(string $id)
    {
        $result = $this->serviceUnitPriceService->delete($id);
        return APIResponse::success('Xóa thành công');
    }
}
