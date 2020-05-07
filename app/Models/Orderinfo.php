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
        return $this->hasMany(Ordertime::class, 'info_id');
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

    /**
     * 新增一条记录以及关联的Ordertime记录
     */
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

    /**
     * 新增一组记录以及各个记录关联的Ordertime记录
     * $requestInfo 请求数据格式如下：
     * {
     *       "object_id":1,
     *       "applyReason":"abc",
     *       "programName":"111",
     *      "orderTimes":[{"orderDate":"2020-05-01","beginTime":"18:00:00","endTime":"20:00:00"},{"orderDate":"2020-05-02","beginTime":"14:00:00","endTime":"17:00:00"}]
     *   }
     * @return void
     */
    public function storeInfo($requestInfo){
        $orderTimeInfos = $requestInfo['orderTimes'];
        /*选取请求日期中已被预订的时间*/
        $orderDates = Arr::pluck($requestInfo['orderTimes'],'orderDate');
        $ordertimes = Ordertime::where('object_id',$requestInfo['object_id'])
                                ->whereIn('orderDate',$orderDates)
                                ->where('applyStatus','<',2)
                                ->select('orderDate', 'orderTime')
                                ->get()
                                ->groupBy('orderDate')
                                ->collapse();

        $infotimes = $this->orderInfoTimesArr($orderTimeInfos, $ordertimes, intval($requestInfo['object_id']));
        if(!$infotimes){
            $ordered_timeStr = $this->orderDateTimeStr($ordertimes);
            return array('status'=>false,'tipInfo'=>$ordered_timeStr);
        }
        //$infotimes = collect($infotimes);
        DB::beginTransaction();
        try{
            $orderInfo = new Orderinfo($requestInfo);
            $orderInfo->proposer_id = request()->user()->id;
            $orderInfo->save();
            $orderInfo->ordertimes()->createMany($infotimes);
            DB::commit();
            return array('status'=>true,'tipInfo'=>$orderInfo);
        }catch(Exception $e){
            DB::rollBack();
            return array('status'=>false,'tipInfo'=>"Fail,please try again!");
        }

        // $orderInfoTimes = $infotimes->map(function($item) use ($orderInfo){
        //     $item['info_id'] = $orderInfo->id;
        //     return $item;
        // });
        //return DB::table('orderinfo')->insert($orderInfoTimes->toArray());
    }

    /**
     * 转换日期时间数组为字符串
     */
    public function orderDateTimeStr($ordertimes){
        $ordertimesArr1 = array();
        $ordertimesArr2 = $ordertimes->toArray();
        foreach($ordertimesArr2 as $ordertimeArr2){
            $ordertimesArr1[] = $ordertimeArr2['orderDate']." ".$ordertimeArr2['orderTime'];
        }
        return  implode(',',$ordertimesArr1);
    }

    /**
     * 返回符合存储格式的预定的日期时间数组
     */
    public function orderInfoTimesArr($orderTimeInfos, $ordertimes, $object_id){
        $infotimes = array();
        foreach($orderTimeInfos as $orderTimeInfo){
            $ordertime = $ordertimes->filter(function($value,$key) use ($orderTimeInfo){
                return $orderTimeInfo['orderDate'] == $value->orderDate;
            });
            $beginHour = strtotime($orderTimeInfo['beginTime']);
            $endHour = strtotime($orderTimeInfo['endTime']);
            for($hour = $beginHour;$hour<$endHour;$hour+=1800){
                $hourTime = Date("H:i:s",$hour);
                if($ordertime->isNotEmpty() && $ordertime->contains('orderTime',$hourTime)){
                    return false;
                }
                $infotime = ['object_id'=>$object_id,
                'orderDate'=>$orderTimeInfo['orderDate'],
                'orderTime'=>$hourTime,
                ];
                array_push($infotimes,$infotime);
            }
        }
        return $infotimes;
    }
}
