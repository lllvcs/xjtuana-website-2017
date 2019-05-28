<?php

namespace App\Http\Requests\API\Shop;

use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('buy', \App\Models\Shop::find($this->id));
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
            // 积分：必须 | 字符串
            'points' => ['required', 'integer'],
            // 售卖数目：必须 | 整数 | 最大为10
            'number' => ['required', 'integer'],
            // 购买人id：必须 | 整数 | 最大为10
            'purchase' => ['required', 'integer'],
        ];
    }
}
