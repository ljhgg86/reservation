<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthorityRequest;

use App\Models\Authority;

class AuthorityController extends Controller
{
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->authority=new Authority();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->responseUtil(Authority::with('types')->get(['id', 'authorityName', 'authorityRemark']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorityRequest $request)
    {
        $this->authorize();
        // return response()->responseUtil(Authority::create($request->all()));
        return response()->responseUtil($this->authority->store($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->responseUtil(Authority::where('id', $id)->with('users')->get(['id','authorityName', 'authorityRemark']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorityRequest $request, Authority $authority)
    {
        if($authority->id == 1){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'无法修改！'
            ],400);
        }
        $this->authorize();
        // return response()->responseUtil($authority->update($request->all()));
        return response()->responseUtil($this->authority->updateAuthority($request->all(), $authority));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Authority $authority)
    {
        if($authority->id == 1){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'无法删除！'
            ],400);
        }
        $this->authorize();
        $authority->users()->detach();
        $authority->types()->detach();
        return response()->responseUtil($authority->delete());
    }

    public function relatedInfos(){
        //return response()->responseUtil($this->authority->relatedInfos());
        return response()->responseUtil(Authority::with('users','types')->get(['id', 'authorityName', 'authorityRemark']));
    }

    // public function addRelatedAuthority(Request $request, $id){
    //     $this->authorize();
    //     return response()->responseUtil($this->authority->addRelatedAuthority($id, $request->input('user_id'), $request->input('type_id')));
    // }

    // public function updateRelatedAuthority(Request $request, $id){
    //     $this->authorize();
    //     return response()->responseUtil($this->authority->updateRelatedAuthority($id, $request->input('user_id'), $request->input('type_id')));
    // }

    // public function deleteRelatedAuthority(Request $request, $id){
    //     $this->authorize();
    //     return response()->responseUtil($this->authority->deleteRelatedAuthority($id, $request->input('user_id'), $request->input('type_id')));
    // }
}
