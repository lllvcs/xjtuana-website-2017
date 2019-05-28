<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PointsRecordRepository as Repo;
use Auth;
use App\Http\Requests\API\PointsRecord\SearchRequest;
use App\Http\Requests\API\PointsRecord\ViewRequest;
use App\Http\Requests\API\PointsRecord\UpdateRequest;
use App\Http\Requests\API\PointsRecord\CreateRequest;
use App\Http\Requests\API\PointsRecord\DestroyRequest;

class PointsRecordController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->search($request->all());;
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show(ViewRequest $request) {
        $data = $this->repo->find_list($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('社员编辑成功！');
        } else {
            return $this->jsonResponse('社员编辑失败！', -1);
        }
    }

    public function store(CreateRequest $request) {
        if( $this->repo->add($request->all()) !== false) {
            return $this->jsonResponse('社员积分添加成功！');
        } else {
            return $this->jsonResponse('社员添加失败，请检查用户是否存在', -1);
        }
    }

    public function destroy($id, DestroyRequest $request) {
        if ( $this->repo->delete($id) ) {
            return $this->jsonResponse('删除记录成功');
        } else {
            return $this->jsonResponse('删除记录失败', -1);
        }
    }
}