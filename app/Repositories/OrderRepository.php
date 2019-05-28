<?php

namespace App\Repositories;

use App\Models\Order;
use Meteorlxy\Laravel\Repository\Repository;
use Carbon\Carbon;
use Auth;
use App\Events\Order\CreateEvent as OrderCreateEvent;
use App\Events\Order\UrgeEvent as OrderUrgeEvent;
use App\Events\Order\ReceiveEvent as OrderReceiveEvent;
use App\Events\Order\ReportEvent as OrderReportEvent;
use App\Events\Order\CompleteEvent as OrderCompleteEvent;
use App\Events\Order\AutoUrgeEvent as OrderAutoUrgeEvent;

class OrderRepository extends Repository
{
    protected $model = Order::class;

    /**
     * 获取单个报修单
     *
     * @param  int  $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model()
            ->withTrashed()
            ->withDetail(!Auth::user()->isMember())
            ->find($id);
    }

    /**
     * 获取单个报修单
     *
     * @param  int  $id
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->model()
            ->withTrashed()
            ->withDetail(!Auth::user()->isMember())
            ->findOrFail($id);
    }

    /**
     * 搜索报修单
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->query($form)->with('member.user.profile');
        return $resource->paginate($form['perpage']);
    }
    
    /**
     * 根据条件构建查询语句
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function query(array $form)
    {
        $resource = $this->model()->withTrashed();
        
        // 报修单号
        if(isset($form['id'])) $resource->where('id', '=', $form['id']);
        // 报修人ID
        if(isset($form['user_id'])) $resource->where('user_id', '=', $form['user_id']);
        // 报修人NETID
        if(isset($form['user_netid'])) {
            $resource->whereHas('user', function ($query) use ($form) {
                $query->where('netid', 'like', '%'.$form['user_netid'].'%');
            });
        }
        // 报修人姓名
        if(isset($form['user_name'])) {
            $resource->whereHas('user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%'.$form['user_name'].'%');
            });
        }
        // 负责网管ID
        if(isset($form['member_id'])) $resource->where('member_id', '=', $form['member_id']);
        // 负责网管NETID
        if(isset($form['member_netid'])) {
            $resource->whereHas('member.user', function ($query) use ($form) {
                $query->where('netid', 'like', '%'.$form['member_netid'].'%');
            });
        }
        // 负责网管姓名
        if(isset($form['member_name'])) {
            $resource->whereHas('member.user.profile', function ($query) use ($form) {
                $query->where('name', 'like', '%'.$form['member_name'].'%');
            });
        }
        // 报修单状态
        if(isset($form['status_id'])) $resource->where('status_id', '=', $form['status_id']);
        // 所属部门
        if(isset($form['department_id'])) {
            $resource->whereHas('building', function ($query) use ($form) {
                $query->where('department_id', '=', $form['department_id']);
            });
        }
        // 所属宿舍楼
        if(isset($form['building_id'])) $resource->where('building_id', '=', $form['building_id']);
        // 房间号
        if(isset($form['room'])) $resource->where('room', 'like','%'.$form['room'].'%');
        // 手机号
        if(isset($form['mobile'])) $resource->where('mobile', 'like', '%'.$form['mobile'].'%');
        // QQ号
        if(isset($form['qq'])) $resource->where('qq', 'like', '%'.$form['qq'].'%');
        // 微信号
        if(isset($form['wechat'])) $resource->where('wechat', 'like', '%'.$form['wechat'].'%');
        // 优先处理方式
        if(isset($form['service_id'])) $resource->where('service_id', '=', $form['service_id']);
        // 关键词
        if(isset($form['keyword'])) $resource->where('detail', 'like', '%'.$form['keyword'].'%');
        // 报修时间
        if(isset($form['created_at_start']) && isset($form['created_at_end'])) {
            $created_at_start =  Carbon::parse($form['created_at_start']);
            $created_at_end =  Carbon::parse($form['created_at_end']);
            $resource->whereBetween('created_at', [
                $created_at_start->toDatetimeString(),
                $created_at_end->addDay()->toDatetimeString()
            ]);
        }
        return $resource;
    }

    /**
     * 用户创建新报修单
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function add(array $form)
    {
        $resource = $this->createModel();

        $resource->status_id = 1;
        $resource->building_id = $form['building'];
        $resource->room = $form['room'];
        $resource->mobile = $form['mobile'];
        $resource->qq = $form['qq'];
        $resource->wechat = $form['wechat'];
        $resource->service_id = $form['service'];
        $resource->detail = $form['detail'];

        return $resource->save() ? $resource : false;
    }

    /**
     * 部长添加新报修单
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function append(array $form)
    {
        $resource = $this->createModel();

        $resource->status_id = 2;
        $resource->member_id = $form['member'];
        $resource->building_id = $form['building'];
        $resource->room = $form['room'];
        $resource->mobile = $form['mobile'];
        $resource->qq = $form['qq'];
        $resource->wechat = $form['wechat'];
        $resource->service_id = $form['service'];
        $resource->detail = $form['detail'];

        return $resource->save() ? $resource : false;
    }

    /**
     * 获取用户所有报修
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userOrders($id) {
        $resource = $this->query([
            'user_id' => $id
        ])->get();
        
        return $resource;
    }

    /**
     * 用户添加新报修
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | false
     */
    public function userAdd(array $form) {
        if ( ( $resource = $this->add($form) ) === false) {
            return false;
        }

        $resource->user()->associate(Auth::user());

        if ($resource->save()) {
            event(new OrderCreateEvent($resource));
            return $resource;
        }
        
        return false;
    }

