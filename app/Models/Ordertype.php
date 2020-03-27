<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordertype extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'typeName', 'typeIcon', 'typeRemark',
    ];
}
