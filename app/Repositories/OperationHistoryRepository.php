<?php

namespace App\Repositories;

use App\Models\OperationHistory;
use Meteorlxy\Laravel\Repository\Repository;
use Carbon\Carbon;

class OperationHistoryRepository extends Repository
{
    protected $model = OperationHistory::class;

    /**
     * 搜索操作记录
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model();
        if(isset($form['id'])) $resource->where('id', '=', $form['id']);
        if(isset($form['user_id'])) $resource->where('user_id', '=', $form['user_id']);
        if(isset($form['content'])) $resource->where('content', '%'.$form['content'].'%');
        if(isset($form['created_at_start']) && isset($form['created_at_end'])) {
            $resource->whereBetween('created_at', [
                Carbon::parse($form['created_at_start'])->toDatetimeString(),
                Carbon::parse($form['created_at_end'])->modify('+1 day')->toDatetimeString()
            ]);
        }
        
        return $resource->get();
    }

    /**
     * 添加新操作记录
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function add(array $form)
    {

        $resource = $this->createModel();

        $resource->user_id = $form['user_id'];
        $resource->content = $form['content'];

        return $resource->save() ? $resource : false;
    }
    
    /**
     * 删除操作记录
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->model()->destroy($id);
    }
}