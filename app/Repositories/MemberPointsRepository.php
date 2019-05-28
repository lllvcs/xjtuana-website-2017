<?php

namespace App\Repositories;

use App\Models\MemberPoints;
use Auth;
use Meteorlxy\Laravel\Repository\Repository;


class MemberPointsRepository extends Repository
{
    protected $model = MemberPoints::class;
    
    /**
     * 获取积分列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }

    /**
     * 获取单个社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()->with('member', 'member.user.profile', 'member.designation', 'member.department')->where('member_id', $id)->first();
    }

    /**
     * 获取单个社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()->with('member', 'member.user.profile', 'member.designation', 'member.department')->where('member_id', $id)->first();
    }
    /**
     * 多个表单给积分
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function editorder(array $form)
    {
        $flag = true;
        foreach ($form['memberList'] as $index => $memberId) {
            $new_form['editor'] = $form['editor'];
            $new_form['desc'] = $form['desc'] . " 由表单" . $form['orderList'][$index] . "加".$form['points'] . "分";
            $new_form['points'] = $form['points'];
            $new_form['id'] = $form['memberList'][$index];
            $new_form['netid'] = $form['netidList'][$index];
            $new_form['order_id'] = $form['orderList'][$index];
            if($new_form['points'] != null && $new_form['id'] != null && $new_form['netid'] != null && $new_form['editor'] != null && $new_form['id'] != null && $new_form['desc'] != null && $form['orderList'][$index] != null){       
                $flag = $flag && $this->update( $form['memberList'][$index], $new_form);
            }


        }
        return $flag;
    }
    /**
     * 搜索社员
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->with('member', 'member.user.profile', 'member.designation', 'member.department');
        if(isset($form['old_members']) && $form['old_members']) $resource->onlyTrashed();
        if(isset($form['id'])) return $resource->find($form['id']);
        if(isset($form['department_id'])) 
            $resource->whereHas('member.department', function ($query) use ($form) {
                $query->where('department_id', '=', $form['department_id']);
            });        
        if(isset($form['designation_id'])) 
            $resource->whereHas('member.designation', function ($query) use ($form) {
                $query->where('designation_id', '=', $form['designation_id']);
            });            
        
        if(isset($form['name'])) {
            $resource->whereHas('member.user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%'.$form['name'].'%');
            });
        }

        return $resource->paginate($form['perpage']);
    }

    /**
     * 添加新的积分
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        if(isset($form['id'])){
            $resource = $this->model()->where('member_id', $form['id'])->first();
        }          
        if(isset($resource['member_id'])){
            return true;
        }      
        if(isset($form['netid'])){
            $userRepo = new UserRepository();
            $user = $userRepo->findPointNeed($form['netid']);
            
            if (!isset($user->member)) return false;
            $resource = $this->find($user->member->id);
            if(!isset($resource['member_id']))
                if(isset($form['points'])) {
                    if($form['points'] > 5){
                        $form['points'] = 5;
                    }
                    $new_row = $this->createModel();
                    $new_row->member_id = $user->member->id;
                    $new_row->points = $form['points'];
                    return $new_row->save();
                }            
        }
        return false;
    }  

    /**
     * 编辑积分
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */    
    public function update($id, array $form) {
        $order_row = 1;
        $record_row = 0;
        $flag = 1;
        
        $orderRepo = new OrderRepository();
        //$memberRepo = new MemberRepository();
        $pointsOrdersRepo = new PointsOrdersRepository();
        
        $user = Auth::user()->member()->first();
        
        if (isset($form['order_id'])){
            $order_departments_id = $orderRepo->getDepartments($form['order_id']);
            
            if($user['department_id'] > 2 && 
               $order_departments_id !== $user['department_id']){
                return false;
            }
            
            $flag =$orderRepo->completePoint($form['order_id']);
            
            $order_form['order_id'] = $form['order_id'];
            $order_form['points'] = $form['points'];
            
            unset($form['order_id']);
            if(isset($form['points'])){
                if ($form['points'] > 5){
                    $form['points'] = 5;
                }
            }
        }
        
        $resource = $this->model()->where('member_id', $id)->first();
        
        if(isset($form['editor'])){
            $editor = $form['editor'];
            unset($form['editor']);
        }
        if(!isset($resource['member_id'])){
            if($this->add($form))
            {
                $resource = $this->model()->where('member_id', $id)->first();
                $form['points'] = 0;
            }else {
                return true;
            }
        }
        $last_points = 0;
        if (isset($order_form['order_id'])){
            $order_form['points_id'] = $resource['id'];
            $last_points = $pointsOrdersRepo->get_points($order_form);
        }
        
        $form['editor'] = $editor;
        if(isset($form['points'])){
            //points limits
            if ($form['points'] > 5){
                    $form['points'] = 5;
            }
            
            $form['points'] = $form['points'] - $last_points;
            $form['points'] = $resource['points'] + $form['points'];
            $resource['points'] = $form['points'];
            $recordRope = new PointsRecordRepository();
            $record_row = $recordRope->add($resource['id'],$form);
        }
        
        // if(isset($form['orderId'])){
        //     $orderRope = new PointsOrdersRepository();
        //     $order_row = $orderRope->add($form);
        // }

        return $resource->save() && $record_row && $order_row && $flag;
    }  
    
    /**
     * 支付积分
     *
     * @param  int  $member_id
     * @param  int  $points
     * @param  string  $desc
     *
     * @return bool
     */
    public function pay_items($member_id, $points ,$desc){
        $resource = $this->model()->where('member_id', $member_id);
        $point = $resource->first();
        
        if(isset($point["member_id"])){
            if (($point["points"] - $points) < 0)
                return false;
            $recordRope = new PointsRecordRepository();
            $form['id'] = $point["id"];
            $form['editor'] = "商店购物";
            $form['points'] = $point["points"] - $points;
            $form['desc'] = $desc;
            
            $record_row = $recordRope->add($point["id"],$form);
            
            return $record_row && $resource->update(['points' => ($point["points"] - $points)]);
        }
        
        return false;
    }
    
    /**
     * 退回积分
     *
     * @param  int  $member_id
     * @param  int  $points
     * @param  string  $desc
     *
     * @return bool
     */
     public function return_items($member_id, $points ,$desc){
        $resource = $this->model()->where('member_id', $member_id);
        $point = $resource->first();
        
        if(isset($point["member_id"])){

            $recordRope = new PointsRecordRepository();
            $form['id'] = $point["id"];
            $form['editor'] = "商店购物";
            $form['points'] = $point["points"] + $points;
            $form['desc'] = $desc;
            
            $record_row = $recordRope->add($point["id"],$form);
            
            return $record_row && $resource->update(['points' => ($point["points"] + $points)]);
        }
        
        return false;
    }
}