<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tpshop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 *
 */

namespace app\home\controller;

use app\common\logic\Integral;
use app\common\logic\Pay;
use app\common\logic\PlaceOrder;
use app\common\logic\PreSellLogic;
use app\common\logic\UserAddressLogic;
use app\common\logic\CouponLogic;
use app\common\logic\CartLogic;
use app\common\logic\OrderLogic;
use app\common\model\Combination;
use app\common\model\Order;
use app\common\model\PreSell;
use app\common\model\Shop;
use app\common\model\SpecGoodsPrice;
use app\common\model\Goods;
use app\common\util\TpshopException;
use think\Db;
use think\Loader;

class Cart extends Base
{

    public $cartLogic; // 购物车逻辑操作类
    public $user_id = 0;
    public $user = array();

    /**
     * 初始化函数
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->cartLogic = new CartLogic();
        if (session('?user')) {
            $user = session('user');
            $user = Db::name('users')->where("user_id", $user['user_id'])->find();
            var_dump($user);die;
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            $this->assign('user', $user); //存储用户信息
            // 给用户计算会员价 登录前后不一样
            if ($user) {
                $discount = (empty((float)$user['discount'])) ? 1 : $user['discount'];
                if ($discount != 1) {
                    $c = Db::name('cart')->where(['user_id' => $user['user_id'], 'prom_type' => 0])->where('member_goods_price = goods_price')->count();
                    $c && Db::name('cart')->where(['user_id' => $user['user_id'], 'prom_type' => 0])->update(['member_goods_price' => ['exp', 'goods_price*' . $discount]]);

                }
            }
        }
    }

    public function index()
    {
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $cartList = $cartLogic->getCartList();//用户购物车
        $userCartGoodsTypeNum = $cartLogic->getUserCartGoodsTypeNum();//获取用户购物车商品总数
        $this->assign('userCartGoodsTypeNum', $userCartGoodsTypeNum);
        $this->assign('cartList', $cartList);//普通购物车列表
        return $this->fetch();
    }

    /**
     * 更新购物车，并返回计算结果
     */
    public function AsyncUpdateCart()
    {
        $cart = input('cart/a', []);
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $cartLogic->AsyncUpdateCart($cart);
        $select_cart_list = $cartLogic->getCartList(1);//获取选中购物车
        $cart_price_info = $cartLogic->getCartPriceInfo($select_cart_list);//计算选中购物车
        $user_cart_list = $cartLogic->getCartList();//获取用户购物车
        $return['cart_list'] = $cartLogic->cartListToArray($user_cart_list);//拼接需要的数据
        $return['cart_price_info'] = $cart_price_info;
        $this->ajaxReturn(['status' => 1, 'msg' => '计算成功', 'result' => $return]);
    }

    /**
     *  购物车加减
     */
    public function changeNum()
    {
        $cart = input('cart/a', []);
        if (empty($cart)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择要更改的商品', 'result' => '']);
        }
        if(!(int)$cart['goods_num']){
            $this->ajaxReturn(['status' => 0, 'msg' => '请输入数量', 'result' => ['limit_num'=>1]]);
        }
        $cartLogic = new CartLogic();
        $result = $cartLogic->changeNum($cart['id'], $cart['goods_num']);
        $this->ajaxReturn($result);
    }

    /**
     * 删除购物车商品
     */
    public function delete()
    {
        echo 1211;die;
        $cart_ids = input('cart_ids/a', []);
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $result = $cartLogic->delete($cart_ids);
        if ($result !== false) {
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功', 'result' => $result]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '删除失败', 'result' => $result]);
        }
    }


