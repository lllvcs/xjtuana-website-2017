<?php

namespace App\Http\Requests\API\OrderStatistic;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
            // 部门：在department数据表中
            'department_id' => ['nullable', 'exists:departments,id'],
            // 报修时间开始：日期
            'created_at_start' => ['required', 'date_format:Y-m-d'],
            // 报修时间结束：日期
            'created_at_end' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
