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
   public function add()
    {
        $goods_id = I("goods_id/d"); // 商品id
        $goods_num = intval(I("goods_num/d"));// 商品数量
        $item_id = I("item_id/d"); // 商品规格id spec_goods_price 表id、  385
       // echo $item_id."+".$goods_num."+".$goods_id;die;
        if(empty($goods_id)){
            return returnBad('请选择要购买的商品',303);
        }
        if(empty($goods_num)){
            return returnBad('购买商品数量不能为0',304);
        }
        if(empty($item_id)){
            return returnBad('选择商品规格',304);
        }
        $goodsinfo=M('goods')->where('goods_id',$goods_id)->find();
        if(empty($goodsinfo)){
            return returnBad('商品不存在或下架了');
    }
//        $specgoodsprice= M('spec_goods_price')->where('item_id',$item_id)->find();
//        if(empty($specgoodsprice)){
//            return returnBad('商品规格不存在');
//        }
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id); //用户id
        $cartLogic->setGoodsModel($goods_id);//商品id
        $cartLogic->setSpecGoodsPriceById($item_id); //规格价格 id
        $cartLogic->setGoodsBuyNum($goods_num); //数量
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
        $cart_ids = input('cart_ids'); //同时删除多个商品
        if(empty($cart_ids)){
            return returnBad('cart_ids参数不能为空');
        }
       $cart=explode(',',$cart_ids);
       if(is_array($cart)){
          foreach ($cart as $k => $v){
              $res[$k]=M('cart')->where('id',$v)->find();
              if(!$res[$k]){
                  unset($cart[$k]);
                  unset($res[$k]);
              }
          }
       }
        if(empty($res)){
            return returnBad("该商品已删除或不存在！");
        }
        $cart_ids=get_arr_column($res,'id');
       $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $result = $cartLogic->delete($cart_ids);
        if($result !== false){
            return returnOk("删除成功");
        }else{
            return returnBad('删除失败',308);
        }
    }





    /**
     * 结算
     */
    public function settlement(){
        $action = input("action/s"); // 行为
        //获取默认地址
        $address_list = M('UserAddress')->where("user_id", $this->user_id)->select();//全部地址列表
        $address = M('UserAddress')->where("user_id = {$this->user_id} and is_default = 1")->field(['address_id,province,city,district,twon,address,mobile,consignee'])->find(); // 看看有没默认收货地址
      // var_dump($address);die;
        if(empty($address)){
            $address = M('UserAddress')->where("user_id = {$this->user_id} and is_default = 0")->order('create_time desc')->field(['address_id,province,city,district,twon,address,mobile,consignee'])->find();
            if($address){
                $data['is_default']=1;
                $data['update_time']=time();
                M('UserAddress')->where("user_id = {$this->user_id} and is_default = 0 and address_id = {$address['address_id']}")->field(['address_id,province,city,district,twon,address,mobile,consignee'])->save($data);
              $address= M('UserAddress')->where("user_id = {$this->user_id} and address_id = {$address['address_id']}")->field(['address_id,province,city,district,twon,address,mobile,consignee'])->find();
            }
        }
        if(!count($address_list)){
            return returnBad('请添加收货地址',400);
        }
//        if(!$address) {// 如果没有设置默认收货地址, 则第一条设置为默认收货地址
//            $region_list = db('region')->where(array('level' => ["in", "1,2,3"]))->cache(true)->getField('id,name');
//            $address['provinceName'] = $region_list[$address['province']];
//            $address['cityName'] = $region_list[$address['city']];
//             $address['districtName'] = $region_list[$address['district']];
//        }
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        //立即购买
        if($action == 'buy_now'){
            $item_id = input("item_id/d"); // 商品价格 规格id 388
            $goods_id = input("goods_id/d"); // 商品id
            $goods_num = input("goods_num/d",1);// 商品数量
            if(empty($item_id)){
                return returnBad('缺少item_id参数',400);
            }
           $spec= M('spec_goods_price')->where(['item_id'=>$item_id])->where(['goods_id'=>$goods_id])->find();
            if(empty($spec)){
                return returnBad('缺少价格规格id或商品没有价格规格',400);
            }
            $cartLogic->setGoodsModel($goods_id);
            $cartLogic->setSpecGoodsPriceById($item_id);
            $cartLogic->setGoodsBuyNum($goods_num);
            $buyGoods = $cartLogic->buyNow();
            //var_dump($buyGoods);die;
            if($buyGoods['status']<0){
                return returnBad($buyGoods['msg'],304);
            }
            $cartList['cartList'][0] = $buyGoods;
           foreach ($cartList['cartList'] as $k =>$v){
               unset($cartList['cartList'][0]["is_virtual"],$cartList['cartList'][0]["virtual_indate"],$cartList['cartList'][0]["sku"],$cartList['cartList'][0]["cut_fee"],$cartList['cartList'][0]["prom_type"],$cartList['cartList'][0]["prom_id"],$cartList['cartList'][0]["add_time"],$cartList['cartList'][0]["prom_type"],$cartList['cartList'][0]["prom_id"],$cartList['cartList'][0]["weight"]);
              unset($cartList['cartList'][0]["goods_fee"],$cartList['cartList'][0]["total_fee"]);
               if($v["goods"]){
                 unset($v["goods"]["exchange_integral"],$v["goods"]["video"],$v["goods"]["cat_id"],$v["goods"]["extend_cat_id"],$v["goods"]["brand_id"],$v["goods"]["click_count"],$v["goods"]["comment_count"],$v["goods"]["store_count"],$v["goods"]["weight"],$v["goods"]["volume"],$v["goods"]["is_virtual"],$v["goods"]["virtual_indate"],$v["goods"]["virtual_limit"],$v["goods"]["virtual_refund"],$v["goods"]["virtual_sales_sum"],$v["goods"]["virtual_refund"],$v["goods"]["virtual_collect_sum"],$v["goods"]["is_recommend"],$v["goods"]["is_new"],$v["goods"]["is_hot"],$v["goods"]["last_update"],$v["goods"]["last_update"],$v["goods"]["last_update"],$v["goods"]["last_update"],$v["goods"]["prom_type"],$v["goods"]["prom_id"],$v["goods"]["spu"],$v["goods"]["sku"],$v["goods"]["template_id"],$v["goods"]["give_integral"],$v["goods"]["suppliers_id"],$v["goods"]["commission"],$v["goods"]["is_on_sale"],$v["goods"]["mobile_content"],$v["goods"]["price_ladder"],$v["goods"]["keywords"],$v["goods"]["goods_sn"],$v["goods"]["is_free_shipping"],$v["goods"]["sort"],$v["goods"]["original_img"]);
              }
           }
        }else {
            $cart_ids = I("cart_ids"); // 购物车id组
            if (!$cart_ids) {
                return returnBad('你的购物车没有选中商品', 305);
            }
            $cart = explode(',', $cart_ids);
            if (is_array($cart)) {
                foreach ($cart as $k => $v) {
                    $res[$k] = M('cart')->where('id', $v)->find();
                    if (!$res[$k]) {
                        unset($cart[$k]);
                        unset($res[$k]);
                    }
                }
            }
            if (empty($res)) {
                return returnBad("该商品已删除或不存在！");
            }
            $cart_ids = get_arr_column($res, 'id');

            $cartList['cartList'] = $cartLogic->getCartListBycartId($cart_ids); // 获取用户选中的购物车商品
            foreach ($cartList['cartList'] as $key => $val) {
                unset($cartList['cartList'][$key]["session_id"], $cartList['cartList'][$key]["goods_sn"], $cartList['cartList'][$key]["bar_code"], $cartList['cartList'][$key]["add_time"], $cartList['cartList'][$key]["prom_type"], $cartList['cartList'][$key]["prom_id"], $cartList['cartList'][$key]["sku"], $cartList['cartList'][$key]["is_on_sale"], $cartList['cartList'][$key]["weight"],$cartList['cartList'][$key]["goods"],$cartList['cartList'][$key]["combination_group_id"],$cartList['cartList'][$key]["member_goods_price"]);

                //var_dump($cartList['cartList']);die;
            }
            $cart_ids= implode(',',$cart_ids);
            $cartList['cartList']['cart_ids']=$cart_ids;
        }
        $activityLogic = new \app\common\logic\ActivityLogic;
        $coupon = $activityLogic->getCouponListInfo($this->user_id);
        $key='id';
        $coupon=$this->assoc_unique($coupon, $key);
        $data = [
            'pay_points' => $this->user['pay_points'],  // 积分
            'user_money' => $this->user['user_money'], //元宝
            'address' => $address,//地址
            'userCartCouponList' => $coupon,  //优惠券，用able判断是否可用
            'cartList' => $cartList['cartList'], // 下单的商品信息
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
        $pay_pwd = input("pay_pwd/s", ''); // 支付密码
        $action = input("action"); // 立即购买
        $data = input('request.');
        $pay_type =input("pay_type",''); //支付方式 1 微信支付 2. 支付宝支付 3. 线下支付
        $cart_ids =input("cart_ids"); //多个购物id 如果不是立即购买就需要加cart_id 多个购物id用逗号隔开
        $cart_validate = Loader::validate('Cart');
        if (!$cart_validate->check($data)) {
            $error = $cart_validate->getError();
            return returnBad($error,304);
        }
        $address = Db::name('user_address')->where("address_id", $address_id)->find();
        if(empty($address)){
            return returnBad("没有获取到地址详细信息，请去个人中心去设置");
        }
        $cartLogic = new CartLogic();
        $pay = new Pay();
        try {
            $cartLogic->setUserId($this->user_id);
            if ($action == 'buy_now') {
                $goods_id = input("goods_id/d"); // 商品id
                $item_id = input("item_id/d"); // 商品规格id
                $goods_num = input("goods_num/d",1);// 商品数量
               $specgoods= M('spec_goods_price')->where(["item_id"=>$item_id])->field(['item_id'])->find();
              if(empty($specgoods)){
                  return returnBad("没有找到商品规格价格");
              }
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
                $cart=explode(',',$cart_ids);
                if(is_array($cart)){
                    foreach ($cart as $k => $v){
                        $res[$k]=M('cart')->where('id',$v)->find();
                        if(!$res[$k]){
                            unset($cart[$k]);
                            unset($res[$k]);
                        }
                    }
                }
                if(empty($res)){
                    return returnBad("该商品已删除或不存在！");
                }
                $cart_ids=get_arr_column($res,'id');
                $userCartList = $cartLogic->getCartListBycartId($cart_ids); // 获取用户选中的购物车商品
                $cartLogic->checkStockCartList($userCartList);
                $pay->payCart($userCartList);
            }


            //使用购物券。、
            $pay->setUserId($this->user_id)
                ->useCouponlistById($coupon_id)->useUserMoneykou($user_money)->usePayPoints($pay_points,false,'mobile');
            // 提交订单
            $placeOrder = new PlaceOrder($pay);
//            if($pay_type ==3){
//                $pwd='1';
//            }
           // echo $pwd;die;
            $placeOrder->setUserAddress($address)->setUserNote($user_note)->addNormalOrder($pay_pwd); //生成订单
            //$cartLogic->clearById($cart_ids);//清理购物车
            $order = $placeOrder->getOrder();//获取订单号
            if($order['order_amount'] >0 ){
                switch ($pay_type){
                    case 1 :
                        $order['order_amount'] = 1 / 100;
                        //订单支付
                        $pay = new PayLogic($this->user['openid'],$order['order_sn'],$order['order_amount']*100);
                        $parameters=$pay->weixinapp();
                        return returnOk($parameters);
                        break;
                    case 2 :
                        $body="下单消费";
                        $out_trade_no=$order['order_sn'];
                        $total_amount=$order['order_amount'];
                        require VENDOR_PATH.'Ali/wappay/pay.php';
                        //$config = parse_url_param($this->pay_code); // 类似于 pay_code=alipay&bank_code=CCB-DEBIT 参数
                       // var_dump($config);die;
                       // $config['body'] = getPayBody($order_id);
                        $config_value['order_sn'] =$order['order_sn'];
                        $config_value['order_amount'] =$order['order_amount'];
                        $config_value['body'] ="下单消费";;

                        break;
                    case 3 :
                       //线下打款
                        $data1['msg']="支付成功！";
                       $data1['out_trade_no']=$order['order_sn'];
                       $data1['total_amount']=$order['order_sn'];
                       $data1['order_id']=$order['order_id'];
                        return returnOk($data1);
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
    function assoc_unique(&$arr, $key) {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        sort($arr); //sort函数对数组进行排序
        return $arr;
    }
}
