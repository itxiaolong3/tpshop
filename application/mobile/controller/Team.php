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
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */
namespace app\mobile\controller;

use app\common\logic\CouponLogic;
use app\common\logic\Pay;
use app\common\logic\PlaceOrder;
use app\common\logic\team\TeamOrder;
use app\common\model\Goods;
use app\common\model\Order;
use app\common\model\OrderGoods;
use app\common\model\TeamActivity;
use app\common\model\TeamFound;
use app\common\model\TeamGoodsItem;
use app\common\util\TpshopException;
use app\common\logic\UsersLogic;
use think\Db;
use think\Page;


class Team extends MobileBase
{
    public $user_id = 0;
    public $user = array();

    /**
     * 构造函数
     */
    public function  __construct()
    {
        parent::__construct();
        if (session('?user')) {
            $user = session('user');
            $user = M('users')->where("user_id", $user['user_id'])->find();
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            $this->assign('user', $user); //存储用户信息
        }
    }

    /**
     * 拼团首页
     * @return mixed
     */
    public function index()
    {
        $goods_category = Db::name('goods_category')->where(['level' => 1, 'is_show' => 1])->select();
        $this->assign('goods_category', $goods_category);
        return $this->fetch();
    }

    public function category()
    {
        $id = input('id/d');//一级分类ID
        $tid = input('tid/d');//二级分类ID
        $two_all_ids = input('tid/s');//二级分类全部id
        $goods_category_level_one = Db::name('goods_category')->where(['id' => $id])->find();
        $goods_category_level_two = Db::name('goods_category')->where(['parent_id' => $goods_category_level_one['id']])->select();//二级分类
        $goods_where = ['cat_id1' => $id];
        if ($tid) {
            $goods_where['cat_id2'] = $tid;
        }
        if ($goods_category_level_two) {
            $goods_category_level_two_arr = get_arr_column($goods_category_level_two, 'id');
            $two_all_ids = implode(',', $goods_category_level_two_arr);
        }
        $this->assign('goods_category_level_one', $goods_category_level_one);
        $this->assign('goods_category_level_two', $goods_category_level_two);
        $this->assign('two_all_ids', $two_all_ids);
        return $this->fetch();
    }


