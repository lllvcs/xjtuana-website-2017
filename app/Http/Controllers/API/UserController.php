<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository as Repo;
use App\Http\Requests\API\User\OrderRequest;
use App\Http\Requests\API\User\UpdateProfileRequest;

class UserController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(Request $request) {
        $data = $request->user()->load('profile.building', 'member.department', 'member.designation', 'member.buildings.campus','member.points');
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function updateProfile(UpdateProfileRequest $request) {
        if ( $this->repo->userUpdateProfile($request->user()->netid, $request->all()) ){
            return $this->jsonResponse('个人资料修改成功!');
        } else {
            return $this->jsonResponse('个人资料修改失败!', -1);
        }
    }
    
    public function task(Request $request) {
        $data = [
            'todoOrders' => $request->user()->member->todoOrders(),
        ];
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function order(OrderRequest $request) {
        $data = $request->user()->member->orders()->paginate($request->perpage);

        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function orderStatistic(OrderRequest $request) {
        $data = $request->user()->member->orders()->count();

        return $this->jsonResponse('OK', 0, $data);
    }

}
