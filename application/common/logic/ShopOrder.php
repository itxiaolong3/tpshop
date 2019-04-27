<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: lhb
 * Date: 2017-05-15
 */

namespace app\common\logic;

use app\common\util\TpshopException;
use think\Model;
use think\Db;

/**
 * 自提订单类
 */
class ShopOrder
{
    private $shopOrder;
    private $shopper;

    public function __construct()
    {
        $this->shopOrder = new \app\common\model\ShopOrder();
    }

    public function setShopOrderModel($shopOrder)
    {
        $this->shopOrder = $shopOrder;
    }

    public function setShopOrderById($shop_order_id){
        $this->shopOrder = \app\common\model\ShopOrder::get($shop_order_id);
    }

    public function setShopper($shopper)
    {
        $this->shopper = $shopper;
    }

    public function writeOff()
    {
        if (empty($this->shopOrder)) {
            throw new TpshopException("自提订单核销", 0, ['status' => 0, 'msg' => '找不到订单']);
        }
        if(!empty($this->shopper)){
            if ($this->shopOrder['shop_id'] != $this->shopper['shop_id']) {
                throw new TpshopException("自提订单核销", 0, ['status' => 0, 'msg' => '订单不属于本门店,不容许核销']);
            }
        }
        if ($this->shopOrder['is_write_off'] == 1) {
            throw new TpshopException("自提订单核销", 0, ['status' => 0, 'msg' => '该订单已核销']);
        }
        $order = $this->shopOrder->order;
        if ($order['shipping_status'] == 1) {
            throw new TpshopException("自提订单核销", 0, ['status' => 0, 'msg' => '该订单已发货']);
        }
        $orderLogic = new Order();
        $orderLogic->setOrderModel($order);
        $orderLogic->deliveryConfirm();
        $orderLogic->orderActionLog('自提订单核销', config('CONVERT_ACTION.delivery_confirm'));
        $this->shopOrder->data(['is_write_off' => 1, 'write_off_time' => time()], true)->save();
    }
	
	public function getShippingStatus($value)
    {
        $status = [0=>'未发货',1=>'已发货'];
        return $status[$value];
    }
	
	public function getDistributionMode($value)
    {
		if($value > 0){
        	$status = '上门自提';
        }else{
        	$status = '快递配送';
		}
		return $status;
    }
}