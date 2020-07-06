<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->user=new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->responseUtil(User::all(['id', 'name', 'realName', 'openId', 'nickName', 'avatarUrl', 'cellphone', 'officephone','regTime', 'email']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize();
        $user = new User($request->all());
        $user->password = bcrypt($request->input('password'));
        return response()->responseUtil($user->save());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->responseUtil(User::where('id', $id)->with('authorities.types')->first(['id', 'name', 'realName', 'openId', 'nickName', 'avatarUrl', 'cellphone', 'officephone', 'regTime', 'email']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $requester = request()->user();
        if(($user->id != $requester->id) && !$requester->is_admin()){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'无权限操作。'
            ]);
        }
        $user->fill($request->all());
        if($request->has('password')){
            $user->password = bcrypt($request->input('password'));
        }
        return response()->responseUtil($user->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize();
        return response()->responseUtil($user->delete());
    }

    /**
     * 模糊搜索手机号码或者姓名
     *
     * @param Request $request
     * @return void
     */
    public function searchUser(Request $request){
        return response()->responseUtil($this->user->searchUsers($request->input('searchContent')));
    }

    /**
     * 返回请求用户信息和关联的权限信息
     *
     * @return void
     */
    public function userInfo(){
        $user = request()->user();
        return response()->responseUtil(User::where('id',$user->id)->with('authorities.types')->first(['id', 'name', 'realName', 'openId', 'nickName', 'avatarUrl', 'cellphone', 'officephone', 'regTime', 'email']));
    }

    /**
     * 添加权限
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function addAuthority(Request $request,$user_id){
        $this->authorize();
        //dd($user_id);
        $user = User::find($user_id);
        if(count($request->input('authority_ids'))){
            $user->authorities()->attach($request->input('authority_ids'));
        }
        return response()->responseUtil(true);
    }

    /**
     * 更新权限
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function updateAuthority(Request $request,$user_id){
        $this->authorize();
        $user = User::find($user_id);
        //if(count($request->input('authority_ids'))){
            $user->authorities()->sync($request->input('authority_ids'));
        //}
        return response()->responseUtil(true);
    }

    /**
     * 判断用户是否有管理某个类型的权限
     *
     * @param [int] $type_id
     * @return boolean
     */
    public function hasTypePower($type_id){
        return response()->responseUtil(request()->user()->hasTypePower($type_id));
    }

}
