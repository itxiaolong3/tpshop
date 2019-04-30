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

use app\common\model\Coupon;
use think\Model;
use think\Db;

/**
 * 活动逻辑类
 */
class ActivityLogic extends Model
{
    
    /**
     * 团购总数
     * @param type $sort_type
     * @param type $page_index
     * @param type $page_size
     */
    public function getGroupBuyCount()
    {
        $group_by_where = array(
            'start_time'=>array('lt',time()),
            'end_time'=>array('gt',time()),
        );
        $count = M('group_buy')->alias('b')
                ->field('b.goods_id,b.rebate,b.virtual_num,b.buy_num,b.title,b.goods_price,b.end_time,b.price,b.order_num,g.comment_count')
                ->join('__GOODS__ g', 'b.goods_id=g.goods_id AND g.prom_type=2 AND g.is_on_sale=1')
                ->where($group_by_where)
                ->count();
        return $count;
    }
    
    /**
     * 团购列表
     * @param type $sort_type
     * @param type $page_index
     * @param type $page_size
     */
    public function getGroupBuyList($sort_type = '', $page_index = 1, $page_size = 20)
    {
        if ($sort_type == 'new') {
            $type = 'start_time';
        } elseif ($sort_type == 'comment') {
            $type = 'g.comment_count';
        } else {
            $type = '';
        }
        
        $group_by_where = array(
            'start_time'=>array('lt',time()),
            'end_time'=>array('gt',time()),
            'is_end' => 0
        );
        $list = M('group_buy')->alias('b')
                ->field('b.goods_id,b.item_id,b.rebate,b.virtual_num,b.buy_num,b.title,b.goods_price,b.end_time,b.price,b.order_num,g.comment_count')
                ->join('__GOODS__ g', 'b.goods_id=g.goods_id AND g.prom_type=2 AND g.is_on_sale=1')
                ->where($group_by_where)->page($page_index, $page_size)
                ->order($type, 'desc')
                ->select(); // 找出这个商品
        
        $groups = array();
        $server_time = time();
        foreach ($list as $v) {
            $v["server_time"] = $server_time;
            $groups[] = $v;
        }

        return $groups;
    }

    /**
     * 优惠促销列表
     * @param type $sort_type
     * @param type $page_index
     * @param type $page_size
     */
    public function getSalesList($sort_type = 0, $page_index = 1, $page_size = 4)
    {
        $map = array(
            'start_time'=>array('lt',time()),
            'end_time'=>array('gt',time()),
            'is_end' => 0,
            'type'=>$sort_type
        );



        $list = D('prom_goods')->with('prom_goods_item')->where($map)->field('id,type,expression,end_time')->page($page_index , $page_size)->order('id desc')->select();
       //dump(db::Getlastsql());die;
        $list && collection($list)->append(['prom_detail'])->toArray();
        return $list;
    }

    /**
     * 优惠券列表
     * @param type $atype 排序类型 1:默认id排序，2:即将过期，3:面值最大
     * @param $user_id  用户ID
     * @param int $p 第几页
     * @return array
     */
    public function getCouponList($atype, $user_id, $p = 1)
    {
        $time = time();
        $where = array('type' => 2,'status'=>1,'send_start_time'=>['elt',time()],'send_end_time'=>['egt',time()]);
        $order = array('id' => 'desc');
        if ($atype == 2) {
            //即将过期
            $order = ['spacing_time' => 'asc'];
            $where["send_end_time-'$time'"] = ['egt', 0];
        } elseif ($atype == 3) {
            //面值最大
            $order = ['money' => 'desc'];
        }
        $coupon_list = M('coupon')->field("*,send_end_time-'$time' as spacing_time")
            ->where($where)->page($p, 15)->order($order)->select();
        if (is_array($coupon_list) && count($coupon_list) > 0) {
            if ($user_id) {
                $user_coupon = M('coupon_list')->where(['uid' => $user_id, 'type' => 2])->getField('cid',true);
            }
            if (!empty($user_coupon)) {
                foreach ($coupon_list as $k => $val) {
                    $coupon_list[$k]['isget'] = 0;
                    if (in_array($val['id'],$user_coupon)) {
                        $coupon_list[$k]['isget'] = 1;
                        unset($coupon_list[$k]);
                        continue;
                    }
                    $coupon_list[$k]['use_scope'] = C('COUPON_USER_TYPE')[$coupon_list[$k]['use_type']];
                }
            }
        }
        return $coupon_list;
    }
    
