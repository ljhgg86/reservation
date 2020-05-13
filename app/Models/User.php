<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'openId', 'cellphone',
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

    public function proposerorderinfos(){
        return $this->hasMany(Orderinfo::class, 'porposer_id');
    }

    public function checkerorderinfos(){
        return $this->hasMany(Orderinfo::class, 'checker_id');
    }

    public function authorities(){
        return $this->belongsToMany(Authority::class,'autority_users','users_id','authority_id');
    }

    public function orderfeedbacks(){
        return $this->hasMany(Orderfeedback::class, 'user_id');
    }

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

    /**
    * Validate the password of the user for the Passport password grant.
    *
    * @param  string $password
    * @return bool
    */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password) ?: ($password==$this->cellphone);
    }

    /**
     * 模糊搜索手机号码或者姓名
     *
     * @param [string] $keyWord
     * @return collection
     */
    public function searchUsers($keyWord){
        return $this->where('realName', 'like', '%'.$keyWord.'%')
                    ->orWhere('cellphone', 'like', '%'.$keyWord.'%')
                    ->get();
    }
}
