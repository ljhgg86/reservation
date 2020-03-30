<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseUtil;

use App\Models\Ordertype;
use App\Models\Orderobject;

class OrderobjectController extends Controller
{

    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->ordertype=new Ordertype();
        $this->orderobject=new Orderobject();
        $this->responseUtil = new ResponseUtil();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    /**
     * 返回所有ordertype和orderobject
     */
    public function typesObjects(){
        //return $this->responseUtil->responseInfo($this->ordertype->typesObjects());
        return response()->responseUtil($this->ordertype->typesObjects());
    }

    /**
     * 返回指定type_id的所有orderobject
     */
    public function typeObjects($type_id){
        //return $this->responseUtil->responseInfo($this->orderobject->typeObjects($type_id));
        return response()->responseUtil($this->orderobject->typeObjects($type_id));
    }

    /**
     * 返回指定id的orderobject,包括关联的ordertype和ordertimerule
     */
    public function object($object_id){
        //return $this->responseUtil->responseInfo($this->orderobject->object($object_id));
        return response()->responseUtil($this->orderobject->object($object_id));
    }

    /**
     * 返回指定id的orderobject，包括关联的ordertimerule和指定日期orderinfo
     */
    public function objectDateTimes($object_id, $date){
        //return $this->responseUtil->responseInfo($this->orderobject->objectDateTimes($object_id,$date));
        return response()->responseUtil($this->orderobject->objectDateTimes($object_id,$date));
    }
}
