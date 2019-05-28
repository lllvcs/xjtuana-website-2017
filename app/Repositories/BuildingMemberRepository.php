<?php

namespace App\Repositories;

use App\Models\Building;
use Meteorlxy\Laravel\Repository\Repository;


class BuildingMemberRepository extends Repository
{
    protected $model = Building::class;
    
    /**
     * 获取单个宿舍楼负责网管
     *
     * @param  int  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id) {
        return $this->model()
            ->with('members.user.profile', 'members.department', 'members.designation')
            ->find($id);
    }
    
    /**
     * 搜索宿舍楼负责网管
     *
     * @param  int  $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all(array $form) {
        
        $resource = $this->model()->with('members.user.profile', 'members.department', 'members.designation');
        if(isset($form['building_id'])) $resource->where('id', '=', $form['building_id']);
        if(isset($form['department_id'])) $resource->where('department_id', '=', $form['department_id']);
        
        return $resource->get();
    }
    
    /**
     * 编辑宿舍楼负责网管
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form) {
        
        $resource = $this->model()->find($id);
        
        return $resource->members()->sync($form['members']);
    }

}