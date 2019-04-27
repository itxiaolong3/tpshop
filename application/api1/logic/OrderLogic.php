<?php
/**
 --------------------------------------------------
 空间类型   订单模型
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace Api\service;
use http\Env\Request;
use Think\Model;
class OrderModel
{
    /*订单列表*/
    public function orderList($post)
    {
        $uid=$post['id'];
        $order=M('order');  //订单表
        $page=!empty($post['page'])?$post['page']:1;    //分页
        $type=!empty($post['type'])?$post['type']:1;    //类别    1所有 2待付款 3待收货 4已收货5.已完成

        $where['o.user_id'] = $uid; //  筛选条件
        switch ($type){
            case 1:
                $where['o.state']=array('neq',9) ;
                break;

            case 2:
                $where['o.state']= 1;   //待付款   1未支付 2已支付 3已发货  4已收货  5退货中  6已退货  7已完成  8已退款  9已关闭  10申请退款
                break;

            case 3:
                $where['o.state']=array('IN',[2,3]) ;   // 2:已付款3:已发货
                break;

            case 4:
                $where['o.state']= 4;   //已完成 4:已收货
                break;

            case 5:
                $where['o.state']= 7;   //已完成 7:已完成
                break;
        }

        $order_arr=$order
            ->where($where)
            ->field('o.state,o.allmoney,p.url,d.goods_id,d.spec,d.goods_name,d.num,d.price')
            ->order('add_time desc')
            ->limit(($page-1)*10,10)
            ->select();

        foreach ($order_arr as $k => $v){
            $id_arr[]=$v['order_id'];
        }

        $arr=array_unique($id_arr);

        foreach ($arr as $key => $value){
            $aim_arr[]=$value;
        }

        foreach ($aim_arr as $k1 => $v1){
            foreach ($order_arr as $k2 =>$v2){
                if($v2['order_id'] == $v1){
                    $result[$k1]['data'][]=$v2;
                    $result[$k1]['order_id']=$v2['order_id'];
                    $result[$k1]['state']=$v2['state'];
                    $result[$k1]['allmoney']=$v2['allmoney'];
                }
            }
        }

        if($order_arr){
            $this->ajaxReturn(['code' => '200','data' => $result ,'msg' => '成功']);exit();
        }elseif ($order_arr == null) {
            $this->ajaxReturn(['code' => '320', 'msg' => '数据为空']);exit();
        }else {
            $this->ajaxReturn(['code' => '401', 'msg' => '获取商品数据失败']);exit();
        }
    }

    /*我的订单列表*/
    public function shopOrderList($post)
    {
        $uid=$post['id'];
        $order=M('order');  //订单表
        $page=!empty($post['page'])?$post['page']:1;    //分页
        $type=!empty($post['type'])?$post['type']:1;    //类别    1所有 2待付款 3待收货 4已收货5.已完成
        
        $where['user_id'] = $uid; //  筛选条件
        switch ($type){
            case 1: 
            $where['state']=array('neq',9) ;
            break;
            
            case 2:
            $where['state']= 1;   //待付款   1未支付 2已支付 3已发货  4已收货  5退货中  6已退货  7已完成  8已退款  9已关闭  10申请退款
            break;
            
            case 3:
            $where['state']= 2;   // 2:已付款待发货
            break;
            
            case 4:
            $where['state']= 3;   //已发货
            break;

            case 5:
            $where['state']= array('IN',[4,7]);   //已收货 已完成 7:已完成
            break;
        }
        $orderList=$order->where($where)
            ->field('id,state,allmoney,add_time,order_id,state,number')
            ->order('add_time desc')
            ->limit(($page-1)*10,10)
            ->select();
        if(isset($orderList)){
            foreach ($orderList as $k => $v){
                $orderList[$k]['statusName'] = getOrderStatus($v['state']);
                $orderList[$k]['goodsList'] = M('order_goods')->where(['order_id'=> $v['id']])->field('goods_id,spec_key_name,goods_name,goods_num,goods_price,goods_pic')->select();
                foreach ($orderList[$k]['goodsList'] as $kk => $vv){
                    $orderList[$k]['goodsList'][$kk]['goods_pic'] = 'http://' . $_SERVER['HTTP_HOST'] .__ROOT__.$vv['goods_pic'];
                }
            }
        }else{
            $orderList = [];
        }

        return $orderList;
    }

    /**
     * 计算订单金额
     * @param type $user_id 用户id
     * @param type $order_goods 购买的商品
     * @param type $coupon_id 优惠券  数组
     */
    public function calculate_price($user = [], $order_goods = '', $coupon_id = 0)
    {
        $user_id = $user['id'];
        if (empty($order_goods)){
            return array('status' => 325, 'msg' => '商品列表不能为空', 'result' => '');
        }
        $goods_id_arr = get_arr_column($order_goods, 'goods_id');
        $goods_arr = M('goods')->where(['id' => ['in', $goods_id_arr]])->cache(true, C('CACHE_TIME'))
            ->getField('id,number,sp,price,preferential,goods,pic'); // 商品id 和重量对应的键值对
        $anum = 0;
        $goods_price = 0;
        $rate = 100;//初始化会员折扣
        if ($user['type'] == 2 || $user['type'] == 3) {
            $rate = M('rate')->where(['level' => $user['type']])->getField('rate');
        }
        foreach ($order_goods as $key => $val) {
            $spec_money = $goods_arr[$val['goods_id']]['price'];
            if($val['specifications_id']){
                $specifications = M('goods_specifications')->where(['id' => $val['specifications_id']])->field('specifications,money')->find();
                $order_goods[$key]['spec_key_name'] = $specifications['specifications'];
                $spec_money = $specifications['money'];
            }
            $order_goods[$key]['goods_pic'] = $goods_arr[$val['goods_id']]['pic'];
            $order_goods[$key]['goods'] = $goods_arr[$val['goods_id']]['goods'];
            $order_goods[$key]['member_discount'] = $rate;
            $order_goods[$key]['goods_discount'] = $goods_arr[$val['goods_id']]['preferential'];
            $order_goods[$key]['goods_price'] = $spec_money;//单价
            $order_goods[$key]['goods_fee'] = $val['number'] * $spec_money * $goods_arr[$val['goods_id']]['preferential']  * ($rate / 100);    // 小计
            $order_goods[$key]['store_count'] = getGoodNum($val['goods_id'], $val['specifications_id']); // 最多可购买的库存数量
            $order_goods[$key]['give_integral'] = $goods_arr[$val['goods_id']]['jifen'];
            if ($order_goods[$key]['store_count'] <= 0 || $order_goods[$key]['store_count'] < $order_goods[$key]['number'])
                return array('status' => -10, 'msg' => "库存不足,请重新下单", 'result' => '');
            $anum += $val['number']; // 购买数量
            $goods_price += $order_goods[$key]['goods_fee']; // 商品总价
        }

        // 因为当前方法在没有user_id 的情况下也可以调用, 因此 需要判断用户id
        if ($user_id) {
            if(!empty($coupon_id)){
                $coupon_price = getCouponMoney($user_id, $coupon_id);
            }
        }
        // 最终应付金额 = 商品价格  - 优惠券
        $order_amount = round($goods_price - $coupon_price, 2);
        // 订单总价 = 商品总价
        $total_amount = $goods_price;

        //订单总价  应付金额  商品总价 共多少件商品  优惠券
        $result = array(
            'total_amount' => $total_amount, // 订单总价
            'order_amount' => $order_amount, // 应付金额      只用于订单在没有参与优惠活动的时候价格是对的, 如果某个商家参与优惠活动 价格会有所变动
            'goods_price'  => $goods_price, // 商品总价
            'anum'         => $anum, // 商品总共数量
            'coupon_price' => $coupon_price,// 优惠券抵消金额
            'order_goods'  => $order_goods, // 商品列表 多加几个字段原样返回
            'rate'         => $rate,  //会员折扣
        );
        return array('status' => 1, 'msg' => "计算价钱成功", 'result' => $result); // 返回结果状态
    }

    /**
     * 添加一个订单
     * @param $user_id|用户id
     * @param $address_id|地址id
     * @param array $coupon_id|优惠券id
     * @return array
     */
    public function addOrder($user,$address_id,$coupon_id = 0,$car_price=[], $cart_id = 0)
    {
        $address = M('address')->where(['id' => $address_id, 'user_id' => $user['id']])->find();
        if (!$address) {
            return array('status' => 318, 'msg' => '地址不存在', 'result' => '');
        }
        // 添加订单
        $order_id = date('Ymdhis') . time() . mt_rand(1000, 9999);// 获取生成订单号
        $data = array(
            'order_id'         =>$order_id, // 订单编号
            'user_id'          =>$user['id'], // 用户id
            'username'         =>$user['username'],
            'consignee'        =>$address['consignee'], // 收货人
            'city'             =>$address['addrcity'],//'城市',
            'address'          =>$address['address'],//'详细地址',
            'tel'              =>$address['tel'],//'手机',
            'coupon_id'        =>$coupon_id,
            'coupon_price'        =>$car_price['couponFee'],
            'allmoney'     =>$car_price['total_amount'],// 订单总额 = 商品总价
            'order_amount'     =>$car_price['payables'],//'应付款金额',
            'give_integral'     => $car_price['give_integral'],//赠送积分
            'state'            =>1,
            'number'            => $car_price['anum'],
            'add_time'         =>time(), // 下单时间
        );

        M()->startTrans();
        $s = true;
        $order_id = M("Order")->add($data);
        if(!$order_id) $s = false;
        // 1插入order_goods 表
        $cartList = $car_price['order_goods'];
        foreach($cartList as $key => $val)
        {
            $data2['order_id']           = $order_id; // 订单id
            $data2['goods_id']           = $val['goods_id']; // 商品id
            $data2['goods_name']         = $val['goods']; // 商品名称
            $data2['goods_num']          = $val['number']; // 购买数量
            $data2['goods_price']        = $val['goods_price']; // 商品价
            $data2['spec_key']           = $val['specifications_id']; // 商品规格
            $data2['spec_key_name']      = $val['spec_key_name']; // 商品规格
            $data2['goods_pic']      = $val['goods_pic']; // 商品规格
            $data2['member_discount'] = $val['member_discount']; // 会员折扣
            $data2['goods_discount'] = $val['goods_discount']; // 商品折扣
            $order_goods_id              = M("OrderGoods")->add($data2);

            if(!$order_goods_id)  $s = false;
            // 扣除商品库存  扣除库存移到 付完款后扣除
            //M('Goods')->where("goods_id = ".$val['goods_id'])->setDec('store_count',$val['goods_num']); // 商品减少库存
        }

        if(!empty($coupon_id)){
            // 2修改优惠券状态
            $data3['user_id'] = $user['id'];
            $data3['order_id'] = $order_id;
            $data3['use_time'] = time();
            $data3['status'] = 1;
            M('coupon_list')->where(['id'=>$coupon_id])->save($data3);
            $cid = M('coupon_list')->where("id",$coupon_id)->getField('cid');
            M('coupon')->where("id",$cid)->setInc('use_num'); // 优惠券的使用数量加一
        }
        // 4 清空购物车
        if($cart_id!=0){
            $cart = M('cart')->where(['user_id'=>$user['id'], 'id'=> ['in', $cart_id]])->delete();
            if(!$cart) $s = false;
        }
        if (!$s) {
            M()->rollback();
            return array('status' => 321, 'msg' => '订单提交失败', 'result' => '');
        }
        M()->commit();
        // 如果有微信公众号 则推送一条消息到微信
//            $jssdk = new JssdkLogic(C['APPID'],C['SECRET']);
//            $wx_content = "你刚刚下了一笔订单:{$order_id} 尽快支付,过期失效!";
//            $jssdk->push_msg($user['openid'],$wx_content);

        return array('status'=>200,'msg'=>'提交订单成功','result'=>$order_id); // 返回新增的订单id
    }

    /**
     * 申请售后
     * @param $rec_id
     * @param $order
     */
    public function addReturnGoods($order,$data)
    {
        $data['addtime'] = time();
        $data['user_id'] = $order['user_id'];
        $data['order_id'] = $order['id'];
        $data['order_sn'] = $order['order_id'];
        $data['refund_money'] = $order['order_amount'];
        $return_id = M('return_goods')->where(['order_id' => $order['id']])->getField('id');
        if(!empty($return_id)){
            $result = M('return_goods')->where(array('id'=>$return_id))->save($data);
        }else{
            $result = M('return_goods')->add($data);
            M('order')->where(array('id' => $order['id'],'user_id' => $order['user_id']))->save(array('state' => 10));
        }
        if($result){
            return ['status'=>200,'msg'=>'申请成功'];
        }
        return ['status'=>302,'msg'=>'申请失败'];
    }

    /**
     * 添加商品评论
     * @param $add
     * @return array
     */
    public function addGoodsComment($add)
    {
        if(empty($add['xin_num'])){
            return array('status'=>306, 'msg'=>'请给商品评分!');
        }
        if (!$add['order_id'] || !$add['goods_id']) {
            return array('status'=>302, 'msg'=>'非法操作');
        }
        //检查订单是否已完成
        $order = M('order')->where(['id' => $add['order_id'], 'user_id' => $add['uid']])->find();
//        if ($order['state'] != 7) {
//            return ['status'=>303, 'msg'=>'该笔订单还未完成'];
//        }
        //检查是否已评论过
        $goods = M('comment')->where(['rec_id' => $add['rec_id']])->find();
//        if ($goods) {
//            return ['status'=>304, 'msg'=>'您已经评论过该商品'];
//        }
        $add['time']    = time();
        $row = M('comment')->add($add);
        if (!$row) {
            return ['status'=>305 ,'msg'=>'评论失败'];
        }

        //更新订单商品表状态
        M('order_goods')->where(['rec_id'=>$add['rec_id'],'order_id'=>$add['order_id']])->save(['is_comment'=>1]);
        M('goods')->where(['id'=>$add['goods_id']])->setInc('goodbrief',1); // 评论数加一
        return ['status'=>200,'msg'=>'评论成功'];
    }

    /**
     * 上传退换货图片，兼容小程序
     * @return array
     */
    public function uploadReturnGoodsImg($name)
    {
        $url = '';
        if($_FILES[$name]['size']>0){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $dir = './Public/upload/'.$name.'/'; // 设置附件上传根目录
            if (!($_exists = file_exists($dir))) {
                mkdir($dir);
            }
            $upload->rootPath  =     $dir;
            /*$upload->savePath  =     '/video'; // 设置附件上传（子）目录*/
            $upload->autoSub = false; //拒绝创建子目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info){// 上传错误提示错误信息
                return $upload->getError();//上传错误提示错误信息
            }
            $url = '/Public/upload/'.$name.'/'.$info[$name]['savename'];
        }
        return $url;
    }

    /**
     * 分成记录
     */
    public function rebateLog($order){
        //查询用户上级信息
        $user=M('user')->field('id,username,fid,fname,fid2,fname2')->where(array('id' => $order['user_id']))->find();
        //查询该笔订单是否已分成
        $e = M('earnings')->field('id')->where(array('user_id' => $order['user_id'], 'order_id'=> $order['id']))->find();
        if(!$e){ //查询该该会员是否有上级
            //查询订单许分成商品
            $order_goods=M('order_goods')->field('rec_id,goods_id,goods_price,goods_discount,member_discount,goods_num')->where(array('order_id' => $order['id']))->select();
            $goods_id_arr = get_arr_column($order_goods, 'goods_id');
            $goods_arr = M('goods')->where(['id' => ['in', $goods_id_arr]])//->cache(true, C('CACHE_TIME'))
                ->getField('id,first_per,second_per'); // 商品id 和重量对应的键值对
                M('earnings')->startTrans();
                $s=true;
            foreach ($order_goods  as $k => $v){
                //判断商品是否有分成金额
                if($goods_arr[$v['goods_id']]['first_per']>0 && $user['fid']){
                    //商品价*商品折扣 * 数量 = 小计
                    $allmoney = $v['goods_price'] * $v['goods_discount'] * $v['goods_num'];
                    $first_per = $allmoney * $goods_arr[$v['goods_id']]['first_per'];//一级会员提成
                    $second_per = $allmoney * $goods_arr[$v['goods_id']]['second_per'];//二级会员提成
                    $earning = array(
                        '$u_a' => $order['id'],
                        'user_id' => $user['id'],
                        'order_id' => $order['order_id'],
                        'rec_id' => $v['rec_id'],
                        'goods_id' => $v['goods_id'],
                        'username' => $user['username'],
                        'fid1' => $user['fid'],
                        'fusername1' => $user['fname'],
                        'fid2' => $user['fid2'],
                        'fusername2' => $user['fname2'],
                        'money1' => $first_per,
                        'money2' => $second_per,
                        'price' => $allmoney,
                        'add_time' => time()
                    );
                    $ea = M('earnings')->add($earning);//分成收益记录
                    if (!$ea) $s = false;
                    //加入用户冻结资金
                    $u_a = M('user')->where(['id'=>$user['fid']])->save(['frozen_balance'=>['exp',"frozen_balance+$first_per"]]);
                    if (!$u_a) $s = false;
                    if($user['fid2']){//加入用户冻结资金
                        $u_b = M('user')->where(['id'=>$user['fid2']])->save(['frozen_balance'=>['exp',"frozen_balance+$second_per"]]);
                        if (!$u_b) $s = false;
                    }
                }
            }
            if ($s) {
                M('earnings')->commit();
            } else {
                M('earnings')->rollback();
            }
        }
    }

    /**
     * 确认收获后佣金发放
     */
    public function grantLog($order){
        $order=M('order')->field('id,state')->where(array('id' => $order['id']))->find();
        if($order['state']!=4){
            return ['status'=>305 ,'msg'=>'订单未达到分佣条件'];
        }
        $order_goods = M('order_goods')->where(['order_id'=>$order['id']])->select();
        if($order_goods){
            $jifen = 0;//累计该笔订单赠送积分
            foreach ($order_goods as $k => $v){
                if($v['is_send']==0){
                    $jifen += $v['give_integral'];
                    $earnings=M('earnings')->field('id,user_id,username,fid1,fid2,money1,money2,give_time1,status')->where(array('rec_id' => $v['rec_id']))->find();
                    if(!$earnings['give_time1']){//发放时间为空则发放佣金
                        M('user')->startTrans();
                        $s=true;
                        $money1 = $earnings['money1'];
                        $money2 = $earnings['money2'];
                        $u_a = M('user')->where(['id'=>$earnings['fid1']])->save(array('frozen_balance'=>array('exp',"frozen_balance-$money1"),'earnings'=>array('exp',"earnings+$money1"),'balance'=>array('exp',"balance+$money1")));
                        if (!$u_a) $s = false;
                        if($earnings['fid2']){//将冻结资金加入到余额中及累加佣金
                            $u_b = M('user')->where(['id'=>$earnings['fid2']])->save(array('frozen_balance'=>array('exp',"frozen_balance-$money2"),'earnings'=>array('exp',"earnings+$money2"),'balance'=>array('exp',"balance+$money2")));
                            if (!$u_b) $s = false;
                        }
                        $re4 = M("earnings")->where("rec_id='".$v['rec_id']."' and give_time1 is null")->save(array('give_time1'=>time()));//将数据变为已发放
                        M('user')->where(['id'=>$earnings['user_id']])->save(['jifen'=>['exp',"jifen+$jifen"]]);
                        if ($s && $re4) {
                            M('user')->commit();
                        } else {
                            M('user')->rollback();
                        }
                    }
                }
            }
        }
    }

}