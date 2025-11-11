<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\UserRequest;
use App\Http\Resources\UserResource;
use App\Responses\APIResponse;
use App\Services\UserService\IUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return APIResponse::paginated(UserResource::collection($this->userService->show($perPage)));
    }
    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->validated();
//        $data["user_id"] = auth()->user()->id;
        $user = $this->userService->add($data);
        return APIResponse::success(new UserResource($user));
    }

    public function findByOrgId($orgId){
        $perPage = intval(request('perPage', 50));
        $perPage = max(1, min($perPage, 50));
        return $this->userService->findByOrgId($orgId, $perPage);
    }
}
