<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */

namespace app\common\logic;

use app\common\model\PreSell as PreSellModel;
use think\db;

/**
 * 预售
 * Class CatsLogic
 * @package common\Logic
 */
class PreSell
{
    private $pre_sell_id;
    private $preSell;
    private $order;

    public function setPreSellById($pre_sell_id)
    {
        if($pre_sell_id > 0){
            $this->pre_sell_id = $pre_sell_id;
            $this->preSell = PreSellModel::get($pre_sell_id);
        }
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function doOrderPayAfter()
    {
        $orderGoods = Db::name('order_goods')->where(['order_id'=>$this->order['order_id']])->find();
        //支付尾款
        if($this->order['pay_status'] == 2){
            $this->order['pay_status'] = 1;
            $this->order['pay_time'] = time();
            $this->order->save();
        }
        if($this->order['pay_status'] == 0){
            if($this->preSell['deposit_price'] > 0){
                //付订金
                $OrderLogic = new OrderLogic();
                $this->order['order_sn'] = $OrderLogic->get_order_sn();
                $this->order['pay_status'] = 2;
                $this->order['paid_money'] = $this->preSell['deposit_price'] * $orderGoods['goods_num'];
            }else{
                //全额
                $this->order['pay_status'] = 1;
            }
            $this->order['pay_time'] = time();
            $this->preSell['deposit_goods_num'] = $this->preSell['deposit_goods_num'] + $orderGoods['goods_num'];
            $this->preSell['deposit_order_num'] = $this->preSell['deposit_order_num'] + 1;
            $this->preSell->save();
            $this->order->save();
        }
    }
}