<?php

namespace App\Http\Requests\API\Order;

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
        return $this->user()->can('search', \App\Models\Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 报修单ID：整数
            'id' => ['nullable', 'integer'],
            // 报修单状态：在order_status数据表中
            'status_id' => ['nullable', 'exists:order_status,id'],
            // 部门：在departments数据表中
            'department_id' => ['nullable', 'exists:departments,id'],
            // 宿舍楼：在buildings数据表中
            'building_id' => ['nullable', 'exists:buildings,id'],
            // 房间号：字符串
            'room' => ['nullable', 'string', 'max:10'],
            // 手机号
            'mobile' => ['nullable', 'regex:/^1[3-9]\d{9}$/'],
            // QQ号
            'qq' => ['nullable', 'regex:/^[1-9]\d{4,9}$/'],
            // 微信号：参考 http://kf.qq.com/touch/faq/120813euEJVf141212Vfi6fA.html?platform=15
            'wechat' => ['nullable', 'regex:/^[a-zA-Z][a-zA-Z\d_-]{5,19}$/'],
            // 服务类别：整数 | 在order_service数据表中
            'service_id' => ['nullable', 'integer', 'exists:order_services,id'],
            // 关键词：字符串
            'keyword' => ['nullable', 'string'],
            // 报修用户id：在users数据表中
            'user_id' => ['nullable', 'exists:users,id'],
            // 报修用户netid：字符串
            'user_netid' => ['nullable', 'string'],
            // 报修用户姓名：字符串
            'user_name' => ['nullable', 'string'],
            // 负责网管id：在members数据表中
            'member_id' => ['nullable', 'exists:members,id'],
            // 负责网管netid：字符串
            'member_netid' => ['nullable', 'string'],
            // 负责网管姓名：字符串
            'member_name' => ['nullable', 'string'],
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
