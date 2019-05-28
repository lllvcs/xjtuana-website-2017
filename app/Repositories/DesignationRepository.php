<?php

namespace App\Repositories;

use App\Models\Designation;
use Meteorlxy\Laravel\Repository\Repository;


class DesignationRepository extends Repository
{
    protected $model = Designation::class;
    
    /**
     * 获取职位列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }

    /**
     * 获取单个职位
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
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
            ->findOrFail($id);
    }

    /**
     * 搜索职位
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
     * 添加新职位
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
     * 删除职位
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
    

}