<?php
namespace app\mobile\controller;
use app\common\model\Order as OrderModel;
/**
 * 客服IM控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 17:29
 */
class Supplier extends MobileBase{

    //客服im界面
    public function index()
    {
        $user = array();
        $goods_id = input('get.goods_id');
        if(session("?user")){
            $user = session('user');
        }
        $user['goods_id'] = $goods_id;
        $this->assign('user',$user);

        $order_id = input('order_id');
        if($order_id){
            $Order = new OrderModel();
            $order = $Order::get(['order_id' => $order_id]);
            $this->assign('order', $order);
        }
        return $this->fetch();
    }

    //app 客服交互页面
    public function appServiceContact()
    {
        $user = [
            'goods_id' => input('get.goods_id') ? : '',
            'user_id' => input('get.user_id') ? : '',
            'nickname' => input('get.nickname') ? : '',
            'head_pic' => input('get.head_pic') ? : '',
        ];
        $this->assign('user',$user);
        return $this->fetch('app');
    }
}