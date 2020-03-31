<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrdertimeruleRequest;
use App\Utils\ResponseUtil;

use App\Models\Ordertimerule;

class OrdertimeruleController extends Controller
{
    protected $ordertimerule;
    protected $responseUtil;
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->ordertimerule=new Ordertimerule();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrdertimeruleRequest $request)
    {
        return response()->responseUtil(Ordertimerule::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->responseUtil($this->timeruleWithObject($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdertimeruleRequest $request, $id)
    {
        return response()->responseUtil(Ordertimerule::where('id',$id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->responseUtil(Ordertimerule::where('id', $id)->update(['delFlag'=>1]));
    }

    /**
     * 获取指定object_id的ordertimerule
     */
    public function objectTimerules($object_id){
        return response()->responseUtil($this->ordertimerule->objectTimerules($object_id));
    }
}
