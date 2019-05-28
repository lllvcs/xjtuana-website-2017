<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $table = 'faq_categories';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany Faq 常见问题
    public function faqs() {
        return $this->hasMany('App\Models\Faq', 'category_id');
    }

}