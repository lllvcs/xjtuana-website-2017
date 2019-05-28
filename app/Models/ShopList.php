<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopList extends Model
{
    protected $table = 'shop_list';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // belongsTo shop 物品
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop', 'items_id');
    }

    // belongsTo shop 物品
    public function buy_member()
    {
        return $this->belongsTo('App\Models\member', 'purchase_id');
    }

    // belongsTo shop 物品
    public function sell_member()
    {
        return $this->belongsTo('App\Models\member', 'seller_id');
    }

    // belongsTo shop_status 购买状态
    public function shop_status()
    {
        return $this->belongsTo('App\Models\ShopStatus', 'buying_state');
    }
}
