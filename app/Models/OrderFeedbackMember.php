<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFeedbackMember extends Model
{
    protected $table = 'order_feedback_member';

    protected $primaryKey = 'id';
    
    protected $fillable = ['score', 'service_id', 'detail'];

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // belongsTo Order 订单
    public function order() {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    // belongsTo OrderService 订单服务
    public function service() {
        return $this->belongsTo('App\Models\OrderService', 'service_id');
    }
    
}