<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AnaHistory extends Model
{
    protected $table = 'sms';

    protected $primaryKey = 'id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('common', function(Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }

}