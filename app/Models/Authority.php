<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Authority extends Model
{
    use SoftDeletes;
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
        'authorityName', 'authorityRemark',
    ];

    public function users(){
        return $this->belongsToMany(User::class,'autority_users','authority_id','users_id');
    }

    public function types(){
        return $this->belongsToMany(Ordertype::class,'autority_type','authority_id','type_id');
    }

    public function relatedInfos(){
        return $this->with('users','types')->get();
    }
}
