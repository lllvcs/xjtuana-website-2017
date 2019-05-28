<?php

namespace App\Http\Controllers\Home\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderCreateRequest;
use App\Http\Requests\User\OrderRateRequest;
use App\Repositories\OrderRepository;
use Auth;

class OrderController extends Controller
{
    protected $order_repo;

    public function __construct(OrderRepository $order_repo)
    {
        $this->order_repo = $order_repo;
    }

    public function index() 
    {
        $data = [
            'orders' => \Auth::user()->load('orders.member.user.profile')->orders,
        ];
        return view('home.pages.user.order.index', $data);
    }

    public function create() 
    {
        $data = [
            'user' => Auth::user()->load('profile'),
            'campuses' => \App\Models\Campus::with('buildings')->get(),
            'services' => \App\Models\OrderService::get(),
        ];
        return view('home.pages.user.order.create', $data);
    }

    public function show($id, \App\Http\Requests\API\Order\ViewRequest $request) 
    {
        $order = $this->order_repo->findOrFail($id);
        
        $data = [
            'order' => $order
        ];
        
        return view('home.pages.user.order.show', $data);
    }

    public function edit($id) 
    {
        $order = $this->order_repo->findOrFail($id);

        if( Auth::user()->cant('update', $order) ) {
            abort(403, '403 Forbidden');
        }

        $data = [
            'order' => $order,
        ];
        return view('user.order.edit', $data);
    }
}
