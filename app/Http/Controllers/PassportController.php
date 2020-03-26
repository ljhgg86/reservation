<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        try{
            $response =  $this->http->request('POST',env('APP_URL').'/oauth/token', [
                'form_params' => $data,
            ]);
        }catch(RequestException $e){
            return $this->unwrapResponse($e->getResponse());
        }

        $token = json_decode((string)$response->getBody(), true);

        return response()->json([
            'status'=>true,
            'data'=>[
                'successflag' => true,
                'access_token' => $token['access_token'],
                'refresh_tOKEN'    => $token['refresh_token'],
                'expires_in' => $token['expires_in'],
            ],
            'message'=>'success',

        ], 200)->cookie('refreshToken', $token['refresh_token'], 14400, null, null, false, true);
    }
}
