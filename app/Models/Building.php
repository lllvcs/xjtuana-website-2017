<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // belongsTo Campus 校区
    public function campus() {
        return $this->belongsTo('App\Models\Campus', 'campus_id');
    }

    // belongsTo Department 部门
    public function department() {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    // belongsToMany Member 社员
    public function members() {
        return $this->belongsToMany('App\Models\Member', 'buildings_members', 'building_id', 'member_id');
    }
    
    // hasMany Order 报修单
    public function orders() {
        return $this->hasMany('App\Models\Order', 'building_id');
    }

    // hasMany UserProfile 用户信息
    public function user_profiles() {
        return $this->hasMany('App\Models\UserProfile', 'dorm_building');
    }

}