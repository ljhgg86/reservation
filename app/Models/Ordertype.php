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
        'typeName', 'typeIcon', 'typeRemark',
    ];

    public function orderobjects(){
        return $this->hasMany(Orderobject::class, 'type_id')
                    ->select('id', 'objectName', 'objectIcon', 'objectRemark');
    }

    public function authorities(){
        return $this->belongsToMany(Authority::class,'authority_type','type_id','authority_id')
                    ->select('authority.id', 'authorityName', 'authorityRemark');
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
