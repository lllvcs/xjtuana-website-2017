<?php

namespace App\Http\Requests\API\ShopList;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('search', \App\Models\Member::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 姓名：字符串
            'name' => ['nullable', 'string'],
            // 分数: Member_Points
        ];
    }
}
