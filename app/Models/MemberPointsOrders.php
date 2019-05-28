<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class MemberPointsOrders extends Model
{
    protected $table = 'member_points_orders';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('pointsRecordScope', function(Builder $builder) {
            $builder
                ->orderBy('created_at', 'desc');
        });
    }

    // belongsTo MemberPoints 社员积分
    public function member_points() {
        return $this->belongsTo('App\Models\MemberPoints', 'points_id');
    }

    // belongsTo MemberPoints 社员积分
    public function orders() {
        return $this->belongsTo('App\Models\MemberPoints', 'order_id');
    }

}