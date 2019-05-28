<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'campuses';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    // hasMany Building 宿舍楼
    public function buildings() {
        return $this->hasMany('App\Models\Building', 'campus_id');
    }
    
}