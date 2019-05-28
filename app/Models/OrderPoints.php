<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class OrderPoints extends Model
{
    protected $table = 'order_points';

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

        static::addGlobalScope('OrderPointsScope', function(Builder $builder) {
            $builder
                ->orderBy('created_at', 'desc');
        });
    }

	// belongsTo Order 表单
    public function order() {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }    
}
