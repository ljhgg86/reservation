<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordertimerule extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'ordertimerule';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_id', 'assignDate', 'weekDate', 'beginTime', 'endTime',
    ];

    public function orderobject(){
        return $this->belongsTo(Orderobject::class,'object_id');
    }
}
