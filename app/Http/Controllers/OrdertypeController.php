<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\OrdertypeRequest;

use App\Utils\ResponseUtil;

use App\Models\Ordertype;

class OrdertypeController extends Controller
{
    protected $responseUtil;

    public function __construct()
    {
        $this->responseUtil = new ResponseUtil();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->responseUtil->responseInfo(Ordertype::where('delFlag', 0)->get());
        return response()->responseUtil(Ordertype::all());
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
    public function store(OrdertypeRequest $request)
    {
        //return $this->responseUtil->responseInfo(Ordertype::create($request->all()));
        return response()->responseUtil(Ordertype::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ordertype $ordertype)
    {
        //return $this->responseUtil->responseInfo(Ordertype::where('delFlag', 0)->where('id',$id)->first());
        return response()->responseUtil($ordertype);
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
    public function update(OrdertypeRequest $request, Ordertype $ordertype)
    {
        //return $this->responseUtil->responseInfo(Ordertype::update($request->all()));
        return response()->responseUtil($ordertype->fill($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordertype $ordertype)
    {
        // return response()->responseUtil(Ordertype::where('id', $id)->update(['delFlag'=>1]));
        return response()->responseUtil($ordertype->delete());
    }
}
