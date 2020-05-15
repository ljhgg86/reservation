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
        return response()->responseUtil(Authority::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorityRequest $request)
    {
        return response()->responseUtil(Authority::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->responseUtil(Authority::where('id', $id)->with('users')->get());
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
        return response()->responseUtil($authority->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Authority $authority)
    {
        $authority->users()->detach();
        $authority->typeinfos()->detach();
        return response()->responseUtil($authority->delete());
    }

    public function relatedInfos(){
        return response()->responseUtil($this->authority->relatedInfos());
    }

    public function addRelatedAuthority(Request $request, $id){
        $authority = Authority::find($id);
        if($authority->users()->attach($request->input('user_id')) && $authority->typeinfos()->attach($request->input('info_id'))){
            return response()->responseUtil(true);
        }
        return response()->responseUtil(false);
    }

    public function updateRelatedAuthority(Request $request, $id){
        $authority = Authority::find($id);
        if($authority->users()->sync($request->input('user_id')) && $authority->typeinfos()->sync($request->input('info_id'))){
            return response()->responseUtil(true);
        }
        return response()->responseUtil(false);
    }

    public function deleteRelatedAuthority(Request $request, $id){
        $authority = Authority::find($id);
        if($authority->users()->detach($request->input('user_id')) && $authority->typeinfos()->detach($request->input('info_id'))){
            return response()->responseUtil(true);
        }
        return response()->responseUtil(false);
    }
}
