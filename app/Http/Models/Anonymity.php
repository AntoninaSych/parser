<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Anonymity extends Model
{
    protected $table = 'anonymity';
    protected $fillable = ['name'];
    public $timestamps = false;
}
