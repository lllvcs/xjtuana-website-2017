<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MemberPointsRepository as Repo;
use Auth;
use App\Http\Requests\API\Points\SearchRequest;
use App\Http\Requests\API\Points\ViewRequest;
use App\Http\Requests\API\Points\UpdateRequest;
use App\Http\Requests\API\Points\CreateRequest;
use App\Http\Requests\API\Points\EditorderRequest;

class PointsController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->search($request->all());;
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, ViewRequest $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('社员编辑成功！');
        } else {
            return $this->jsonResponse('只能修改本部的部员！', -1);
        }
    }

    public function editorder(EditorderRequest $request) {
        if ( $this->repo->editorder($request->all()) ) {
            return $this->jsonResponse('社员编辑成功！');
        } else {
            return $this->jsonResponse('只能修改本部的部员！', -1);
        }
    }

    public function store(CreateRequest $request) {
        if($this->repo->add($request->all()) !== false) {
            return $this->jsonResponse('社员积分添加成功！');
        } else {
            return $this->jsonResponse('社员添加失败，请检查用户是否存在', -1);
        }
    }
}