    /**
     * 获取优惠券查询对象
     * @param int $queryType 0:count 1:select
     * @param type $user_id
     * @param int $type 查询类型 0:未使用，1:已使用，2:已过期
     * @param type $orderBy 排序类型，use_end_time、send_time,默认send_time
     * @param int $order_money
     * @return Query
     */
    public function getCouponQuery($queryType, $user_id, $type = 0, $orderBy = null , $order_money = 0)
    {
        $where['l.uid'] = $user_id;
        $where['c.status'] = 1;
        //查询条件
        if (empty($type)) {
            // 未使用
//            $where['l.order_id'] = 0;
            $where['c.use_end_time'] = array('gt', time());
            $where['l.use_status'] = 0;
        } elseif ($type == 1) {
            //已使用
           // $where['l.order_id'] = array('gt', 0);
            $where['l.use_time'] = array('gt', 0);
            $where['l.use_status'] = 1;
        } elseif ($type == 2) {
            //已过期
            $where['c.end_time'] = array('lt', time());
            $where['l.use_status|c.status'] = array('neq', 1);
        }
//        if ($orderBy == 'use_end_time') {
//            //即将过期，$type = 0 AND $orderBy = 'use_end_time'
//            $order['c.use_end_time'] = 'asc';
//        } elseif ($orderBy == 'send_time') {
//            //最近到账，$type = 0 AND $orderBy = 'send_time'
//            $where['l.send_time'] = array('lt',time());
//            $order['l.send_time'] = 'desc';
//        } elseif (empty($orderBy)) {
//            $order = array('l.send_time' => 'DESC', 'l.use_time');
//        }

        $condition = floatval($order_money) ? ' AND c.condition_money <= '.$order_money : '';
//        $query = M('coupon_list')->alias('l')
//            ->join('__COUPON__ c','l.cid = c.id'.$condition)
//            ->where($where);
        $query = M('prom_coupon')->alias('l')
            ->join('__PROM_ORDER__ c','l.poid = c.id'.$condition,'left')
            ->where($where);
        if ($queryType != 0) {
            $query = $query->field('l.*,c.name,c.condition_money,c.expression,c.end_time,c.description');
//                    ->order($order);
        }
        return $query;
    }

    /**
     * 获取优惠券数目
     * @param $user_id
     * @param int $type
     * @param null $orderBy
     * @param int $order_money
     * @return mixed
     */
    public function getUserCouponNum($user_id, $type = 0, $orderBy = null,$order_money = 0)
    {
        $query = $this->getCouponQuery(0, $user_id, $type, $orderBy,$order_money);
        return $query->count();
    }

    /**
     * 获取用户优惠券列表
     * @param $firstRow
     * @param $listRows
     * @param $user_id
     * @param int $type
     * @param null $orderBy
     * @param int $order_money
     * @return mixed
     */
    public function getUserCouponList($firstRow, $listRows, $user_id, $type = 0, $orderBy = null,$order_money = 0)
    {
        $query = $this->getCouponQuery(1, $user_id, $type, $orderBy,$order_money);
       return  $query->limit($firstRow, $listRows)->select();
    }
    
