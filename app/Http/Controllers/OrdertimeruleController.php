<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrdertimeruleRequest;
use App\Utils\ResponseUtil;

use App\Models\Ordertimerule;
use App\Models\Orderobject;

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
        $orderobject = Orderobject::find($request->input('object_id'));
        $this->authorize($orderobject->type_id);
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
        return response()->responseUtil($this->ordertimerule->where('id',$id)->with('orderobject')->first(['id', 'object_id', 'assignDate', 'weedDate', 'beginTime', 'endTime']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdertimeruleRequest $request, Ordertimerule $ordertimerule)
    {
        $orderobject = Orderobject::find($ordertimerule->object_id);
        $this->authorize($orderobject->type_id);
        return response()->responseUtil($ordertimerule->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordertimerule $ordertimerule)
    {
        $orderobject = Orderobject::find($ordertimerule->object_id);
        $this->authorize($orderobject->type_id);
        return response()->responseUtil($ordertimerule->delete());
    }

    /**
     * 获取指定object_id的ordertimerule
     */
    public function objectTimerules($object_id){
        return response()->responseUtil($this->ordertimerule->objectTimerules($object_id));
    }
}
