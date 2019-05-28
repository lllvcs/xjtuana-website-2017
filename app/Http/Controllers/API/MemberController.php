<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository as Repo;
use Auth;
use App\Http\Requests\API\Member\SearchRequest;
use App\Http\Requests\API\Member\CreateRequest;
use App\Http\Requests\API\Member\UpdateRequest;
use App\Http\Requests\API\Member\UpdateNetidRequest;
use App\Http\Requests\API\Member\DestroyRequest;
use App\Http\Requests\API\Member\ForceDestroyRequest;
use App\Http\Requests\API\Member\RestoreRequest;
use App\Http\Requests\API\Member\ViewRequest;
use App\Http\Requests\API\Member\ExportRequest;

class MemberController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->search($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, ViewRequest $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function store(CreateRequest $request) {
        if( $this->repo->add($request->all()) !== false) {
            return $this->jsonResponse('社员添加成功！');
        } else {
            return $this->jsonResponse('社员添加失败，请检查NETID是否正确', -1);
        }
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('社员编辑成功！');
        } else {
            return $this->jsonResponse('社员编辑失败！', -1);
        }
    }
    
    public function updateNetid($id, UpdateNetidRequest $request) {
        if( $this->repo->updateNetid($id, $request->netid) !== false) {
            return $this->jsonResponse('社员NETID更新成功！');
        } else {
            return $this->jsonResponse('社员NETID更新失败，请检查NETID是否正确', -1);
        }
    }
    
    public function destroy($id, DestroyRequest $request) {
        if ( $this->repo->delete($id) ) {
            return $this->jsonResponse('社员退社成功！');
        } else {
            return $this->jsonResponse('社员退社失败！', -1);
        }
    }
    
    public function restore($id, RestoreRequest $request) {
        if ( $this->repo->restore($id) ) {
            return $this->jsonResponse('社员回社成功！');
        } else {
            return $this->jsonResponse('社员回社失败！', -1);
        }
    }
    
    public function forceDestroy($id, ForceDestroyRequest $request) {
        if ( $this->repo->forceDelete($id) ) {
            return $this->jsonResponse('社员永久删除成功！');
        } else {
            return $this->jsonResponse('社员永久删除失败！', -1);
        }
    }
    
    public function exportExcel(ExportRequest $request) {
        return $this->repo->export('excel')->download();
    }

}
