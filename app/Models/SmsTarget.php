<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTarget extends Model
{
    protected $table = 'sms_targets';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['mobile'];

    // belongsTo Sms 短信
    public function sms() {
        return $this->belongsTo('App\Models\Sms', 'sms_id');
    }

}