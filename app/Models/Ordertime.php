<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ordertime extends Model
{
    use SoftDeletes;
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
        'object_id', 'info_id', 'orderDate', 'applyStatus', 'beginTime', 'endTime'
    ];

    public function orderobject(){
        return $this->belongsTo(Orderobject::class,'object_id');
    }

    public function orderinfo(){
        return $this->belongsTo(Orderinfo::class,'info_id');
    }

    public function scopeTimesByDatetime($query){
        return $query->orderBy('orderDate','ASC')->orderBy('beginTime','ASC');
    }
}
