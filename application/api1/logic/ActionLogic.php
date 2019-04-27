<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4
 * Time: 17:01
 */

namespace Api\service;


class ActionModel
{
    /**
     * 获取用户可用的优惠券
     * @param $user_id|用户id
     * @param $orderprice | 订单金额
     * @return array
     */
    public function getUserAbleCouponList($user_id)
    {
        $userCouponArr = [];
        $userCouponList = M("coupon_list")->where("user_id=$user_id and status = 0 and use_time is null")->select();//用户优惠券
        if(!$userCouponList){
            return $userCouponArr;
        }
        $userCouponId = get_arr_column($userCouponList, 'cid');
        $couponList = M("coupon")
            ->where('id', 'IN', $userCouponId)
            ->where('status', 1)
            ->where('use_start_time', '<', time())
            ->where('use_end_time', '>', time())
            ->select();//检查优惠券是否可以用
        foreach ($userCouponList as $userCoupon => $userCouponItem) {
            foreach ($couponList as $coupon => $couponItem) {
                if ($userCouponItem['cid'] == $couponItem['id']) {
                    //消费金额
                    $tmp = $userCouponItem;
                    $tmp['name'] = $couponItem['name'];
                    $tmp['money'] = $couponItem['money'];
                    $tmp['ctype'] = $couponItem['type'];
                    $tmp['condition'] = $couponItem['condition'];
                    $tmp['use_start_time'] = $couponItem['use_start_time'];
                    $tmp['use_end_time'] = $couponItem['use_end_time'];
                    $tmp['url'] = $couponItem['url'];
                    $userCouponArr[] = $tmp;
                }
            }
        }
        return $userCouponArr;
    }

}