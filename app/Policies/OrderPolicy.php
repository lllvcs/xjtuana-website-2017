<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can search orders.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function search(User $user)
    {
        // 对于社员，可以浏览报修单
        if ($user->isMember()) {
            return true;
        }

        // 对于用户，不能浏览报修单
        return false;
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        // 对于社员，可以查看所有报修单
        if ($user->isMember()) {
            return true;
        }

        // 对于用户，判断是否是该用户的报修单
        return $user->id === $order->user->id;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // 对于社员，不能进行报修
        if ($user->isMember()) {
            return false;
        }

        // 对于用户，可以创建报修单（撤销和已存在报修单在Controller中判断）
        return true;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        // 对于社员，社长团&研发部部长&报修单对应运维部长可以编辑，且订单处于待处理状态
        // 目前仅支持修改负责网管，所以只有在接单后才可以编辑
        if ($user->isMember()) {
            return $order->status_id === 2
                && $user->isManager()
                && (
                    $user->member->department_id === 1 
                    || $user->member->department_id === 2
                    || $user->member->department_id === $order->building->department_id
                );
        }

        // 对于用户，判断是否是该用户的报修单，且仍未完成（网管仍未提交报告）
        return ($user->id === $order->user->id) && ($order->status_id < 3);
    }
    
    /**
     * Determine whether the user can cancel the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function cancel(User $user, Order $order)
    {
        // 判断是否是该用户报修单，且仍未有网管接单
        return ($user->id === $order->user->id) && ($order->status_id === 1);
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        // 对于社员，社长团&研发部部长&报修单对应运维部长可以删除，且订单没有处于已报告/已完成/撤销/删除状态
        if ($user->isMember()) {
            return $order->status_id  < 3
                && $order->deleted_at === null
                && $user->isManager()
                && (
                    $user->member->department_id === 1 
                    || $user->member->department_id === 2
                    || $user->member->department_id === $order->building->department_id
                );
        }

        // 对于用户，不能删除
        return false;
    }

    /**
     * Determine whether the user can urge the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function urge(User $user, Order $order)
    {
        // 判断是否是该用户报修单，且仍未有网管接单
        return ($user->id === $order->user->id) && ($order->status_id === 1);
    }

    /**
     * Determine whether the user can rate the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function rate(User $user, Order $order)
    {
        // 判断是否是该用户报修单，且订单已完成待评价
        return ($user->id === $order->user->id) && ($order->status_id === 3);
    }
    
    /**
     * Determine whether the user can receive the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function receive(User $user, Order $order)
    {
        // 对于社员，判断报修单处于待接单状态
        if ($user->isMember()) {
            return $order->status_id === 1;
        }
        // 对于用户，不能接单
        return false;
    }
    
    /**
     * Determine whether the user can report the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function report(User $user, Order $order)
    {
        // 对于社员，判断是否负责该报修，且处于可提交报告状态
        if ($user->isMember()) {
            return $order->status_id === 2 && $user->member->id === $order->member_id;
        }
        // 对于用户，不能提交报告
        return false;
    }
    
    /**
     * Determine whether the user can complete the order.
     *
     * @param  App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function complete(User $user, Order $order)
    {
        // 对于社员
        if ($user->isMember()) {
            // 除负责网管外，只有相应运维部长和管理员可以标记完成
            $manager = $user->isManager() && ($user->isAdmin() || $user->member->department_id === $order->building->department_id);
            $member = $user->member->id === $order->member_id;
            return $order->status_id === 2 && $order->feedback_member !== null && ($manager || $member);
        }
        // 对于用户，不能标记完成
        return false;
    }

    /**
     * Determine whether the user can append orders.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function append(User $user)
    {
        // 对于社员，只有部长以上能添加报修单
        if ($user->isMember()) {
            return $user->isManager();
        }

        // 对于用户，不能添加报修单
        return false;
    }
}
