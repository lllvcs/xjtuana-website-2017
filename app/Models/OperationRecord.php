<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OperationRecord extends Model
{
    protected $table = 'operation_records';

    protected $primaryKey = 'id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('common', function(Builder $builder) {
            $builder
                ->with('type', 'user')
                ->orderBy('created_at', 'desc');
        });
    }

    // belongsTo User 用户
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    // belongsTo OperationType 操作类型
    public function type() {
        return $this->belongsTo('App\Models\OperationType', 'type_id');
    }

}