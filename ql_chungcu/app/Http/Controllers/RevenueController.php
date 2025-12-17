<?php

namespace App\Http\Controllers;

use App\Http\Requests\RevenueRequest\RevenueFilterRequest;
use App\Http\Requests\RevenueRequest\RevenueRequest;
use App\Http\Requests\RevenueRequest\GenerateRevenueRequest;
use App\Http\Resources\RevenueResource;
use App\Responses\APIResponse;
use App\Services\RevenueService\IRevenueService;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    protected IRevenueService $revenueService;

    public function __construct(IRevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Lấy danh sách khoản thu theo filter
     */
    public function index(RevenueFilterRequest $request)
    {
        $filters = $request->validated();
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));

        $revenues = $this->revenueService->getRevenueByFilters($filters, $perPage);
        return APIResponse::paginated(RevenueResource::collection($revenues['revenues'])->additional([
            'summary' => [
                'total_paid'   => (float)$revenues['summary']->paid,
                'total_expect' => (float)$revenues['summary']->total_expect,
            ]
        ]));
    }

    public function store(RevenueRequest $request)
    {
        $data = $request->validated();
        $revenue = $this->revenueService->createRevenue($data);
        return APIResponse::success('Thêm mới thành công!');
    }

    public function update(RevenueRequest $request, string $id)
    {
        $data = $request->validated();
        $revenue = $this->revenueService->updateRevenue($id, $data);
        return APIResponse::success(new RevenueResource($revenue));
    }

    public function delete(string $id)
    {
        $result = $this->revenueService->deleteRevenue($id);
        return APIResponse::success('Xóa thành công');
    }

    // Tự động tạo khoản thu theo thang cho tung can ho cua toa nha
    public function generateMonthlyRevenues(GenerateRevenueRequest $request)
    {
        $data = $request->validated();
        $result = $this->revenueService->generateMonthlyRevenues($data['building_id'], $data['year'], $data['month']);

        if ($result['total_revenue'] > 0) {
            return APIResponse::success('Tạo khoản thu theo tháng thành công');
        } else {
            return APIResponse::success('Không có khoản thu nào được tạo mới');
        }
    }
}