    /**
     * 购物车优惠券领取列表
     */
    public function getStoreCoupon()
    {
        $goods_ids = input('goods_ids/a', []);
        $goods_category_ids = input('goods_category_ids/a', []);
        if (empty($goods_ids) && empty($goods_category_ids)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '获取失败', 'result' => '']);
        }
        $CouponLogic = new CouponLogic();
        $newStoreCoupon = $CouponLogic->getStoreGoodsCoupon($goods_ids, $goods_category_ids);
        if ($newStoreCoupon) {
            $user_coupon = Db::name('coupon_list')->where('uid', $this->user_id)->getField('cid', true);
            foreach ($newStoreCoupon as $key => $val) {
                if (in_array($newStoreCoupon[$key]['id'], $user_coupon)) {
                    $newStoreCoupon[$key]['is_get'] = 1;//已领取
                } else {
                    $newStoreCoupon[$key]['is_get'] = 0;//未领取
                }
            }
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $newStoreCoupon]);
    }

    /**
     * ajax 将商品加入购物车
     */
    function add()
    {
        $goods_id = I("goods_id/d"); // 商品id
        $goods_num = I("goods_num/d");// 商品数量
        $item_id = I("item_id/d"); // 商品规格id
        if (empty($goods_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择要购买的商品', 'result' => '']);
        }
        if (empty($goods_num)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '购买商品数量不能为0', 'result' => '']);
        }
        if ($goods_num > 200) {
            $this->ajaxReturn(['status' => 0, 'msg' => '购买商品数量大于200', 'result' => '']);
        }
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $cartLogic->setGoodsModel($goods_id);
        $cartLogic->setSpecGoodsPriceById($item_id);
        $cartLogic->setGoodsBuyNum($goods_num);
        try {
            $cartLogic->addGoodsToCart();
            $this->ajaxReturn(['status' => 1, 'msg' => '加入购物车成功']);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * ajax 将搭配购商品加入购物车
     */
    public function addCombination()
    {
        $combination_id = input('combination_id/d');//搭配购id
        $num = input('num/d');//套餐数量
        $combination_goods = input('combination_goods/a');//套餐里的商品
        if (empty($combination_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $cartLogic = new CartLogic();
        $combination = Combination::get(['combination_id' => $combination_id]);
        $cartLogic->setUserId($this->user_id);
        $cartLogic->setCombination($combination);
        $cartLogic->setGoodsBuyNum($num);
        try {
            $cartLogic->addCombinationToCart($combination_goods);
            $this->ajaxReturn(['status' => 1, 'msg' => '成功加入购物车']);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 购物车第二步确定页面
     */
    public function cart2()
    {
        $goods_id = input("goods_id/d"); // 商品id
        $goods_num = input("goods_num/d");// 商品数量
        $item_id = input("item_id/d"); // 商品规格id
        $action = input("action"); // 行为
        if ($this->user_id == 0) {
            $this->error('请先登录', U('Home/User/login'));
        }
        $cartLogic = new CartLogic();
        $couponLogic = new CouponLogic();
        $cartLogic->setUserId($this->user_id);
        //立即购买
        if ($action == 'buy_now') {
            $cartLogic->setGoodsModel($goods_id);
            $cartLogic->setSpecGoodsPriceById($item_id);
            $cartLogic->setGoodsBuyNum($goods_num);
            $buyGoods = [];
            try {
                $buyGoods = $cartLogic->buyNow();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $this->error($error['msg']);
            }
            $cartList['cartList'][0] = $buyGoods;
            $cartGoodsTotalNum = $goods_num;
        } else {
            if ($cartLogic->getUserCartOrderCount() == 0) {
                $this->error('你的购物车没有选中商品', 'Cart/index');
            }
            $cartList['cartList'] = $cartLogic->getCartList(1); // 获取用户选中的购物车商品
            $cartList['cartList'] = $cartLogic->getCombination($cartList['cartList']);  //找出搭配购副商品
            $cartGoodsTotalNum = count($cartList['cartList']);
        }

        $cartGoodsList = get_arr_column($cartList['cartList'], 'goods');
        $cartGoodsId = get_arr_column($cartGoodsList, 'goods_id');
        $cartGoodsCatId = get_arr_column($cartGoodsList, 'cat_id');
        $cartPriceInfo = $cartLogic->getCartPriceInfo($cartList['cartList']);  //初始化数据。商品总额/节约金额/商品总共数量
        $userCouponList = $couponLogic->getUserAbleCouponList($this->user_id, $cartGoodsId, $cartGoodsCatId);//用户可用的优惠券列表
        $cartList = array_merge($cartList, $cartPriceInfo);
        $userCartCouponList = $cartLogic->getCouponCartList($cartList, $userCouponList);
        $this->assign('userCartCouponList', $userCartCouponList);  //优惠券，用able判断是否可用
        $this->assign('cartGoodsTotalNum', $cartGoodsTotalNum);
        $this->assign('cartList', $cartList['cartList']); // 购物车的商品
//        halt($cartList['cartList'][0]['combination_cart']);
        $this->assign('cartPriceInfo', $cartPriceInfo);//商品优惠总价
        return $this->fetch();
    }

    /*
     * ajax 获取用户收货地址 用于购物车确认订单页面
     */
    public function ajaxAddress()
    {
        $address_list = Db::name('UserAddress')->where(['user_id' => $this->user_id])->order('is_default desc')->select();
        if ($address_list) {
            $area_id = array();
            foreach ($address_list as $val) {
                $area_id[] = $val['province'];
                $area_id[] = $val['city'];
                $area_id[] = $val['district'];
                $area_id[] = $val['twon'];
            }
            $area_id = array_filter($area_id);
            $area_id = implode(',', $area_id);
            $regionList = Db::name('region')->where("id", "in", $area_id)->getField('id,name');
            $this->assign('regionList', $regionList);
        }
        $address_where['is_default'] = 1;
        $c = Db::name('UserAddress')->where(['user_id' => $this->user_id, 'is_default' => 1])->count(); // 看看有没默认收货地址
        if ((count($address_list) > 0) && ($c == 0)) // 如果没有设置默认收货地址, 则第一条设置为默认收货地址
            $address_list[0]['is_default'] = 1;
        $this->assign('address_list', $address_list);
        return $this->fetch('ajax_address');
    }

    /**
     * ajax 获取订单商品价格 或者提交 订单
     */
    public function cart3()
    {
        if ($this->user_id == 0) {
            exit(json_encode(array('status' => -100, 'msg' => "登录超时请重新登录!", 'result' => null))); // 返回结果状态
        }
        $address_id = input("address_id/d", 0); //  收货地址id
        $invoice_title = input('invoice_title');  // 发票
        $taxpayer = input('taxpayer');       // 纳税人识别号
        $invoice_desc = input('invoice_desc');       // 发票内容
        $coupon_id = input("coupon_id/d"); //  优惠券id
        $pay_points = input("pay_points/d", 0); //  使用积分
        $user_money = input("user_money/f", 0); //  使用余额
        $user_note = input("user_note/s", ''); // 用户留言
        $pay_pwd = input("pay_pwd/s", ''); // 支付密码
        $goods_id = input("goods_id/d"); // 商品id
        $goods_num = input("goods_num/d");// 商品数量
        $item_id = input("item_id/d"); // 商品规格id
        $action = input("action"); // 立即购买
        $shop_id = input('shop_id/d', 0);//自提点id
        $take_time = input('take_time/d');//自提时间
        $consignee = input('consignee/s');//自提点收货人
        $mobile = input('mobile/s');//自提点联系方式
        $is_virtual = input('is_virtual/d',0);
        $data = input('request.');
        $cart_validate = Loader::validate('Cart');
        if($is_virtual === 1){
            $cart_validate->scene('is_virtual');
        }
        if (!$cart_validate->check($data)) {
            $error = $cart_validate->getError();
            $this->ajaxReturn(['status' => 0, 'msg' => $error, 'result' => '']);
        }
        $address = Db::name('user_address')->where("address_id", $address_id)->find();
        $cartLogic = new CartLogic();
        $pay = new Pay();
        try {
            $cartLogic->setUserId($this->user_id);
            if ($action == 'buy_now') {
                $cartLogic->setGoodsModel($goods_id);
                $cartLogic->setSpecGoodsPriceById($item_id);
                $cartLogic->setGoodsBuyNum($goods_num);
                $buyGoods = $cartLogic->buyNow();
                $cartList[0] = $buyGoods;
                $pay->payGoodsList($cartList);
            } else {
                $userCartList = $cartLogic->getCartList(1);
                $cartLogic->checkStockCartList($userCartList);
                $pay->payCart($userCartList);
            }
            $pay->setUserId($this->user_id)
                ->setShopById($shop_id)
                ->delivery($address['district'])
                ->orderPromotion()
                ->useCouponById($coupon_id)
                ->useUserMoney($user_money)
                ->usePayPoints($pay_points);
            // 提交订单
            if ($_REQUEST['act'] == 'submit_order') {
                $placeOrder = new PlaceOrder($pay);
                $placeOrder->setUserAddress($address)
                    ->setConsignee($consignee)
                    ->setMobile($mobile)
                    ->setInvoiceTitle($invoice_title)
                    ->setUserNote($user_note)
                    ->setTaxpayer($taxpayer)
                    ->setInvoiceDesc($invoice_desc)
                    ->setPayPsw($pay_pwd)
                    ->setTakeTime($take_time)
                    ->addNormalOrder();
                $cartLogic->clear();
                $order = $placeOrder->getOrder();
                $this->ajaxReturn(['status' => 1, 'msg' => '提交订单成功', 'result' => $order['order_sn']]);
            }
            $this->ajaxReturn(['status' => 1, 'msg' => '计算成功', 'result' => $pay->toArray()]);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 订单支付页面
     */
    public function cart4()
    {
        if (empty($this->user_id)) {
            $this->redirect('User/login');
        }
        $order_id = I('order_id/d');
        $order_sn = I('order_sn/s', '');
        $order_where['user_id'] = $this->user_id;
        if ($order_sn) {
            $order_where['order_sn'] = $order_sn;
        } else {
            $order_where['order_id'] = $order_id;
        }
        $Order = new Order();
        $order = $Order->where($order_where)->find();
        empty($order) && $this->error('订单不存在！');
        if ($order['order_status'] == 3) {
            $this->error('该订单已取消', U("Mobile/Order/order_detail", array('id' => $order['order_id'])));
        }
        if (empty($order) || empty($this->user_id)) {
            $order_order_list = U("User/login");
            header("Location: $order_order_list");
            exit;
        }
        // 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
        if ($order['pay_status'] == 1) {
            $order_detail_url = U("Home/Order/order_detail", array('id' => $order['order_id']));
            header("Location: $order_detail_url");
            exit;
        }
        //如果是预售订单，支付尾款
        if ($order['pay_status'] == 2 && $order['prom_type'] == 4) {
            if ($order['pre_sell']['pay_start_time'] > time()) {
                $this->error('还未到支付尾款时间' . date('Y-m-d H:i:s', $order['pre_sell']['pay_start_time']));
            }
            if ($order['pre_sell']['pay_end_time'] < time()) {
                $this->error('对不起，该预售商品已过尾款支付时间' . date('Y-m-d H:i:s',$order['pre_sell']['pay_end_time'] ));
            }
        }
        $payment_where = array(
            'type' => 'payment',
            'status' => 1,
            'scene' => array('in', array(0, 2))
        );
        //预售和抢购暂不支持货到付款
        $orderGoodsPromType = M('order_goods')->where(['order_id' => $order['order_id']])->getField('prom_type', true);
        $no_cod_order_prom_type = [4,5];//预售订单，虚拟订单不支持货到付款
        if (in_array($order['prom_type'], $no_cod_order_prom_type) || in_array(1, $orderGoodsPromType) || $order['shop_id'] > 0) {
            $payment_where['code'] = array('neq', 'cod');
        }
        $paymentList = M('Plugin')->where($payment_where)->select();
        $paymentList = convert_arr_key($paymentList, 'code');

        foreach ($paymentList as $key => $val) {
            $val['config_value'] = unserialize($val['config_value']);
            if ($val['config_value']['is_bank'] == 2) {
                $bankCodeList[$val['code']] = unserialize($val['bank_code']);
            }
        }

        $bank_img = include APP_PATH . 'home/bank.php'; // 银行对应图片
        $this->assign('paymentList', $paymentList);
        $this->assign('bank_img', $bank_img);
        $this->assign('order', $order);
        $this->assign('bankCodeList', $bankCodeList);
        $this->assign('pay_date', date('Y-m-d', strtotime("+1 day")));

        return $this->fetch();
    }


    //ajax 请求购物车列表
    public function header_cart_list()
    {
        $cartLogic = new CartLogic();
        $cartLogic->setUserId($this->user_id);
        $cartList = $cartLogic->getCartList();
        $cartPriceInfo = $cartLogic->getCartPriceInfo($cartList);
        $this->assign('cartList', $cartList); // 购物车的商品
        $this->assign('cartPriceInfo', $cartPriceInfo); // 总计
        $template = I('template', 'header_cart_list');
        return $this->fetch($template);
    }

    /**
     * 兑换积分商品
     */
    public function buyIntegralGoods()
    {
        $goods_id = input('goods_id/d');
        $item_id = input('item_id/d');
        $goods_num = input('goods_num');
        $Integral = new Integral();
        $Integral->setUserById($this->user_id);
        $Integral->setGoodsById($goods_id);
        $Integral->setSpecGoodsPriceById($item_id);
        $Integral->setBuyNum($goods_num);
        try {
            $Integral->checkBuy();
            $url = U('Cart/integral', ['goods_id' => $goods_id, 'item_id' => $item_id, 'goods_num' => $goods_num]);
            $result = ['status' => 1, 'msg' => '购买成功', 'result' => ['url' => $url]];
            $this->ajaxReturn($result);
        } catch (TpshopException $t) {
            $result = $t->getErrorArr();
            $this->ajaxReturn($result);
        }
    }

    /**
     *  积分商品结算页
     * @return mixed
     */
    public function integral()
    {
        $goods_id = input('goods_id/d');
        $item_id = input('item_id/d');
        $goods_num = input('goods_num/d', 1);
        if (empty($goods_id)) {
            $this->error('非法操作');
        }
        $Goods = new Goods();
        $goods = $Goods->where(['goods_id' => $goods_id])->find();
        if (empty($goods)) {
            $this->error('该商品不存在');
        }
        $goods = $goods->toArray();
        if ($item_id) {
            $SpecGoodsPrice = new SpecGoodsPrice();
            $spec_goods_price = $SpecGoodsPrice->where('goods_id', $goods_id)->where('item_id', $item_id)->find();
            $goods['shop_price'] = $spec_goods_price['price'];
            $goods['key_name'] = $spec_goods_price['key_name'];
        }
        $goods['goods_num'] = $goods_num;
        $this->assign('goods', $goods);
        return $this->fetch();
    }

    /**
     *  积分商品价格提交
     * @return mixed
     */
    public function integral2()
    {
        if ($this->user_id == 0) {
            $this->ajaxReturn(['status' => -100, 'msg' => "登录超时请重新登录!", 'result' => null]);
        }
        $goods_id = input('goods_id/d');
        $item_id = input('item_id/d');
        $goods_num = input('goods_num/d');
        $address_id = input("address_id/d"); //  收货地址id
        $user_note = input('user_note'); // 给卖家留言
        $invoice_title = input('invoice_title'); // 发票
        $taxpayer = input('taxpayer'); // 发票纳税人识别号
        $invoice_desc = input('invoice_desc'); // 发票内容
        $user_money = input("user_money/f", 0); //  使用余额
        $pay_pwd = input('pay_pwd');
        $shop_id = input('shop_id/d', 0);//自提点id
        $take_time = input('take_time/d');//自提时间
        $consignee = input('consignee/s');//自提点收货人
        $mobile = input('mobile/s');//自提点联系方式
        $integral = new Integral();
        $integral->setUserById($this->user_id);
        $integral->setShopById($shop_id);
        $integral->setGoodsById($goods_id);
        $integral->setBuyNum($goods_num);
        $integral->setSpecGoodsPriceById($item_id);
        $integral->setUserAddressById($address_id);
        $integral->useUserMoney($user_money);
        try {
            $integral->checkBuy();
            $pay = $integral->pay();
            // 提交订单
            if ($_REQUEST['act'] == 'submit_order') {
                $placeOrder = new PlaceOrder($pay);
                $placeOrder->setUserAddress($integral->getUserAddress());
                $placeOrder->setConsignee($consignee);
                $placeOrder->setMobile($mobile);
                $placeOrder->setInvoiceTitle($invoice_title);
                $placeOrder->setUserNote($user_note);
                $placeOrder->setTaxpayer($taxpayer);
                $placeOrder->setInvoiceDesc($invoice_desc);
                $placeOrder->setPayPsw($pay_pwd);
                $placeOrder->setTakeTime($take_time);
                $placeOrder->addNormalOrder();
                $order = $placeOrder->getOrder();
                $this->ajaxReturn(['status' => 1, 'msg' => '提交订单成功', 'result' => $order['order_id']]);
            }
            $this->ajaxReturn(['status' => 1, 'msg' => '计算成功', 'result' => $pay->toArray()]);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }

    }

    /**
     *  获取发票信息
     * @date2017/10/19 14:45
     */
    public function invoice()
    {
        $map['user_id'] = $this->user_id;
        $field = [
            'invoice_title',
            'taxpayer',
            'invoice_desc',
        ];

        $info = M('user_extend')->field($field)->where($map)->find();
        if (empty($info)) {
            $result = ['status' => -1, 'msg' => 'N', 'result' => ''];
        } else {
            $result = ['status' => 1, 'msg' => 'Y', 'result' => $info];
        }
        $this->ajaxReturn($result);
    }

    /**
     *  保存发票信息
     * @date2017/10/19 14:45
     */
    public function save_invoice()
    {
        if (IS_AJAX) {
            //A.1获取发票信息
            $invoice_title = trim(I("invoice_title"));
            $taxpayer = trim(I("taxpayer"));
            $invoice_desc = trim(I("invoice_desc"));
            //B.1校验用户是否有历史发票记录
            $map['user_id'] = $this->user_id;
            $info = M('user_extend')->where($map)->find();
            //B.2发票信息
            $data = [];
            $data['invoice_title'] = $invoice_title;
            $data['taxpayer'] = $taxpayer;
            $data['invoice_desc'] = $invoice_desc;
            //B.3发票抬头
            if ($invoice_title == "个人") {
                $data['invoice_title'] = "个人";
                $data['taxpayer'] = "";
            }
            //是否存贮过发票信息
            if (empty($info)) {
                $data['user_id'] = $this->user_id;
                M('user_extend')->add($data)? $status=1:$status=-1;
            }else{
                (M('user_extend')->where($map)->save($data)) === false ? $status = -1 : $status = 1;
            }
            $this->ajaxReturn(['status' => $status, 'msg' =>'', 'result' =>'']);

        }
    }

    /**
     * 优惠券兑换
     */
    public function cartCouponExchange()
    {
        $coupon_code = input('coupon_code');
        $couponLogic = new CouponLogic;
        $return = $couponLogic->exchangeCoupon($this->user_id, $coupon_code);
        $this->ajaxReturn($return);
    }

    /**
     * 预售
     */
    public function pre_sell()
    {
        $prom_id = input('prom_id/d');
        $goods_num = input('goods_num/d');
        if ($this->user_id == 0){
            $this->error('请先登录');
        }
        if(empty($prom_id)){
            $this->error('参数错误');
        }
        $PreSell = new PreSell();
        $preSell = $PreSell::get($prom_id);
        if(empty($preSell)){
            $this->error('活动不存在');
        }
        $PreSellLogic = new PreSellLogic($preSell->goods, $preSell->specGoodsPrice);
        if($PreSellLogic->checkActivityIsEnd()){
            $this->error('活动已结束');
        }
        if(!$PreSellLogic->checkActivityIsAble()){
            $this->error('活动未开始');
        }
        $cartList = [];
        try{
            $cartList[0] = $PreSellLogic->buyNow($goods_num);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->error($error['msg']);
        }
        $cartTotalPrice = array_sum(array_map(function($val){return $val['goods_fee'];}, $cartList));//商品优惠总价
        $this->assign('cartList', $cartList);//购物车列表
        $this->assign('preSell', $preSell);
        $this->assign('cartTotalPrice', $cartTotalPrice);
        return $this->fetch();
    }

    public function pre_sell_place()
    {
        if ($this->user_id == 0){
            $this->ajaxReturn(['status' => -100, 'msg' => "登录超时请重新登录!", 'result' => null]);// 返回结果状态
        }
        $address_id = input("address_id/d"); //  收货地址id
        $user_note = input('user_note/s'); // 给卖家留言
        $invoice_title = input('invoice_title'); // 发票
        $taxpayer = input('taxpayer'); // 纳税人识别号
        $invoice_desc = input('invoice_desc'); // 发票内容
        $goods_num = input("goods_num/d");// 商品数量
        $pre_sell_id = input("pre_sell_id/d");// 预售活动id
        $data = input('request.');
        $cart_validate = Loader::validate('Cart');
        if (!$cart_validate->check($data)) {
            $error = $cart_validate->getError();
            $this->ajaxReturn(['status' => 0, 'msg' => $error, 'result' => '']);
        }
        $address = Db::name('UserAddress')->where("address_id", $address_id)->find();
        $pay = new Pay();
        $PreSell = new PreSell();
        $preSell = $PreSell::get($pre_sell_id);
        $PreSellLogic = new PreSellLogic($preSell->goods, $preSell->specGoodsPrice);
        try{
            //预售商品暂不支持优惠券，积分，余额支付。当订金支付时，订单退款涉及积分余额退款和原设计冲突
            $cart_list[0] = $PreSellLogic->buyNow($goods_num);
            $pay->payGoodsList($cart_list)->setUserId($this->user_id)->delivery($address['district']);
            if ($_REQUEST['act'] == 'submit_order') {
                $placeOrder = new PlaceOrder($pay);
                $placeOrder->setUserAddress($address);
                $placeOrder->setInvoiceTitle($invoice_title);
                $placeOrder->setUserNote($user_note);
                $placeOrder->setTaxpayer($taxpayer);
                $placeOrder->setInvoiceDesc($invoice_desc);
                $placeOrder->addPreSellOrder($preSell);
                $order = $placeOrder->getOrder();
                $this->ajaxReturn(['status'=>1,'msg'=>'提交订单成功','result'=>$order['order_sn']]);
            }
            $result = $pay->toArray();
            $result['deposit_price'] = $preSell['deposit_price'];//订金
            $result['balance_price'] = ($preSell['ing_price'] - $preSell['deposit_price']) * $goods_num;//尾款
            $return_arr = array('status' => 1, 'msg' => '计算成功', 'result' => $result); // 返回结果状态
            $this->ajaxReturn($return_arr);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }
}
