<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';

    protected $primaryKey = 'id';

    public $timestamps = false;

    // hasMany members 社员
    public function members() {
        return $this->hasMany('App\Models\Member', 'designation_id');
    }
    
}