<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends  Model
{
    protected $table = 'country';

    protected $fillable = ['name'];
    public $timestamps = false;
}
