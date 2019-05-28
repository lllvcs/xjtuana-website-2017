<?php

namespace App\Repositories;

use App\Models\Department;
use Meteorlxy\Laravel\Repository\Repository;


class DepartmentRepository extends Repository
{
    protected $model = Department::class;
    
    /**
     * 获取部门列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->with('buildings', 'members.user.profile')->get();
    }

    /**
     * 获取单个部门
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('user.profile', 'member.user.profile', 'building.campus', 'feedback_user', 'feedback_member')
            ->find($id);
    }

    /**
     * 获取单个部门
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('user.profile', 'member.user.profile', 'building.campus', 'feedback_user', 'feedback_member')
            ->findOrFail($id);
    }

    /**
     * 搜索部门
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model();
        if(isset($form['id'])) $resource->where('id', '=', $form['id']);
        if(isset($form['name'])) $resource->where('name', 'like', '%'.$form['name'].'%');
        
        return $resource->get();
    }

    /**
     * 添加新部门
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function add(array $form)
    {
        $resource = $this->createModel();

        $resource->name = $form['name'];

        return $resource->save() ? $resource : false;
    }

    /**
     * 删除部门
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
    

}