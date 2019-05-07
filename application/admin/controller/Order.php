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
 * Author: 当燃
 * Date: 2015-09-09
 */
namespace app\admin\controller;
use app\admin\logic\RefundLogic;
use app\admin\logic\KdniaoLogic;
use app\api\logic\PayLogic;
use app\common\logic\PlaceOrder;
use app\common\model\Order as OrderModel;
use app\common\logic\Pay;
use app\common\model\OrderGoods;
use app\common\logic\OrderLogic;
use app\common\logic\MessageFactory;
use app\common\model\ReturnGoods;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Page;
use think\Db;

class Order extends Base {
    public  $order_status;
    public  $pay_status;
    public  $shipping_status;
    /**
     * 初始化操作
     */
    public function _initialize() {
        parent::_initialize();
        C('TOKEN_ON',false); // 关闭表单令牌验证
        $this->order_status = C('ORDER_STATUS');
        $this->pay_status = C('PAY_STATUS');
        $this->shipping_status = C('SHIPPING_STATUS');
        // 订单 支付 发货状态
        $this->assign('order_status',$this->order_status);
        $this->assign('pay_status',$this->pay_status);
        $this->assign('shipping_status',$this->shipping_status);
    }

    /**
     *订单首页
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * Ajax首页
     */
    public function ajaxindex(){
        $begin = $this->begin;
        $end = $this->end;
        // 搜索条件
        $condition = array('shop_id'=>0);
        $keyType = I("key_type");
        $keywords = I('keywords','','trim');
        
        $consignee =  ($keyType && $keyType == 'consignee') ? $keywords : I('consignee','','trim');
        $consignee ? $condition['consignee'] = trim($consignee) : false;

        if($begin && $end){
        	$condition['add_time'] = array('between',"$begin,$end");
        }
        $condition['prom_type'] = array('lt',5);
        $order_sn = ($keyType && $keyType == 'order_sn') ? $keywords : I('order_sn') ;
        $order_sn ? $condition['order_sn'] = trim($order_sn) : false;
        
        I('order_status') != '' ? $condition['order_status'] = I('order_status') : false;
        I('pay_status') != '' ? $condition['pay_status'] = I('pay_status') : false;
        //I('pay_code') != '' ? $condition['pay_code'] = I('pay_code') : false;
        if(I('pay_code')){
            switch (I('pay_code')){
                case '余额支付':
                    $condition['pay_name'] = I('pay_code');
                    break;
                case '积分兑换':
                    $condition['pay_name'] = I('pay_code');
                    break;
                case 'alipay':
                    $condition['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $condition['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $condition['pay_name'] = '';
                    $condition['pay_code'] = '';
                    break;
                default:
                    $condition['pay_code'] = I('pay_code');
                    break;
            }
        }

        I('shipping_status') != '' ? $condition['shipping_status'] = I('shipping_status') : false;
        I('user_id') ? $condition['user_id'] = trim(I('user_id')) : false;
        $sort_order = I('order_by','DESC').' '.I('sort');
        $count = Db::name('order')->where($condition)->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        $orderList = Db::name('order')->where($condition)->limit($Page->firstRow,$Page->listRows)->order($sort_order)->select();
        $this->assign('orderList',$orderList);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('pager',$Page);
        return $this->fetch();
    }

    /**
     * 虚拟订单列表
     * @return mixed
     */
    public function virtual_list(){
        return $this->fetch();
    }
    public  function ajaxVirtualList(){
        $pay_status = I('pay_status');
        $keyType = I("key_type");
        $keywords = I('keywords','','trim');
        $condition=['add_time'=>['between',"$this->begin,$this->end"],'prom_type'=>5];
        $pay_status !='' ? $condition['pay_status'] = $pay_status : false;
        if(I('pay_code')){
            switch (I('pay_code')){
                case '余额支付':
                    $condition['pay_name'] = I('pay_code');
                    break;
                case 'alipay':
                    $condition['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $condition['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $condition['pay_name'] = '';
                    $condition['pay_code'] = '';
                    break;
                default:
                    $condition['pay_code'] = I('pay_code');
                    break;
            }
        }

        if(!empty($keywords)){
            $keyType == 'mobile'   ? $condition['mobile']  = $keywords : false;
            $keyType == 'order_sn' ? $condition['order_sn'] = $keywords: false;
        }
//        halt($condition);
        $count = Db::name('order')->where($condition)->count();
        $Page  = new AjaxPage($count,20);
        $orderList = Db::name('order')->where($condition)->limit($Page->firstRow,$Page->listRows)->order('order_id desc')->select();
        $this->assign('orderList',$orderList);
        $this->assign('pager',$Page);
        $this->assign('total_count',$count);
        return $this->fetch();
    }

    // 虚拟订单
    public function virtual_info(){
    header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    public function virtual_cancel(){
        $order_id = I('order_id/d');
        if(IS_POST){
            $admin_note = I('admin_note');
            $order = M('order')->where(array('order_id'=>$order_id))->find();
            if($order){
                $r = M('order')->where(array('order_id'=>$order_id))->save(array('order_status'=>3,'admin_note'=>$admin_note));
                if($r){
                    $commonOrder = new \app\common\logic\Order();
                    $commonOrder->setOrderById($order_id);
                    $commonOrder->orderActionLog('取消订单',$admin_note,$this->admin_id);
                    $this->ajaxReturn(array('status'=>1,'msg'=>'操作成功'));
                }else{
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'操作失败'));
                }
            }else{
                $this->ajaxReturn(array('status'=>-1,'msg'=>'订单不存在'));
            }
        }
        $order = M('order')->where(array('order_id'=>$order_id))->find();
        $this->assign('order',$order);
        return $this->fetch();
    }

    public function verify_code(){
        if(IS_POST){
            $vr_code = trim(I('vr_code'));
            if (!preg_match('/^[a-zA-Z0-9]{15,18}$/',$vr_code)) {
                $this->ajaxReturn(['status'=>0,'msg' => '兑换码格式错误，请重新输入']);
            }
            $vr_code_info = M('vr_order_code')->where(array('vr_code'=>$vr_code))->find();
            $order = M('order')->where(['order_id'=>$vr_code_info['order_id']])->field('order_status,order_sn,user_note,user_id')->find();
            if($order['order_status'] >2){
                $this->ajaxReturn(['status'=>0,'msg' => '兑换码对应订单状态不符合要求']);
            }
            if(empty($vr_code_info)){
                $this->ajaxReturn(['status'=>0,'msg' => '该兑换码不存在']);
            }
            if ($vr_code_info['vr_state'] == '1') {
                $this->ajaxReturn(['status'=>0,'msg' => '该兑换码已被使用']);
            }
            if ($vr_code_info['vr_indate'] < time()) {
                $this->ajaxReturn(['status'=>0,'msg'=>'该兑换码已过期，使用截止日期为： '.date('Y-m-d H:i:s',$vr_code_info['vr_indate'])]);
            }
            if ($vr_code_info['refund_lock'] > 0) {//退款锁定状态:0为正常,1为锁定(待审核),2为同意
                $this->ajaxReturn(['status'=>0,'msg'=> '该兑换码已申请退款，不能使用']);
            }
            $update['vr_state'] = 1;
            $update['vr_usetime'] = time();
            M('vr_order_code')->where(array('vr_code'=>$vr_code))->save($update);
            //检查订单是否完成
            $condition = array();
            $condition['vr_state'] = 0;
            $condition['refund_lock'] = array('in',array(0,1));
            $condition['order_id'] = $vr_code_info['order_id'];
            $condition['vr_indate'] = array('gt',time());
            $vr_order = M('vr_order_code')->where($condition)->select();
            if(empty($vr_order)){
                $data['order_status'] = 2;  //此处不能直接为4，不然前台不能评论
                $data['shipping_status'] = 1;  //此处不能直接为4，不然前台不能评论
                $data['confirm_time'] = time();
                M('order')->where(['order_id'=>$vr_code_info['order_id']])->save($data);
                M('order_goods')->where(['order_id'=>$vr_code_info['order_id']])->save(['is_send'=>1]);  //把订单状态改为已收货
            }
            $order_goods = M('order_goods')->where(['order_id'=>$vr_code_info['order_id']])->find();
            if($order_goods){
                if(empty($vr_order)){
                    // 商品待评价提醒
                    $goods = M('goods')->where(["goods_id" => $order_goods['goods_id']])->field('original_img')->find();
                    $send_data = [
                        'message_title' => '商品待评价',
                        'message_content' => $order_goods['goods_name'],
                        'img_uri' => $goods['original_img'],
                        'order_sn' => $order_goods['rec_id'],
                        'order_id' => $vr_code_info['order_id'],
                        'mmt_code' => 'evaluate_logistics',
                        'type' => 4,
                        'users' => [$order['user_id']],
                        'category' => 2,
                        'message_val' => []
                    ];
                    $messageFactory = new \app\common\logic\MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }
                $result = [
                    'vr_code'=>$vr_code,
                    'order_goods'=>$order_goods,
                    'order'=>$order,
                    'goods_image'=>goods_thum_images($order_goods['goods_id'],240,240),
                ];
                $this->ajaxReturn(['status'=>1,'msg'=>'兑换成功','result'=>$result]);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'虚拟订单商品不存在']);
            }
        }
        return $this->fetch();
    }
    /**
     * 虚拟订单临时支付方法，以后要删除
     */
    public function generateVirtualCode(){
        $order_id = I('order_id/d');
        // 获取操作表
        $order = M('order')->where(array('order_id'=>$order_id))->find();
        update_pay_status($order['order_sn'], ['admin_id'=>session('admin_id'),'note'=>'订单付款成功']);
        $vr_order_code = Db::name('vr_order_code')->where('order_id',$order_id)->find();
        if(!empty($vr_order_code)){
            $this->success('成功生成兑换码', U('Order/virtual_info',['order_id'=>$order_id]), 1);
        }else{
            $this->error('生成兑换码失败', U('Order/virtual_info',['order_id'=>$order_id]), 1);
        }
    }
    /**
     * ajax 发货订单列表
    */
    public function ajaxdelivery(){
    	$condition = array();
    	I('consignee') ? $condition['consignee'] = trim(I('consignee')) : false;
    	I('order_sn') != '' ? $condition['order_sn'] = trim(I('order_sn')) : false;
    	$shipping_status = I('shipping_status');
    	$condition['shipping_status'] = empty($shipping_status) ? array('neq',1) : $shipping_status;
        $condition['order_status'] = array('in','1,2,4');
        $condition['prom_type'] = ['neq',5];
    	$count = M('order')->where($condition)->count();
    	$Page  = new AjaxPage($count,10);
    	//搜索条件下 分页赋值
    	foreach($condition as $key=>$val) {
            if(!is_array($val)){
                $Page->parameter[$key]   =   urlencode($val);
            }
    	}
    	$show = $Page->show();
    	if($shipping_status)
    	    $orderList = M('order')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->field("*,IF(shipping_name='','无需物流',shipping_name) as shipping_name")->order('add_time DESC')->select();
    	else
    	    $orderList = M('order')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->order('add_time DESC')->select();
    	$this->assign('orderList',$orderList);
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('pager',$Page);
    	return $this->fetch();
    }
    
    public function refund_order_list(){
    	I('consignee') ? $condition['consignee'] = trim(I('consignee')) : false;
    	I('order_sn') != '' ? $condition['order_sn'] = trim(I('order_sn')) : false;
    	I('mobile') != '' ? $condition['mobile'] = trim(I('mobile')) : false;
        $prom_type = input('prom_type');
        if($prom_type){
            $condition['prom_type'] = $prom_type;
        }
    	$condition['shipping_status'] = 0;
    	$condition['order_status'] = 3;
    	$condition['pay_status'] = array('gt',0);
    	$count = M('order')->where($condition)->count();
    	$Page  = new Page($count,10);
    	$show = $Page->show();
    	$orderList = M('order')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->order('add_time DESC')->select();
    	$this->assign('orderList',$orderList);
    	$this->assign('page',$show);// 赋值分页输出
    	$this->assign('pager',$Page);
    	return $this->fetch();
    }
    
    public function refund_order_info($order_id){
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
    	$this->assign('order',$order);
    	return $this->fetch();
    }

    /**
     * 取消订单退款
     * @throws \think\Exception
     */
    public function refund_order(){
        $data = I('post.');
        $orderModel = new OrderModel();
        $order = $orderModel::get(['order_id'=>$data['order_id']]);
        if(!$order){
            $this->error('订单不存在或参数错误');
        }
        if($data['pay_status'] == 3){
            $messageFactory = new \app\common\logic\MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 2]);


            $refundLogic = new RefundLogic();
            if($data['refund_type'] == 1){
            	//订单退款退余额
                if($refundLogic->updateRefundOrder($order,1)){
                    $messageLogic->sendRefundNotice($data['order_id'],$order['order_amount']);
                    $this->success('成功退款到账户余额');
                }else{
                    $this->error('退款失败');
                }
            }
            if($data['refund_type']== 0){
                //订单支付原路退回
                //订单退款退余额
                if($refundLogic->updateRefundOrder($order,0)){
                    $messageLogic->sendRefundNotice($data['order_id'],$order['order_amount']);
                    $this->success('成功退款到账户余额');
                }else{
                    $this->error('退款失败');
                }


                if($order['pay_code']=="weixin"){//微信支付支持原路退回

                    //return $res;
                    if ($res['result_code'] == 'SUCCESS') {

                        $this->success("退款成功");die;
                    }
                    $this->error($res['err_code_des']);die;
                }else{
                    $this->error('该订单支付方式不支持在线退回');
                }

//                $pay_code_arr = ['weixin'/*PC+公众号微信支付*/ , 'alipay'/*APP,PC支付宝支付*/   , 'alipayMobile'/*手机支付宝支付*/ , 'miniAppPay'/*小程序微信支付*/  , 'appWeixinPay'/*APP微信支付*/];
//                if(in_array($order['pay_code'] , $pay_code_arr)){}
            }
        }else{
            M('order')->where(array('order_id'=>$order['order_id']))->save($data);
            $this->success('拒绝退款操作成功');
        }
    }

    /**
     * 订单详情
     * @return mixed
     */
    public function detail(){
        $order_id = input('order_id', 0);
        $orderModel = new OrderModel();
        $order = $orderModel::get(['order_id'=>$order_id]);
        if(empty($order)){
            $this->error('订单不存在或已被删除');
        }
        $this->assign('order', $order);
        return $this->fetch();
    }

    /**
     * 获取订单操作记录
     */
    public function getOrderAction(){
        $order_id = input('order_id/d',0);
        $order_id <= 0 && $this->ajaxReturn(['status'=>1,'msg'=>'参数错误！！']);
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order = $orderObj->toArray();
        // 获取操作记录
        $action_log = Db::name('order_action')->where(['order_id'=>$order_id])->order('log_time desc')->select();
        $admins = M("admin")->getField("admin_id , user_name", true);
        $user = M("users")->field('user_id,nickname')->where(['user_id'=>$order['user_id']])->find();
        //查找用户昵称
        foreach ($action_log as $k => $v){
            if ($v['action_user'] == 0){
                $action_log["$k"]['action_user_name'] = '用户:'.$user['nickname'];
            }else{
                $action_log["$k"]['action_user_name'] = '管理员:'.$admins[$v['action_user']];
            }
            $action_log["$k"]["log_time"] = date('Y-m-d H:i:s',$v['log_time']);
            $action_log["$k"]["order_status"] = $this->order_status[$v['order_status']];
            $action_log["$k"]["pay_status"] = $this->pay_status[$v['pay_status']];
            $action_log["$k"]["shipping_status"] = $this->shipping_status[$v['shipping_status']];
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'参数错误！！','data'=>$action_log]);
    }

    /**
     * 拆分订单
     */
    public function split_order(){
    	$order_id = I('order_id');
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
    	if($order['pay_status'] == 0){
    		$this->error('未支付订单不允许拆分');
    		exit;
    	}
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
    	$orderGoods = $order['orderGoods'];
        if($orderGoods){
            $orderGoods = collection($orderGoods)->toArray();
        }
    	if(IS_POST){
    		//################################先处理原单剩余商品和原订单信息
    		$old_goods = I('old_goods/a');
    		foreach ($orderGoods as $val){
    			$all_goods[$val['rec_id']] = $val;//所有商品信息
    		}


    		//################################新单处理
    		for($i=1;$i<20;$i++){
                $temp = $this->request->param($i.'_old_goods/a');
    			if(!empty($temp)){
    				$split_goods[] = $temp;
    			}
    		}

    		foreach ($split_goods as $key=>$vrr){
    			foreach ($vrr as $k=>$v){
    				$all_goods[$k]['goods_num'] = $v;
    				$brr[$key][] = $all_goods[$k];
    			}
    		}

    		$user_money = $order['user_money'] / $order['total_amount'];
    		$integral = $order['integral'] / $order['total_amount'];
    		$order_amount = $order['order_amount'] / $order['total_amount'];
    		$split_user_money = 0;// 累计
    		$split_integral = 0;
    		$split_order_amount = 0;

            foreach($brr as $k=>$goods){
                $newPay = new Pay();
                try{
                    $newPay->setUserId($order['user_id']);
                    $newPay->payGoodsList($goods);
                    $newPay->delivery($order['district']);
                    $newPay->orderPromotion();
                } catch (TpshopException $t) {
                    $error = $t->getErrorArr();
                    $this->error($error['msg']);
                }
    			$new_order = $order;
    			$new_order['order_sn'] = date('YmdHis').mt_rand(1000,9999);
    			$new_order['parent_sn'] = $order['order_sn'];
    			//修改订单费用
    			$new_order['goods_price']    = $newPay->getGoodsPrice(); // 商品总价
    			$new_order['total_amount']   = $newPay->getTotalAmount(); // 订单总价
//                if($order['pay_name'] == '余额支付'){
                    //修改拆分订单余额展示
//                    $new_order['user_money'] = $newPay->getTotalAmount();
                    $new_order['user_money'] = floor(($user_money * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }else{
//                    $new_order['order_amount']   = $newPay->getOrderAmount(); // 应付金额
                    $new_order['order_amount']   = floor(($order_amount * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }
                $new_order['integral'] = floor(($integral * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
                //前面按订单总比例拆分，剩余全部给最后一个订单
                if($k == count($brr)-1){
                    $new_order['user_money'] = $order['user_money']-$split_user_money;
                    $new_order['integral'] = $order['integral']-$split_integral;
                    $new_order['order_amount'] = $order['order_amount']-$split_order_amount;
                }else{
                    $split_user_money += $new_order['user_money'];
                    $split_integral += $new_order['integral'];
                    $split_order_amount += $new_order['order_amount'];
                }
                if($order['integral'] > 0 ){
                    $new_order['integral_money'] = $new_order['integral']/($order['integral']/$order['integral_money']);
                }
                $new_order['add_time'] = time();
    			unset($new_order['order_id']);
    			$new_order_id = DB::name('order')->insertGetId($new_order);//插入订单表
    			foreach ($goods as $vv){
    				$vv['order_id'] = $new_order_id;
    				unset($vv['rec_id']);
    				$nid = M('order_goods')->add($vv);//插入订单商品表
    			}
    		}
            //拆分订单后删除原父订单信息
            $orderObj->delete();
            DB::name('order_goods')->where(['order_id'=>$order_id])->delete();

    		//################################新单处理结束
    		$this->success('操作成功',U('Admin/Order/index'));
            exit;
    	}

    	foreach ($orderGoods as $val){
    		$brr[$val['rec_id']] = array('goods_num'=>$val['goods_num'],'goods_name'=>getSubstr($val['goods_name'], 0, 35).$val['spec_key_name']);
    	}
    	$this->assign('order',$order);
    	$this->assign('goods_num_arr',json_encode($brr));
    	$this->assign('orderGoods',$orderGoods);
    	return $this->fetch();
    }

    /**
     * 价钱修改
     */
    public function editprice($order_id){
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order = $orderObj->toArray();
        $this->editable($order);
        if(IS_POST){
        	$admin_id = session('admin_id');
            if(empty($admin_id)){
                $this->error('非法操作');
                exit;
            }
            $update['discount'] = I('post.discount');
            $update['shipping_price'] = I('post.shipping_price');
            if($update['shipping_price'] < 0){
                $this->error('运费不能小于0');
                exit;
            }
            //total_amount-->goods_price
            $update['order_amount'] = $order['goods_price']+$update['shipping_price']-$update['discount']-$order['user_money']-$order['integral_money']-$order['coupon_price']-$order['order_prom_amount'];
            $row = M('order')->where(array('order_id'=>$order_id))->save($update);
            if(!$row){
                $this->success('没有更新数据',U('Admin/Order/editprice',array('order_id'=>$order_id)));
            }else{
                $this->success('操作成功',U('Admin/Order/detail',array('order_id'=>$order_id)));
            }
            exit;
        }
        $this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * 订单删除
     * @param int $id 订单id
     */
    public function delete_order(){
        $order_id = I('post.order_id/d',0);
    	$order = new \app\common\logic\Order($order_id);
        $order->setOrderById($order_id);
        try{
            $order->adminDelOrder();
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功']);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            $this->ajaxReturn($error);
        }
    }

    /**
     * 订单取消付款
     * @param $order_id
     * @return mixed
     */
    public function pay_cancel($order_id){
    	if(I('remark')){
    		$data = I('post.');
    		$note = array('退款到用户余额','已通过其他方式退款','不处理，误操作项');
    		if($data['refundType'] == 0 && $data['amount']>0){
    			accountLog($data['user_id'], $data['amount'], 0,  '退款到用户余额');

                // 退款消息
                $messageFactory = new MessageFactory();
                $messageLogic = $messageFactory->makeModule(['category' => 2]);
                $messageLogic->sendRefundNotice($data['order_id'],$data['amount']);

    		}
    		$orderLogic = new OrderLogic();
            $orderLogic->orderProcessHandle($data['order_id'],'pay_cancel');
            $commonOrder = new \app\common\logic\Order();
            $commonOrder->setOrderById($data['order_id']);
    		$d = $commonOrder->orderActionLog($data['remark'].':'.$note[$data['refundType']],'支付取消',$this->admin_id);
    		if($d){
    			exit("<script>window.parent.pay_callback(1);</script>");
    		}else{
    			exit("<script>window.parent.pay_callback(0);</script>");
    		}
    	}else{
    		$order = M('order')->where("order_id=$order_id")->find();
    		$this->assign('order',$order);
    		return $this->fetch();
    	}
    }

    /**
     * 订单打印
     * @param string $id
     * @return mixed
     */
    public function order_print($id=''){
        if($id){
            $order_id = $id;
        }else{
            $order_id = I('order_id');
        }
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order =$orderObj->append(['full_address','orderGoods','delivery_method'])->toArray();

//        halt($order['orderGoods']);
        $order['province'] = getRegionName($order['province']);
        $order['city'] = getRegionName($order['city']);
        $order['district'] = getRegionName($order['district']);
        $order['full_address'] = $order['province'].' '.$order['city'].' '.$order['district'].' '. $order['address'];
        if($id){
            return $order;
        }else{
            $shop = tpCache('shop_info');
            $area_list = Db::name('region')->where('id', 'IN', [$shop['province'], $shop['city'], $shop['district']])->order('level asc')->select();
            $shop['address'] =$area_list[0]['name'].' '.$area_list[1]['name'].' '.$area_list[2]['name'].' '.$shop['address'];
            $this->assign('order',$order);
            $this->assign('shop',$shop);
            $template = I('template','picking');
            return $this->fetch($template);
        }
    }

    /**
     *批量打印发货单
     */
    public function delivery_print(){
        $ids =input('print_ids');
        $order_ids=trim($ids,',');
        $orderModel= new OrderModel();
        $orderObj = $orderModel->whereIn('order_id',$order_ids)->select();
        if ($orderObj){
            $order = collection($orderObj)->append(['orderGoods','full_address'])->toArray();
        }
        $shop = tpCache('shop_info');
        $this->assign('order',$order);
        $this->assign('shop',$shop);
        $template = I('template','print');
        return $this->fetch($template);
    }

    /**
     * 快递单打印
     */
    public function shipping_print($id=''){
        if($id){
            $order_id = $id;
        }else{
            $order_id = I('get.order_id');
        }
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order =$orderObj->append(['full_address'])->toArray();
        //查询是否存在订单及物流
        $shipping = Db::name('shipping')->where('shipping_code',$order['shipping_code'])->find();
        if(!$shipping){
        	$this->error('快递公司不存在');
        }
		if(empty($shipping['template_html'])){
			$this->error('请设置'.$shipping['shipping_name'].'打印模板');
		}
        $shop = tpCache('shop_info');//获取网站信息
        $shop['province'] = empty($shop['province']) ? '' : getRegionName($shop['province']);
        $shop['city'] = empty($shop['city']) ? '' : getRegionName($shop['city']);
        $shop['district'] = empty($shop['district']) ? '' : getRegionName($shop['district']);

        $order['province'] = getRegionName($order['province']);
        $order['city'] = getRegionName($order['city']);
        $order['district'] = getRegionName($order['district']);
        $template_var = array("发货点-名称", "发货点-联系人", "发货点-电话", "发货点-省份", "发货点-城市",
        		 "发货点-区县", "发货点-手机", "发货点-详细地址", "收件人-姓名", "收件人-手机", "收件人-电话",
        		"收件人-省份", "收件人-城市", "收件人-区县", "收件人-邮编", "收件人-详细地址", "时间-年", "时间-月",
        		"时间-日","时间-当前日期","订单-订单号", "订单-备注","订单-配送费用");
        $content_var = array($shop['store_name'],$shop['contact'],$shop['phone'],$shop['province'],$shop['city'],
        	$shop['district'],$shop['phone'],$shop['address'],$order['consignee'],$order['mobile'],$order['phone'],
        	$order['province'],$order['city'],$order['district'],$order['zipcode'],$order['address'],date('Y'),date('M'),
        	date('d'),date('Y-m-d'),$order['order_sn'],$order['admin_note'],$order['shipping_price'],
        );
        $shipping['template_html_replace'] = str_replace($template_var, $content_var, $shipping['template_html']);
        if($id){
            return $shipping;
        }else{
            $shippings[0]=$shipping;
            $this->assign('shipping',$shippings);
            return $this->fetch("print_express");
        }

    }

    /**
     *批量打印快递单
     */
    public function shipping_print_batch(){
        $ids=I('post.ids3');
        $ids=substr($ids,0,-1);
        $ids=explode(',', $ids);
        if(!is_numeric($ids[0])){
            unset($ids[0]);
        }

        $shippings=array();
        foreach ($ids as $k => $v) {
            $shippings[$k]=$this->shipping_print($v);
        }
        $this->assign('shipping',$shippings);
        return $this->fetch("print_express");
    }

    /**
     * 生成发货单
     */
    public function deliveryHandle(){
        $orderLogic = new OrderLogic();
		$data = I('post.');
		$res = $orderLogic->deliveryHandle($data);
		if($res['status'] == 1){
			if($data['send_type'] == 2 && !empty($res['printhtml'])){
				$this->assign('printhtml',$res['printhtml']);
				return $this->fetch('print_online');
			}
			$this->success('操作成功',U('Admin/Order/delivery_info',array('order_id'=>$data['order_id'])));
		}else{
			$this->error($res['msg'],U('Admin/Order/delivery_info',array('order_id'=>$data['order_id'])));
		}
    }

    public function delivery_info($id=''){
        if($id){
           $order_id=$id; 
        }else{
           $order_id = I('order_id');
        }

    	$orderGoodsMdel = new OrderGoods();
        $orderModel = new OrderModel();
        $orderObj = $orderModel->where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address'])->toArray();
    	$orderGoods = $orderGoodsMdel::all(['order_id'=>$order_id,'is_send'=>['lt',2]]);
        if($id){
            if(!$orderGoods){
                $this->error('所选订单有商品已完成退货或换货');//已经完成售后的不能再发货
            }
        }else{
            if(!$orderGoods){
                $this->error('此订单商品已完成退货或换货');//已经完成售后的不能再发货  
            }
        }

        if($id){ 
            $order['orderGoods']=$orderGoods;
            $order['goods_num']=count($orderGoods);
            return $order;
        }else{
            $delivery_record = M('delivery_doc')->alias('d')->join('__ADMIN__ a','a.admin_id = d.admin_id')->where('d.order_id='.$order_id)->select();
            if($delivery_record){
                $order['invoice_no'] = $delivery_record[count($delivery_record)-1]['invoice_no'];
            }
            $this->assign('order',$order);
            $this->assign('orderGoods',$orderGoods);
            $this->assign('delivery_record',$delivery_record);//发货记录
            $shipping_list = Db::name('shipping')->field('shipping_name,shipping_code')->where('')->select();
            $this->assign('shipping_list',$shipping_list);
            $express_switch = tpCache('express.express_switch');
            $this->assign('express_switch',$express_switch);
            return $this->fetch();    
        }
    }

    /**
     * 发货单列表
     */
    public function delivery_list(){
        return $this->fetch();
    }

    /**
    *批量发货
    */
    public function delivery_batch(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    /**
    *批量发货处理 
    */
    public function delivery_batch_handle(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    /**
     * 删除某个退换货申请
     */
    public function return_del(){
        $id = I('get.id');
        M('return_goods')->where("id = $id")->delete();
        $this->success('成功删除!');
    }

    /**
     * 退换货操作
     */
    public function return_info()
    {
        $return_id = I('id');
        $return_goods = M('return_goods')->where(['id'=> $return_id])->find();
        !$return_goods && $this->error('非法操作!');
        $user = M('users')->where(['user_id' => $return_goods['user_id']])->find();
        $order = M('order')->where(array('order_id'=>$return_goods['order_id']))->find();
        $order['goods'] = M('order_goods')->where(['rec_id' => $return_goods['rec_id']])->find();
        $return_goods['delivery'] = unserialize($return_goods['delivery']);  //订单的物流信息，服务类型为换货会显示
        $return_goods['seller_delivery'] = unserialize($return_goods['seller_delivery']);  //订单的物流信息，服务类型为换货会显示
        if($return_goods['imgs']) $return_goods['imgs'] = explode(',', $return_goods['imgs']);
        $this->assign('id',$return_id); // 用户
        $this->assign('user',$user); // 用户
        $this->assign('return_goods',$return_goods);// 退换货
        $this->assign('order',$order);//退货订单信息
        $this->assign('return_type',C('RETURN_TYPE'));//退货订单信息
        $this->assign('refund_status',C('REFUND_STATUS'));
        return $this->fetch();
    }

    /**
     *修改退货状态
     */
    public function checkReturniInfo()
    {
        $orderLogic = new OrderLogic();
        $post_data = I('post.');
        $return_goods = Db::name('return_goods')->where(['id'=>$post_data['id']])->find();
        !$return_goods && $this->ajaxReturn(['status'=>-1,'msg'=>'非法操作!']);
        $type_msg = C('RETURN_TYPE');
        $status_msg = C('REFUND_STATUS');
        switch ($post_data['status']){
            case -1 :$post_data['checktime'] = time();break;
            case 1 :$post_data['checktime'] = time();break;
            case 3 :$post_data['receivetime'] = time();break;  //卖家收货时间
            default;
        }
        if($return_goods['type'] > 0  && $post_data['status'] == 4){
            $post_data['seller_delivery']['express_time'] = date('Y-m-d H:i:s');
            $post_data['seller_delivery'] = serialize($post_data['seller_delivery']); //换货的物流信息
            Db::name('order_goods')->where(['rec_id'=>$return_goods['rec_id']])->save(['is_send'=>2]);
        }
        $note ="退换货:{$type_msg[$return_goods['type']]}, 状态:{$status_msg[$post_data['status']]},处理备注：{$post_data['remark']}";
        $result = M('return_goods')->where(['id'=>$post_data['id']])->save($post_data);
        if($result && $post_data['status']==1 && $return_goods['type']!=2)
        {
            //审核通过才更改订单商品状态，进行退货，退款时要改对应商品修改库存
            $order = OrderModel::get($return_goods['order_id']);
            $commonOrderLogic = new OrderLogic();
            $commonOrderLogic->alterReturnGoodsInventory($order,$return_goods['rec_id']); //审核通过，恢复原来库存
            if($return_goods['type'] < 2){
                $orderLogic->disposereRurnOrderCoupon($return_goods); // 是退货可能要处理优惠券
                // 退款提醒 改在 refund_balance或refund_back方法进行提醒
                //$messageFactory = new \app\common\logic\MessageFactory();
                //$messageLogic = $messageFactory->makeModule(['category' => 2]);
                // 发退款消息
                //$messageLogic->sendRefundNotice($return_goods['order_id'], $return_goods['refund_money'] + $return_goods['refund_deposit']);
            }
        }
        $commonOrder = new \app\common\logic\Order();
        $commonOrder->setOrderById($return_goods['order_id']);
        $commonOrder->orderActionLog($note,'退换货',$this->admin_id);
        $this->ajaxReturn(['status'=>1,'msg'=>'修改成功','url'=>'']);
    }

    //售后退款原路退回
    public function refund_back(){
    	$return_id = I('id');
        $refund_deposit = I('refund_deposit/d',0);
        $refund_money = I('refund_money/d',0);
        $refund_integral = I('refund_integral/d',0);
        $refundLogic = new RefundLogic();
        $refundLogic->setRefundDeposit($refund_deposit);
        $refundLogic->setRrefundMoney($refund_money);
        $refundLogic->setRrefundIntegral($refund_integral);
        $return_goods = M('return_goods')->where("id= $return_id")->find();
    	$rec_goods = M('order_goods')->where(array('order_id'=>$return_goods['order_id'],'goods_id'=>$return_goods['goods_id']))->find();
    	$order = M('order')->where(array('order_id'=>$rec_goods['order_id']))->find();
    	if($order['pay_code'] == 'weixin'){
            // 退款提醒
            $messageFactory = new \app\common\logic\MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 2]);

    		$orderLogic = new OrderLogic();
    		$return_goods['refund_money'] = $orderLogic->getRefundGoodsMoney($return_goods);

//            include_once  PLUGIN_PATH."payment/weixin/weixin.class.php";
//            $payment_obj =  new \weixin($order['pay_code']);
//            $data = array('transaction_id'=>$order['transaction_id'],'total_fee'=>$order['order_amount'],'refund_fee'=>$return_goods['refund_money']);
//            $result = $payment_obj->payment_refund($data);

            $tuiOrder = [
                'out_trade_no'=>$order['order_sn'],
                'out_refund_no'=> date('ymd',time()).time().$order['user_id'],
                'total_fee' => $order['order_amount'],
                'refund_fee'=>$return_goods['refund_money'],
            ];
            $weipay = new PayLogic();
            $result = $weipay->weiReturn($tuiOrder);
            if($result['return_code'] == 'SUCCESS'  && $result['result_code'] == 'SUCCESS'){
                $refundLogic->updateRefundGoods($return_goods['rec_id']);//订单商品售后退款
                $this->ajaxReturn(['status'=>1,'msg'=>'退款成功','url'=>U("Admin/Order/return_list")]);
                // 发退款消息
                $messageLogic->sendRefundNotice($return_goods['order_id'], $return_goods['refund_money']);
            }else{
                $this->ajaxReturn(['status'=>-1,'msg'=>'退款失败'.$result['return_msg']]);
            }
    	}else{
    	    $this->ajaxReturn(['status'=>-1,'msg'=>'该订单支付方式不支持在线退回']);
    	}
    }

    /**
     * 退款到用户余额，余额+积分支付
     * 有用三方金额支付的不走这个方法
     */
    public function refund_balance(){
		$rec_id = I('rec_id');
		$refund_deposit = I('refund_deposit/f',0);
		$refund_money = I('refund_money/f',0);
		$refund_integral = I('refund_integral/f',0);
		$return_goods = M('return_goods')->where(array('rec_id'=>$rec_id))->find();
		if(empty($return_goods)) $this->ajaxReturn(['status'=>0,'msg'=>"参数有误"]);
		$refundLogic = new RefundLogic();
		$refundLogic->setRefundDeposit($refund_deposit);//余额 账号里的
		$refundLogic->setRrefundMoney($refund_money); //要退的金额 第三方支付
		$refundLogic->setRrefundIntegral($refund_integral); //积分
		$refundLogic->updateRefundGoods($rec_id,1);//售后商品退款

        $messageFactory = new \app\common\logic\MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 2]);
        $messageLogic->sendRefundNotice($return_goods['order_id'], $refund_deposit + $refund_money);

		$this->ajaxReturn(['status'=>1,'msg'=>"操作成功",'url'=>U("Admin/Order/return_list")]);
    }

    /**
     * 管理员生成申请退货单
     */
    public function add_return_goods()
   {
            $order_id = I('order_id');
            $goods_id = I('goods_id');

            $return_goods = M('return_goods')->where("order_id = $order_id and goods_id = $goods_id")->find();
            if(!empty($return_goods))
            {
                $this->error('已经提交过退货申请!',U('Admin/Order/return_list'));
                exit;
            }
            $order = M('order')->where("order_id = $order_id")->find();

            $data['order_id'] = $order_id;
            $data['order_sn'] = $order['order_sn'];
            $data['goods_id'] = $goods_id;
            $data['addtime'] = time();
            $data['user_id'] = $order[user_id];
            $data['remark'] = '管理员申请退换货'; // 问题描述
            M('return_goods')->add($data);
            $this->success('申请成功,现在去处理退货',U('Admin/Order/return_list'));
            exit;
    }

    /**
     * 订单操作
     * @param $id
     */
    public function order_action(){    	
        $orderLogic = new OrderLogic();
        $action = I('get.type');
        $order_id = I('get.order_id');
        if($action && $order_id){
            if($action !=='pay'){
                $convert_action= C('CONVERT_ACTION')["$action"];
                $commonOrder = new \app\common\logic\Order();
                $commonOrder->setOrderById($order_id);
                $res =  $commonOrder->orderActionLog(I('note'),$convert_action,$this->admin_id);
            }
        	 $a = $orderLogic->orderProcessHandle($order_id,$action,array('note'=>I('note'),'admin_id'=>0));
        	 if($res !== false && $a !== false){
                 if ($action == 'remove') {
                     $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Order/index')]);
                 }
                 $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url' => U('Order/detail',array('order_id'=>$order_id))]);
        	 }else{
                 if ($action == 'remove') {
                     $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'url' => U('Order/index')]);
                 }
        	 	$this->ajaxReturn(['status' => 0,'msg' => '操作失败','url' => U('Order/index')]);
        	 }
        }else{
        	$this->ajaxReturn(['status' => 0,'msg' => '参数错误','url' => U('Order/index')]);
        }
    }
    
    public function order_log(){
    	$order_sn = I('order_sn');
    	$condition = array();
        $begin = $this->begin;
        $end = $this->end;
        $condition['log_time'] = array('between',"$begin,$end");
        if($order_sn){   //搜索订单号
            $order_id_arr = Db::name('order')->where(['order_sn' => $order_sn])->getField('order_id',true);
            $order_ids =implode(',',$order_id_arr);
            $condition['order_id']=['in',$order_ids];
            $this->assign('order_sn',$order_sn);
        }

    	$admin_id = I('admin_id');
		if($admin_id >0 ){
			$condition['action_user'] = $admin_id;
		}
    	$count = M('order_action')->where($condition)->count();
    	$Page = new Page($count,20);

    	foreach($condition as $key=>$val) {
    		$Page->parameter[$key] = urlencode($begin.'_'.$end);
    	}
    	$show = $Page->show();
    	$list = M('order_action')->where($condition)->order('action_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

    	//绑定订单类型是否虚拟，方便跳转对应订单详情
        $order_id_array = array_unique(array_column($list,'order_id'));
        $order_type_list = db('order')->where('order_id','in',$order_id_array)->field('order_id,prom_type')->select();
        $new_arr = [];
        foreach ($order_type_list as $k => $v){
            $new_arr[$order_type_list[$k]['order_id']] = $v['prom_type'];
        }

        $orderIds = [];
        foreach ($list as $k => $log) {
            $list[$k]['prom_type'] = $new_arr[$log['order_id']];
            if (!$log['action_user']) {
                $orderIds[] = $log['order_id'];
            }
        }
        if ($orderIds) {
            $users = M("users")->alias('u')->join('__ORDER__ o', 'o.user_id = u.user_id')->getField('o.order_id,u.nickname');
        }
        $this->assign('users',$users);
    	$this->assign('list',$list);
    	$this->assign('pager',$Page);
    	$this->assign('page',$show);   	
    	$admin = M('admin')->getField('admin_id,user_name');
    	$this->assign('admin',$admin);    	
    	return $this->fetch();
    }

    /**
     * 检测订单是否可以编辑
     * @param $order
     */
    private function editable($order){
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
        return;
    }

    /**
     * 导出订单
     */
    public function export_order()
    {
    	//搜索条件
        $order_status = I('order_status','');
        $order_ids = I('order_ids');
        $prom_type = I('prom_type'); //订单类型
        $keyType =   I("key_type");  //查找类型
        $keywords = I('keywords','','trim');
        $where= ['add_time'=>['between',"$this->begin,$this->end"]];
        if(!empty($keywords)){
            $keyType == 'mobile'   ? $where['mobile']  = $keywords : false;
            $keyType == 'order_sn' ? $where['order_sn'] = $keywords: false;
            $keyType == 'consignee' ? $where['consignee'] = $keywords: false;
        }
        $prom_type != '' ? $where['prom_type'] = $prom_type : $where['prom_type'] = ['lt',5];
        if($order_status>-1 && $order_status != ''){
            $where['order_status'] = $order_status;
        }
        if($order_ids){
            $where['order_id'] = ['in', $order_ids];
        }
        if(I('pay_code')){
            switch (I('pay_code')){
                case '余额支付':
                    $where['pay_name'] = I('pay_code');
                    break;
                case '积分兑换':
                    $where['pay_name'] = I('pay_code');
                    break;
                case 'alipay':
                    $where['pay_code'] = ['in',['alipay','alipayMobile']];
                    break;
                case 'weixin':
                    $where['pay_code'] = ['in',['weixin','weixinH5','miniAppPay']];
                    break;
                case '其他方式':
                    $where['pay_name'] = '';
                    $where['pay_code'] = '';
                    break;
                default:
                    $where['pay_code'] = I('pay_code');
                    break;
            }
        }
        $orderList = Db::name('order')->field("*,FROM_UNIXTIME(add_time,'%Y-%m-%d') as create_time")->where($where)->order('order_id')->select();
    	$strTable ='<table width="500" border="1">';
    	$strTable .= '<tr>';
    	$strTable .= '<td style="text-align:center;font-size:12px;width:120px;">订单编号</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="100">日期</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货人</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">收货地址</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">电话</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单金额</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">实际支付</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付方式</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">支付状态</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">发货状态</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品数量</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品信息</td>';
    	$strTable .= '</tr>';
	    if(is_array($orderList)){
	    	$region	= get_region_list();
	    	foreach($orderList as $k=>$val){
	    		$strTable .= '<tr>';
	    		$strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order_sn'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['create_time'].' </td>';	    		
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['consignee'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'."{$region[$val['province']]},{$region[$val['city']]},{$region[$val['district']]},{$region[$val['twon']]}{$val['address']}".' </td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['mobile'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['goods_price'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['order_amount'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['pay_name'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$this->pay_status[$val['pay_status']].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$this->shipping_status[$val['shipping_status']].'</td>';
	    		$orderGoods = D('order_goods')->where('order_id='.$val['order_id'])->select();
	    		$strGoods="";
                $goods_num = 0;
	    		foreach($orderGoods as $goods){
                    $goods_num = $goods_num + $goods['goods_num'];
	    			$strGoods .= "商品编号：".$goods['goods_sn']." 商品名称：".$goods['goods_name'];
	    			if ($goods['spec_key_name'] != '') $strGoods .= " 规格：".$goods['spec_key_name'];
	    			$strGoods .= "<br />";
	    		}
	    		unset($orderGoods);
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods_num.' </td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$strGoods.' </td>';
	    		$strTable .= '</tr>';
	    	}
	    }
    	$strTable .='</table>';
    	unset($orderList);
    	downloadExcel($strTable,'order');
    	exit();
    }
    
    /**
     * 退货单列表
     */
    public function return_list(){
        return $this->fetch();
    }

    /*
     * ajax 退货订单列表
     */
    public function ajax_return_list(){
        // 搜索条件
        $order_sn =  trim(I('order_sn'));
        $order_by = I('order_by','') ? I('order_by') : 'id';
        $sort_order = I('sort_order') ? I('sort_order') : 'desc';
        $status =  I('status');
        $where = [];
        if($order_sn){
            $where['order_sn'] =['like', '%'.$order_sn.'%'];
        }
        if($status != ''){
            $where['status'] = $status;
        }
        $ReturnGoods = new ReturnGoods();
        $count = $ReturnGoods->where($where)->count();
        $Page  = new AjaxPage($count,13);
        $show = $Page->show();
        $list = $ReturnGoods->where($where)->order("$order_by $sort_order")->limit("{$Page->firstRow},{$Page->listRows}")->select();
        $state = C('REFUND_STATUS');
        $return_type = C('RETURN_TYPE');
        $this->assign('state',$state);
        $this->assign('return_type',$return_type);
        $this->assign('list',$list);
        $this->assign('pager',$Page);
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }

    /**
     * 添加订单
     */
    public function add_order()
    {
        //  获取省份
        $province = M('region')->where(array('parent_id'=>0,'level'=>1))->select();
        $this->assign('province',$province);
        return $this->fetch();
    }

    /**
     * 提交添加订单
     */
    public function addOrder(){
            $user_id = I('user_id');// 用户id 可以为空
            $admin_note = I('admin_note'); // 管理员备注
            //收货信息
            $user  = Db::name('users')->where(['user_id'=>$user_id])->find();
            $address['consignee'] = I('consignee');// 收货人
            $address['province'] = I('province'); // 省份
            $address['city'] = I('city'); // 城市
            $address['district'] = I('district'); // 县
            $address['address'] = I('address'); // 收货地址
            $address['mobile'] = I('mobile'); // 手机
            $address['zipcode'] = I('zipcode'); // 邮编
            $address['email'] = $user['email']; // 邮编
            $invoice_title = I('invoice_title');// 发票抬头
            $taxpayer = I('taxpayer');// 纳税人识别号
            if(!empty($taxpayer)){
                $invoice_desc = "商品明细";// 发票内容
            }
            $goods_id_arr = I("goods_id/a");
            $orderLogic = new OrderLogic();
            $order_goods = $orderLogic->get_spec_goods($goods_id_arr);
            $pay = new Pay();
            try{
                $pay->setUserId($user_id);
                $pay->payGoodsList($order_goods);
                $pay->delivery($address['district']);
                $pay->orderPromotion();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $this->error($error['msg']);
            }
            $placeOrder = new PlaceOrder($pay);
            $placeOrder->setUserAddress($address);
            $placeOrder->setInvoiceTitle($invoice_title);
            $placeOrder->setTaxpayer($taxpayer);
            $placeOrder->setInvoiceDesc($invoice_desc);
            $placeOrder->addNormalOrder();
            $order = $placeOrder->getOrder();
            if($order) {
                M('order_action')->add([
                    'order_id'      => $order['order_id'],
                    'action_user'   => session('admin_id'),
                    'order_status'  => 0,  //待支付
                    'shipping_status' => 0, //待确认
                    'action_note'   => $admin_note,
                    'status_desc'   => '提交订单',
                    'log_time'      => time()
                ]);
                $this->success('添加订单成功',U("Admin/Order/detail",array('order_id'=>$order['order_id'])));
            } else{
                $this->error('添加失败');
            }
    }


    /**
     * 订单编辑
     * @return mixed
     */
    public function edit_order(){
        $order_id = I('order_id');
        $orderLogic = new OrderLogic();
        $orderModel = new OrderModel();
        $orderObj = $orderModel->where(['order_id'=>$order_id])->find();
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
        $orderGoods = $order['orderGoods'];
        if(IS_POST)
        {
            $order['consignee'] = I('consignee');// 收货人
            $order['province'] = I('province'); // 省份
            $order['city'] = I('city'); // 城市
            $order['district'] = I('district'); // 县
            $order['address'] = I('address'); // 收货地址
            $order['mobile'] = I('mobile'); // 手机
            $order['invoice_title'] = I('invoice_title');// 发票
            $order['taxpayer'] = I('taxpayer');// 纳税人识别号
            $order['admin_note'] = I('admin_note'); // 管理员备注
            $order['admin_note'] = I('admin_note'); //
            $order['shipping_code'] = I('shipping');// 物流方式
            $order['shipping_name'] = Db::name('shipping')->where('shipping_code',I('shipping'))->getField('shipping_name');
            $order['pay_code'] = I('payment');// 支付方式
            $order['pay_name'] = M('plugin')->where(array('status'=>1,'type'=>'payment','code'=>I('payment')))->getField('name');
            $goods_id_arr = I("goods_id/a");
            $new_goods = $old_goods_arr = array();
            //################################订单添加商品
            if($goods_id_arr){
                $new_goods = $orderLogic->get_spec_goods($goods_id_arr);
                foreach($new_goods as $key => $val)
                {
                    $val['order_id'] = $order_id;
                    $val['final_price'] = $val['goods_price'];
                    $rec_id = M('order_goods')->add($val);//订单添加商品
                    if(!$rec_id)
                        $this->error('添加失败');
                }
            }

            //################################订单修改删除商品
            $old_goods = I('old_goods/a');
            foreach ($orderGoods as $val){
                if(empty($old_goods[$val['rec_id']])){
                    M('order_goods')->where("rec_id=".$val['rec_id'])->delete();//删除商品
                }else{
                    //修改商品数量
                    if($old_goods[$val['rec_id']] != $val['goods_num']){
                        $val['goods_num'] = $old_goods[$val['rec_id']];
                        M('order_goods')->where("rec_id=".$val['rec_id'])->save(array('goods_num'=>$val['goods_num']));
                    }
                    $old_goods_arr[] = $val;
                }
            }

            $goodsArr = array_merge($old_goods_arr,$new_goods);
            $pay = new Pay();
            try{
                $pay->setUserId($order['user_id']);
                $pay->payOrder($goodsArr);
                $pay->delivery($order['district']);
                $pay->orderPromotion();
            } catch (TpshopException $t) {
                $error = $t->getErrorArr();
                $this->error($error['msg']);
            }
            //################################修改订单费用
            $order['goods_price']    = $pay->getGoodsPrice(); // 商品总价
            $order['shipping_price'] = $pay->getShippingPrice();//物流费
            $order['order_amount']   = $pay->getOrderAmount(); // 应付金额
            $order['total_amount']   = $pay->getTotalAmount(); // 订单总价
            $o = M('order')->where('order_id='.$order_id)->save($order);
            $commonOrder = new \app\common\logic\Order();
            $commonOrder->setOrderById($order_id);
            $l = $commonOrder->orderActionLog('修改订单','修改订单',$this->admin_id);//操作日志
            if($o && $l){
                $this->success('修改成功',U('Admin/Order/editprice',array('order_id'=>$order_id)));
            }else{
                $this->success('修改失败',U('Admin/Order/detail',array('order_id'=>$order_id)));
            }
            exit;
        }
        // 获取省份
        $province = M('region')->where(array('parent_id'=>0,'level'=>1))->select();
        //获取订单城市
        $city =  M('region')->where(array('parent_id'=>$order['province'],'level'=>2))->select();
        //获取订单地区
        $area =  M('region')->where(array('parent_id'=>$order['city'],'level'=>3))->select();
        //获取支付方式
        $payment_where = ['status'=>1,'type'=>'payment'];
        if($order['shop_id'] > 0){
            //预售订单和抢购不支持货到付款
            $payment_where['code'] = array('neq','cod');
        }
        $payment_list = M('plugin')->where($payment_where)->select();
        //获取配送方式
        $shipping_list = Db::name('shipping')->field('shipping_name,shipping_code')->where('')->select();

        $this->assign('order',$order);
        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('area',$area);
        $this->assign('orderGoods',$orderGoods);
        $this->assign('shipping_list',$shipping_list);
        $this->assign('payment_list',$payment_list);
        return $this->fetch();
    }

    /**
     * 选择搜索商品
     */
    public function search_goods()
    {
    	$brandList =  M("brand")->select();
    	$categoryList =  M("goods_category")->select();
    	$this->assign('categoryList',$categoryList);
    	$this->assign('brandList',$brandList);
    	$where = 'exchange_integral = 0 and is_on_sale = 1 and is_virtual =' . I('is_virtual/d',0);//搜索条件
    	I('intro')  && $where = "$where and ".I('intro')." = 1";
    	if(I('cat_id')){
    		$this->assign('cat_id',I('cat_id'));    		
            $grandson_ids = getCatGrandson(I('cat_id')); 
            $where = " $where  and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
                
    	}
        if(I('brand_id')){
            $this->assign('brand_id',I('brand_id'));
            $where = "$where and brand_id = ".I('brand_id');
        }
    	if(!empty($_REQUEST['keywords']))
    	{
    		$this->assign('keywords',I('keywords'));
    		$where = "$where and (goods_name like '%".I('keywords')."%' or keywords like '%".I('keywords')."%')" ;
    	}
        $goods_count =M('goods')->where($where)->count();
        $Page = new Page($goods_count,C('PAGESIZE'));
    	$goodsList = M('goods')->where($where)->order('goods_id DESC')->limit($Page->firstRow,$Page->listRows)->select();
                
        foreach($goodsList as $key => $val)
        {
            $spec_goods = M('spec_goods_price')->where("goods_id = {$val['goods_id']}")->select();
            $goodsList[$key]['spec_goods'] = $spec_goods;            
        }
        if($goodsList){
            //计算商品数量
            foreach ($goodsList as $value){
                if($value['spec_goods']){
                    $count += count($value['spec_goods']);
                }else{
                    $count++;
                }
            }
            $this->assign('totalSize',$count);
        }

    	$this->assign('page',$Page->show());
    	$this->assign('goodsList',$goodsList);
    	return $this->fetch();
    }
    
    public function ajaxOrderNotice(){
        $order_amount = M('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        echo $order_amount;
    }

    /**
     * 删除订单日志
     */
    public function delOrderLogo(){
        $ids = I('ids');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'url'  =>'']);
        $order_ids = rtrim($ids,",");
        $res = Db::name('order_action')->whereIn('order_id',$order_ids)->delete();
        if($res !== false){
            $this->ajaxReturn(['status' => 1,'msg' =>"删除成功！",'url'  =>'']);
        }else{
            $this->ajaxReturn(['status' => -1,'msg' =>"删除失败",'url'  =>'']);
        }
    }

    /**
     * 导出发货单中包含的发货商品
     */
    public function exportDeliveryGoods()
    {
        $order_ids = I('ids4');
        if(empty($order_ids)){
            $this->error('没有选中订单', U('admin/order/delivery_list'));
        }
        $where['order_id'] = ['in', $order_ids];
        $orderList = Db::name('order')->field('order_sn,order_id,total_amount')->where($where)->order('order_id')->select();
        if(!$orderList){
            $this->error('没找到相关订单信息', U('admin/order/delivery_list'));
        }
        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;width:125px;">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">订单总价</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">商品信息</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">对应商品规格</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">对应商品数量</td>';
        $strTable .= '</tr>';
            foreach($orderList as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['order_sn'].'</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['total_amount'].'</td>';
                $orderGoods = D('order_goods')->where('order_id='.$val['order_id'])->select();
                $strGoods="";
                $goods_num = '';
                $spec_key_name = '';
                foreach($orderGoods as $goods){
                    $goods_num .= '&nbsp;'.$goods['goods_num'];
                    $goods_num  .= "<br />";
                    $strGoods .= "&nbsp;商品编号：".$goods['goods_sn']." 商品名称：".$goods['goods_name'];
                    $strGoods .= "<br />";
                    $spec_key_name .= "&nbsp;".($goods['spec_key_name'] ?: '无' );
                    $spec_key_name .= "<br />";
                }
                unset($orderGoods);
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$strGoods.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$spec_key_name.' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$goods_num.' </td>';
                $strTable .= '</tr>';
            }

        $strTable .='</table>';
        unset($orderList);
        downloadExcel($strTable,'goods_list');
        exit();
    }

}
