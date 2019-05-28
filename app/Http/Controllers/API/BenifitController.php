<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Xjtuana\HealthCheck\Result;

class BenifitController extends Controller
{
    public function healthcheck(Request $request) {
        $data = [
            'jetbrains' => \CheckJetbrains::check('jetbrains.as.xjtuana.com')->getData(),
            'kms' => \CheckKms::check('kms.as.xjtuana.com', 1688)->getData(),
            'shadowsocks_gfw' => \CheckShadowsocks::check('gfw.proxy.xjtuana.com', 7011)->getData(),
            'shadowsocks_xjtu' => \CheckShadowsocks::check('xjtu.proxy.xjtuana.com', 6011)->getData(),
        ];
        return $this->jsonResponse('OK', 0, $data);
    }
}
