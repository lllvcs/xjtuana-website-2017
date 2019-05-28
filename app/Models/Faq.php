<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $primaryKey = 'id';

    // 使用软删除
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('common', function(Builder $builder) {
            $builder
                ->orderBy('category_id', 'asc')
                ->orderBy('index', 'asc')
                ->orderBy('created_at', 'desc');
        });
    }

    // belongsTo FaqCategory 类别
    public function category() {
        return $this->belongsTo('App\Models\FaqCategory', 'category_id');
    }

}