<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResponseUtil;

use App\Models\Orderinfo;
use App\Models\Orderobject;

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
        //return response()->responseUtil($this->orderinfo->storeInfo($request->all()));
        $rst = $this->orderinfo->store($request->all());
        if(!$rst['status']){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>$rst['tipInfo']
            ],400);
        }
        return response()->json([
            'status'=>true,
            'data'=>$rst['tipInfo'],
            'message'=>'成功'
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->responseUtil($this->orderinfo->where('id',$id)->with('proposer','checker','ordertimes','orderobject.ordertype')->first(['id', 'object_id', 'proposer_id', 'applyReason', 'applyTime', 'programName', 'applyStatus', 'checker_id']));
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
        $orderinfo = $this->orderinfo->find($id);
        if($orderinfo->proposer_id != request()->user()->id){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'无权限修改内容。'
            ]);
        }
        $rst = $this->orderinfo->updateInfo($request->except('applyStatus'), $id);
        if(!$rst['status']){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>$rst['tipInfo']
            ],400);
        }
        return response()->json([
            'status'=>true,
            'data'=>$rst['tipInfo'],
            'message'=>'成功'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orderinfo $orderinfo)
    {
        $requester = request()->user();
        if(($orderinfo->proposer_id != $requester->id) && !$requester->is_admin()){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'无权限删除内容。'
            ]);
        }
        return response()->responseUtil($orderinfo->delInfo());
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
        return response()->responseUtil($this->orderinfo->objectInfos($object_id,$request->input('listCount'),$request->input('minId')));
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

    /**
     * 审核预约信息
     *
     * @param Request $request
     * @param [int] $id
     * @return responseutil
     */
    public function verifyInfos(Request $request, $id){
        $orderinfo = $this->orderinfo->find($id);
        $orderobject = Orderobject::find($orderinfo->object_id);
        $this->authorize($orderobject->type_id);
        return response()->responseUtil($this->orderinfo->verifyInfo($request->input('applyStatus'),$id));
    }
}
