<?php
/**
 --------------------------------------------------
 空间类型   购物车控制器
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace app\api\controller;

use Api\service\ActionModel;
use Api\service\OrderModel;
use Api\service\PayModel;
use Api\service\UserModel;
use Api\service\CartModel;

class Action extends Base
{
    /* 
     * 添加购物车
     *  */
    public function addCart()
    {
        $post = $this->check_post();
        if (empty($post['openid'])) {
            $this->ajaxReturn(['code' => '303', 'msg' => '用户openid不能为空']);
            exit();
        }
        $shop = new CartModel();
        $data = $shop->addcart($post);
        if(!empty($data['code'])){
            $this->ajaxReturn($data);exit();
        }
        $this->return_ajax($data);
    }

    public function saveNum()
    {
        $post = $this->check_post();
        if (empty($post['openid'])) {
            $this->ajaxReturn(['code' => '303', 'msg' => '用户openid不能为空']);
            exit();
        }
        //获取用户信息
        $userModel = new UserModel();
        $user = $userModel->getuser($post);
        $post['uid'] = $user["id"];
        $shop = new CartModel();
        $data = $shop->saveNum($post);
        $this->return_ajax($data);
    }

    /* 
     * 获取购物车列表
     *  */
    public function cartList()
    {
        $post = $this->check_post();
        if (empty($post['openid'])) {
            $this->ajaxReturn(['code' => '303', 'msg' => '用户openid不能为空']);
            exit();
        }
        $shop = new CartModel();
        $data = $shop->cartList($post);
        $this->return_ajax($data);
    }

    /*
 * 删除购物车
 *  */
    public function delCart()
    {
        $post = $this->check_post();
        if (empty($post['arr_cid'])) {
            $this->ajaxReturn(['code' => '302', 'msg' => '请选择商品']);
            exit();
        }
        $shop = new CartModel();
        $data = $shop->delCart($post);
        if ($data) {
            $this->ajaxReturn(['code' => '200', 'msg' => '删除成功', 'data' => $data]);
            exit();
        } else {
            $this->ajaxReturn(['code' => '302', 'msg' => '删除失败', 'data' => $data]);
            exit();
        }
    }

    /*商品详情立即购买*/
    public function goorder()
    {
        $post = $this->check_post();
        if (empty($post['openid'])) {
            $this->ajaxReturn(['code' => '303', 'msg' => '用户openid不能为空']);
            exit();
        }

        //获取用户信息
        $userModel = new UserModel();
        $user = $userModel->getuser($post);
        $uid = $user["id"];
        //查询出默认地址
        $addr = M("address")->where("user_id = $uid and type = 1")->find();
        if (!$addr) {
            $aa = M("address")->where("user_id =  $uid")->select();
            if ($aa[0]) {
                $addr = $aa[0];
            } else {
                $this->ajaxReturn(['code' => '400', 'msg' => '请添加收货地址']);
                exit();
            }
        }

        $state = $post['state'] ? $post['state'] : 2;
        $good = $post['goods'];//购买商品的情况

        $arr = json_decode(htmlspecialchars_decode($good), true);

        //查询会员折扣
        $rate = 100;//初始化会员折扣
        if ($user['type'] >= 2) {
            $rate = M('rate')->where(['level' => $user['type']])->getField('rate');
        }
        $tomoney = 0;//初始化订单总价，判断优惠券消费是否满足条件
        if ($state == 2) {//一件商品-立即购买
            $goods_id = $arr[0]['goods_id'];
//            echo $goods_id;die;
            if (!$goods_id) {
                $this->ajaxReturn(['code' => '308', 'msg' => '商品ID没有传']);
                exit();
            }
            $number = $arr[0]['number'];
            if (!$number) {
                $this->ajaxReturn(['code' => '309', 'msg' => '商品数量没有传']);
                exit();
            }

            $specifications = $arr[0]['specifications_id'];
            $re = M("goods")->where("id= $goods_id")->find();
            $price = (float)round($re['price'] * $re['preferential'], 2);
            if ($re['sp'] == 1) {
                if (!$specifications) {
                    $this->ajaxReturn(['code' => '310', 'msg' => '商品规格没有传']);
                    exit();
                }
                $r = M("goods_specifications")->where("id = $specifications and goods_id = $goods_id")->find();
                $gospecifications = $r['specifications'];
                $price = (float)round($r['money'] * $re['preferential'], 2);
            } else {
                $gospecifications = '';
                $specifications = '';
            }
            $money = $price  * ($rate / 100) * $number;
            $tomoney = $price * $number;
            $goods_arr[0]['goods_id'] = $goods_id;
            $goods_arr[0]['price'] = $price * $number;
            $goods_arr[0]['number'] = "$number";
            $goods_arr[0]['goods'] = $re['goods'];
            $goods_arr[0]['picture'] = 'http://' . $_SERVER['HTTP_HOST'] .__ROOT__.$re['pic'];
            $goods_arr[0]['specifications'] = $gospecifications;
            $goods_arr[0]['specifications_id'] = $specifications;

        } elseif ($state == 1) {  //购物车
            $money = 0;
            $cart_id = $post['cart_id']; //  购物车id
            $goods_arr = M("cart")->where(['user_id'=>$user['id'], 'id'=> ['in', $cart_id]])->field('id,goods,goods_id,price,specifications_id,number')->select();
            foreach ($goods_arr as $k => $v) {
                $ggo = M("goods")->where(['id'=>$v['goods_id']])->find();
                $goods_arr[$k]['goods'] = $ggo['goods'];
                $goods_arr[$k]['picture'] = 'http://' . $_SERVER['HTTP_HOST'] .__ROOT__.$ggo['pic'];
                if (!$ggo) {
                    M("cart")->where(['user_id'=>$uid, 'goods_id'=>$v['goods_id']])->delete();
                    $this->ajaxReturn(['code' => '330', 'msg' => '商品' . $ggo['goods'] . '已下架']);
                    exit();
                }
                if ($v['specifications_id']) {
                    $spp = M("goods_specifications")->where(['id'=>$v['specifications_id'], 'goods_id'=>$v['goods_id']])->find();
                    if (!$spp) {
                        $goods_arr[$k]['spec_key_name'] = $spp['specifications'];
                        M("cart")->where(['user_id'=>$uid,'goods_id'=>$v['goods_id']])->delete();
                        $this->ajaxReturn(['code' => '320', 'msg' => '该规格商品已不存在，请重新购物']);
                        exit();
                    }
                    $pricenumber = ((float)round($spp['money'] * $ggo['preferential'], 2)) * $v['number'];
                    $goods_arr[$k]['price'] = "$pricenumber";
                    $goods_arr[$k]['specifications_id'] = $spp['id'];
                    $goods_arr[$k]['specifications'] = $spp['specifications'];
                    $money += ((float)round($spp['money'] * $ggo['preferential'] * ($rate / 100), 2)) * $v['number'];
                    $tomoney += $spp['money'] * $v['number'];
                } else {
                    if ($ggo['sp'] == 1) {
                        $msg = '商品' . $ggo['goods'] . '有规格，请重新选择';
                        M("cart")->where(['user_id' => $uid, 'goods_id' => $v['goods_id']])->delete();
                        $this->ajaxReturn(['code' => '338', 'msg' => $msg]);
                        exit();
                    }else{
                        $goods_arr[$k]['specifications_id'] = '';
                        $goods_arr[$k]['specifications'] = '';
                    }
//                    var_dump($ggo);die;
                    $pricenumber = ((float)round($ggo['price'] * $ggo['preferential'], 2)) * $v['number'];
                    $goods_arr[$k]['price'] = "$pricenumber";
                    $money += ((float)round($ggo['price'] * $ggo['preferential'] * ($rate / 100), 2)) * $v['number'];
                    $tomoney += $ggo['price'] * $v['number'];
                }
            }
        } else {
            $this->ajaxReturn(['code' => '500', 'msg' => '非法操作']);
            exit();
        }
        $data['goods'] = $goods_arr;
        $actionModel = new ActionModel();
        $coupon = $actionModel->getUserAbleCouponList($uid, $tomoney);
        foreach ($coupon as $k => $val){//判断优惠券是否满足下单金额
            if($val['condition']>$tomoney){
                unset($coupon[$k]);
            }
        }
        $data['coupon'] = $coupon;
        $data['rate'] = $rate;
        $data['tomoney'] = "$tomoney";
        $data['money'] = "$money";
        $data['address_id'] = $addr['id'];
        $data['username'] = $addr['consignee'];
        $data['phone'] = $addr['tel'];
        $data['addr'] = $addr['addrcity'] . $addr['address'];
        $data['freight'] = '0.00';
        $data['state'] = $state;
        $this->ajaxReturn(['code' => '200', 'msg' => '查询成功', 'data' => $data]);
        exit();
    }


    /* 
     * 添加用户收货地址
     *  */
    public function addressAdd()
    {
        $post = I('post.');
        $check = tokenCheck();    //--如果拿到微信openid则返回用户id--

        if (!$check) {
            $this->ajaxReturn(['code' => '300', 'msg' => '未获取到有效的openid']);
            exit();
        }

        $address = M('address');  //  用户地址表
        $num = $address->where(array('user_id' => $check))->count();
        if ($num > 9) {
            $this->ajaxReturn(['code' => '401', 'msg' => '每个用户最多添加十条收货地址']);
            exit();
        }

        $province = $post['province'];    //省
        $city = $post['city'];    //市
        $county = $post['county'];    //县
        $addr = $post['addr'];    //详细地址
        $tel = $post['tel'];  //联系人电话
        $consignee = $post['consignee'];  //联系人

        if (empty($province) || empty($city) || empty($county) || empty($addr) || empty($tel) || empty($consignee)) {
            $this->ajaxReturn(['code' => '401', 'msg' => '缺少必填参数']);
            exit();
        }

        $arr = array(
            'user_id' => $check,
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'address' => $addr,
            'tel' => $tel,
            'consignee' => $consignee,
            'add_time' => time()
        );

        $result = $address->add($arr);

        if ($result) {
            $this->ajaxReturn(['code' => '200', 'msg' => '操作成功']);
            exit();
        } else {
            $this->ajaxReturn(['code' => '401', 'msg' => '操作失败']);
            exit();
        }

    }

    //生成订单
    public function addorder()
    {
        $post = $this->check_post();
        if (empty($post['openid'])) {
            $this->ajaxReturn(['code' => '303', 'msg' => '用户openid不能为空']);
            exit();
        }
        //获取用户信息
        $userModel = new UserModel();
        $orderLogic = new OrderModel();
        $user = $userModel->getuser($post);
        $id = $user['id'];
        if (!$user) {
            $this->ajaxReturn(['code' => '303', 'msg' => '会员不存在']);
            exit();
        }

        $address_id = $post['address_id']; //  收货地址id
        $coupon_id = $post['coupon_id']; //  优惠券id

        $state = $post['state'] ? $post['state'] : 2;

        if (!$address_id) {
            $this->ajaxReturn(['code' => '317', 'msg' => '地址没有选择']);
            exit();
        }

        if($state==1){//购物车
            $cart_id = $post['cart_id']; //  购物车id
            $order_goods = M("cart")->where(['user_id'=>$id, 'id'=> ['in', $cart_id]])->select();
            if(!$order_goods){
                $this->ajaxReturn(['code' => '318', 'msg' => '购物车没有商品']);
            }
        }else{
            $good = $post['goods'];//购买商品的情况
            if (!$good) {
                $this->ajaxReturn(['code' => '308', 'msg' => '参数错误']);
                exit();
            }
            $order_goods = json_decode(htmlspecialchars_decode($good), true);
        }

        $result = $orderLogic->calculate_price($user, $order_goods, $coupon_id);
        if ($result['status'] < 0){
            $this->ajaxReturn(['code' => $result['status'], 'msg' => $result['msg']]);exit();
        }
        $car_price = array(
            'couponFee'     => $result['result']['coupon_price'], // 优惠券
            'total_amount'  => $result['result']['total_amount'], // 订单总额 减去优惠券 优惠活动
            'payables'      => $result['result']['order_amount'], // 应付金额
            'goodsFee'      => $result['result']['goods_price'],// 总商品价格
            'order_goods'   => $result['result']['order_goods'],// 商品列表
            'anum'          => $result['result']['anum'],// 总商品价格
        );

        // 提交订单
        $res = $orderLogic->addOrder($user, $address_id, $coupon_id, $car_price, $cart_id); // 添加订单
        if($res){
            $order = M('order')->where(array('id'=>$res['result'],'user_id'=>$user['id']))->find();
            //if($order['state'] == 1){ //该订单未未付款
                // 生成分成记录
                //M('order')->where(array('id' => $order['id']))->save(array('state'=>2, 'pay_time'=> time(), 'type' => 2));
                //$orderModel = new OrderModel();
                //$orderModel->rebateLog($order); // 生成分成记录
            //}
            $payModel=new PayModel($post['openid'], $order['order_id'], intval($order['order_amount']*100));
            $parameters=$payModel->weixinapp();
        }
        $this->ajaxReturn(['code' => $res['status'], 'msg' => $res['msg'], 'data'=> $parameters]);exit();
    }

    public function rebateLog(){
        $order = M('order')->where(array('id'=>'10173'))->find();
        if($order['state'] == 1) { //该订单未未付款
            // 生成分成记录
            $orderModel = new OrderModel();
            $orderModel->rebateLog($order); // 生成分成记录
        }
    }
}