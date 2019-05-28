<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    // 批量赋值字段
    protected $fillable = [
        'name',
        'gender',
        'stuid',
        'college',
        'class',
        'dorm_building',
        'dorm_room',
        'mobile',
        'email',
        'hometown',
        'birthday'
    ];
    
    // 隐藏字段
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    // belongsTo User 用户
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // belongsTo Building 宿舍楼
    public function building() {
        return $this->belongsTo('App\Models\Building', 'dorm_building', 'name');
    }

}