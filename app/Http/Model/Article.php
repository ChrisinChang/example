<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'art_id';
    public $timestamps = false;
    protected $guarded = []; //排除輸入的字段 / fillable 只有輸入的字段可以通過
}
