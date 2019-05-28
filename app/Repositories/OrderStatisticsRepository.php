<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Member;
use App\Models\Building;
use Meteorlxy\Laravel\Repository\Repository;
use Carbon\Carbon;

class OrderStatisticsRepository extends Repository
{
    protected $model = Order::class;
    
    /**
     * 获取各状态报修数量
     *
     * @return array
     */
    public function index() {
        $newOrdersCount = $this->model()->where('status_id', 1)->count();
        $receivedOrdersCount = $this->model()->where('status_id', 2)->count();
        $reportedOrdersCount = $this->model()->where('status_id', 3)->count();
        $finishedOrdersCount = $this->model()->where('status_id', 4)->count();
        $allOrdersCount = $newOrdersCount + $receivedOrdersCount + $reportedOrdersCount + $finishedOrdersCount;
        
        $data = [
            'newOrders' => $newOrdersCount,
            'receivedOrders' => $receivedOrdersCount,
            'reportedOrders' => $reportedOrdersCount,
            'finishedOrders' => $finishedOrdersCount,
            'allOrders' => $allOrdersCount,
        ];
        return $data;
    }
     
    /**
     * 每日报修数统计
     *
     * @return array
     */
    public function daily(array $form) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope']);
        
        if(isset($form['department_id'])) {
            $resource->whereHas('building', function ($query) use ($form) {
                $query->where('department_id', '=', $form['department_id']);
            });
        }
        if(isset($form['building_id'])) $resource->where('building_id', '=', $form['building_id']);
        
        $created_at_start =  Carbon::parse($form['created_at_start']);
        $created_at_end =  Carbon::parse($form['created_at_end']);
        $resource->whereBetween('created_at', [
            $created_at_start->toDatetimeString(),
            $created_at_end->addDay()->toDatetimeString()
        ]);
        $data = $resource->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
            
        $dataArray = $data->toArray();
        
        // 补全没有报修的日期。
        $index = 0;
        $fullDateRange = [];
        for($date = $created_at_start; $date->lt($created_at_end); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $total = 0;
            if (isset($dataArray[$index]) && $dateString === $dataArray[$index]['date']) {
                $total = $dataArray[$index++]['total'];
            }
            $fullDateRange[] = [
                'date' => $dateString,
                'total' => $total,
            ];
        }

        return $fullDateRange;
    }
     
    /**
     * 社员处理报修统计
     *
     * @return array
     */
    public function member(array $form) {
        
        $resource = Member::select('id', 'user_id', 'department_id');
            
        $resource->withCount(['orders' => function ($query) use ($form) {
            $created_at_start =  Carbon::parse($form['created_at_start']);
            $created_at_end =  Carbon::parse($form['created_at_end']);
            $query->withoutGlobalScopes(['orderScope'])->whereBetween('completed_at', [
                $created_at_start->toDatetimeString(),
                $created_at_end->addDay()->toDatetimeString()
            ]);
        }]);
        // 是否退役
        if (isset($form['old_members'])) {
            if ($form['old_members']) {
                $resource->onlyTrashed();
            }
        } else {
            $resource->withTrashed();
        }
        // 所属部门
        if(isset($form['department_id'])) {
            $resource->whereHas('department', function ($query) use ($form) {
                $query->where('id', '=', $form['department_id']);
            });
        }
        
        $data = $resource->with(['user' => function ($query) {
                $query->with(['profile' => function ($query) {
                    $query->select('user_id', 'name');
                }])->select('id');
            }])
            ->orderBy('orders_count', 'desc')
            ->get();
            
        return $data;
    }
     
    /**
     * 宿舍楼报修统计
     *
     * @return array
     */
    public function building(array $form) {
        
        $resource = Building::select('id', 'name', 'department_id');
            
        $resource->withCount(['orders' => function ($query) use ($form) {
            $created_at_start =  Carbon::parse($form['created_at_start']);
            $created_at_end =  Carbon::parse($form['created_at_end']);
            $query->withoutGlobalScopes(['orderScope'])->whereBetween('created_at', [
                $created_at_start->toDatetimeString(),
                $created_at_end->addDay()->toDatetimeString()
            ]);
        }]);
        
        
        if(isset($form['department_id'])) {
            $resource->whereHas('department', function ($query) use ($form) {
                $query->where('id', '=', $form['department_id']);
            });
        }
        
        $data = $resource
            ->orderBy('orders_count', 'desc')
            ->orderBy('id', 'asc')
            ->get();
            
        return $data;
    }

}