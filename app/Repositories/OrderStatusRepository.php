<?php

namespace App\Repositories;

use App\Models\OrderStatus;
use Meteorlxy\Laravel\Repository\Repository;

class OrderStatusRepository extends Repository
{
    protected $model = OrderStatus::class;
    
    /**
     * 获取所有报修单状态
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()
            ->get();
    }

    /**
     * 获取单个报修单状态
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->find($id);
    }

    /**
     * 获取单个报修单状态
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->findOrFail($id);
    }

    /**
     * 搜索报修单状态
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->withTrashed();
        
        if(isset($form['id'])) $resource->where('id', '=', $form['id']);
        if(isset($form['name'])) $resource->where('name', '=', $form['name']);
        
        return $resource->get();
    }

    /**
     * 添加新报修单状态
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
     * 删除报修单状态
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
    

}