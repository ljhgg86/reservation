<?php
namespace App\Utils;
class ResponseUtil{

    public function responseInfo($data){
        if(empty($data) || count($data)==0){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'失败'
            ],400);
        }
        return response()->json([
            'status'=>true,
            'data'=>$data,
            'message'=>'成功'
        ],200);
    }

}
