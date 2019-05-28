<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    protected $table = 'order_services';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany Order 订单
    public function orders() {
        return $this->belongsTo('App\Models\Order', 'service_id');
    }

    // hasMany OrderFeedbackMember 维修报告
    public function order_feedback_member() {
        return $this->belongsTo('App\Models\OrderFeedbackMember', 'service_id');
    }

}