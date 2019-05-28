<?php

namespace App\Http\Requests\API\Points;

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
        return \Auth::user()->can('search', \App\Models\MemberPoints::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 报修单状态：在order_status数据表中
            'old_members' => ['nullable', 'boolean'],
            // 姓名：字符串
            'name' => ['nullable', 'string'],
            // 分数: Member_Points
        ];
    }
}
