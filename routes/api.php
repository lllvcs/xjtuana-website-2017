<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
  'prefix' => '/v1',
  'namespace' => 'API\V1',
], function() {
    Route::name('api.v1.wechat.get-netid-by-openid')->get('/getNetidByOpenid', 'WechatController@getNetidByOpenid');
});