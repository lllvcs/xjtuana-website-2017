<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FaqCategoryRepository as Repo;
use App\Http\Requests\API\FaqCategory\SearchRequest;
use App\Http\Requests\API\FaqCategory\CreateRequest;
use App\Http\Requests\API\FaqCategory\UpdateRequest;
use App\Http\Requests\API\FaqCategory\DestroyRequest;
use App\Http\Requests\API\FaqCategory\ViewRequest;

class FaqCategoryController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->all();
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, ViewRequest $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function store(CreateRequest $request) {
        if ( $this->repo->add($request->all()) ) {
            return $this->jsonResponse('Faq类别添加成功！');
        } else {
            return $this->jsonResponse('Faq类别添加失败！', -1);
        }
    }
    
    public function update($id, UpdateRequest $request) {
        if ( $this->repo->update($id, $request->all()) ) {
            return $this->jsonResponse('Faq类别更新成功！');
        } else {
            return $this->jsonResponse('Faq类别更新失败！', -1);
        }
    }
    
    public function destroy($id, DestroyRequest $request) {
        if ( $this->repo->delete($id) ) {
            return $this->jsonResponse('Faq类别删除成功！');
        } else {
            return $this->jsonResponse('Faq类别删除失败！', -1);
        }
    }

}
