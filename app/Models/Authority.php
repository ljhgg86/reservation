<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authority extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'authority';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'authorityName', 'authorityRemark', 'delFlag',
    ];

    public function users(){
        return $this->belongsToMany(User::class,'autority_users','authority_id','users_id');
    }
}