    /**
     * 部长添加新报修
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model | false
     */
    public function managerAdd(array $form) {
        if ( ( $resource = $this->append($form) ) === false) {
            return false;
        }

        $resource->user()->associate(Auth::user());

        if ($resource->save()) {
            return $resource;
        }
        
        return false;
    }

    /**
     * 用户催促报修
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function userUrge($id) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);

        if ($resource->urged_at === null ? 
            (time() - strtotime($resource->created_at) < 10800 ? true : false) :
            (time() - strtotime($resource->urged_at) < 10800 ? true : false)
        ) {
            return false;
        }

        $resource->urged_at = Carbon::now();
        $resource->save();

        event(new OrderUrgeEvent($resource));

        return true;
    }

    /**
     * 催促所有未接单报修
     *
     * @return bool
     */
    public function autoUrge() {
        $todo_orders = $this->model()->withoutGlobalScopes(['orderScope'])
            ->where('status_id', '=', 1)
            ->get();
        
        foreach ($todo_orders as $order) {
            event(new OrderAutoUrgeEvent($order));
        }

        return true;
    }

    /**
     * 用户撤销报修单
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function userCancel($id) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        $resource->status_id = 99;
        $resource->save();

        return $resource->delete();
    }

    /**
     * 管理员删除报修单
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function adminDelete($id) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        $resource->status_id = 100;
        $resource->save();

        return $resource->delete();
    }

    /**
     * 管理员删除报修单
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function delete($id) {
        return $this->adminDelete($id);
    }
    
    /**
     * 社员接单
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function receive($id) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        $resource->status_id = 2;
        $resource->member()->associate(Auth::user()->member);
        $resource->received_at = Carbon::now();
        
        if ($resource->save()) {
            event(new OrderReceiveEvent($resource));
            return true;
        }
        
        return false;
    }
    
    /**
     * 编辑报修单
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function update($id, array $form) {
        
        $resource = $this->model()->find($id);
        
        if(isset($form['member_id'])) $resource->member_id = $form['member_id'];
        if(isset($form['mobile'])) $resource->mobile = $form['mobile'];
        if(isset($form['qq'])) $resource->qq = $form['qq'];
        if(isset($form['wechat'])) $resource->wechat = $form['wechat'];
        
        return $resource->save();
    }
    
    /**
     * 用户评价
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function rate($id, array $form) {
        return $this->userRate($id, $form);
    }
    
    /**
     * 用户评价
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function userRate($id, array $form) {
        $resource = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        
        if($resource->feedback_user){
            return false;
        }
        
        $resource->status_id = 4;
        $resource->feedback_user()->create([
            'score' => $form['score'],
            'detail' => $form['detail'],
        ]);
        
        return $resource->save();
    }
    
    /**
     * 超时系统自动好评
     *
     * @return bool
     */
    public function autoRate() {
        try {
            $expired_orders = $this->model()->withoutGlobalScopes(['orderScope'])
                ->where('status_id', '=', 3)
                ->doesntHave('feedback_user')
                ->where('completed_at', '<', Carbon::now()->subDays(5))
                ->get();
                
            $auto_feedback_user = [
                'score' => 5,
                'detail' => '超过5天未评价，自动好评。',
            ];
            
            $expired_orders->map(function ($order) use ($auto_feedback_user){
                $this->rate($order->id, $auto_feedback_user);
            });
            
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    
    /**
     * 社员报告
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function report($id, array $form) {
        $order = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        
        $feedback_member_arr = [
            'score' => $form['score'],
            'service_id' => $form['service_id'],
            'detail' => $form['detail'],
        ];
        
        if( $order->feedback_member === null ){
            $feedback_member = $order->feedback_member()->create($feedback_member_arr);
        } else {
            $feedback_member = $order->feedback_member->update($feedback_member_arr);
        }

        if ($feedback_member) {
            event(new OrderReportEvent($order));
            return true;
        }
        return false;
    }

    /**
     * 社长标记报修评分
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function completePoint($id) 
    {
        $order = $this->model()->withTrashed()->findOrFail($id);

        if($order->status_id == 4 || $order->status_id == 5){
            $order->status_id = 5;

            if ($order->save()) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 社员标记报修完成
     *
     * @param  int  $id
     * @param  array  $form
     *
     * @return bool
     */
    public function complete($id) {
        $order = $this->model()->withoutGlobalScopes(['orderScope'])->findOrFail($id);
        
        if($order->feedback_member === null){
            return false;
        }
        
        $order->status_id = 3;
        $order->completed_at = Carbon::now();

        if ($order->save()) {
            event(new OrderCompleteEvent($order));
            return true;
        }
        return false;
    }
    
    /**
     * 获取楼层归属部门
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getDepartments($id)
    {
        $people = $this->model()->with('building')->where("id",$id)->first();
        return $people["building"]['department_id'];
    }
}