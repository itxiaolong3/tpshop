<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 10:58
 */

namespace app\api\controller;


use app\api\logic\PayLogic;
use app\common\logic\CartLogic;
use app\common\logic\CouponLogic;
use app\common\logic\DistributLogic;
use app\common\logic\Pay;
use app\common\logic\PlaceOrder;
use app\common\logic\UsersLogic;
use app\common\util\TpshopException;
use think\Db;
use think\Loader;

class Cart extends Base
{
    /**
     * ajax 购物车列表
     */
    public function index()
    {
        $user_id = $this->user_id;
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($user_id);
        $cartList = $cartLogic->getCartList();//用户购物车
        foreach ($cartList as $k => $v){
            $cartList[$k]["original_img"] = url_add_domain($v["goods"]["original_img"]);
            unset($cartList[$k]["goods"],$cartList[$k]["prom_type"],$cartList[$k]["prom_id"],$cartList[$k]["sku"],$cartList[$k]["spec_key"],$cartList[$k]["item_id"],$cartList[$k]["spec_key_name"],$cartList[$k]["bar_code"],$cartList[$k]["weight"],$cartList[$k]["session_id"]);
        }
       return returnOk($cartList);
    }

    /**
     * ajax 将商品加入购物车
     */
    function add()
    {
        $goods_id = I("goods_id/d"); // 商品id
        $goods_num = I("goods_num/d",1);// 商品数量
        $item_id = I("item_id/d"); // 商品规格id
        if(empty($goods_id)){
            return returnBad('请选择要购买的商品',303);
        }
        if(empty($goods_num)){
            return returnBad('购买商品数量不能为0',304);
        }

        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $cartLogic->setGoodsModel($goods_id);
        $cartLogic->setSpecGoodsPriceById($item_id);
        $cartLogic->setGoodsBuyNum($goods_num);
        try {
            $cartLogic->addGoodsToCart();
            return returnOk('添加购物车成功！');
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            return returnBad($error,308);
        }
    }

    /**
     *  购物车数量修改
     */
    public function changeNum(){
        $id = I("cart_id/d");//购物商品id
        $goods_num = I("goods_num/d", 1);
        if (empty($id)) {
            return returnBad('请选择要更改的商品',308);
        }
        $cartLogic = new CartLogic();
        $result = $cartLogic->changeNum($id, $goods_num);
        if($result['status']==0){
            return returnBad($result['msg']);
        }
        return returnOk('购物车数量修改成功');
    }

    /**
     * 删除购物车商品
     */
    public function delete(){
        $cart_ids = input('cart_ids/a',[]);
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $result = $cartLogic->delete($cart_ids);
        if($result !== false){
            return returnOk();
        }else{
            return returnBad('删除失败',308);
        }
    }


