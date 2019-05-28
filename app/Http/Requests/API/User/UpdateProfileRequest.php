<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 手机号
            'mobile' => ['required', 'regex:/^1[3-9]\d{9}$/'],
            // QQ号
            'qq' => ['nullable', 'regex:/^[1-9]\d{4,9}$/'],
            // 微信号：参考 http://kf.qq.com/touch/faq/120813euEJVf141212Vfi6fA.html?platform=15，同时也可以使用手机号和QQ号作为微信号
            'wechat' => ['nullable', 'regex:/^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$/', 'max:50'],
            // 邮箱
            'email' => ['nullable', 'regex:/^[a-zA-Z0-9].*@.+(\..+)+$/', 'max:50'],
            // 生日：日期
            'birthday' => ['nullable', 'date_format:Y-m-d', 'regex:/(^19)|(^20)/'],
            // 故乡：字符串
            'hometown' => ['nullable', 'string', 'max:50'],
            // 个人简介：字符串
            'desc' => ['nullable', 'string'],
        ];
    }
}
