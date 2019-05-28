<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class OrderRateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 评分：必须 | 1-5
            'score' => ['required', 'integer', 'in:1,2,3,4,5'],
            // 评价详情：必须 | 字符串
            'detail' => ['required', 'string'],
        ];
    }
}
