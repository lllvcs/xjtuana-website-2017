<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    protected $table = 'operation_records';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany OperationRecord 操作记录
    public function records() {
        return $this->hasMany('App\Models\OperationRecord', 'type_id');
    }
    
}