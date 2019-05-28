<?php

namespace App\Http\Requests\API\Member;

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
            // 社员ID：整数
            'id' => ['nullable', 'integer'],
            // 是否退社：boolean
            'old_members' => ['nullable', 'boolean'],
            // 部门：在department数据表中
            'department_id' => ['nullable', 'exists:departments,id'],
            // 职位：在designation数据表中
            'designation_id' => ['nullable', 'exists:designations,id'],
            // NETID：字符串
            'netid' => ['nullable', 'string'],
            // 姓名：字符串
            'name' => ['nullable', 'string'],
            // 当前页数：数字
            'page' => ['required', 'integer'],
            // 每页条目数：数字
            'perpage' => ['required', 'integer'],
        ];
    }
}
