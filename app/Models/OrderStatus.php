<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany Order 订单
    public function orders() {
        return $this->belongsTo('App\Models\Order', 'status_id');
    }

}