    /**
     * 拼团首页列表
     */
    public function AjaxTeamList()
    {
        $p = Input('p', 1);
        $id = input('id/d');//一级分类ID
        $tid = input('tid/d');//二级分类ID
        $two_all_ids = input('two_all_ids/s');//二级分类全部id
        $goods_where = [];
        if ($id && $two_all_ids) {
            $category_three_ids = Db::name('goods_category')->where(['parent_id' => ['in', $two_all_ids]])->getField('id', true);//三级分类id
            $goods_where['cat_id'] = ['in', $category_three_ids];
        }
        if ($tid) {
            $category_three_ids = Db::name('goods_category')->where(['parent_id' => $tid])->getField('id', true);//三级分类id
            $goods_where['cat_id'] = ['in', $category_three_ids];
        }
        $team_where = ['a.status' => 1, 'a.is_lottery' => 0, 'a.deleted' => 0];
        if (count($goods_where) > 0) {
            $goods_ids = Db::name('goods')->where(['is_on_sale' => 1])->where($goods_where)->getField('goods_id', true);
            if (!empty($goods_ids)) {
                $team_where['i.goods_id'] = ['IN', $goods_ids];
            } else {
                $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => '']);
            }
        }
        $TeamGoodsItem = new TeamGoodsItem();
        $team_goods_items = $TeamGoodsItem->alias('i')->join('__TEAM_ACTIVITY__ a', 'a.team_id = i.team_id')->with([
            'goods' => function ($query) {
                $query->field('goods_id,goods_name,shop_price');
            },
            'specGoodsPrice' => function ($query) {
                $query->field('item_id,price');
            }])->where($team_where)->group('i.goods_id')->order('a.team_id desc')->page($p, 10)->select();
        $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $team_goods_items]);
    }

    public function info()
    {
        $team_id = input('team_id/d');
        if (empty($team_id)) {
            $this->error('参数错误', U('Mobile/Team/index'));
        }
        $TeamActivity = new TeamActivity();
        $team_activity = $TeamActivity->where('team_id', $team_id)->find();
        if (empty($team_activity)) {
            $this->error('该商品拼团活动不存在或者已被删除', U('Mobile/Team/index'));
        }
        if (empty($team_activity['goods']) || $team_activity['goods']['is_on_sale'] == 0) {
            $this->error('此商品不存在或者已下架', U('Mobile/Team/index'));
        }
        $user_id = cookie('user_id');
        if ($user_id) {
            $collect = Db::name('goods_collect')->where(array("goods_id" => $team_activity['goods_id'], "user_id" => $user_id))->count();
            $this->assign('collect', $collect);
        }
        $this->assign('team_activity', $team_activity);
        return $this->fetch();
    }

    public function ajaxCheckTeam()
    {
        $item_id = input('item_id/d', 0);
        $goods_id = input('goods_id/d');
        if (empty($goods_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $TeamGoodsItem = new TeamGoodsItem();
        $team_goods_item = $TeamGoodsItem->with('team_activity,specGoodsPrice,goods')->where(['goods_id' => $goods_id, 'item_id' => $item_id, 'deleted' => 0])->find();
        if (empty($team_goods_item) || empty($team_goods_item['team_activity'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '该商品拼团活动不存在或者已被删除']);
        }
        if (empty($team_goods_item['goods'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '此商品不存在或者已下架']);
        }
        $team_goods_item = $team_goods_item->append(['team_activity' => ['bd_url', 'front_status_desc', 'bd_pic']])->toArray();
        $this->ajaxReturn(['status' => 1, 'msg' => '此商品拼团活动可以购买', 'result' => ['team_goods_item' => $team_goods_item]]);

    }

    public function ajaxTeamFound()
    {
        $goods_id = input('goods_id');
        $TeamActivity = new TeamActivity();
        $TeamFound = new TeamFound();
        $team_ids = $TeamActivity->where(['goods_id' => $goods_id, 'status' => 1, 'is_lottery' => 0, 'deleted' => 0])->getField('team_id', true);
        //活动正常，抽奖团未开奖才获取商品拼团活动拼单
        if (count($team_ids) > 0) {
            $teamFounds = $TeamFound->with('order,teamActivity')->where(['team_id' => ['IN', $team_ids], 'status' => 1])->order('found_id desc')->select();
            if ($teamFounds) {
                $teamFounds = collection($teamFounds)->append(['surplus'])->toArray();
            }
            $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => ['teamFounds' => $teamFounds]]);
        } else {
            $this->ajaxReturn(['status' => 0, 'msg' => '没有相关记录', 'result' => []]);
        }
    }

    /**
     * 下单
     */
    public function addOrder()
    {
        $goods_id = input('goods_id/d');
        $item_id = input('item_id/d', 0);
        $goods_num = input('goods_num/d');
        $found_id = input('found_id/d');//拼团id，有此ID表示是团员参团,没有表示团长开团
        if ($this->user_id == 0) {
            $this->ajaxReturn(['status' => -101, 'msg' => '购买拼团商品必须先登录', 'result' => ['url' => U('User/login')]]);
        }
        if (empty($goods_num)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '至少购买一份', 'result' => '']);
        }
        $team = new \app\common\logic\team\Team();
        $team->setUserById($this->user_id);
        $team->setTeamGoodsItemById($goods_id, $item_id);
        $team->setTeamFoundById($found_id);
        $team->setBuyNum($goods_num);
        try {
            $team->buy();
            $goods = $team->getTeamBuyGoods();
            $goodsList[0] = $goods;
            $pay = new Pay();
            $pay->setUserId($this->user_id);
            $pay->payGoodsList($goodsList);
            $placeOrder = new PlaceOrder($pay);
            $placeOrder->addTeamOrder($team);
            $order = $placeOrder->getOrder();
            $team->log($order);
            $this->ajaxReturn(['status' => 1, 'msg' => '提交拼团订单成功', 'result' => ['order_id' => $order['order_id']]]);
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 结算页
     * @return mixed
     */
    public function order()
    {
        $order_id = input('order_id/d', 0);
        if (empty($this->user_id)) {
            $this->redirect("User/login");
            exit;
        }
        $Order = new Order();
        $OrderGoods = new OrderGoods();
        $setRedirectUrl = new UsersLogic();
        $order = $Order->where(['order_id' => $order_id, 'user_id' => $this->user_id])->find();
        if (empty($order)) {
            $this->error('订单不存在或者已取消', U("Mobile/Order/order_list"));
        }
        $setRedirectUrl->orderPageRedirectUrl($_SERVER['REQUEST_URI'], $order_id);

        $order_goods = $OrderGoods->with('goods')->where(['order_id' => $order_id])->find();
        // 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
        if ($order['pay_status'] == 1) {
            $order_detail_url = U("Mobile/Order/order_detail", array('id' => $order_id));
            $this->redirect($order_detail_url);
        }
        if ($order['order_status'] == 3) {   //订单已经取消
            $this->error('订单已取消', U("Mobile/Order/order_list"));
        }
        //微信浏览器
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            $plugin_where = ['type' => 'payment', 'status' => 1, 'code' => 'weixin'];
        } else {
            $plugin_where = ['type' => 'payment', 'status' => 1, 'scene' => 1];
        }
        $pluginList = Db::name('plugin')->where($plugin_where)->select();
        $paymentList = convert_arr_key($pluginList, 'code');
        //不支持货到付款
        foreach ($paymentList as $key => $val) {
            $val['config_value'] = unserialize($val['config_value']);
            //判断当前浏览器显示支付方式
            if (($key == 'weixin' && !is_weixin()) || ($key == 'alipayMobile' && is_weixin())) {
                unset($paymentList[$key]);
            }
            if ($key == 'weixin' && is_weixin()) {
                $paymentList[$key]['icon'] = 'app_' . $paymentList[$key]['icon'];
            }
        }
        //订单没有使用过优惠券
        if ($order['coupon_price'] <= 0) {
            $couponLogic = new CouponLogic();
            $userCouponList = $couponLogic->getUserAbleCouponList($this->user_id, [$order_goods['goods_id']], [$order_goods['goods']['cat_id']]);//用户可用的优惠券列表
            $team = new \app\common\logic\team\Team();
            $team->setOrder($order);
            $userCartCouponList = $team->getCouponOrderList($userCouponList);
            $order_can_use_coupon_num = $team->getOrderCanUseCouponNum();
            $this->assign('userCartCouponList', $userCartCouponList);
            $this->assign('order_can_use_coupon_num', $order_can_use_coupon_num);
        }
        $this->assign('paymentList', $paymentList);
        $this->assign('order', $order);
        $this->assign('order_goods', $order_goods);
        return $this->fetch();
    }

    /**
     * 获取订单详情
     */
    public function getOrderInfo()
    {
        $order_id = input('order_id/d');
        $goods_num = input('goods_num/d');
        $coupon_id = input('coupon_id/d');
        $address_id = input('address_id/d');
        $user_money = input('user_money/f');
        $pay_points = input('pay_points/d');
        $pay_pwd = trim(input("pay_pwd")); //  支付密码
        $user_note = trim(input("user_note")); //  用户备注
        $act = input('post.act', '');
        if (empty($this->user_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '登录超时', 'result' => ['url' => U("User/login")]]);
        }
        if (empty($order_id)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => []]);
        }
        try {
            $teamOrder = new TeamOrder($this->user_id, $order_id);
            $teamOrder->changNum($goods_num);//更改数量
            $teamOrder->pay();//获取订单结账信息
            $teamOrder->useUserAddressById($address_id);//设置配送地址
            $teamOrder->useCouponById($coupon_id);//使用优惠券
            $teamOrder->useUserMoney($user_money);//使用余额
            $teamOrder->usePayPoints($pay_points, "mobile");//使用积分
            $order = $teamOrder->getOrder();//获取订单信息
            $orderGoods = $teamOrder->getOrderGoods();//获取订单商品信息
            if ($act == 'submit_order') {
                $teamOrder->setUserNote($user_note);//设置用户备注
                $teamOrder->setPayPsw($pay_pwd);//设置支付密码
                $teamOrder->submit();//确认订单
                $this->ajaxReturn(['status' => 1, 'msg' => '提交成功', 'result' => ['order_amount' => $order['order_amount']]]);
            } else {
                $couponLogic = new CouponLogic();
                $userCouponList = $couponLogic->getUserAbleCouponList($this->user_id, [$orderGoods['goods_id']], [$orderGoods['goods']['cat_id']]);//用户可用的优惠券列表
                $team = new \app\common\logic\team\Team();
                $team->setOrder($order);
                $userCartCouponList = $team->getCouponOrderList($userCouponList);
                $result = [
                    'order' => $order,
                    'order_goods' => $orderGoods,
                    'couponList' => $userCartCouponList
                ];
                $this->ajaxReturn(['status' => 1, 'msg' => '计算成功', 'result' => $result]);
            }
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 拼团分享页
     * @return mixed
     */
    public function found()
    {
        $found_id = input('id/d');
        if (empty($found_id)) {
            $this->error('参数错误', U('Mobile/Team/index'));
        }

        $team = new \app\common\logic\team\Team();
        $team->setTeamFoundById($found_id);
        $teamFound = $team->getTeamFound();
        $teamFollow = $teamFound->teamFollow()->where('status','IN', [1,2])->select();
        $this->assign('teamFollow', $teamFollow);//团员
        $this->assign('team', $teamFound->teamActivity);//活动
        $this->assign('teamFound', $teamFound);//团长        
        return $this->fetch();
    }

    public function ajaxGetMore()
    {
        $p = input('p/d', 0);
        $TeamGoodsItem = new TeamGoodsItem();
        $team_goods_items = $TeamGoodsItem->with('goods')->alias('i')->join('__TEAM_ACTIVITY__ a', 'a.team_id = i.team_id')
            ->where(['a.status' => 1, 'a.deleted' => 0])->page($p, 4)->group('i.goods_id')->order(['a.is_recommend' => 'desc', 'a.sort' => 'desc'])->select();
        if (empty($team_goods_items)) {
            $this->ajaxReturn(['status' => 0, 'msg' => '已显示完所有记录']);
        } else {
            $result = collection($team_goods_items)->append(['team_activity' => ['virtual_sale_num']])->toArray();
            $this->ajaxReturn(['status' => 1, 'msg' => '', 'result' => $result]);
        }
    }

    public function lottery()
    {
        $team_id = input('team_id/d', 0);
        $team_lottery = Db::name('team_lottery')->where('team_id', $team_id)->select();
        $TeamActivity = new TeamActivity();
        $team = $TeamActivity->where('team_id', $team_id)->find();
        $this->assign('team', $team);
        $this->assign('team_lottery', $team_lottery);
        return $this->fetch();
    }

}