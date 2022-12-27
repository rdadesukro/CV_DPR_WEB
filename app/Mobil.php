<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    //
    protected $table = 'mobils';
    public function setoran()
    {
        return $this->hasOne('App\Setoran');
    }

}
