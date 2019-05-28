<?php

namespace App\Repositories;

use App\Models\User;
use Meteorlxy\Laravel\Repository\Repository;
use WsUserInfo;
use WsUserPhoto;

class UserRepository extends Repository
{
    protected $model = User::class;

    /**
     * 根据Netid查找用户
     *
     * @param  string   $netid
     *
     * @return \Illuminate\Database\Eloquent\Model | null
     */
    public function find($netid) {
        return $this->model()->where('netid', $netid)->first();
    }

    /**
     * 根据Netid查找用户  积分系统接口
     *
     * @param  string   $netid
     *
     * @return \Illuminate\Database\Eloquent\Model | null
     */
    public function findPointNeed($netid) {
        return $this->model()->with('member')->where('netid', $netid)->first();
    }

    /**
     * 通过Netid添加用户
     *
     * @param  string   $netid
     *
     * @return \Illuminate\Database\Eloquent\Model | null
     */
    public function add($netid) {
        
        if (($user = $this->find($netid)) === null) {
            if (($userinfo = $this->fetchProfile($netid)) === null) {
                return false;
            }
            $user = $this->model()->create([
                'netid' => $netid
            ]);
            $this->updateProfile($user, $userinfo);
        }
        return $user; 
    }
    
    /**
     * 根据Netid，通过Webservice获取并自动更新其用户资料
     * 前提是netid已存在于users表中
     *
     * @param  string   $netid
     * @param  bool     $isForced
     *
     * @return bool
     */
    public function autoUpdateProfile($netid, $isForced = false) {
        if (($user = $this->find($netid)) === null) {
            return false;
        }

        if ( $user->profile === null || $isForced ) {
            $userinfo = $this->fetchProfile($netid);
            return $this->updateProfile($user, $userinfo);
        }
        return true;
    }

    /**
     * 根据Netid，通过Webservice获取其用户资料
     *
     * @param  string   $netid
     * @param  bool     $isForced
     *
     * @return Object | null
     */
    public function fetchProfile($netid) {
        
        try {
            if ( empty($userinfo = WsUserInfo::getByNetid($netid)) ) {
                return null;
            }
            return $userinfo[0];
        } catch(\Exception $e) {
            return null;
        }
    }
    
    /**
     * 通过传入的用户信息更新profile
     *
     * @param  \App\Models\User | string    $user       传入User实例或netid
     * @param  Object                       $userinfo
     *
     * @return bool
     */
    public function updateProfile($user, $userinfo) {
        if (!is_object($userinfo)) {
            return false;
        }
        
        if (!$user instanceof User) {
            if (($user = $this->find($user)) === null) {
                return false;
            }
        }

        $dorm = $this->parseDorm($userinfo->roomnumber);

        $profile_arr = [
            'name' => $userinfo->username,
            'gender' => $userinfo->gender,
            'stuid' => $userinfo->userno,
            'college' => $userinfo->dep,
            'class' => $userinfo->classid,
            'dorm_building' => $dorm['dorm_building'],
            'dorm_room' => $dorm['dorm_room'],
            'mobile' => $userinfo->mobile,
            'email' => $userinfo->email,
            'hometown' => $userinfo->nationplace,
            'birthday' => $userinfo->birthday
        ];

        // 若用户profile不存在则调用create方法创建，已存在则使用update方法更新
        if (($profile = $user->profile) === null) {
            return $user->profile()->create($profile_arr);
        } else {
            return $profile->update($profile_arr);
        }
    }
    

    /**
     * 根据WebService返回的宿舍字符串，分离出宿舍楼和房间号
     *
     * @param  string   $str_dorm
     *
     * @return array
     */
    public function parseDorm($str_dorm) {
        if(preg_match('/^(.+)-(\d+)$/', $str_dorm, $match)){
            $dorm['dorm_building'] = $match[1];
            $dorm['dorm_room'] =  $match[2];
        }
        else{
            $dorm['dorm_building'] = '';
            $dorm['dorm_room'] =  '';
        }
        return $dorm;
    }
    
    /**
     * 删除用户
     *
     * @param  string  $netid
     *
     * @return bool
     */
    public function delete($netid) {
        $resource = $this->find($netid);
        return $resource->delete();
    }
    
    /**
     * 用户手动更新个人资料
     *
     * @param  \App\Models\User | string    $user       传入User实例或netid
     * @param  array                        $form
     *
     * @return bool
     */
    public function userUpdateProfile($user, array $form) {
        if (!$user instanceof User) {
            if (($user = $this->find($user)) === null) {
                return false;
            }
        }
        
        $profile = $user->profile;
        
        if (isset($form['mobile'])) $profile->mobile = $form['mobile'];
        if (isset($form['qq'])) $profile->qq = $form['qq'];
        if (isset($form['wechat'])) $profile->wechat = $form['wechat'];
        if (isset($form['email'])) $profile->email = $form['email'];
        if (isset($form['birthday'])) $profile->birthday = $form['birthday'];
        if (isset($form['hometown'])) $profile->hometown = $form['hometown'];
        if (isset($form['desc'])) $profile->desc = $form['desc'];

        return $profile->save();
    }

}