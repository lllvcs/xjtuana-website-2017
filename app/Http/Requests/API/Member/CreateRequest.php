<?php

namespace App\Http\Requests\API\Member;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', \App\Models\Member::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 部门：在department数据表中
            'department_id' => ['required', 'exists:departments,id'],
            // 职位：在designations数据表中
            'designation_id' => ['required', 'exists:designations,id'],
            // NETID：其实应该在user表中(exists:users,netid)，但若未登录过系统则不存在，要尝试创建
            'netid' => ['required', 'string'],
        ];
    }
}
