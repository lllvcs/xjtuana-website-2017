<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BuildingMemberRepository as Repo;
use App\Http\Requests\API\BuildingMember\SearchRequest;
use App\Http\Requests\API\BuildingMember\UpdateRequest;

class BuildingMemberController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->all($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function show($id, Request $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('网管分配成功！');
        } else {
            return $this->jsonResponse('网管分配失败！', -1);
        }
    }

}