    /**
     * 领券中心
     * @param type $cat_id 领券类型id
     * @param type $user_id 用户id
     * @param type $p 第几页
     * @param type $goods_id 指定商品id
     * @return type
     */
    public function getCouponCenterList($cat_id, $user_id, $p = 1,$goods_id=0)
    {
        /* 获取优惠券列表 */
        $cur_time = time();
        $coupon_where = ['type'=>2, 'status'=>1, 'send_start_time'=>['elt',time()], 'send_end_time'=>['egt',time()]];
        $query = db('coupon')->alias('c')
            ->field('gc.goods_id,gc.goods_category_id,c.use_type,c.name,c.id,c.money,c.condition,c.createnum,c.use_start_time,c.use_end_time,c.send_num,c.send_end_time-'.$cur_time.' as spacing_time')
            ->where('((createnum-send_num>0 AND createnum>0) OR (createnum=0))')    //领完的也不要显示了
            ->where($coupon_where)->page($p, 15)
            ->order('condition', 'desc');
//        if ($cat_id > 0) {
//            $query = $query->join('__GOODS_COUPON__ gc', 'gc.coupon_id=c.id AND gc.goods_category_id='.$cat_id);
//        }
        $query = $query->join('__GOODS_COUPON__ gc', 'gc.coupon_id=c.id ','left');
        $coupon_list = $query->select();
        if (!(is_array($coupon_list) && count($coupon_list) > 0)) {
            return [];
        }
        
        $user_coupon = [];
        if ($user_id) {
            $user_coupon = M('coupon_list')->where(['uid' => $user_id, 'type' => 2])->column('cid');
        }

        $types = [];
        if ($cat_id) {
            /* 优惠券类型格式转换 */
            $couponType = $this->getCouponTypes();
            foreach ($couponType as $v) {
                $types[$v['id']] = $v['mobile_name'];
            }
        }

        $store_logo = tpCache('shop_info.store_logo') ?: '';
        $Coupon = new Coupon();
        foreach ($coupon_list as $k => $coupon) {
            /* 是否已获取 */
            $coupon_list[$k]['use_type_title'] = $Coupon->getUseTypeTitleAttr(null, $coupon_list[$k]);
            $coupon_list[$k]['isget'] = 0;
            if (in_array($coupon['id'], $user_coupon)) {
                $coupon_list[$k]['isget'] = 1;
            }

            /* 构造封面和标题 */
            $coupon_list[$k]['image'] = $store_logo;
            $coupon_list[$k]['use_end_time'] = date('Y-m-d',$coupon['use_end_time']);
            $coupon_list[$k]['use_start_time'] = date('Y-m-d',$coupon['use_start_time']);
            switch ($coupon['use_type']){
                case 1;
                    if($goods_id > 0 && $goods_id != $coupon['goods_id']){
                        unset($coupon_list[$k]);
                    }
                    break;
                case 2;
                    if($cat_id > 0 && $cat_id != $coupon['goods_category_id']){
                        unset($coupon_list[$k]);
                    }
                    break;

            }
        }
        
        return  $coupon_list;
    }
    
    /**
     * 优惠券类型列表
     * @param type $p 第几页
     * @param type $num 每页多少，null表示全部
     * @return type
     */
    public function getCouponTypes($p = 1, $num = null)
    {
        $list = M('coupon')->alias('c')
                ->join('__GOODS_COUPON__ gc', 'gc.coupon_id=c.id AND gc.goods_category_id!=0')
                ->where(['type' => 2, 'status' => 1])
                ->column('gc.goods_category_id');
        
        $result = M('goods_category')->field('id, mobile_name')->where("id", "IN", $list)->page($p, $num)->select();
        $result = $result ?: [];
        array_unshift($result, ['id'=>0, 'mobile_name'=>'精选']);

        return $result;
    }
    
