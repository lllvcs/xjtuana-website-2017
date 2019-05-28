<?php

namespace App\Http\Requests\API\Shop;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', \App\Models\Shop::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // id号：在member数据表中
            'id' => ['required', 'exists:shop_items'],
            // 物品名：必须 | 字符串
            'name' => ['required', 'string'],
            // 所有人id：必须 | 整数 | 最大为10
            'own' => ['required', 'integer'],
            // 所有人名：必须 | 整数 | 最大为10
            'own_name' => ['required', 'string'],
            // 积分：必须 | 字符串
            'points' => ['required', 'integer'],
            // 售卖数目：必须 | 整数 | 最大为10
            'number' => ['required', 'integer'],
            // 已卖数目：必须 | 整数 | 最大为10
            'sold' => ['required', 'integer'],
            // 细节：必须 | 整数 | 最大为10
            'details' => ['required', 'string'],
        ];
    }
}
