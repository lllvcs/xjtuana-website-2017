<?php

namespace App\Http\Middleware;

use Closure;

class SwitchLanguage
{
    /**
     * 根据路由自动切换语言包
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = "zh";// 选择的语言包，默认中文
        $clientUrl = $request->getPathInfo();// 获取请求路径
        // 如果路由为空或者/，则直接选择中文版本
        if (!empty($clientUrl) || $clientUrl == '/') {
            // 获取系统可选择的语言包数组
            $langs = config('global.lang');//这里配置语言种类
            // 切割数组获取路由中的语言包信息
            $urls = explode('/', $clientUrl);
            // 如果选择的语言版本存在，则切换到该版本
            if (!empty($urls) && $urls[1] == "en") {
                $lang = $urls[1];
            }
        }
        app()->setLocale($lang);// 设置语言包
        //dd($request);
        return $next($request);
    }

}