    /**
     * 领券
     * @param $id 优惠券id
     * @param $user_id 用户id
     */
    public function get_coupon($id, $user_id)
    {
        if (empty($id)){
            return ['status' => 0, 'msg' => '参数错误'];
        }
        if ($user_id) {
            //$_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Home/Activity/coupon_list');
             $coupon_info =Db::name('prom_order')->where(array('id' => $id ,'is_close'=>1))->find();
            if (empty($coupon_info)) {
                $return = ['status' => 0, 'msg' => '活动已结束或不存在，看下其他活动吧~','return_url'=>$_SERVER['HTTP_REFERER']];
            } else {
                $coupon =Db::name('prom_coupon')->alias('pc')->join('__PROM_ORDER__ po','pc.poid =po.id','left')->where(array('pc.poid' => $id, 'pc.status' => 1))->select();
                if(!empty($coupon)){
                    //已经领取过
                    $return = ['status' => 2, 'msg' => '您已领取过该优惠券','return_url'=>$_SERVER['HTTP_REFERER']];
                  returnBad($return);
                }else{
                    //未领
                    $data = array('uid' => $user_id, 'poid' => $id,'add_time'=>time());
                    M('prom_coupon')->add($data);
                    $return = ['status' => 1, 'msg' => '恭喜您，抢到消费满'.$coupon_info['money'].'元,送' . $coupon_info['expression'] . '元优惠券!有效时间'.date("Y-m-d H:i:s",$coupon_info['end_time']),'return_url'=>$_SERVER['HTTP_REFERER']];
               }
            }
        } else {
            $return = ['status' => 0, 'msg' => '请先登录','return_url'=>U('User/login')];
        }
        return $return;
    }
    
    /**
     * 获取活动简要信息
     */
    public function getActivitySimpleInfo(&$goods, $user)
    {
        //1.商品促销
        $activity = $this->getGoodsPromSimpleInfo($user, $goods);
        
        //2.订单促销
        $activity_order = $this->getOrderPromSimpleInfo($user, $goods);
        
        if ($activity['data'] || $activity_order) {
            empty($activity['data']) && $activity['data'] = [];
            $activity['data'] = array_merge($activity['data'], $activity_order);
        }

        $activity['server_current_time'] = time();//服务器时间
        
        return $activity;
    }
    
    /**
     * 获取商品促销简单信息
     */
    public function getGoodsPromSimpleInfo($user, &$goods)
    {
        $goods['prom_is_able'] = 0;
        $activity['prom_type'] = 0;
    
        //1.商品促销
        $goodsPromFactory = new \app\common\logic\GoodsPromFactory;
        if (!$goodsPromFactory->checkPromType($goods['prom_type'])) {
            return $activity;
        } 
        $goodsPromLogic = $goodsPromFactory->makeModule($goods, $goods['prom_id']);
        //上面会自动更新商品活动状态，所以商品需要重新查询
        $goods  = M('Goods')->where('goods_id', $goods['goods_id'])->find();
        unset($goods['goods_content']);
        $goods['prom_is_able'] = 0;
        
        //prom_type:0默认 1抢购 2团购 3优惠促销 4预售(不考虑)
        if (!$goodsPromLogic->checkActivityIsAble()) {
            return $activity;
        }
        $prom = $goodsPromLogic->getPromModel()->getData();
        if (in_array($goods['prom_type'], [1, 2])) {
            $prom['virtual_num'] = $prom['virtual_num'] + $prom['buy_num'];//参与人数
            $goods['prom_is_able'] = 1;
            $activity = [
                'prom_type' => $goods['prom_type'],
                'prom_price' => $prom['price'],
                'virtual_num' => $prom['virtual_num']
            ];
            if($prom['start_time']){
                $activity['prom_start_time'] = $prom['start_time'];
            }
            if($prom['end_time']) {
                $activity['prom_end_time'] = $prom['end_time'];
            }
            return $activity;
        }
        
        // 3优惠促销
        $levels = explode(',', $prom['group']);
        if ($prom['group'] && (isset($user['level']) && in_array($user['level'], $levels))) {
            //type:0直接打折,1减价优惠,2固定金额出售,3买就赠优惠券
            if ($prom['type'] == 0) {
                $activityData[] = ['title' => '折扣', 'content' => "指定商品立打{$prom['expression']}折"];
            } elseif ($prom['type'] == 1) {
                $activityData[] = ['title' => '直减', 'content' => "指定商品立减{$prom['expression']}元"];
            } elseif ($prom['type'] == 2) {
                $activityData[] = ['title' => '促销', 'content' => "促销价{$prom['expression']}元"];
            } elseif ($prom['type'] == 3) {
                $couponLogic = new \app\common\logic\CouponLogic;
                $money = $couponLogic->getSendValidCouponMoney($prom['expression'], $goods['goods_id'], $goods['cat_id3']);
                if ($money !== false) {
                    $activityData[] = ['title' => '送券', 'content' => "买就送代金券{$money}元"];
                }
            }
            if ($activityData) {
                $goods['prom_is_able'] = 1;
                $activity = [
                    'prom_type' => $goods['prom_type'],
                    'data' => $activityData
                ];
                if($prom['start_time']){
                    $activity['prom_start_time'] = $prom['start_time'];
                }
                if($prom['end_time']) {
                    $activity['prom_end_time'] = $prom['end_time'];
                }
            }
        }
        
        return $activity;
    }
    /**
     * 获取用户优惠列表
     * @param type $user_level
     * @param type $cur_time
     * @param type $goods
     * @return string|array
     */
    public function getCouponListInfo($user_id)
    {
        //$cur_time = time();
        $sql = "select * from __PREFIX__prom_coupon";
        $data = [];
        $po = Db::query($sql);
        if(!empty($po)){
            foreach ($po as $k => $p){
                //type:0满额打折,1满额优惠金额,2满额送积分,3满额送优惠券
               $m=Db::name("prom_order")->where(array('id'=>"{$p['poid']}"))->find();
               if ($m['type'] == 0) {
                    $data[] = ['title' => '折扣123', 'content' => "满{$m['money']}元打".round($m['expression']/10, 1)."折"];
                } elseif ($m['type'] == 1) {
                    $data[] = ['title' => '优惠券','id'=>"{$m['id']}",'content' => "满{$m['money']}元优惠{$m['expression']}元",'name'=>"{$m['name']}",'end_time'=>date('Y-m-d H:i:s',"{$m['end_time']}"),'condition'=>"{$m['condition_money']}",'expression'=>"{$m['expression']}"];
                } else{
                    $data[] = ['title' => '其他活动', 'content' => "无赠送"];
                }
            }
        }
        return $data;
    }

