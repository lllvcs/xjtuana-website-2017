<?php

namespace App\Http\Requests\API\Order;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        return $this->user()->can('report', \App\Models\Order::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 评分：必须 | 1-5
            'score' => ['required', 'integer', 'in:1,2,3,4,5'],
            // 服务类别：必须 | 整数 | 在order_service数据表中
            'service_id' => ['required', 'integer', 'exists:order_services,id'],
            // 报告详情：必须 | 字符串
            'detail' => ['required', 'string'],
        ];
    }
}
