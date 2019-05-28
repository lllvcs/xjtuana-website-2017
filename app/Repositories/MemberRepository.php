<?php

namespace App\Repositories;

use App\Models\Member;
use Meteorlxy\Laravel\Repository\Repository;


class MemberRepository extends Repository
{
    protected $model = Member::class;
    
    /**
     * 获取现役社员列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }
    
    /**
     * 获取已退社社员列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function old()
    {
        return $this->model()->onlyTrashed()->get();
    }

    /**
     * 获取单个社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('department', 'designation', 'user.profile', 'buildings.campus')
            ->find($id);
    }

    /**
     * 获取单个社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('department', 'designation', 'user.profile', 'buildings.campus')
            ->findOrFail($id);
    }

    /**
     * 搜索社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->with('user.profile', 'department', 'designation');
        // 是否包含退役社员
        if(isset($form['old_members'])) {
            if ($form['old_members']) {
                $resource->onlyTrashed();
            }
        } else {
            $resource->withTrashed();
        }
        // 社员ID
        if(isset($form['id'])) return $resource->find($form['id']);
        // 所属部门
        if(isset($form['department_id'])) $resource->where('department_id', '=', $form['department_id']);
        // 所属职位
        if(isset($form['designation_id'])) $resource->where('designation_id', '=', $form['designation_id']);
        // 社员NETID
        if(isset($form['netid'])) {
            $resource->whereHas('user', function ($query) use ($form) {
                $query->where('netid', 'like', '%'.$form['netid'].'%');
            });
        }
        // 社员姓名
        if(isset($form['name'])) {
            $resource->whereHas('user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%'.$form['name'].'%');
            });
        }
        
        return $resource->paginate($form['perpage']);
    }

    /**
     * 添加新社员
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        $userRepo = new UserRepository();
        if ( ($user = $userRepo->add($form['netid'])) === false) {
            return false;
        }

        if ( $user->member()->withTrashed()->first() !== null) {
            return false;
        }
        $member = $user->member()->create([
            'department_id' => $form['department_id'],
            'designation_id' => $form['designation_id'],
        ]);
        return $member;
    }
    
    /**
     * 编辑社员
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form) {
        
        $resource = $this->model()->withTrashed()->find($id);
        
        if(isset($form['department_id'])) $resource->department_id = $form['department_id'];
        if(isset($form['designation_id'])) $resource->designation_id = $form['designation_id'];
        
        return $resource->save();
    }
    
    /**
     * 更新社员NETID
     *
     * @param  int      $id
     * @param  string   $neitd
     *
     * @return bool
     */
    public function updateNetid($id, $netid) {
        
        if ( ($member = $this->model()->find($id)) === null) {
            return false;
        }
        
        $userRepo = new UserRepository();
        
        if ( ($newUser = $userRepo->add($netid)) === false) {   // 获取社员新的用户
            return false;
        }
        
        if ( $newUser->member()->withTrashed()->first() ) {     // 如果是现役/退役社员
            return false;
        }
        
        $member->user()->associate($newUser);                   // 将社员关联到新的用户上
        return $member->save();
    }

    /**
     * 删除社员
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        $resource = $this->model()->find($id);
        return $resource->delete();
    }
    
    /**
     * 恢复已删除社员
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function restore($id) {
        $resource = $this->model()->onlyTrashed()->find($id);
        return $resource->restore();
    }

    /**
     * 永久删除社员，前提是已经被软删除
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function forceDelete($id) {
        $resource = $this->model()->onlyTrashed()->find($id);
        return $resource->forceDelete();
    }
    
    /**
     * 导出社员通讯录
     *
     * @return bool
     */
    public function export(string $type) {
        return $this->{'export' . studly_case($type)}();
    }
    
    /**
     * 导出社员通讯录Excel
     *
     * @return bool
     */
    public function exportExcel() {
        return \Excel::create('XJTUANA社团通讯录_' . date('Ymd', time()), function($excel) {
            $excel
                ->setTitle('XJTUANA社团通讯录_' . date('Ymd', time()))
                ->setCreator('system@xjtuana.com')
                ->setLastModifiedBy('system@xjtuana.com')
                ->setCompany('xjtuana')
                ->setSubject('社团通讯录')
                ->setDescription('西安交通大学学生网络管理协会(XJTUANA)社团通讯录 ' . date('Ymd', time()))
                ->setKeywords('xjtuana,members')
                ->sheet('社团通讯录', function($sheet) {
                    $sheet->appendRow([
                        '部门', '职位', '姓名', '性别', '班级', '学号', 'NetID', '宿舍', '手机号', 'QQ', '微信', '支付宝', '邮箱', '生日', '家乡', '自我介绍', '负责宿舍楼'
                    ]);
                    $members = $this->with('department', 'designation', 'user.profile', 'buildings')->get();
                    foreach($members as $member) {
                        $sheet->appendRow([
                            $member->department->name,
                            $member->designation->name,
                            $member->user->profile->name,
                            $member->user->profile->gender,
                            $member->user->profile->class,
                            $member->user->profile->stuid,
                            $member->user->netid,
                            $member->user->profile->dorm_building . '-' . $member->user->profile->dorm_room,
                            $member->user->profile->mobile,
                            $member->user->profile->qq,
                            $member->user->profile->wechat,
                            $member->user->profile->alipay,
                            $member->user->profile->email,
                            $member->user->profile->birthday,
                            $member->user->profile->hometown,
                            $member->user->profile->desc,
                            implode(',', array_pluck($member->buildings, 'name')),
                        ]);
                    }
                });
        });
    }
    
    /**
     * 获取社员名字
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getName($id)
    {
        $people = $this->model()->with('user.profile')->where("id",$id)->first()->profile()->first();
        return $people["name"];
    }
    
    /**
     * 获取社员部门
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getDepartments($id)
    {
        $people = $this->model()->where("id",$id)->first();
        return $people["department_id"];
    }
}