<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechatAccount extends Model
{
    protected $table = 'user_wechat_accounts';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['openid'];
    
    // 隐藏字段
    protected $hidden = [
        'user_id',
        'updated_at',
    ];
    
    // belongsTo User 用户
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}