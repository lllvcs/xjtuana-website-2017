<?php

namespace App\Http\Requests\API\BuildingMember;

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
        return $this->user()->can('searchMembers', \App\Models\Building::class);
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
            // 宿舍楼：在buildings数据表中
            'building_id' => ['nullable', 'exists:buildings,id'],
        ];
    }
}
