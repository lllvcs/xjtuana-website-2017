<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isMember();
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
            'status_id' => ['nullable', 'exists:order_status,id'],
            // 部门：在departments数据表中
            'department_id' => ['nullable', 'exists:departments,id'],
            // 宿舍楼：在buildings数据表中
            'building_id' => ['nullable', 'exists:buildings,id'],
            // 房间号：字符串
            'room' => ['nullable', 'string', 'max:10'],
            // 服务类别：整数 | 在order_service数据表中
            'service_id' => ['nullable', 'integer', 'exists:order_services,id'],
            // 关键词：字符串
            'keyword' => ['nullable', 'string'],
            // 报修时间开始：日期
            'created_at_start' => ['required', 'date_format:Y-m-d'],
            // 报修时间结束：日期
            'created_at_end' => ['required', 'date_format:Y-m-d'],
            // 当前页数：数字
            'page' => ['required', 'integer'],
            // 每页条目数：数字
            'perpage' => ['required', 'integer'],
        ];
    }
}
