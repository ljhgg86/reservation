<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Models\User;

class PassportController extends Controller
{

    protected $http;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->http = new Client();
        $this->user = new User();
    }
    public function login(Request $request)
    {
        $data = array(
            'username' => request('name'),
            'password' => request('password'),
            'scope'    => '',
            'client_id'     => env('RESERVATION_CLIENT_ID'),
            'client_secret' => env('RESERVATION_CLIENT_SECRET'),
            'grant_type'    => 'password'
        );
        return $this->passportTokenUtil($data);
    }

    public function refresh(){
        $data = array(
            'refresh_token' => request()->cookie('refreshtoken'),
            'scope'    => '',
            'client_id'     => env('RESERVATION_CLIENT_ID'),
            'client_secret' => env('RESERVATION_CLIENT_SECRET'),
            'grant_type'    => 'refresh_token'
        );
        return $this->passportTokenUtil($data);
    }

    public function passportTokenUtil($formParams){
        try{
            $response =  $this->http->post(env('APP_URL').'/oauth/token', [
                'form_params' => $formParams,
            ]);
        }catch(RequestException $e){var_dump($e->getMessage());
            return response()->json([
                'status'=>false,
                'data'=>[
                ],
                'message'=>'失败'
            ],400);
        }

        $token = json_decode((string)$response->getBody(), true);
        $user = $this->user->where('name',$formParams['username'])
                            ->with('authorities')
                            ->first(['id','name','realName','openId','nickName','avatarUrl','cellphone','officephone','regTime','email']);

        return response()->json([
            'status'=>true,
            'data'=>[
                'access_token' => $token['access_token'],
                'auth_id'    => md5($token['refresh_token']),
                'expires_in' => $token['expires_in'],
                'user' => $user,
            ],
            'message'=>'成功',

        ], 200)->cookie('refreshToken', $token['refresh_token'], 21600, null, null, false, true);
    }
}
