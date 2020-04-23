<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ordertype extends Model
{
    use SoftDeletes;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'ordertype';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'typeName', 'typeIcon', 'typeRemark', 'delFlag',
    ];

    public function orderobjects(){
        return $this->hasMany(Orderobject::class, 'type_id');
    }

    /**
     * 返回所有ordertype和orderobject
     */
    // public function typesObjects(){
    //     return $this->where('delFlag',0)
    //                 ->with(['orderobjects'=>function($query){
    //                     $query->where('delFlag',0);
    //                 }])
    //                 ->get();
    // }
}
