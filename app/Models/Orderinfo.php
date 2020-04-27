<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderinfo extends Model
{
    use SoftDeletes;
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orderinfo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_id', 'applyReason', 'applyTime', 'programName', 'applyStatus',
    ];

    public function orderobject(){
        return $this->belongsTo(Orderobject::class,'object_id');
    }

    public function proposer(){
        return $this->belongsTo(User::class,'proposer_id');
    }

    public function checker(){
        return $this->belongsTo(User::class,'checker_id');
    }

    public function ordertimes(){
        return $this->hasMany(Ordertime::class, 'object_id');
    }

    /**
     * 返回listcount条info
     *
     * @param [int] $listCount
     * @param [int] $minId
     * @return collection
     */
    public function getInfos($listCount, $minId){
        return $this->where('id','<',$minId)
                    ->with('proposer','checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    /**
     * 返回指定ordertype的listcount条info
     *
     * @param [int] $type_id
     * @param [int] $listCount
     * @param [int] $minId
     * @return collection
     */
    public function typeInfos($type_id, $listCount, $minId){
        $objectIds = Orderobject::where('type_id',$type_id)->select('id')->get();
        return $this->whereIn('object_id', $objectIds)
                    ->where('id','<',$minId)
                    ->with('proposer','checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    /**
     * 返回指定orderobject的listcount条info
     *
     * @param [int] $object_id
     * @param [int] $listCount
     * @param [int] $minId
     * @return collection
     */
    public function objectInfos($object_id, $listCount, $minId){
        return $this->where('object_id', $object_id)
                    ->where('id','<',$minId)
                    ->with('proposer','checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    /**
     * 返回指定用户的listcount条info
     *
     * @param [int] $proposer
     * @param [int] $listCount
     * @param [int] $minId
     * @return collection
     */
    public function userInfos($proposer, $listCount, $minId){
        return $this->where('proposer_id', $proposer->id)
                    ->where('id','<',$minId)
                    ->with('checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    /**
     * 返回指定用户指定ordertype的listcount条info
     *
     * @param [User] $proposer
     * @param [int] $type_id
     * @param [int] $listCount
     * @param [int] $minId
     * @return void
     */
    public function userTypeInfos($proposer, $type_id, $listCount, $minId){
        $objectIds = Orderobject::where('type_id',$type_id)->select('id')->get();
        return $this->whereIn('object_id', $objectIds)
                    ->where('proposer_id', $proposer->id)
                    ->where('id','<',$minId)
                    ->with('proposer','checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    /**
     * 返回指定用户指定object的listcount条info
     *
     * @param [User] $proposer
     * @param [int] $object_id
     * @param [int] $listCount
     * @param [int] $minId
     * @return void
     */
    public function userObjectInfos($proposer, $object_id, $listCount, $minId){
        return $this->where('object_id', $object_id)
                    ->where('proposer_id', $proposer->id)
                    ->where('id','<',$minId)
                    ->with('proposer','checker','ordertimes','orderobject.ordertype')
                    ->orderBy('id','DESC')
                    ->take($listCount);
    }

    public function store($requestInfo){
        $ordertimes = Ordertime::where('object_id',$requestInfo['object_id'])
                                ->where('orderDate',$requestInfo['orderDate'])
                                ->where('applyStatus','<',2)
                                ->select('orderTime')
                                ->get();
        $beginHour = Date("H",$requestInfo['beginTime']);
        $endHour = Date("H",$requestInfo['endTime']);
        $infotimes = collect();
        for($hour = $beginHour;$hour<$endHour;$hour++){
            if($ordertimes>contains($hour)){
                return false;
            }
            $infotimes->concat(['object_id'=>$requestInfo['object_id'],
                                'orderDate'=>$requestInfo['orderDate'],
                                'orderTime'=>$hour,
                                ]);
        }
        $orderInfo = new Orderinfo($requestInfo);
        $orderInfo->proposer_id = request()->user()->id;
        $orderInfo->save();
        return $infotimes->each(function($infotime) use($orderInfo){
            $orderInfo->ordertimes()->save($infotime);
        });
    }

}
