<?php

namespace App\Repositories;

use App\Models\ShopList;
use Meteorlxy\Laravel\Repository\Repository;

class ShopListRepository extends Repository
{
    protected $model = ShopList::class;

    /**
     * 订单列表
     *
     * @param  array    $form
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model()->with('buy_member.user.profile','sell_member.user.profile',"shop_status")->orderBy('buying_state', 'asc');

        if (isset($form['id'])) {
            return $resource->find($form['id']);
        }

        if (isset($form['buying_state'])) {
            if($form['buying_state'] != 3){
                $resource->where('buying_state', '<=', $form['buying_state']);
                
                if (isset($form['seller_id'])){
                    $resource->where('seller_id',$form['seller_id']);
                }
        
                if (isset($form['purchase_id'])){
                    $resource->where('purchase_id',$form['purchase_id']);
                }
            }else{
                $resource->where([['seller_id', '=', $form['seller_id']],
                                  ['buying_state', '>', 2],
                                  ['buying_state', '<', 10],])
                         ->orWhere([['purchase_id', '=', $form['purchase_id']],
                                  ['buying_state', '=', 2],])
                         ->orWhere([['purchase_id', '=', $form['purchase_id']],
                                  ['buying_state', '>', 2],
                                  ['buying_state', '<', 10],]);
            }
        }

        if (isset($form['price'])) {
            if (($form['price'] == 0)) {
                $resource->orderBy('points', 'desc');
            }
            if (($form['price'] == 1)) {
                $resource->orderBy('points', 'asc');
            }
        }
        


        if (isset($form['own'])) {
            if (($form['own'] == 0)) {
                $resource->where('own_id', '=', 0);
            }
            if (($form['own'] == 1)) {
                $resource->where('own_id', '<>', 0);
            }
        }

        if (isset($form['name'])) {
            $resource->where('name', 'like', '%' . $form['name'] . '%');   
        }

        if (isset($form['name'])) {
            $resource->where('name', 'like', '%' . $form['name'] . '%');
           
        }

        if (isset($form['own_name'])) {
            $resource->whereHas('member.user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%' . $form['own_name'] . '%');
            });
        }
        $resource->latest();
        return $resource->paginate($form['perpage']);
    }

    /**
     * 获取单个社员的积分记录
     *
     * @param  array    $form
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find_list(array $form)
    {
        $resource = $this->model()->with('member_points.member', 'member_points.member.user.profile', 'member_points.member.designation', 'member_points.member.department');
        $resource->whereHas('member_points.member', function ($query) use ($form) {
            $query->where('member_id', '=', $form['id']);
        });
        return $resource->paginate($form['perpage']);
    }
    
    /**
     * 买家取消
     *
     * @param  int    $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buy_del($id){
        $resource = $this->model()->where("id",$id);
        $shopRepo = new ShopRepository();
        
        return $shopRepo->restore($resource->first()->items_id,$resource->first()->number) && 
               $resource->update(['buying_state' => 1000]); 
    }
    
    /**
     * 卖家取消
     *
     * @param  int    $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function sell_del($id){
        $resource = $this->model()->where("id",$id);
        $shopRepo = new ShopRepository();
        $pointsRepo = new MemberPointsRepository();
        $memberRepo = new MemberRepository();
        
        if($resource->first()->buying_state == 1){
            return  $shopRepo->restore($resource->first()->items_id,$resource->first()->number) && 
                    $resource->update(['buying_state' => 1001]);
        }
        
        $edit = $memberRepo->getName($resource->first()->seller_id);
        $number = (string)(intval($resource->first()->number)*intval($resource->first()->points));
        $name = (string)($resource->first()->name);
        $desc = "卖家取消了订单,从" . $edit . "购买的" . $name . "退回了" . $number;
        
        return $pointsRepo->return_items($resource->first()->purchase_id,
                                      $resource->first()->number * $resource->first()->points,
                                      $desc) && 
               $shopRepo->restore($resource->first()->items_id,$resource->first()->number) && 
               $resource->update(['buying_state' => 1001]); 
    }
    
    /**
     * 买家支付
     *
     * @param  int    $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buy($id){
        $resource = $this->model()->where("id",$id);
        $pointsRepo = new MemberPointsRepository();
        
        $memberRepo = new MemberRepository();
        
        if ($resource->first()->seller_id == 0){
            $edit = "系统";
        }else{
            $edit = $memberRepo->getName($resource->first()->seller_id);
        }
        
        $number = (string)(intval($resource->first()->number)*intval($resource->first()->points));
        $name = (string)($resource->first()->name);
        $desc = "从" . $edit . "购买了" . $name . "花费了" . $number;
        
        return $pointsRepo->pay_items($resource->first()->purchase_id,
                                      $resource->first()->number * $resource->first()->points,
                                      $desc) && 
               $resource->update(['buying_state' => 2]); 
    }
    
    /**
     * 卖家确认
     *
     * @param  int    $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function sell($id){
        $resource = $this->model()->where("id",$id);
        $pointsRepo = new MemberPointsRepository();
        
        $memberRepo = new MemberRepository();
        
        $edit = $memberRepo->getName($resource->first()->purchase_id);
        
        $number = (string)(intval($resource->first()->number)*intval($resource->first()->points));
        $name = (string)($resource->first()->name);
        $desc = "向" . $edit . "卖出了" . $name . "获得了" . $number;
        
        return $pointsRepo->pay_items($resource->first()->seller_id,
                                      -$resource->first()->number * $resource->first()->points,
                                      $desc) && 
               $resource->update(['buying_state' => 3]); 
    }
    
    /**
     * 添加新的购物记录
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add_list(array $form)
    {
        $new_row = $this->createModel();
        $new_row->items_id = $form['id'];
        $new_row->name = $form['name'];
        $new_row->purchase_id = $form['purchase'];
        $new_row->seller_id = $form['own'];
        $new_row->buying_state = 1;
        $new_row->number = $form['number'];
        $new_row->points = $form['points'];

        return $new_row->save();
    }
    
    /**
     * 设置记录为删除
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function del($id)
    {
        return $this->model()->where("id",$id)->update(['buying_state' => 999]);
    }
    
    /**
     * 申请退款
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function back($id)
    {
        return $this->model()->where("id",$id)->update(['buying_state' => 4]);
    }
    
    /**
     * 申请取消退款
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function back_del($id)
    {
        return $this->model()->where("id",$id)->update(['buying_state' => 3]);
    }
    
    /**
     * 同意申请退款
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function back_agree($id)
    {
        $flag = true;
        $resource = $this->model()->where("id",$id);
        $pointsRepo = new MemberPointsRepository();
        $memberRepo = new MemberRepository();
        
        $buy = $memberRepo->getName($resource->first()->purchase_id);
        
        $number = (string)(intval($resource->first()->number)*intval($resource->first()->points));
        $name = (string)($resource->first()->name);
        
        $sell = "系统";
        if ($resource->first()->seller_id == 0){
            $sell_desc = "向" . $buy . "卖出的" . $name . "退款了" . $number;
        }else{
            $sell = $memberRepo->getName($resource->first()->seller_id);
            $sell_desc = "向" . $buy . "卖出的" . $name . "退款了" . $number;
            $flag = $pointsRepo->pay_items($resource->first()->seller_id,
                                           $resource->first()->number * $resource->first()->points,
                                           $sell_desc);
        }
        
        $buy_desc = "从" . $sell . "购买的" . $name . "退款了" . $number;
        
        return $flag && 
               $pointsRepo->return_items($resource->first()->purchase_id,
                                      $resource->first()->number * $resource->first()->points,
                                      $buy_desc) &&
               $this->model()->where("id",$id)->update(['buying_state' => 5]);
    }
    
}
