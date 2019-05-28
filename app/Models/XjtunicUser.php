<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XjtunicUser extends Model
{
    protected $table = 'xjtunic_users';

    protected $primaryKey = 'userid';

    protected $keyType = 'string';

    public $incrementing = false;
    
    public function xjtuana_user() {
        return $this->hasOne('App\Models\User', 'netid');
    }
}