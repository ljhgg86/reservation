<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderfeedback extends Model
{
    use SoftDeletes;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orderfeedback';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'info_id', 'feedbackContent',
    ];

    public function orderinfo(){
        return $this->belongsTo(Orderinfo::class,'info_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function infoFeedbacks($info_id){
        return $this->where('info_id',$info_id)
                    ->with('user')
                    ->orderBy('feedbackTime','asc')
                    ->get();
    }
}
