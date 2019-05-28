<?php

namespace App\Http\Controllers\Auth;

use Auth;
use CasProxy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Jobs\UserProfileUpdate;
use Xjtuana\Cas\ProxyClient\CasProxyClientException;

class LoginController extends Controller {

    protected $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function login(Request $request) {

        // 判断是否登录
        if (Auth::guest()) {

            // 通过CAS登录并获取用户NETID
            try {
                $netid = CasProxy::login();
            } catch(CasProxyClientException $e) {
                if ($e->getCode() === CasProxyClientException::GUID_INVALID) {
                    abort(419, '页面已过期，请返回首页后重试');
                } else {
                    abort(502, '学校认证服务器又挂了囧');
                }
            }
        
            $request->session()->regenerate();

            // 根据NETID在数据库中进行匹配
            if ( ! Auth::attempt(['netid' => $netid]) ) {
                
                // 若用户是第一次登录本系统，则在数据库中增加对应用户并用该用户登录
                try {
                    $user = $this->userRepo->add($netid);
                } catch(\Exception $e) {
                    abort(502, '学校认证服务器又挂了囧');
                }
                if ( $user === false ) {
                    abort(502, '学校认证服务器又挂了囧');
                }
                Auth::login($user);
            }

            if ( Auth::user()->profile === null ) {
                // 如果数据库中用户资料不存在，则立即重新获取。
                dispatch_now(new UserProfileUpdate(Auth::id()));
            } else {
                // 如果数据库中用户资料已存在，则异步获取。
                dispatch(new UserProfileUpdate(Auth::id()));
            }

            // 记录用户登录次数和登录时间
            Auth::user()->logged_in_at = Carbon::now();
            Auth::user()->save();
        }

        // 调用intended方法，返回登录前页面，若没有设置登录前页面，则返回首页
        return redirect()->intended('/');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        Auth::guard()->logout();

        $request->session()->flush();

        $request->session()->save();
        
        CasProxy::logout();

        return redirect()->intended('/');
    }
}
