<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany Building 宿舍楼
    public function buildings() {
        return $this->hasMany('App\Models\Building', 'department_id');
    }

    // hasMany members 社员
    public function members() {
        return $this->hasMany('App\Models\Member', 'department_id');
    }

    // mangagers 部门负责人
    public function managers() {
        return $this->hasMany('App\Models\Member', 'department_id')->where('designation_id', 4);
    }
    
}