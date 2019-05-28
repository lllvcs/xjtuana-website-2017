<?php

namespace App\Http\Controllers\Home\JoinUs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JoinUsController extends Controller
{

    public function getHome() 
    {
        return redirect('https://jinshuju.net/f/AbgclO');
        $data = [
            
        ];
        return view('home.pages.join-us.home', $data);
    }

}
