<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ShopList\CreateRequest;
use App\Http\Requests\API\ShopList\DeleteRequest;
use App\Http\Requests\API\ShopList\BackRequest;
use App\Http\Requests\API\ShopList\BackAgreeRequest;
use App\Http\Requests\API\ShopList\DeleteBuyRequest;
use App\Http\Requests\API\ShopList\DeleteSellRequest;
use App\Http\Requests\API\ShopList\DeleteBackRequest;
use App\Http\Requests\API\ShopList\SearchRequest;
use App\Http\Requests\API\ShopList\UpdateRequest;
use App\Http\Requests\API\ShopList\BuyRequest;
use App\Http\Requests\API\ShopList\SellRequest;
use App\Http\Requests\API\ShopList\ViewRequest;
use App\Repositories\ShopListRepository as Repo;

class ShopListController extends Controller
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

    public function sell_del($id, DeleteSellRequest $request)
    {
        if ($this->repo->sell_del($id)) {
            return $this->jsonResponse('订单取消成功！');
        } else {
            return $this->jsonResponse('订单取消失败！', -1);
        }
    }
    
    public function del($id, DeleteRequest $request)
    {
        if ($this->repo->del($id)) {
            return $this->jsonResponse('礼物删除成功！');
        } else {
            return $this->jsonResponse('礼物删除失败！', -1);
        }
    }

    public function sell($id, SellRequest $request)
    {
        if ($this->repo->sell($id)) {
            return $this->jsonResponse('商品出售成功！');
        } else {
            return $this->jsonResponse('商品出售失败！', -1);
        }
    }

    public function buy_del($id, DeleteBuyRequest $request)
    {
        if ($this->repo->buy_del($id)) {
            return $this->jsonResponse('订单取消成功！');
        } else {
            return $this->jsonResponse('订单取消失败！', -1);
        }
    }

    public function buy($id, BuyRequest $request)
    {
        if ($this->repo->buy($id)) {
            return $this->jsonResponse('商品购买成功！');
        } else {
            return $this->jsonResponse('商品购买失败！', -1);
        }
    }
    
    public function back($id, BackRequest $request)
    {
        if ($this->repo->back($id)) {
            return $this->jsonResponse('商品退款成功！');
        } else {
            return $this->jsonResponse('商品退款失败！', -1);
        }
    }
    
    public function back_agree($id, BackAgreeRequest $request)
    {
        if ($this->repo->back_agree($id)) {
            return $this->jsonResponse('商品退款成功！');
        } else {
            return $this->jsonResponse('商品退款失败！', -1);
        }
    }
    
    public function back_del($id, DeleteBackRequest $request)
    {
        if ($this->repo->back_del($id)) {
            return $this->jsonResponse('商品退款取消成功！');
        } else {
            return $this->jsonResponse('商品退款取消失败！', -1);
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
