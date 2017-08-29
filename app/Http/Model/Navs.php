<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Navs extends Model
{
    protected $table = 'navs';
    protected $primaryKey = 'nav_id';
    public $timestamps = false;
    protected $guarded = []; //排除輸入的字段 / fillable 只有輸入的字段可以通過
}
