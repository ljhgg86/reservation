<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use DB;

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
                    ->take($listCount)
                    ->get();
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
                    ->take($listCount)
                    ->get();
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
                    ->take($listCount)
                    ->get();
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
                    ->take($listCount)
                    ->get();
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
                    ->take($listCount)
                    ->get();
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
                    ->take($listCount)
                    ->get();
    }

    public function store($requestInfo){
        $ordertimes = Ordertime::where('object_id',$requestInfo['object_id'])
                                ->where('orderDate',$requestInfo['orderDate'])
                                ->where('applyStatus','<',2)
                                ->select('orderTime')
                                ->get();
        $beginHour = intval(Date("H",strtotime($requestInfo['beginTime'])));
        $endHour = intval(Date("H",strtotime($requestInfo['endTime'])));
        $infotimes = array();
        for($hour = $beginHour;$hour<$endHour;$hour++){dump($hour);
            $hour_time = Date("H:i:s",mktime($hour,0,0));
            if($ordertimes->contains("orderTime",$hour_time)){
                return false;
            }
            $infotime = ['object_id'=>intval($requestInfo['object_id']),
            'orderDate'=>$requestInfo['orderDate'],
            'orderTime'=>$hour_time,
            ];
            array_push($infotimes,$infotime);
        }
        $infotimes = collect($infotimes);
        $orderInfo = new Orderinfo($requestInfo);
        $orderInfo->proposer_id = request()->user()->id;
        $orderInfo->save();
        return $infotimes->each(function($infotime) use($orderInfo){
            $infotime['info_id'] = $orderInfo->id;
            $ordertime = new Ordertime;
            $ordertime->create($infotime);
        });
    }

    public function storeInfo($requestInfo){
        $orderTimeInfos = $requestInfo['orderTimes'];
        $orderDates = Arr::pluck($requestInfo['orderTimes'],'orderDate');
        $ordertimes = Ordertime::where('object_id',$requestInfo['object_id'])
                                ->whereIn('orderDate',$orderDates)
                                ->where('applyStatus','<',2)
                                ->select('orderDate', 'orderTime')
                                ->groupBy('orderDate')
                                ->get();

        $infotimes = array();
        foreach($orderTimeInfos as $orderTimeInfo){
            $ordertime = $ordertimes->filter(function($value,$key) use ($orderTimeInfo){
                return $orderTimeInfo['orderDate'] == $value->orderDate;
            });
            $beginHour = strtotime($orderTimeInfo['beginTime']);
            $endHour = strtotime($orderTimeInfo['endTime']);
            for($hour = $beginHour;$hour<$endHour;$hour+=3600){
                $hourTime = Date("H:i:s",$hour);
                if($ordertime->isNotEmpty() && $ordertime->contains('orderTime',$hourTime)){
                    return false;
                }
                $infotime = ['object_id'=>intval($requestInfo['object_id']),
                'orderDate'=>$orderTimeInfo['orderDate'],
                'orderTime'=>$hourTime,
                ];
                array_push($infotimes,$infotime);
            }
        }
        $infotimes = collect($infotimes);
        $orderInfo = new Orderinfo($requestInfo);
        $orderInfo->proposer_id = request()->user()->id;
        $orderInfo->save();
        $orderInfoTimes = $infotimes->map(function($item) use ($orderInfo){
            $item['info_id'] = $orderInfo->id;
            return $item;
        });
        return DB::table('orderinfo')->insert($orderInfoTimes->toArray());
    }

}
