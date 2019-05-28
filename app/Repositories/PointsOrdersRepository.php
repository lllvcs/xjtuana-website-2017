<?php

namespace App\Repositories;

use App\Models\MemberPointsOrders;
use Meteorlxy\Laravel\Repository\Repository;


class PointsOrdersRepository extends Repository
{
    protected $model = MemberPointsOrders::class;

    /**
     * 返回积分多少
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function get_points(array $form)
    {
        $resource = $this->model()->where("order_id",$form['order_id']);
        $record = $resource->first();
        if (isset($record["points"])){
            $points = $record["points"];
            if ($this->update($form)){
                return $points;
            }
        }else{
            if ($this->add($form)){
                return 0;
            }
        }
    }

    /**
     * 添加订单映射
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        $new_row = $this->createModel();
        $new_row->points_id = $form['points_id'];
        $new_row->points = $form['points'];
        $new_row->order_id = $form['order_id'];
        
        return $new_row->save();
    }
    
    /**
     * 更新订单映射
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function update(array $form)
    {
        return $this->model()->where("points_id",$form['points_id'])->update(['points' => $form['points']]);
    }
    
    
    public function delete($id)
    {
        $resource = $this->model()->find($id);
        return $resource->delete();
    }    
}