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

    /**
     * 获取指定object_id的timerule
     *
     * @param [int] $object_id
     * @return void
     */
    public function objectTimerules($object_id){
        return $this->where('object_id',$object_id)
                    ->where('delFlag',0)
                    ->get();
    }

    public function timeruleWithObject($id){
        return $this->where('id', $id)
                    ->where('delFlag',0)
                    ->with(['orderobject'=>function($query){
                        $query->where('delFlag', 0);
                    }])
                    ->first();
    }
}
