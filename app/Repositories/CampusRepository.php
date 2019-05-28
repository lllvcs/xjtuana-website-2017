<?php

namespace App\Repositories;

use App\Models\Campus;
use Meteorlxy\Laravel\Repository\Repository;


class CampusRepository extends Repository
{
    protected $model = Campus::class;
    
    /**
     * 获取校区列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->with('buildings')->get();
    }

    /**
     * 获取单个校区
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->find($id);
    }

    /**
     * 获取单个校区
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->findOrFail($id);
    }

    /**
     * 搜索校区
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model();
        if(isset($form['id'])) return $resource->find($form['id']);
        if(isset($form['name'])) $resource->where('name', 'like', '%'.$form['name'].'%');
        
        return $resource->get();
    }

    /**
     * 添加新校区
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
     * 删除校区
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
    
}