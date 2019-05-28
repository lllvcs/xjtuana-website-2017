<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Member extends Model
{
    protected $table = 'members';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    // 批量赋值字段
    protected $fillable = [
        'department_id',
        'designation_id',
    ];
    
    // 隐藏字段
    protected $hidden = [
        'user_id', 
        'updated_at',
    ];
    
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
                ->orderBy('department_id', 'asc')
                ->orderBy('designation_id', 'asc')
                ->orderBy('created_at', 'asc');
        });
    }

    // belongsTo User 用户
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    // hasOne UserProfile
    public function profile() {
        return $this->user->profile();
    }
    
    // hasMany Order
    public function orders() {
        return $this->hasMany('App\Models\Order', 'member_id');
    }

    // belongsTo Department 部门
    public function department() {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    // belongsTo Designation 职位
    public function designation() {
        return $this->belongsTo('App\Models\Designation', 'designation_id');
    }

    // hasOne MemberPoints 积分
    public function points() {
        return $this->hasOne('App\Models\MemberPoints', 'member_id');
    }

    // belongsToMany Building 宿舍楼
    public function buildings() {
        return $this->belongsToMany('App\Models\Building', 'buildings_members', 'member_id', 'building_id');
    }
    
    /**
     * 获取待处理报修单
     *
     * @return bool
     */
    public function todoOrders() {
        return $this->orders()->where('status_id', '<', 3)->get();
    }

}