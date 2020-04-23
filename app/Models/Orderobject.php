<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderobject extends Model
{
    use SoftDeletes;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orderobject';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'objectName', 'objectIcon', 'objectRemark', 'delFlag',
    ];

    public function ordertype(){
        return $this->belongsTo(Ordertype::class,'type_id');
    }

    public function ordertimerules(){
        return $this->hasMany(Ordertimerule::class, 'object_id');
    }

    public function orderinfos(){
        return $this->hasMany(Orderinfo::class, 'object_id');
    }

    public function ordertimes(){
        return $this->hasMany(Ordertime::class, 'object_id');
    }

    /**
     * 返回所有ordertype和orderobject
     */
    public function typesObjects(){
        return $this->with('ordertype')
                    ->get();
    }

    /**
     * 返回指定type_id的所有orderobject
     */
    public function typeObjects($type_id){
        return $this->where('type_id',$type_id)
                    ->first();
    }

    /**
     * 返回指定id的orderobject,包括关联的ordertype和ordertimerule信息
     */
    // public function object($object_id){
    //     return $this->where('id',$object_id)
    //                 ->with('ordertype','ordertimerules')
    //                 ->first();
    // }

    /**
     * 返回指定id的orderobject的信息和对应指定日期的ordertime信息
     */
    public function objectDateTimes($object_id, $date){
        return $this->where('id',$object_id)
                    ->with(['ordertimerules','ordertime'=>function($query) use($date) {
                        $query->where('orderDate',$date)
                                ->with('orderinfo');
                    }])
                    ->first();
    }

    /**
     * 返回指定id的orderobject的信息和对应指定月份的ordertime信息
     */
    public function objectMonth($object_id, $month){
        return $this->where('id',$object_id)
                    ->with(['ordertimerules','ordertime'=>function($query) use($month){
                        $query->whereMonth('orderDate',$month)
                                ->with('orderinfo');
                    }])
                    ->first();
    }
}
