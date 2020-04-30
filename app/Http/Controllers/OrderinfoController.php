<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseUtil;

use App\Models\Orderinfo;

class OrderinfoController extends Controller
{
    protected $orderinfo;
    protected $responseUtil;
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->orderinfo=new Orderinfo();
        $this->responseUtil = new ResponseUtil();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->responseUtil($this->orderinfo->getInfos($request->input('listCount'),$request->input('minId')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->responseUtil($this->orderinfo->storeInfo($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Orderinfo $orderinfo)
    {
        return response()->responseUtil($orderinfo->with('proposer','checker','ordertimes','orderobject.ordertype')->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orderinfo $orderinfo)
    {
        return response()->responseUtil($orderinfo->delete());
    }

    /**
     * 返回type关联的所有orderinfo
     *
     * @param Request $request
     * @param [int] $type_id
     * @return responseutil
     */
    public function typeInfos(Request $request, $type_id){
        return response()->responseUtil($this->orderinfo->typeInfos($type_id,$request->input('listCount'),$request->input('minId')));
    }

    /**
     * 返回object关联的所有orderinfo
     *
     * @param Request $request
     * @param [int] $object_id
     * @return responseutil
     */
    public function objectInfos(Request $request, $object_id){
        return response()->responseUtil($this->orderinfo->typeInfos($object_id,$request->input('listCount'),$request->input('minId')));
    }

    /**
     * 返回请求用户的所有orderinfo
     *
     * @param Request $request
     * @return responseutil
     */
    public function userInfos(Request $request){
        return response()->responseUtil($this->orderinfo->userInfos(request()->user(),$request->input('listCount'),$request->input('minId')));
    }

    /**
     * 返回请求用户type关联的所有orderinfo
     *
     * @param Request $request
     * @param [int] $type_id
     * @return responseutil
     */
    public function userTypeInfos(Request $request, $type_id){
        return response()->responseUtil($this->orderinfo->userInfos(request()->user(),$type_id,$request->input('listCount'),$request->input('minId')));
    }

    /**
     * 返回请求用户object关联的所有orderinfo
     *
     * @param Request $request
     * @param [int] $object_id
     * @return responseutil
     */
    public function userObjectInfos(Request $request, $object_id){
        return response()->responseUtil($this->orderinfo->userInfos(request()->user(),$object_id,$request->input('listCount'),$request->input('minId')));
    }
}
