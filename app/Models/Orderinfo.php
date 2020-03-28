<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderinfo extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orderinfo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proposer_id', 'object_id', 'applyReason', 'applyTime', 'programName', 'applyStatus', 'checker_id',
    ];

    public function orderobject(){
        return $this->belongsTo(Orderobject::class,'object_id');
    }

    public function proposer(){
        return $this->belongsTo(User::class,'proposer_id');
    }

    public function checker(){
        return $this->belongsTo(User::class,'checker_id');
    }

    public function ordertimes(){
        return $this->hasMany(Ordertime::class, 'object_id');
    }

}
