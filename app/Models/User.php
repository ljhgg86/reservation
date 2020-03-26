<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'openId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        // $this->validatePhone($username) ?
        //   $credentials['name'] = $username :
        //   $credentials['openId'] = $username;

        //   var_dump($username);

        // return self::where($credentials)->first();
        return self::orwhere('name',$username)->orWhere('openId',$username)->first();
    }

    // public function validatePhone($number){
    //     return preg_match('/^1[3,4,5,7,8,9][0,9]{9}$/',$number);
    // }
}
