<?php

namespace App\Http\Requests\API\PointsRecord;

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
        return \Auth::user()->can('update', \App\Models\MemberPointsRecord::class);
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
             'id' => ['nullable', 'exists:member_points,member_id'],
            // // 职位：在designations数据表中
            // 'designation_id' => ['nullable', 'exists:designations,id'],
        ];
    }
}
