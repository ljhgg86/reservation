<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MyResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('responseUtil', function ($data) {
            //判断是否是Collection类型
            $isCollection = is_object($data) ? strpos(get_class($data),'Collection') : is_object($data);
            //判断是否成功
            $resultFlag = $isCollection ? $data->isEmpty() : empty($data);
            if($resultFlag){
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
            });
    }
}
