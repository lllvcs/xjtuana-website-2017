<?php

namespace App\Http\Requests\API\Order;

use Illuminate\Foundation\Http\FormRequest;

class AppendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('append', \App\Models\Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 负责网管：必须 | 在members数据表中
            'member' => ['required', 'exists:members,id'],
            // 宿舍楼：必须 | 在building数据表中
            'building' => ['required', 'exists:buildings,id'],
            // 房间号：必须 | 字符串
            'room' => ['required', 'string', 'max:10'],
            // 手机号
            'mobile' => ['required', 'regex:/^1[3-9]\d{9}$/'],
            // QQ号
            'qq' => ['nullable', 'regex:/^[1-9]\d{4,9}$/'],
            // 微信号：参考 http://kf.qq.com/touch/faq/120813euEJVf141212Vfi6fA.html?platform=15，同时也可以使用手机号和QQ号作为微信号
            'wechat' => ['nullable', 'regex:/^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$/'],
            // 服务类别：必须 | 整数 | 在order_service数据表中
            'service' => ['required', 'integer', 'exists:order_services,id'],
            // 报修详情：必须 | 字符串
            'detail' => ['required', 'string'],
        ];
    }
}
