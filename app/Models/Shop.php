<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    protected $table = 'shop_items';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // belongsTo member 用户
    public function member() {
      return $this->belongsTo('App\Models\Member', 'own_id');
    }
}