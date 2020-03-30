<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordertime extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'ordertime';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_id', 'info_id', 'orderDate', 'orderTime', 'applyStatus',
    ];

    public function orderobject(){
        return $this->belongsTo(Orderobject::class,'object_id');
    }

    public function orderinfo(){
        return $this->belongsTo(Orderinfo::class,'info_id');
    }
}