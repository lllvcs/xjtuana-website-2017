<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFeedbackUser extends Model
{
    protected $table = 'order_feedback_user';

    protected $primaryKey = 'id';
    
    protected $fillable = ['score', 'detail'];

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // belongsTo Order 订单
    public function order() {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
    
}