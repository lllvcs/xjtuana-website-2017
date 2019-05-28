<?php

namespace App\Repositories;

use App\Models\MemberPointsRecord;
use Meteorlxy\Laravel\Repository\Repository;


class PointsRecordRepository extends Repository
{
    protected $model = MemberPointsRecord::class;

    /**
     * 获取单个社员的积分记录
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find_list(array $form)
    {
        $resource = $this->model()->with('member_points.member','member_points.member.user.profile', 'member_points.member.designation', 'member_points.member.department')->orderBy('id', 'desc');
        $resource->whereHas('member_points.member', function ($query) use ($form) {
            $query->where('member_id', '=', $form['id']);
        });         
        return $resource->paginate($form['perpage']);
    }    

    /**
     * 添加新的积分
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add($id, array $form)
    {
        if(isset($form['id'])){
            $new_row = $this->createModel();
            $new_row->member_points_id = $id;
            if (isset($form['points'])){
                $new_row->change = $form['points'];
                if(isset($form['desc'])){
                    $new_row->desc = $form['desc'];
                    if(isset($form['editor'])){
                        $new_row->editor = $form['editor'];
                    }
                }
            }            
        }
        return $new_row->save();
    }

    public function delete($id)
    {
        $resource = $this->model()->find($id);
        return $resource->delete();
    }    
}