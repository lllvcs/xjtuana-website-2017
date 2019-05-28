<?php

namespace App\Repositories;

use App\Models\Faq;
use Meteorlxy\Laravel\Repository\Repository;


class FaqRepository extends Repository
{
    protected $model = Faq::class;
    
    /**
     * 获取所有Faq
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->with('category')->get();
    }
    
    /**
     * 获取单个Faq
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('category')
            ->find($id);
    }

    /**
     * 获取单个Faq
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->withTrashed()
            ->with('category')
            ->findOrFail($id);
    }

    /**
     * 搜索Faq
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->with('category');
        if(isset($form['is_valid']) && !$form['is_valid']) $resource->onlyTrashed();
        if(isset($form['id'])) return $resource->find($form['id']);
        if(isset($form['category_id'])) $resource->where('category_id', '=', $form['category_id']);
        if(isset($form['question'])) $resource->where('question', 'like', '%'.$form['question'].'%');
        if(isset($form['answer'])) $resource->where('answer', 'like', '%'.$form['answer'].'%');
        
        return $resource->paginate($form['perpage']);
    }

    /**
     * 添加新Faq
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        $resource = $this->createModel();

        $resource->category_id = $form['category_id'];
        $resource->index = $form['index'];
        $resource->question = $form['question'];
        $resource->answer = $form['answer'];

        return $resource->save() ? $resource : false;
    }
    
    /**
     * 编辑Faq
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form) {
        
        $resource = $this->model()->withTrashed()->find($id);
        
        if(isset($form['category_id'])) $resource->category_id = $form['category_id'];
        if(isset($form['index'])) $resource->index = $form['index'];
        if(isset($form['question'])) $resource->question = $form['question'];
        if(isset($form['answer'])) $resource->answer = $form['answer'];
        
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
    
    /**
     * 恢复已删除Faq
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
     * 永久删除Faq，前提是已经被软删除
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function forceDelete($id) {
        $resource = $this->model()->onlyTrashed()->find($id);
        return $resource->forceDelete();
    }
    
}