<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/* 
|--------------------------------------------------------------------------
| 社团主页，不需要登录即可访问 
*/
Route::get('/', function () {return redirect('/zh');});
Route::get('/user/order/create', function () {return redirect('/zh/user/order/create');});

Route::group(['prefix' => 'zh', 'middleware' => 'switch_lang'], function () {
    Route::group(['namespace' => 'Home'], function() {
        Route::name('home')->get('', 'HomeController@getIndex')->middleware('setzh');
        Route::name('home.faqs')->get('/faqs', 'HomeController@getFaqs')->middleware('setzh');
    });
});

Route::group(['prefix' => 'en', 'middleware' => 'switch_lang'], function () {
    Route::group(['namespace' => 'Home'], function() {
        Route::name('home')->get('', 'HomeController@getIndex')->middleware('seten');
        Route::name('home.faqs')->get('/faqs', 'HomeController@getFaqs')->middleware('seten');
    });
});
/* 
|--------------------------------------------------------------------------
| 登录登出
*/
Route::namespace('Auth')->group(function() {
    Route::name('login')->get('login', 'LoginController@login');
    Route::name('logout')->get('logout', 'LoginController@logout');
    //Route::name('login')->get('/zh/login', 'LoginController@login')->middleware('setzh');;
    //Route::name('logout')->get('/zh/logout', 'LoginController@logout')->middleware('setzh');;
    //Route::name('login')->get('/en/login', 'LoginController@login')->middleware('seten');
    //Route::name('logout')->get('/en/logout', 'LoginController@logout')->middleware('seten');
});


//Route::get('/', function () {return redirect('/zh');});
/* 
|--------------------------------------------------------------------------
| 微信接口
*/
require base_path('routes/wechat.php');


