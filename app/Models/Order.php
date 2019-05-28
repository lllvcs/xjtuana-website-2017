<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    protected $table = 'orders';

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

        static::addGlobalScope('orderScope', function(Builder $builder) {
            $builder
                ->withTrashed()
                ->with('status', 'building', 'service')
                ->orderBy('status_id', 'asc')
                ->orderBy('created_at', 'desc');
        });
    }
    
    /**
     * 获取详细信息
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithDetail($query, $hiddenProfile = false)
    {
        $query = $query->with([
            'user.profile', 
            'member.points',
            'member.user.profile', 
            'member.department', 
            'member.designation', 
            'building.campus', 
            'feedback_user', 
            'feedback_member.service',
        ]);
        
        return $hiddenProfile ? $query->hiddenProfile() : $query;
    }
    
    /**
     * 对用户隐藏部分信息
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHiddenProfile($query)
    {
        return $query->with([
            'member' => function ($query) {
                $query->select('id', 'user_id', 'department_id', 'designation_id')->with([
                    'user' => function ($query) {
                        $query->select('id')->with([
                            'profile' => function ($query) {
                                $query->select('user_id', 'name');
                            }    
                        ]);
                    },
                    'department',
                    'designation',
                ]);
            },
            'user' => function ($query) {
                $query->select('id')->with([
                    'profile' => function ($query) {
                        $query->select('user_id', 'name', 'stuid', 'gender');
                    }    
                ]);
            },
            'feedback_member' => function ($query) {
                $query->select('order_id', 'detail');
            },
        ]);
    }

    // belongsTo User 用户
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // belongsTo Member 社员
    public function member() {
        return $this->belongsTo('App\Models\Member', 'member_id')->withTrashed();
    }

    // belongsTo OrderStatus 状态
    public function status() {
        return $this->belongsTo('App\Models\OrderStatus', 'status_id');
    }

    // belongsTo OrderService 服务
    public function service() {
        return $this->belongsTo('App\Models\OrderService', 'service_id');
    }

    // belongsTo Building 宿舍楼
    public function building() {
        return $this->belongsTo('App\Models\Building', 'building_id');
    }

    // hasOne OrderFeedbackUser 用户反馈
    public function feedback_user() {
        return $this->hasOne('App\Models\OrderFeedbackUser', 'order_id');
    }

    // hasOne OrderFeedbackMember 社员反馈
    public function feedback_member() {
        return $this->hasOne('App\Models\OrderFeedbackMember', 'order_id');
    }

    // hasOne OrderPoints 报修单积分
    public function points() {
        return $this->hasOne('App\Models\OrderPoints', 'order_id');
    }
}