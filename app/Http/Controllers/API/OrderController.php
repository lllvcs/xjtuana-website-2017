<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository as Repo;
use Auth;
use App\Http\Requests\API\Order\MineRequest;
use App\Http\Requests\API\Order\SearchRequest;
use App\Http\Requests\API\Order\AppendRequest;
use App\Http\Requests\API\Order\CreateRequest;
use App\Http\Requests\API\Order\CancelRequest;
use App\Http\Requests\API\Order\UrgeRequest;
use App\Http\Requests\API\Order\ReceiveRequest;
use App\Http\Requests\API\Order\UpdateRequest;
use App\Http\Requests\API\Order\ReportRequest;
use App\Http\Requests\API\Order\CompleteRequest;
use App\Http\Requests\API\Order\RateRequest;
use App\Http\Requests\API\Order\DestroyRequest;
use App\Http\Requests\API\Order\ViewRequest;

class OrderController extends Controller
{
    protected $repo;

    public function __construct(Repo $repo) {
        $this->repo = $repo;
    }
    
    public function mine(MineRequest $request) {
        $data = $this->repo->userOrders($request->user()->id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function index(SearchRequest $request) {
        $data = $this->repo->search($request->all());
        return $this->jsonResponse('OK', 0, $data);
    }

    public function show($id, ViewRequest $request) {
        $data = $this->repo->find($id);
        return $this->jsonResponse('OK', 0, $data);
    }
    
    public function store(CreateRequest $request) {
        // 是否有未完成报修
        if (null !== ($undoOrder = $request->user()->orders()->whereIn('status_id', [1, 2, 3])->first())) {
            return $this->jsonResponse('您有未完成报修单！', -2, [
                'redirect' => route('user.order.show', ['id' => $undoOrder->id]),
            ]);
        }
        // 是否最近撤销
        if ($request->user()->hasRecentCancel()) {
            return $this->jsonResponse('距上次撤销报修单不足30分钟，暂时不能报修！', -3);
        }
        // 是否成功添加报修单
        if (!($neworder = $this->repo->userAdd($request->all()))) {
            return $this->jsonResponse('服务器错误！报修单提交失败！', -1);
        } else {
            return $this->jsonResponse('报修单提交成功！网管同学会尽快联系您！', 0, [
                'redirect' => route('user.order.show', ['id' => $neworder->id]),
            ]);
        }
    }
    
    public function cancel($id, CancelRequest $request) {
        if ($this->repo->userCancel($id)) {
            return $this->jsonResponse('报修单撤销成功！30分钟内无法再次报修！', 0);
        } else {
            return $this->jsonResponse('服务器错误！报修单撤销失败！', -1);
        }
    }

    public function urge($id, UrgeRequest $request) {
        if ($this->repo->userUrge($id)) {
            return $this->jsonResponse('催促成功，已通知相关网管和管理员！3小时内无法再次催促。', 0);
        } else {
            return $this->jsonResponse('距报修或上次催促不足3小时，请您耐心等待！网管会成员都是本校学生，志愿为大家服务，若处理不及时还望您能理解~', -1);
        }
    }
    
    public function receive($id, ReceiveRequest $request) {
        if ($this->repo->receive($id)) {
            return $this->jsonResponse('已成功接单，请尽快处理！');
        } else {
            return $this->jsonResponse('接单出错，请刷新或稍候再试！', -1);
        }
    }
    
    public function report($id, ReportRequest $request) {
        if ($this->repo->report($id, $request->all())) {
            return $this->jsonResponse('维修报告已提交！');
        } else {
            return $this->jsonResponse('维修报告提交出错！', -1);
        }
    }
    
    public function complete($id, CompleteRequest $request) {
        if ($this->repo->complete($id, $request->all())) {
            return $this->jsonResponse('报修单已标记完成！');
        } else {
            return $this->jsonResponse('报修单标记完成出错！', -1);
        }
    }
    
    public function rate($id, RateRequest $request) {
        if ($rateorder = $this->repo->userRate($id, $request->all())) {
            return $this->jsonResponse('报修单评价成功，感谢您对我们工作的支持！', 0);
        } else {
            return $this->jsonResponse('服务器错误！报修单评价失败！', -1);
        }
    }

    public function destroy($id, DestroyRequest $request) {
        if($this->repo->delete($id)) {
            return $this->jsonResponse('报修单已删除！');
        } else {
            return $this->jsonResponse('报修单删除出错！', -1);
        }
    }
    
    public function update($id, UpdateRequest $request) {
        if ($this->repo->update($id, $request->all())) {
            return $this->jsonResponse('报修单已修改！');
        } else {
            return $this->jsonResponse('报修单修改出错！', -1);
        }
    }
    
    public function append(AppendRequest $request) {
        if ($this->repo->managerAdd($request->all())) {
            return $this->jsonResponse('报修单添加成功！');
        } else {
            return $this->jsonResponse('报修单添加出错！', -1);
        }
    }
}
