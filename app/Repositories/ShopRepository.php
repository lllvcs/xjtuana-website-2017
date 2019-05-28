<?php

namespace App\Repositories;

use App\Models\Shop;
use Meteorlxy\Laravel\Repository\Repository;

class ShopRepository extends Repository
{
    protected $model = Shop::class;

    /**
     * 获取礼物列表
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function all()
    {
        return $this->model()->get();
    }
    
    
    /**
     * 取消订单后回复数目
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restore($id, $number)
    {
        $resource = $this->model()->where("id",$id);
        $shop = $resource->first();
        
        if (isset($shop["id"])){
            if (($shop["sold"] - $number) >= 0 ){
                return $resource->update(['sold' => ($shop["sold"] - $number)]);
            }else{
                return $resource->update(['sold' => 0]);
            }
        }
        return true;
    }
    
    /**
     * 获取单个礼物
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()->with('member')->where('own_id', $id)->first();
    }

    /**
     * 搜索礼物
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model();
        
        if (isset($form['id'])) {
            return $resource->find($form['id']);
        }

        if ($form['point_filter'] == 1) {
            $resource->where('points', '<=', $form['my_points']);
        }

        if (isset($form['time'])) {
            if (($form['time'] == 0)) {
                $resource->latest();
            }
            if (($form['time'] == 1)) {
                $resource->oldest();
            }
        }

        if (isset($form['items'])) {
            if (($form['items'] == 1)) {
                $resource->whereColumn('number', '>', 'sold');
            }
            if (($form['items'] == 2)) {
                $resource->whereColumn('number', '=', 'sold');
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

        if (isset($form['details'])) {
            $resource->where('details', 'like', '%' . $form['details'] . '%');   
        }

        if (isset($form['name'])) {
            $resource->where('name', 'like', '%' . $form['name'] . '%');
           
        }

        if (isset($form['own_name'])) {
            $resource->whereHas('member.user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%' . $form['own_name'] . '%');
            });
        }

        return $resource->paginate($form['perpage']);
    }

    /**
     * 添加新的物品
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function add(array $form)
    {
        $resource = $this->model()->where('own_id', $form['own'])->first();

        if (isset($resource['own_id']) && $resource['own_id']) {
            return false;
        }
        if($form['number'] <= 0){
            return false;
        }
        $new_row = $this->createModel();

        $new_row['own_id'] = $form['own'];
        $new_row['name'] = $form['name'];
        $new_row['points'] = $form['points'];
        $new_row['number'] = $form['number'];
        $new_row['own_name'] = $form['own_name'];
        $new_row['details'] = $form['details'];
        $new_row['sold'] = 0;

        return $new_row->save();
    }

    /**
     * 购买物品
     * @param  int  $id
     * @param  array  $form
     *
     * @return \Illuminate\Database\Eloquent\Model | bool
     */
    public function buy($id, array $form)
    {
        $resource = $this->model()->find($id);
        
        if($form['number'] <= 0){
            return false;
        }
        if (($resource['sold'] + $form['number']) <= $resource['number']){
            $resource['sold'] = $resource['sold'] + $form['number'];
        }else{
            $resource['sold'] = $resource['number'];
        }

        $listRepo = new ShopListRepository();

        return $resource->save() && $listRepo->add_list($form);
    }

    /**
     * 编辑物品
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form)
    {
        $resource = $this->model()->where('own_id', $form['own'])->first();
        if (isset($resource['own_id']) && $resource['own_id'] && ($resource['id'] != $id)) {
            return false;
        }

        $resource = $this->model()->find($id);
        $resource['own_id'] = $form['own'];
        if($form['sold'] < 0){
            $form['sold'] = 0;
        }
        if ($form['sold'] > $form['number']) {
            $form['sold'] = 0;
        }
        if($form['number'] <= 0){
            return false;
        }
        
        $resource['name'] = $form['name'];
        $resource['points'] = $form['points'];
        $resource['own_name'] = $form['own_name'];
        $resource['sold'] = $form['sold'];
        $resource['points'] = $form['points'];
        $resource['number'] = $form['number'];
        $resource['details'] = $form['details'];

        return $resource->save();
    }

    /**
     * 删除物品
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $resource = $this->model()->find($id);
        return $resource->delete();
    }
}
