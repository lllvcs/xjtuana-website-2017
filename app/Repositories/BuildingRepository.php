<?php

namespace App\Repositories;

use App\Models\Building;
use Meteorlxy\Laravel\Repository\Repository;


class BuildingRepository extends Repository
{
    protected $model = Building::class;
    
    /**
     * 获取宿舍楼列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }

    /**
     * 获取单个宿舍楼
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->find($id);
    }

    /**
     * 获取单个宿舍楼
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->findOrFail($id);
    }

    /**
     * 搜索宿舍楼
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
     * 添加新宿舍楼
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
     * 删除宿舍楼
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
    
}