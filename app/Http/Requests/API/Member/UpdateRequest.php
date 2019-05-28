<?php

namespace App\Http\Requests\API\Member;

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
        return $this->user()->can('update', \App\Models\Member::withTrashed()->find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 部门：在departments数据表中
            'department_id' => ['nullable', 'exists:departments,id'],
            // 职位：在designations数据表中
            'designation_id' => ['nullable', 'exists:designations,id'],
        ];
    }
}
