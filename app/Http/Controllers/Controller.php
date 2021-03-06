<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authorize($type_id = 0)
    {
        $owner=request()->user();
        if($owner->is_super_admin() || $owner->is_admin() || $owner->hasTypePower($type_id)){
            return true;
        }
        return response()->json([
            'status' => false,
            'data'=>[
                'successflag' => false
            ],
            'message' => '没有权限执行此操作',
        ])->setStatusCode(401);
    }
}