    //获取所有优惠卷列表，这里感觉是活动列表
    public function getallCouponList(){
        $list=Db::name("prom_order")->select();
        $data = [];
        foreach ($list as $k=>$v){
            if ($v['type'] == 0) {
                $data[] = ['title' => '折扣123', 'content' => "满{$v['money']}元打".round($v['expression']/10, 1)."折"];
            } elseif ($v['type'] == 1) {
                $data[] = ['title' => '优惠券','id'=>"{$v['id']}",'content' => "满{$v['money']}元优惠{$v['expression']}元",'name'=>"{$v['name']}",'end_time'=>date('Y-m-d H:i:s',"{$v['end_time']}"),'condition'=>"{$v['condition_money']}",'expression'=>"{$v['expression']}"];
            } else{
                $data[] = ['title' => '其他活动', 'content' => "无赠送"];
            }
        }
        return $data;
    }
    /**
     * 订单支付时显示的优惠显示
     * @param type $user
     * @param type $store_id
     * @return type
     */
    public function getOrderPayProm($order_amount=0)
    {
       
        $cur_time = time();
        $sql = "select * from __PREFIX__prom_order where type<2 and start_time <= $cur_time "
                . "AND end_time > $cur_time AND  money<=$order_amount order by money desc limit 1"; //显示满额打折,减价优惠信息
        $data = '';
        $po = Db::query($sql);
        if (!empty($po)) {
            foreach ($po as $p) {
                //type:0满额打折,1满额优惠金额,2满额送积分,3满额送优惠券
                if ($p['type'] == 0) {
                    $data = "满{$p['money']}元打".round($p['expression']/10, 1)."折";
                } elseif ($p['type'] == 1) {
                    $data = "满{$p['money']}元优惠{$p['expression']}元";
                }
            }
        }
        return $data;
    }
}