<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class uploadController extends Controller
{
    /**
     * 单张图片上传
     *
     * @param Request $request
     * @return void
     */
    public function uploadFile(Request $request){
        if(!$request->hasFile("image")){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'上传文件不存在！'
            ],400);
        }
        $extArray = ['jpeg','jpg','gif','gpeg','png'];
        $file = $request->file('image');
        $file_ext = strtolower($file->extension());
        if(!in_array($file_ext,$extArray)){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'上传文件格式错误！'
            ],400);
        }
        $path = $file->store('image');
        return response()->responseUtil(asset('storage/'.$path));
    }

    public function uploadFiles(Request $request){
        if(!$request->hasFile("images")){
            return response()->json([
                'status'=>false,
                'data'=>'',
                'message'=>'上传文件不存在！'
            ],400);
        }
        $images = array();
        $files = $request->file('images');
        foreach($files as $file){
            $path = $file->store('image');
            $images[] = asset('storage/'.$path);
        }

        return response()->responseUtil($images);
    }
}