    /**
     * 结算
     */
    public function settlement(){
        $goods_id = input("goods_id/d"); // 商品id
        $goods_num = input("goods_num/d",1);// 商品数量
        $item_id = input("item_id/d"); // 商品规格id
        $action = input("action/s"); // 行为
        if ($this->user_id == 0){
            return returnBad('请先登录',302);
        }
        //获取默认地址
        $address_list = M('UserAddress')->where("user_id", $this->user_id)->select();
        $address = M('UserAddress')->where("user_id = {$this->user_id} and is_default = 1")->find(); // 看看有没默认收货地址
        if(!count($address_list)){
            return returnBad('请添加收货地址',400);
        }
        if(!$address) {// 如果没有设置默认收货地址, 则第一条设置为默认收货地址
            $address = $address_list[0];
            $address['is_default'] = 1;
        }
        if($address){
            $region_list = db('region')->where(array('level' => ["in", "1,2,3"]))->cache(true)->getField('id,name');
            $address['provinceName'] = $region_list[$address['province']];
            $address['cityName'] = $region_list[$address['city']];
            $address['districtName'] = $region_list[$address['district']];
        }

        $cartLogic = new CartLogic();
        $couponLogic = new CouponLogic();
        $cartLogic->setUserId($this->user_id);
        //立即购买
        if($action == 'buy_now'){
            $cartLogic->setGoodsModel($goods_id);
            $cartLogic->setSpecGoodsPriceById($item_id);
            $cartLogic->setGoodsBuyNum($goods_num);
            $buyGoods = $cartLogic->buyNow();
            if($buyGoods['status']<0){
                return returnBad($buyGoods['msg'],304);
            }
            $cartList['cartList'][0] = $buyGoods;
        }else{
            $cart_ids = I("cart_ids"); // 购物车id组
            if (!$cart_ids){
                return returnBad('你的购物车没有选中商品',305);
            }
            $cartList['cartList'] = $cartLogic->getCartListBycartId($cart_ids); // 获取用户选中的购物车商品
            $cartList['cartList'] = $cartLogic->getCombination($cartList['cartList']);  //找出搭配购副商品
        }
        $cartGoodsList = get_arr_column($cartList['cartList'],'goods');
        $cartGoodsId = get_arr_column($cartGoodsList,'goods_id');
        $cartGoodsCatId = get_arr_column($cartGoodsList,'cat_id');
        $cartPriceInfo = $cartLogic->getCartPriceInfo($cartList['cartList']);  //初始化数据。商品总额/节约金额/商品总共数量
        $userCouponList = $couponLogic->getUserAbleCouponList($this->user_id, $cartGoodsId, $cartGoodsCatId);//用户可用的优惠券列表
        $cartList = array_merge($cartList,$cartPriceInfo);
        $userCartCouponList = $cartLogic->getCouponCartList($cartList, $userCouponList);
        foreach ($userCartCouponList as $k => $v){
            $userCartCouponList[$k]["coupon"]["money"] = intval($v["coupon"]["money"]);
            $userCartCouponList[$k]["coupon"]["condition"] = intval($v["coupon"]["condition"]);
        }
        foreach ($cartList['cartList'] as $k => $v){
            $cartList['cartList'][$k]['original_img'] = url_add_domain($v['goods']['original_img']);
            unset($cartList['cartList'][$k]['goods']);
        }

        //积分规则修改后的逻辑
        $pay_points = $this->user['pay_points'];
        $isUseIntegral = tpCache('integral.is_use_integral');
        $isPointMinLimit = tpCache('integral.is_point_min_limit');
        $isPointRate = tpCache('integral.is_point_rate');
        $isPointUsePercent = tpCache('integral.is_point_use_percent');
        $point_rate = tpCache('integral.point_rate');

        if($isUseIntegral==1 && $isPointUsePercent==1) {
            $use_percent_point = tpCache('integral.point_use_percent')/100;
        }else{
            $use_percent_point = 1;
        }
        if($isUseIntegral==1 && $isPointMinLimit==1) {
            $min_use_limit_point = tpCache('integral.point_min_limit');
        }else{
            $min_use_limit_point = 0;
        }

        if($isUseIntegral == 0 || $isPointRate != 1){
            $integralMoney = 0;
        }else{
            if($use_percent_point > 0 && $use_percent_point < 1){
                //计算订单最多使用多少积分
                $point_limit = intval($cartPriceInfo["total_fee"] * $point_rate * $use_percent_point);
                if($pay_points > $point_limit){
                    $pay_points = $point_limit;
                }
            }
            //计算订单最多使用多少积分(没勾选比例的情况)
            if ($min_use_limit_point > 0 && $pay_points < $min_use_limit_point) {
                $integralMoney = 0;
            }else{
                $order_amount_pay_point = round($cartPriceInfo["total_fee"] * $point_rate,2);
                if($pay_points > $order_amount_pay_point){
                    $payPoints = $order_amount_pay_point;
                }else{
                    $payPoints = $pay_points;
                }
                $integralMoney = $payPoints / $point_rate;
            }
        }
        $data = [
            'integralMoney' => $integralMoney,
            'pay_points' => $this->user['pay_points'],
            'user_money' => $this->user['user_money'],
            'address' => $address,
            'userCartCouponList' => $userCartCouponList,  //优惠券，用able判断是否可用
            'cartList' => $cartList['cartList'], // 下单的商品
            'cartPriceInfo' => $cartPriceInfo, // 下单的商品
        ];
        return returnOk($data);
    }

