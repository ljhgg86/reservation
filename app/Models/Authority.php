<?php

namespace App\Models;

use DB;
use Exception;
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
                    ->select('users.id', 'name', 'realName', 'openId', 'nickName', 'avatarUrl', 'cellphone', 'officephone', 'regTime', 'email');
    }

    public function types(){
        return $this->belongsToMany(Ordertype::class,'authority_type','authority_id','type_id')
                    ->select('ordertype.id', 'typeName', 'typeIcon', 'typeRemark');
    }

    public function store($requestInfo){
        try{
            DB::beginTransaction();
            $authority = $this->create($requestInfo);
            if(count($requestInfo['type_ids'])){
                $authority->types()->attach($requestInfo['type_ids']);
            }
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }

    }

    public function updateAuthority($requestInfo, $authority){
        try{
            DB::beginTransaction();
            $authority->update($requestInfo);
            if(count($requestInfo['type_ids'])){
                $authority->types()->sync($requestInfo['type_ids']);
            }
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
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
    // public function addRelatedAuthority($id, $user_id, $type_id){
    //     $authority = $this->find($id);
    //     DB::beginTransaction();
    //     try{
    //         if(count($user_id)){
    //             $authority->users()->attach($user_id);
    //         }
    //         if(count($type_id)){
    //             $authority->types()->attach($type_id);
    //         }
    //         DB::commit();
    //         return true;
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return false;
    //     }
    // }

    /**
     * 更新权限关联的用户和类型
     *
     * @param [int] $id
     * @param [int] $user_id
     * @param [int] $type_id
     * @return void
     */
    // public function updateRelatedAuthority($id, $user_id, $type_id){
    //     $authority = $this->find($id);
    //     DB::beginTransaction();
    //     try{
    //         if(count($user_id)){
    //             $authority->users()->sync($user_id);
    //         }
    //         if(count($type_id)){
    //             $authority->types()->sync($type_id);
    //         }
    //         DB::commit();
    //         return true;
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return false;
    //     }
    // }

    /**
     * 删除权限和用户及类型的关联
     *
     * @param [int] $id
     * @param [int] $user_id
     * @param [int] $type_id
     * @return void
     */
    // public function deleteRelatedAuthority($id, $user_id, $type_id){
    //     $authority = $this->find($id);
    //     DB::beginTransaction();
    //     try{
    //         if(count($user_id)){
    //             $authority->users()->detach($user_id);
    //         }
    //         if(count($type_id)){
    //             $authority->types()->detach($type_id);
    //         }
    //         DB::commit();
    //         return true;
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         return false;
    //     }
    // }
}
