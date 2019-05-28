<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xjtuana\Cas\Auth\CasUser;

class User extends CasUser
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $authIdentifierName = 'netid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['netid'];
    
    // 隐藏字段
    protected $hidden = [
        'id', 
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // SoftDeletes
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /* ========== Relation Begin ============ */


    // hasOne UserWechatAccount
    public function wechat_account() {
        return $this->hasOne('App\Models\UserWechatAccount', 'user_id');
    }
    
    // hasOne UserProfile
    public function profile() {
        return $this->hasOne('App\Models\UserProfile', 'user_id');
    }

    // hasOne Member
    public function member() {
        return $this->hasOne('App\Models\Member', 'user_id');
    }

    // hasMany Order
    public function orders() {
        return $this->hasMany('App\Models\Order', 'user_id')->withTrashed();
    }

    /**
     * 判断用户是否为社员
     *
     * @return bool
     */
    public function isMember() {
        return ! empty($this->member);
    }
    
    /**
     * 判断用户是否为部长以上
     *
     * @return bool
     */
    public function isManager() {
        return $this->isMember() && $this->member->designation_id < 5;
    }
    
    /**
     * 判断用户是否为社长团/研发部长
     *
     * @return bool
     */
    public function isAdmin() {
        return $this->isManager() && ($this->member->department_id === 1 || $this->member->department_id === 2);
    }

    /**
     * 判断用户是否有近期撤销订单
     *
     * @return bool
     */
    public function hasRecentCancel() {
        $cancelledOrder = $this
            ->orders()
            ->withoutGlobalScopes()
            ->onlyTrashed()
            ->where('status_id', 99)
            ->orderBy('deleted_at', 'desc')
            ->first();
        return $cancelledOrder === null ? false : 
            time() - strtotime($cancelledOrder->deleted_at) < 1800 ? true : false;
    }

}
