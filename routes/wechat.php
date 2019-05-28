<?php

/*
|--------------------------------------------------------------------------
| Wechat Routes
|--------------------------------------------------------------------------
*/


// 微信网页授权验证文件
Route::get('/MP_verify_' . config('wechat.oauth2.verify') . '.txt', 'Wechat\WechatController@verify');

Route::namespace('Wechat')->prefix('/wechat')->group(function() {
    
    // 微信绑定NetID接口
    Route::prefix('/auth')->middleware('auth')->group(function() {
        Route::name('wechat.bind')->get('/bind', 'AuthController@bind');
        Route::name('wechat.bind.confirm')->post('/bind/confirm', 'AuthController@bindConfirm');
        Route::name('wechat.unbind')->get('/unbind', 'AuthController@unbind');
        Route::name('wechat.unbind.confirm')->post('/unbind/confirm', 'AuthController@unbindConfirm');
    });
        
    // 监听微信服务器传来的消息/事件
    Route::any('/listen', 'WechatController@listen');
    
    // 显示微信网页
    Route::middleware('wechat.oauth2')->group(function() {
        Route::view('/', 'wechat')->name('wechat.home');
    });
    
    // 微信API
    Route::namespace('API')->prefix('/api')->middleware(['auth', 'only.admin'])->group(function() {
        Route::prefix('/url')->group(function() {
            Route::name('wechat.api.url.bind')->get('/bind', 'UrlController@bind');
            Route::name('wechat.api.url.unbind')->get('/unbind', 'UrlController@unbind');
            Route::name('wechat.api.url.order')->get('/order', 'UrlController@order');
            Route::name('wechat.api.url.dashboard')->get('/dashboard', 'UrlController@dashboard');
        });
        
        // Route::name('wechat.api.menu.update')->post('/menu', 'MenuController@update');
        // Route::name('wechat.api.template.send')->post('/template/send', 'TemplateController@send');
    });
});
