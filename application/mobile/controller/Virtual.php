<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * 2016-11-21
 */
namespace app\mobile\controller;

use app\common\logic\OrderLogic;
use app\common\logic\VirtualLogic;
use think\Page;
use think\Db;
class Virtual extends MobileBase
{
    public $user_id = 0;
    public $user = array();

    public function _initialize()
    {
        parent::_initialize();
        if (session('?user')) {
            $user = session('user');
            $user = M('users')->where("user_id", $user['user_id'])->find();
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            $this->assign('user', $user); //存储用户信息
            $this->assign('user_id', $this->user_id);
        } else {
            $nologin = array(
                'login', 'pop_login', 'do_login', 'logout', 'verify', 'set_pwd', 'finished',
                'verifyHandle', 'reg', 'send_sms_reg_code', 'identity', 'check_validate_code',
                'forget_pwd', 'check_captcha', 'check_username', 'send_validate_code',
            );
            if (!in_array(ACTION_NAME, $nologin)) {
                header("location:" . U('Mobile/User/login'));
                exit;
            }
            if (ACTION_NAME == 'password') $_SERVER['HTTP_REFERER'] = U("Mobile/User/index");
        }

    }

    public function buy_virtual(){
    	$goods = $this->check_virtual_goods();
    	$this->assign('goods',$goods);
    	return $this->fetch();
    }

    public function check_virtual_goods(){
        $goods_id = I('goods_id/d');
        if(empty($goods_id))$this->error('请求参数错误');
        $goods = M('goods')->where(array('goods_id'=>$goods_id))->find();
        if(!$goods)$this->error('该商品不允许购买，原因有：商品下架、不存在、过期等');
        if($goods['is_virtual'] == 1 && $goods['virtual_indate']>time() && $goods['store_count']>0){
            $goods_num = $goods['goods_num'] = I('goods_num/d');
            if($goods_num < 1){$this->error('最少购买1件');}
            if ($goods['virtual_limit'] > $goods['store_count'] || $goods['virtual_limit'] == 0) {
                $goods['virtual_limit'] = $goods['store_count'];
            }
            $goods_spec = I('goods_spec/a');
            if(!empty($goods_spec) && $goods_spec !='undefined'){
                $specGoodsPriceList = M('SpecGoodsPrice')->where(array('goods_id'=>$goods_id))->cache(true,TPSHOP_CACHE_TIME)->getField("key,key_name,price,store_count,sku"); // 获取商品对应的规格价钱 库存 条码
                foreach($goods_spec as $key => $val){
                    if($val != 'undefined'){
                        $spec_item[] = $val; // 所选择的规格项
//                        $goods['spec_key_name'] .= $specGoodsPriceList[$val]['key_name'];
                    }
                }
                if(!empty($spec_item) && $spec_item !='undefined') // 有选择商品规格
                {
                    sort($spec_item);
                    $spec_key = implode('_', $spec_item);
                    if($specGoodsPriceList[$spec_key]['store_count'] < $goods_num){
                        $this->error('该商品规格库存不足');
                    }
                    $goods['goods_spec_key'] = $spec_key;
                    $goods['spec_key_name'] = $specGoodsPriceList[$spec_key]['key_name'];
                    $spec_price = $specGoodsPriceList[$spec_key]['price']; // 获取规格指定的价格
                    $goods['shop_price'] = empty($spec_price) ? $goods['shop_price'] : $spec_price;
                }
            }

            $goods_spec_key = I('goods_spec_key');
            if(!empty($goods_spec_key)){
                $specGoods = M('SpecGoodsPrice')->where(array('goods_id'=>$goods_id,'key'=>$goods_spec_key))->find();
                if($specGoods['store_count']<$goods_num)$this->error('该商品规格库存不足');
                $goods['shop_price'] = empty($specGoods['price']) ? $goods['shop_price'] : $specGoods['price'];
                $goods['goods_spec_key'] = $goods_spec_key;
                $goods['spec_key_name'] = $specGoods['key_name'];
            }
            $goods['goods_fee'] = $goods['shop_price']*$goods['goods_num'];
            return $goods;
        }else{
            $this->error('该商品不允许购买，原因可能：商品下架、不存在、过期等');
        }
    }

    /**
     * 虚拟订单列表
     */
    public function virtual_list()
    {
        $type = I('get.type');
        $search_key = I('search_key');
        $virtualLogic = new \app\common\logic\VirtualLogic;
        $result = $virtualLogic->orderList($this->user_id, $type, $search_key);        
        $this->assign('order_status', C('ORDER_STATUS'));
        $this->assign('shipping_status', C('SHIPPING_STATUS'));
        $this->assign('pay_status', C('PAY_STATUS'));
        $this->assign('order_list', $result['order_list']);
        $this->assign('active', 'order_list');
        $this->assign('active_status', $type);
        $this->assign('now', time());
        if ($_GET['is_ajax']) {
            return $this->fetch('ajax_Virtual_list');
        }
        return $this->fetch();
    }

    /**
     * 虚拟订单详情
     */
    public function virtual_order(){
        $Order = new \app\common\model\Order();
        $VirtualLogic = new VirtualLogic();
        $order_id = I('get.order_id/d');
        $map['order_id'] = $order_id;
        $map['user_id'] = $this->user_id;
        $orderobj = $Order->where($map)->find();
        if(!$orderobj) $this->error('没有获取到订单信息');
        // 添加属性  包括按钮显示属性 和 订单状态显示属性
        $order_info = $orderobj->append(['order_status_detail','virtual_order_button','order_goods','cancel_info'])->toArray();
        if($order_info['prom_type'] != 5){   //普通订单
            $this->redirect(U('Order/order_detail',['id'=>$order_id]));
        }
        //获取订单操作记录
        $order_action = Db::name('order_action')->where(array('order_id'=>$order_id))->select();
        $data = $VirtualLogic->check_virtual_code($order_info);
        $vrorders = $data['vrorders'];
        $order_info = $data['order_info'];
        $this->assign('vrorders',$vrorders);
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('order_info',$order_info);
        $this->assign('order_action',$order_action);
        return $this->fetch();
    }

    /**
     * 虚拟订单确定收货
     */
    public function virtual_confirm(){
        $order_id = I('post.order_id/d', 0);
        $data = confirm_order($order_id, $this->user_id);
        if($data['status']==1){
            Db::name('order_goods')->where(['order_id'=>$order_id])->save(['is_send'=>1]);  //订单商品状态需要更改一下，不然评价列表找不到
        }
        $this->ajaxReturn($data);
    }
}  