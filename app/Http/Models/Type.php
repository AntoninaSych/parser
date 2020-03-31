<?php


namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name'];
    protected $table = 'proxy_type';
    public $timestamps = false;
}
