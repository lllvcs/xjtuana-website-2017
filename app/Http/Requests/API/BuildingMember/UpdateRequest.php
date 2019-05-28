<?php

namespace App\Http\Requests\API\BuildingMember;

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
        return $this->user()->can('updateMembers', \App\Models\Building::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 网管：数组
            'members' => ['nullable','array'],
            // 网管ID：在members数据表中
            'members.*' => ['sometimes', 'exists:members,id'],
        ];
    }
}
