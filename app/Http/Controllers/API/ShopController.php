<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Shop\CreateRequest;
use App\Http\Requests\API\Shop\DeleteRequest;
use App\Http\Requests\API\Shop\SearchRequest;
use App\Http\Requests\API\Shop\UpdateRequest;
use App\Http\Requests\API\Shop\BuyRequest;
use App\Http\Requests\API\Shop\ViewRequest;
use App\Repositories\ShopRepository as Repo;

class ShopController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index(SearchRequest $request)
    {
        $data = $this->repo->search($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, ViewRequest $request)
    {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }

    public function update($id, UpdateRequest $request)
    {
        if ($this->repo->update($id, $request->all())) {
            return $this->jsonResponse('礼物编辑成功！（个人只能放一个商品）');
        } else {
            return $this->jsonResponse('礼物编辑失败！', -1);
        }
    }

    public function delete($id, DeleteRequest $request)
    {
        if ($this->repo->delete($id)) {
            return $this->jsonResponse('礼物删除成功！');
        } else {
            return $this->jsonResponse('礼物删除失败！', -1);
        }
    }

    public function buy($id, BuyRequest $request)
    {
        if ($this->repo->buy($id, $request->all())) {
            return $this->jsonResponse('礼物购买成功！');
        } else {
            return $this->jsonResponse('礼物购买失败！', -1);
        }
    }

    public function create(CreateRequest $request)
    {
        if ($this->repo->add($request->all())) {
            return $this->jsonResponse('礼物编辑成功！（个人只能放一个商品）');
        } else {
            return $this->jsonResponse('礼物编辑失败！', -1);
        }
    }

}
