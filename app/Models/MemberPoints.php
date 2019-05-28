<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPoints extends Model
{
    protected $table = 'member_points';

    protected $primaryKey = 'id';

    // belongsTo Member 社员
    public function member() {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }

    // hasMany MemberPointsRecord 积分记录
    public function records() {
        return $this->hasMany('App\Models\MemberPointsRecord', 'member_points_id');
    }
}