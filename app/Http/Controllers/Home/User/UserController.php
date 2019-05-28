<?php

namespace App\Http\Controllers\Home\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function getHome() 
    {
        $data = [
            'recent_orders' => \Auth::user()->orders()->take(5)->get(),
        ];
        return view('home.pages.user.home', $data);
    }

}
