<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrderStatisticsRepository as Repo;
use App\Http\Requests\API\OrderStatistic\DailyRequest;
use App\Http\Requests\API\OrderStatistic\MemberRequest;
use App\Http\Requests\API\OrderStatistic\BuildingRequest;

class OrderStatisticsController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(Request $request) {
        $data = $this->repo->index();
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function daily(DailyRequest $request) {
        $data = $this->repo->daily($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function member(MemberRequest $request) {
        $data = $this->repo->member($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function building(BuildingRequest $request) {
        $data = $this->repo->building($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }
    
}
