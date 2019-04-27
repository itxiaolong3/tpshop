<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 8:54
 */

namespace app\api\controller;

use app\api\logic\PayLogic;
use app\api\logic\PayModel;
use think\Db;

class Payment extends Base
{
    public function getPay()
    {
        //手机端在线充值
        //C('TOKEN_ON',false); // 关闭 TOKEN_ON
        header("Content-type:text/html;charset=utf-8");
        $order_id = I('order_id/d'); //订单id
        $openid = I('openid');
        $data['buy_vip'] = I('buy_vip',0);
        if(is_ios()  && empty($order_id)){
            $order_id = session('pay_order_id');
        }
        $user = $this->user;
        $data['account'] = I('account');
        if($data["account"] < 1 && $data['buy_vip']!=1){
            return returnBad("充值金额不能小于1元", 308);
        }
        if ($order_id > 0) {
            M('recharge')->where(array('order_id' => $order_id, 'user_id' => $user['user_id']))->save($data);
        } else {
            $body = '充值到余额';
            if($data['buy_vip'] == 1){
                $map['user_id'] = $user['user_id'];
                //$u = Db::name("users")->where($map)->find();
                if($user){
                    $data['level'] = I('level',0);
                    if(!$data['level']){
                        $data['level'] = $user['level'];
                    }
                    $deposit = Db::name("user_level")->where(['level_id'=>$data['level']])->value('deposit');
                    if($deposit>$user['deposit']){
                        $data['account'] = $deposit - $user['deposit'];
                    }else{
                        return returnBad("级别选择错误", 308);
                    }
                }
                $body = '缴纳保证金';
            }
            $data['user_id'] = $user['user_id'];
            $data['nickname'] = $user['nickname'];
            $data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
            $data['ctime'] = time();
            $order_id = M('recharge')->add($data);
        }

        if ($order_id) {
            $order = M('recharge')->where("order_id", $order_id)->find();
            if (is_array($order) && $order['pay_status'] == 0) {
                M('recharge')->where("order_id", $order_id)->save(array('pay_code' => 'weixin', 'pay_name' => '微信支付'));
                //微信JS支付
                $pay = new PayLogic($openid,$order['order_sn'],$order['account']*100,$body,'');
                $parameters=$pay->weixinapp();
                return returnOk($parameters);
            } else {
                return returnBad('此充值订单，已完成支付!',307);
            }
        } else {
            return returnBad('提交失败,参数有误!',307);
        }
    }
}