    /**
     * 提交订单
     */
    public function addOrder(){
        $address_id = input("address_id/d", 0); //  收货地址id 102
        $coupon_id = input("coupon_id/d"); //  优惠券id
        $pay_points = input("pay_points/d", 0); //  使用积分
        $user_money = input("user_money/f", 0); //  使用余额
        $user_note = input("user_note/s", ''); // 用户留言
        //$pay_pwd = input("pay_pwd/s", ''); // 支付密码
        $goods_id = input("goods_id/d"); // 商品id
        $goods_num = input("goods_num/d",1);// 商品数量
        $item_id = input("item_id/d"); // 商品规格id
        $action = input("action"); // 立即购买
        $pay_type = input("pay_type"); //支付方式 1 微信支付 2. 支付宝支付 3. 线下支付
        $data = input('request.');
        $cart_validate = Loader::validate('Cart');
        if (!$cart_validate->check($data)) {
            $error = $cart_validate->getError();
            return returnBad($error,304);
        }

        $address = Db::name('user_address')->where("address_id", $address_id)->find();
        $cartLogic = new CartLogic();
        $pay = new Pay();
        try {
            $cartLogic->setUserId($this->user_id);
            if ($action == 'buy_now') {
                $cartLogic->setGoodsModel($goods_id);//242
                $cartLogic->setSpecGoodsPriceById($item_id); //383
                $cartLogic->setGoodsBuyNum($goods_num); //2
                $buyGoods = $cartLogic->buyNow();
                $cartList[0] = $buyGoods;
                $pay->payGoodsList($cartList);
            } else {
                $cart_ids = I("cart_ids"); // 购物车id组
                if (!$cart_ids){
                    return returnBad('你的购物车没有选中商品',305);
                }
                $userCartList = $cartLogic->getCartListBycartId($cart_ids); // 获取用户选中的购物车商品
                $cartLogic->checkStockCartList($userCartList);
                $pay->payCart($userCartList);
            }
            $pay->setUserId($this->user_id)->delivery($address['district'])->orderPromotion()
                ->useCouponById($coupon_id)->useUserMoney($user_money)->usePayPoints($pay_points,false,'mobile');
            // 提交订单
            $placeOrder = new PlaceOrder($pay);
            $placeOrder->setUserAddress($address)->setUserNote($user_note)->addNormalOrder();
            $cartLogic->clearById($cart_ids);
            $order = $placeOrder->getOrder();

            if($order['order_amount'] >0 ){
                switch ($pay_type){
                   case 1 :
                            $order['order_amount'] = mt_rand(1, 3) / 100;
                        //订单支付
                        $pay = new PayLogic($this->user['openid'],$order['order_sn'],$order['order_amount']*100);
                        $parameters=$pay->weixinapp();
                        return returnOk($parameters);
                        break;
                    case 2 :
                        require VENDOR_PATH.'Ali/wappay/payWeb.php';
//                            $order['order_amount'] = mt_rand(1, 2) / 100;
//                           require_once  PLUGINS_PATH."payment/alipay/alipay.class.php";
//                          $alipay= new \alipay($order['order_sn']);
//                          $result= $alipay->get_code();
//                           var_dump($result);die;
//                        //订单支付
//                      //  $pay = new PayLogic($this->user['openid'],$order['order_sn'],$order['order_amount']*100);
//
//                     //   $parameters=$pay->weixinapp();
//                        return returnOk($result);
                        break;
                    case 3 :
                       //线下打款
                        //订单支付
                        $pay = new PayLogic($this->user['openid'],$order['order_sn'],$order['order_amount']*100);
                        $parameters=$pay->weixinapp();
                        return returnOk($parameters);
                        break;
                    default:
                        return returnBad('请选择支付方式');
                }

        }
            return returnOk(["order_id"=>$order['order_id']]);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            return returnBad($error['msg'], 309);
        }
    }

    /*
    * 订单支付页面
    */
    public  function wxPay(){
        if(empty($this->user_id)){
            return returnBad('登录超时请重新登录',302);
        }
        $order_id = I('order_id/d');
        $order = M('order')->where(array('order_id'=>$order_id,'user_id'=>$this->user_id))->find();
        if(!$order){
            return returnBad('订单不存在',306);
        }
        if($order['pay_status'] == 1){
            return returnBad('该订单已支付',306);
        }
        $payModel=new PayLogic($this->user['openid'], $order['order_sn'], $order['order_amount']*100);
        $parameters=$payModel->weixinapp();
        return returnOk($parameters);
    }
}