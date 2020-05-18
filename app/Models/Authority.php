<?php

namespace App\Models;

use DB;

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
        return $this->belongsToMany(User::class,'authority_users','authority_id','users_id')
                    ->select('id', 'name', 'realName', 'openId', 'nickName', 'avatarUrl', 'cellphone', 'officephone', 'regTime', 'email');
    }

    public function types(){
        return $this->belongsToMany(Ordertype::class,'authority_type','authority_id','type_id')
                    ->select('id', 'typeName', 'typeIcon', 'typeRemark');
    }

    /**
     * 获取所有权限以及关联的用户和类型信息
     *
     * @return void
     */
    public function relatedInfos(){
        return $this->with('users','types')->get(['id', 'authorityName', 'authorityRemark']);
    }

    /**
     * 关联权限到用户和类型
     *
     * @param [int] $id
     * @param [int] $user_id
     * @param [int] $type_id
     * @return void
     */
    public function addRelatedAuthority($id, $user_id, $type_id){
        $authority = $this->find($id);
        DB::beginTransaction();
        try{
            $authority->users()->attach($user_id);
            $authority->types()->attach($type_id);
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
    }

    /**
     * 更新权限关联的用户和类型
     *
     * @param [int] $id
     * @param [int] $user_id
     * @param [int] $type_id
     * @return void
     */
    public function updateRelatedAuthority($id, $user_id, $type_id){
        $authority = $this->find($id);
        DB::beginTransaction();
        try{
            $authority->users()->sync($user_id);
            $authority->types()->sync($type_id);
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
    }

    /**
     * 删除权限和用户及类型的关联
     *
     * @param [int] $id
     * @param [int] $user_id
     * @param [int] $type_id
     * @return void
     */
    public function deleteRelatedAuthority($id, $user_id, $type_id){
        $authority = $this->find($id);
        DB::beginTransaction();
        try{
            $authority->users()->detach($user_id);
            $authority->types()->detach($type_id);
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
