<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function getIndex() {
        $data = [
            'member_count' => \App\Models\Member::count(),
            'order_count' => \App\Models\Order::count(),
        ];
        return view('home.pages.index', $data);
    }

    public function getFaqs() {
        $data = [
            'faq_categories' => \App\Models\FaqCategory::with('faqs')->get(),
        ];
        return view('home.pages.faqs', $data);
    }
}
