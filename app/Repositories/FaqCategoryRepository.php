<?php

namespace App\Repositories;

use App\Models\FaqCategory;
use Meteorlxy\Laravel\Repository\Repository;


class FaqCategoryRepository extends Repository
{
    protected $model = FaqCategory::class;
    
    /**
     * 获取所有Faq类别
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }
    
    /**
     * 获取单个Faq类别
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()->withCount('faqs')->find($id);
    }

    /**
     * 获取单个Faq类别
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()->withCount('faqs')->findOrFail($id);
    }

    /**
     * 搜索Faq类别
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->with('category');
        if(isset($form['id'])) return $resource->find($form['id']);
        if(isset($form['name'])) $resource->where('name', 'like', '%'.$form['name'].'%');
        
        return $resource->paginate($form['perpage']);
    }

    /**
     * 添加新Faq类别
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        $resource = $this->createModel();

        $resource->name = $form['name'];

        return $resource->save() ? $resource : false;
    }
    
    /**
     * 编辑Faq类别
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form) {
        
        $resource = $this->model()->find($id);
        
        if(isset($form['name'])) $resource->name = $form['name'];
        
        return $resource->save();
    }

    /**
     * 删除Faq
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        $resource = $this->model()->find($id);
        return $resource->delete();
    }
    
}