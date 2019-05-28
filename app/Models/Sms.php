<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Sms extends Model
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

        static::addGlobalScope('smsScope', function(Builder $builder) {
            $builder
                ->with('targets')
                ->orderBy('created_at', 'desc');
        });
    }

    // hasMany SmsTarget 短信目标
    public function targets() {
        return $this->hasMany('App\Models\SmsTarget', 'sms_id');
    }

}