/* 
|--------------------------------------------------------------------------
| 需要登录才能访问
*/
Route::group([
    'middleware' => 'auth'
], function() {
    
    /* 
    |--------------------------------------------------------------------------
    | 招新界面
    */
    Route::group([
        'prefix' => '/join-us',
        'namespace' => 'Home\JoinUs',
    ], function() {
        Route::name('join-us.home')->get('/', 'JoinUsController@getHome');


    });

    /* 
    |--------------------------------------------------------------------------
    | 用户界面
    */
    
    // Route::group(['prefix' => '{lang}', 'middleware' => 'switch_lang'], function () {
    //     Route::group([
    //         'prefix' => '/user',
    //         'namespace' => 'Home\User',
    //     ], function() {
    //         Route::name('user.home')->get('/', 'UserController@getHome');
    
    //         // 订单操作
    //         Route::group(['prefix' => '/order'], function() {
    //             Route::name('user.order.index')->get('/', 'OrderController@index');
    //             Route::name('user.order.create')->get('/create', 'OrderController@create');
    //         });
    
    //     });
    // });
    //Route::get('zh/user/order/{id}', function () {return redirect('zh/user/order/{id}');});
    //Route::name('user.order.show')->get('zh/user/order/{id}', 'OrderController@show');
    
    Route::group([
        'prefix' => '/zh',
        'namespace' => 'Home\User',
    ], function() {
        // 订单操作
        Route::group(['prefix' => '/user'], function() {
            
            Route::name('user.home')->get('/', 'UserController@getHome')->middleware('setzh');
            
            Route::group(['prefix' => '/order'], function() {
                Route::name('user.order.show')->get('/{id}', 'OrderController@show')->middleware('setzh');
                Route::name('user.order.index')->get('/', 'OrderController@index')->middleware('setzh');
                Route::name('user.order.create')->get('/create', 'OrderController@create')->middleware('setzh');
            });
        });
    });
    
    Route::group([
        'prefix' => '/en',
        'namespace' => 'Home\User',
    ], function() {
        // 订单操作
        Route::group(['prefix' => '/user'], function() {
            
            Route::name('user.home')->get('/', 'UserController@getHome')->middleware('seten');
            
            Route::group(['prefix' => '/order'], function() {
                Route::name('user.order.show')->get('/{id}', 'OrderController@show')->middleware('seten');  
                Route::name('user.order.index')->get('/', 'OrderController@index')->middleware('seten');
                Route::name('user.order.create')->get('/create', 'OrderController@create')->middleware('seten');
            });
        });
    });
    
    Route::group([
        'prefix' => '/user',
        'namespace' => 'Home\User',
    ], function() {

        // 订单操作
        Route::group(['prefix' => '/order'], function() {
            Route::name('user.order.store')->post('/store', 'OrderController@store');
            Route::name('user.order.edit')->get('/{id}/edit', 'OrderController@edit');
            Route::name('user.order.update')->match(['put', 'patch'], '/{id}', 'OrderController@update');
            Route::name('user.order.cancel')->delete('/{id}', 'OrderController@cancel');
            Route::name('user.order.urge')->post('/{id}/urge', 'OrderController@urge');
            Route::name('user.order.rate')->post('/{id}/rate', 'OrderController@rate');
        });

    });

    /* 
    |--------------------------------------------------------------------------
    | 社员界面
    */
    Route::view('/dashboard/{any?}', 'dashboard')->name('dashboard.home')->where('any', '(?!api).*')->middleware('only.member')->middleware('setzh');
    //Route::view('/en/dashboard/{any?}', 'dashboard')->name('dashboard.home')->where('any', '(?!api).*')->middleware('only.member')->middleware('seten');

    /* 
    |--------------------------------------------------------------------------
    | Internal API
    */
    Route::group([
        'prefix' => '/api', 
        'namespace' => 'API',
    ], function() {
        Route::name('api.user')->get('/user', 'UserController@index');
        Route::name('api.user.profile.update')->match(['put', 'patch'], '/user/profile', 'UserController@updateProfile');
        
        Route::name('api.user.task')->get('/user/task', 'UserController@task');
        Route::name('api.user.order')->get('/user/order', 'UserController@order');
        Route::name('api.user.order.statistics')->get('/user/order/statistics', 'UserController@orderStatistic');
        
        Route::name('api.order.mine')->get('/order/mine', 'OrderController@mine');
        Route::name('api.order.index')->get('/order', 'OrderController@index');
        Route::name('api.order.show')->get('/order/{id}', 'OrderController@show');
        Route::name('api.order.store')->post('/order', 'OrderController@store');
        Route::name('api.order.cancel')->delete('/order/{id}/cancel', 'OrderController@cancel');
        Route::name('api.order.urge')->post('/order/{id}/urge', 'OrderController@urge');
        Route::name('api.order.receive')->post('/order/{id}/receive', 'OrderController@receive');
        Route::name('api.order.report')->post('/order/{id}/report', 'OrderController@report');
        Route::name('api.order.complete')->post('/order/{id}/complete', 'OrderController@complete');
        Route::name('api.order.rate')->post('/order/{id}/rate', 'OrderController@rate');
        Route::name('api.order.update')->match(['put', 'patch'], '/order/{id}', 'OrderController@update')->middleware('only.manager');
        Route::name('api.order.destroy')->delete('/order/{id}', 'OrderController@destroy')->middleware('only.manager');
        Route::name('api.order.append')->post('/order/append', 'OrderController@append')->middleware('only.manager');
        
        Route::name('api.order_status.index')->get('/order_status', 'OrderStatusController@index');
        Route::name('api.order_service.index')->get('/order_service', 'OrderServiceController@index');
        
        
        Route::name('api.order_statistics.index')->get('/order_statistics', 'OrderStatisticsController@index');
        Route::name('api.order_statistics.daily')->get('/order_statistics/daily', 'OrderStatisticsController@daily');
        Route::name('api.order_statistics.member')->get('/order_statistics/member', 'OrderStatisticsController@member');
        Route::name('api.order_statistics.building')->get('/order_statistics/building', 'OrderStatisticsController@building');
        
        Route::name('api.campus.index')->get('/campus', 'CampusController@index');
        Route::name('api.campus.show')->get('/campus/{id}', 'CampusController@show');
        
        Route::name('api.department.index')->get('/department', 'DepartmentController@index');
        Route::name('api.department.show')->get('/department/{id}', 'DepartmentController@show');
        
        Route::name('api.designation.index')->get('/designation', 'DesignationController@index');
        
        Route::name('api.building_member.index')->get('/building_member', 'BuildingMemberController@index');
        Route::name('api.building_member.show')->get('/building_member/{id}', 'BuildingMemberController@show');
        Route::name('api.building_member.update')->put('/building_member/{id}', 'BuildingMemberController@update')->middleware('only.manager');
        
        
        Route::name('api.member.index')->get('/member', 'MemberController@index');
        Route::name('api.member.show')->get('/member/{id}', 'MemberController@show');
        Route::name('api.member.store')->post('/member', 'MemberController@store')->middleware('only.admin');
        Route::name('api.member.update')->match(['put', 'patch'], '/member/{id}', 'MemberController@update')->middleware('only.admin');
        Route::name('api.member.update.netid')->match(['put', 'patch'], '/member/{id}/netid', 'MemberController@updateNetid')->middleware('only.admin');
        Route::name('api.member.destroy')->delete('/member/{id}', 'MemberController@destroy')->middleware('only.admin');
        Route::name('api.member.restore')->post('/member/{id}/restore', 'MemberController@restore')->middleware('only.admin');
        Route::name('api.member.destroy.force')->delete('/member/{id}/force', 'MemberController@forceDestroy')->middleware('only.admin');
        Route::name('api.member.export.excel')->get('/member/export/excel', 'MemberController@exportExcel')->middleware('only.admin');
        
        Route::name('api.tool.sms')->post('/tool/sms', 'ToolController@sms')->middleware('only.manager');
        Route::name('api.tool.userinfo')->get('/tool/userinfo', 'ToolController@userinfo')->middleware('only.manager');
        Route::name('api.tool.userphoto')->get('/tool/userphoto', 'ToolController@userphoto')->middleware('only.manager');
        Route::name('api.tool.networklog')->get('/tool/networklog', 'ToolController@networklog');
        
        Route::name('api.faq.index')->get('/faq', 'FaqController@index');
        Route::name('api.faq.show')->get('/faq/{id}', 'FaqController@show');
        Route::name('api.faq.store')->post('/faq', 'FaqController@store')->middleware('only.manager');
        Route::name('api.faq.update')->match(['put', 'patch'], '/faq/{id}', 'FaqController@update')->middleware('only.manager');
        Route::name('api.faq.destroy')->delete('/faq/{id}', 'FaqController@destroy')->middleware('only.manager');
        Route::name('api.faq.restore')->post('/faq/{id}/restore', 'FaqController@restore')->middleware('only.manager');
        Route::name('api.faq.destroy.force')->delete('/faq/{id}/force', 'FaqController@forceDestroy')->middleware('only.admin');
        
        Route::name('api.faq_category.index')->get('/faq_category', 'FaqCategoryController@index');
        Route::name('api.faq_category.show')->get('/faq_category/{id}', 'FaqCategoryController@show');
        Route::name('api.faq_category.store')->post('/faq_category', 'FaqCategoryController@store')->middleware('only.manager');
        Route::name('api.faq_category.update')->match(['put', 'patch'], '/faq_category/{id}', 'FaqCategoryController@update')->middleware('only.manager');
        Route::name('api.faq_category.destroy')->delete('/faq_category/{id}', 'FaqCategoryController@destroy')->middleware('only.admin');

        Route::name('api.benifit.healthcheck')->get('/benifit/healthcheck', 'BenifitController@healthcheck');

        Route::name('api.points.index')->get('/points', 'PointsController@index')->middleware('only.manager');
        Route::name('api.points.editorder')->post('/points/editorder', 'PointsController@editorder')->middleware('only.manager');
        Route::name('api.points.show')->get('/points/{id}', 'PointsController@show')->middleware('only.manager');
        Route::name('api.points.update')->match(['put', 'patch'], '/points/{id}', 'PointsController@update')->middleware('only.manager');
        Route::name('api.points.store')->post('/points', 'PointsController@store')->middleware('only.manager');

        Route::name('api.points_record.index')->get('/points_record/{id}', 'PointsRecordController@index')->middleware('only.manager');
        Route::name('api.points_record.show')->get('/points_record', 'PointsRecordController@show');
        Route::name('api.points_record.update')->match(['put', 'patch'], '/points_record/{id}', 'PointsRecordController@update')->middleware('only.manager');
        Route::name('api.points_record.destroy')->delete('/points_record/{id}', 'PointsRecordController@destroy')->middleware('only.admin');


        Route::name('api.shop.index')->get('/shop', 'ShopController@index');
        Route::name('api.shop.create')->post('/shop', 'ShopController@create');
        Route::name('api.shop.update')->match(['put', 'patch'],'/shop/{id}', 'ShopController@update');
        Route::name('api.shop.delete')->delete('/shop/{id}', 'ShopController@delete');
        Route::name('api.shop.buy')->post('/shop/{id}', 'ShopController@buy');

        Route::name('api.shop_list.index')->get('/shop_list', 'ShopListController@index');
        Route::name('api.shop_list.del')->delete('/shop_list/del/{id}', 'ShopListController@del');
        Route::name('api.shop_list.buy')->post('/shop_list/buy/{id}', 'ShopListController@buy');
        Route::name('api.shop_list.buy_del')->delete('/shop_list/buy/{id}', 'ShopListController@buy_del');
        Route::name('api.shop_list.sell')->post('/shop_list/sell/{id}', 'ShopListController@sell');
        Route::name('api.shop_list.sell_del')->delete('/shop_list/sell/{id}', 'ShopListController@sell_del');
        Route::name('api.shop_list.back')->post('/shop_list/back/{id}', 'ShopListController@back');
        Route::name('api.shop_list.back_del')->delete('/shop_list/back/{id}', 'ShopListController@back_del');
        Route::name('api.shop_list.back_agree')->post('/shop_list/back_agree/{id}', 'ShopListController@back_agree');

    });

});
