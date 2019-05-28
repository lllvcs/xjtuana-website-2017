<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FaqRepository as Repo;
use App\Http\Requests\API\Faq\SearchRequest;
use App\Http\Requests\API\Faq\CreateRequest;
use App\Http\Requests\API\Faq\UpdateRequest;
use App\Http\Requests\API\Faq\DestroyRequest;
use App\Http\Requests\API\Faq\ViewRequest;
use App\Http\Requests\API\Faq\RestoreRequest;
use App\Http\Requests\API\Faq\ForceDestroyRequest;

class FaqController extends Controller
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
        if ( $this->repo->add($request->all()) ) {
            return $this->jsonResponse('Faq添加成功!');
        } else {
            return $this->jsonResponse('Faq添加失败!', -1);
        }
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('Faq更新成功!');
        } else {
            return $this->jsonResponse('Faq更新失败!', -1);
        }
    }
    
    public function destroy($id, DestroyRequest $request) {
        if ( $this->repo->delete($id) ) {
            return $this->jsonResponse('Faq删除成功!');
        } else {
            return $this->jsonResponse('Faq删除失败!', -1);
        }
    }
    
    public function restore($id, RestoreRequest $request) {
        if ( $this->repo->restore($id) ) {
            return $this->jsonResponse('Faq恢复成功!');
        } else {
            return $this->jsonResponse('Faq恢复失败!', -1);
        }
    }
    
    public function forceDestroy($id, ForceDestroyRequest $request) {
        if ( $this->repo->forceDelete($id) ) {
            return $this->jsonResponse('Faq永久删除成功!');
        } else {
            return $this->jsonResponse('Faq永久删除失败!', -1);
        }
    }

}
