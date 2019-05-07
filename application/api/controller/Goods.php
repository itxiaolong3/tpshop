<?php
/**
 --------------------------------------------------
 空间类型   商品控制器
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace app\api\controller;

use app\api\logic\UserLogic;
use app\common\logic\ActivityLogic;
use app\common\logic\FreightLogic;
use app\common\logic\GoodsLogic;
use app\common\logic\Integral;
use app\common\logic\OrderLogic;
use app\common\logic\PlaceOrder;
use app\common\util\TpshopException;
use think\Db;
use think\Page;
use think\Request;

class Goods extends Base{
   //商品分类
    public function index(){
        $filter_param = array(); // 帅选数组
        $id = I('post.id/d',30); // 当前分类id 基因工程
        $isgive = I('post.is_give/d',0);
        $keyword = I('keyword'); // 搜索关键字
        $sort = I('post.sort') ? I('post.sort') : 'sort'; // 排序
        $sort_asc = I('post.sort_asc') ? I('post.sort_asc') : 'desc'; // 排序
        $price =  I('post.price'); // 价钱
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        $keyword && ($filter_param['keyword'] = $keyword); //加入帅选条件中

        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = M('GoodsCategory')->where("id", $id)->find();// 当前分类
         //($goodsCate['level'] == 1) && header('Location:'.U('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        if($id){
          //  $cateArr = $goodsLogic->get_goods_cate($goodsCate);
            $cateArr='';
            $cat_id_arr = getCatGrandson($id);
        }else{
            $cat_id_arr = getCatGrandsonAll();
            $cateArr = M('goods_category')->where(['is_show'=>1,'level'=>1])->cache(true)->field('id,name,parent_id,level')->select(); // 键值分类数组
        }
        if(!$cateArr){
            $cateArr = M('goods_category')->where(['is_show'=>1,'level'=>1])->cache(true)->field('id,name,parent_id,level')->select(); // 键值分类数组
        }
        // 帅选 品牌 规格 属性 价格,是否赠品
        $goods_where = ['is_on_sale' => 1,'is_give' => $isgive, 'exchange_integral' => 0, 'cat_id' => ['in', $cat_id_arr]];
        if ($keyword)// 搜索关键字
        {
            $goods_where["goods_name"] = ["like", "%$keyword%"];
        }
        //1,19,25,26,24,27
        //exchange_integral  积分兑换：0不参与积分兑换，积分和现金的兑换比例见后台配置 cat_id分类id
        $filter_goods_id = Db::name('goods')->where($goods_where)->getField("goods_id", true);
        // 过滤帅选的结果集里面找商品
        if ($price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice(0, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        //筛选网站自营,入驻商家,货到付款,仅看有货,促销商品
//        $sel = $post['sel'];
//        if ($sel) {
//            $goods_id_4 = $goodsLogic->getFilterSelected($sel, $cat_id_arr);
//            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_4);
//        }
        $count = count($filter_goods_id);
        $page = new Page($count, C('PAGESIZE'));
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'desc' : 'asc'; // 防注入
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort'; // 防注入
            $goods_list = M('goods')->field("goods_id,goods_name,shop_price,original_img,sales_sum,goods_remark,video,music")->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow . ',' . $page->listRows)->select();
            foreach ($goods_list as $k => $v){
                if ($v['original_img']){
                    $goods_list[$k]['original_img'] = url_add_domain($v['original_img']);
                }
                if($v['video']){
                    $goods_list[$k]['video'] = url_add_domain($v['video']);
                }
                if($v['music']){
                    $goods_list[$k]['music'] = url_add_domain($v['music']);
                }

            }
        }

        $data=[
            'goods_list'=> $goods_list?$goods_list:[], //商品列表
            'cateArr'=> $cateArr,//
           // 'filter_menu'=> $filter_menu,
            //'filter_spec'=> $filter_spec,
          //  'filter_price'=> $filter_price,
           // 'filter_param'=> $filter_param,
            'sort_asc' => $sort_asc == 'asc' ? 'desc' : 'asc'
        ];
        if($data){
            return returnOk($data);
        }else {
            return returnBad('获取商品数据失败',302);
        }
    }

    //商品详情
    public function goodsInfo()
    {
        $goodsLogic = new GoodsLogic();
        $goods_id = I("post.goods_id/d");
        //$region_id = I('region_id/d');//28242 配送区域
        if(empty($goods_id) ){
            return returnBad('缺少商品id');
        }

        $user_id = $this->user_id;
        $goodsModel = new \app\common\model\Goods();
        $goods = M('goods')->where(['goods_id'=>$goods_id])->field(['goods_id,goods_content,goods_remark,market_price,shop_price,goods_name,is_free_shipping,is_on_sale,is_virtual,virtual_indate,cat_id,shuoming,video,music,keywords'])->find();
        //if(empty($goods) || ($goods['is_on_sale'] == 0) || ($goods['is_virtual']==1 && $goods['virtual_indate'] <= time()) || empty($goods['goods_id'])){
        if(empty($goods) || ($goods['is_on_sale'] == 0) || empty($goods['goods_id'])){
           return returnBad("此商品不存在或者已下架", 306);
        }
        unset($goods['virtual_indate'],$goods['is_on_sale']);
        //添加足迹
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
        }
        //商品视频
        if ($goods['video']){
            $goods['video'] = url_add_domain($goods['video']);
        }
        //商品音乐
        if ($goods['music']){
            $goods['music'] = url_add_domain($goods['music']);
        }
        //虚拟课程下的讲师标签
        if ($goods['is_virtual']){
            $tabarr=explode('|',$goods['keywords']);
            $goods['tab']=$tabarr;
        }else{
            $goods['tab']=[];
        }
        $goods_images_list = M('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
        foreach ($goods_images_list as $k => $v){
            $goods_images_list[$k]['image_url'] = url_add_domain($v['image_url']);
           unset($goods_images_list[$k]['goods_id']);
        }
        //商品属性
        $goods_attribute = M('GoodsAttribute')->where(['type_id'=>$goods['goods_type']])->getField('attr_id,attr_name'); // 查询商品属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品规格属性表
        foreach ($goods_attr_list as $k => $v){
            $goods_attr[$k]['value'] = $goods_attribute[$v['attr_id']].": ".$v['attr_value'];
        }
        //商品规格
        $filter_spec = $goodsLogic->get_spec($goods_id);
//        $item_id=M('spec_goods_price')->where('key','88')->field('item_id')->find();
//        var_dump($item_id);die;
//        if(!empty($filter_spec)){
//            foreach($filter_spec as $key => $val){
//                 foreach($val as $k =>$v){
//                     $item_id[]=M('spec_goods_price')->where('key',$v['item_id'])->getField('item_id');
//                     $filter_spec[$key][$k]['item_ids']=$item_id['item_id'];
//                 }
////                $item_id[]=M('spec_goods_price')->where('item_id',$val['item_id'])->getField('item_id');
////                 $filter_spec[$key]['item_id']=$item_id['item_id'];
//            }
//        }
        //var_dump($filter_spec);die;
        //$spec_goods_price  = M('spec_goods_price')->where("goods_id", $goods_id)->getField("key,price,store_count,item_id"); // 规格 对应 价格 库存表
        //$goods['sale_num'] = M('order_goods')->where(['goods_id'=>$goods_id,'is_send'=>1])->count(); //is_send 0未发货，1已发货，2已换货，3已退货 查询销量
        $freight = 10;
        $user_address=M('user_address')->where(['user_id'=>$this->user_id])->select();
        if(!empty($user_address)){
            $user_address=M('user_address')->where(['user_id'=>$this->user_id])->where(['is_default'=>1])->field(['address_id,consignee,country,province,city,district,twon,address,is_default'])->find();
            if(empty($user_address)){
                $user_address=M('user_address')->where(['user_id'=>$this->user_id])->where(['is_default'=>0])->field(['address_id,consignee,country,province,city,district,twon,address,is_default'])->order('create_time desc')->find();
//               if(empty($user_address)){
////                   return returnBad('请去添加发货地址！');
////               }
                M('user_address')->where(['address_id'=>$user_address['address_id']])->save(['is_default'=>1]);
            }
        }else{
          // return returnBad('请去添加发货地址！');
        }


//        if($region_id){
//            $freightLogic = new FreightLogic();
//            $freightLogic->setGoodsModel($goods);
//            $freightLogic->setRegionId($region_id);
//            $freightLogic->setGoodsNum(1);
//            $isShipping = $freightLogic->checkShipping();//检测配送地区 is_free_shipping 0 不包邮 1 包邮
//            if ($isShipping) {
//                $freightLogic->doCalculation();
//                $freight = $freightLogic->getFreight();
//            } else {
//                return returnBad("该地区不支持配送");
//            }
//        }
//        //商品优惠券
//        $activityLogic = new \app\common\logic\ActivityLogic;
//        $coupon = $activityLogic->getCouponListInfo($this->user_id);
//        $i = 0;
//        $couponList = [];
//        foreach ($coupon as $k => $v){
//            $v["expression"] = intval($v["expression"]);
//            $couponList[$i++] = $v;
//        }
        //当前用户收藏
        $collect = M('goods_collect')->where(array("goods_id"=>$goods_id ,"user_id"=>$user_id))->count();
        $goods_collect_count = M('goods_collect')->where(array("goods_id"=>$goods_id))->count(); //商品收藏数

        /*商品评论*/
        $comment['list'] = M('Comment')->alias('c')
            ->join('__USERS__ u', 'u.user_id = c.user_id', 'LEFT')
            ->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => 0])->field('c.comment_id,c.goods_id,c.username,c.content,c.add_time,c.user_id,c.img,c.goods_rank ,u.head_pic')->select();
        $goods_rank=M('Comment')->alias('c')
            ->join('__USERS__ u', 'u.user_id = c.user_id', 'LEFT')
            ->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => 0])->sum('c.goods_rank');
        $comment['count']=count($comment['list']);//总数
        if($comment['count']==0){
            $comment['count']=1;
        }
        $comment['praise']=$goods_rank/($comment['count']*5); //评分
        foreach ($comment['list'] as $k =>$v){
          if(!empty($comment['list'][$k]['img'])){
              $comment['list']['img']['imgs']=[];
              $img = explode(",", $comment['list'][$k]['img']);
              foreach ($img as $kk =>$vv){
                  $comment['list'][$k]["imgs"][] = url_add_domain($vv);
              }
          }
        unset($comment['list'][$k]['img']);
        unset($comment['list']['img']);
       }
        $point_rate = tpCache('integral.point_rate');
        //$point_rate = tpCache('shopping.point_rate');
        $goods['goods_content'] = htmlspecialchars_decode($goods['goods_content']);
//        $price= get_arr_column($spec_goods_price,'price');
//        if(min($price)==max($price)){
//            $price=min($price);
//        }
//        $price =min($price)."-".max($price);
        $data = [
            'collect' => $collect, //商品收藏
            'goods_attr' => $goods_attr, //属性参数
            'filter_spec' => $filter_spec,//规格参数
           // 'spec_goods_price' => $spec_goods_price, // 规格 对应 价格 库存表
            'goods_images_list' => $goods_images_list,//商品缩略图
            'goods' => $goods, //商品信息
            //'goods_price'=>$price,//区间价格
           // 'coupon_list' =>$couponList, //购物券列表
            'goods_collect_count' => $goods_collect_count, //商品收藏人数
            'point_rate' => $point_rate,
            'address'=>$user_address, //地址
            'comment' => $comment, //商品评论
            'freight' => $freight //运费
        ];
        return returnOk($data);
    }

    /*
     * ajax获取商品评论
     */
    public function ajaxComment()
    {
        $goods_id = I("goods_id/d", 0);
        $commentType = I('commentType', '1'); // 1 全部 2好评 3 中评 4差评
        if ($commentType == 5) {
            $where = array(
                'goods_id' => $goods_id, 'parent_id' => 0, 'img' => ['<>', ''], 'is_show' => 1
            );
        } else {
            $typeArr = array('1' => '0,1,2,3,4,5', '2' => '4,5', '3' => '3', '4' => '0,1,2');
            $where = array('is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => 0, 'goods_rank' => ['in', $typeArr[$commentType]]);
        }
        $count = M('Comment')->where($where)->count();
        $page_count = C('PAGESIZE');
        $page = new Page($count, $page_count);
        $list = M('Comment')
            ->alias('c')
            ->join('__USERS__ u', 'u.user_id = c.user_id', 'LEFT')
            ->where($where)->field('c.*,u.head_pic')
            ->order("add_time desc")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $replyList = M('Comment')->where(['goods_id' => $goods_id, 'parent_id' => ['>', 0]])->order("add_time desc")->select();
        foreach ($list as $k => $v) {

            if($v['img']) {
                $img = explode(",", $v['img']);
                foreach ($img as $vv){
                    $imgs[] = url_add_domain($vv);
                }
                $list[$k]["imgs"] = $imgs;
            }
            //$list[$k]['img'] = unserialize($v['img']); // 晒单图片
//            $replyList[$v['comment_id']] = M('Comment')->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => $v['comment_id']])->order("add_time desc")->select();
//            $list[$k]['reply_num'] = Db::name('reply')->where(['comment_id' => $v['comment_id'], 'parent_id' => 0])->count();
        }
       $data = [
           "commentlist" => $list,
           "count" => $count,
           'commentType' => $commentType
       ];
        return returnOk($data);
//        $this->assign('commentlist', $list);// 商品评论
//        $this->assign('replyList', $replyList); // 管理员回复
//        $this->assign('count', $count);//总条数
    }



    /**
     * 商品物流配送和运费
     */
    public function dispatching()
    {
        $goods_id = I('goods_id/d');//143
        $region_id = I('region_id/d');//28242
        $Goods = new \app\common\model\Goods();
        $goods = $Goods->cache(true)->where('goods_id', $goods_id)->find();
        $freightLogic = new FreightLogic();
        $freightLogic->setGoodsModel($goods);
        $freightLogic->setRegionId($region_id);
        $freightLogic->setGoodsNum(1);
        $isShipping = $freightLogic->checkShipping();
        if ($isShipping) {
            $freightLogic->doCalculation();
            $freight = $freightLogic->getFreight();
            return returnOk(['msg' => '可配送', 'result' => ['freight' => $freight]]);
        } else {
            return returnBad("该地区不支持配送", 308);
        }
    }

    /**
     * 领券
     */
    public function getCoupon()
    {
        $id = I('coupon_id/d');
        $openid = I("post.openid");
        $userLogic = new UserLogic();
        $user = $userLogic->getuser($openid);
        $activityLogic = new ActivityLogic();
        $return = $activityLogic->get_coupon($id, $user["user_id"]);
        return returnOk($return);
    }

    /**
     * 用户收藏某一件商品
     */
    public function collect_goods()
    {
        $goods_id = I('goods_id/d');
        $goodsLogic = new GoodsLogic();
        $result = $goodsLogic->collect_goods($this->user_id, $goods_id);
        return returnOk($result);
    }

    /*
     * 商品详情-点击收藏商品
     */
    public function click_collection(){
        $post = $this->check_post();
        if (empty($post['openid'])) {
            returnBad('用户openid不能为空',303);
        }
        //获取用户信息
        $userModel = new UserModel();
        $user = $userModel->getuser($post);
        $post['uid'] = $user["id"];
        $detail = new ShopModel();
        $data = $detail->click_collection($post);
        if($data){
            returnOk($data);
        }else {
            returnBad('失败',302);
        }
    }

    /**
     * 积分商城
     */
    public function integralMall()
    {
        $rank = I('get.rank');
        //以兑换量（购买量）排序
        if ($rank == 'num') {
            $ranktype = 'sales_sum';
            $order = 'desc';
        }
        //以需要积分排序
        if ($rank == 'integral') {
            $ranktype = 'exchange_integral';
            $order = 'desc';
        }
        //积分规则修改后的逻辑
        $point_rate = tpCache('integral.point_rate');

        //$point_rate = tpCache('shopping.point_rate');
        $goods_where = array(
            'is_on_sale' => 1,  //是否上架
        );
        //积分兑换筛选
        $exchange_integral_where_array = array(array('gt', 0));

        // 分类id
        if (!empty($cat_id)) {
            $goods_where['cat_id'] = array('in', getCatGrandson($cat_id));
        }
        //我能兑换
        $user_id = cookie('user_id');
        if ($rank == 'exchange' && !empty($user_id)) {
            //获取用户积分
            $user_pay_points = intval(M('users')->where(array('user_id' => $user_id))->getField('pay_points'));
            if ($user_pay_points !== false) {
                array_push($exchange_integral_where_array, array('lt', $user_pay_points));
            }
        }
        $goods_where['exchange_integral'] = $exchange_integral_where_array;  //拼装条件
        $goods_list_count = M('goods')->where($goods_where)->count();   //总页数
        $page = new Page($goods_list_count, 15);
        $Goods = new \app\common\model\Goods();
        $goods_list = $Goods->where($goods_where)->order($ranktype, $order)->limit($page->firstRow . ',' . $page->listRows)->select();
        $goods_category = M('goods_category')->where(array('level' => 1))->select();
        foreach ($goods_list as $k => $v){
            $goods_list[$k]['original_img'] = url_add_domain($v['original_img']);
        }

        $data = [
            "pay_points" => $this->user['pay_points'],//用户所有积分
            "goods_category" => $goods_category,//商品1级分类
            "goods_list" => $goods_list,
//            "point_rate" => $point_rate //兑换率
        ];
       return returnOk($data);
    }


    /**
     *  积分商品价格提交
     * @return mixed
     */
    public function integral()
    {
        if ($this->user_id == 0) {
            return returnBad("登录超时请重新登录", 302);
        }
        $goods_id = input('goods_id/d');
        $consignee = input('consignee');
        $mobile = input('mobile');
        $address = input('address');
        if(!$consignee){
            return returnBad("收件人不能为空", 308);
        }
        if(!$mobile){
            return returnBad("手机号不能为空", 308);
        }
        if(!$address){
            return returnBad("地址不能为空", 308);
        }

        $pay_points = Db::name("users")->where(['user_id'=>$this->user_id])->getField('pay_points');
        $goods = Db::name("goods")->field('goods_id,exchange_integral')->where(['goods_id'=>$goods_id])->find();
        $exchange_integral = $goods['exchange_integral'];
        if($pay_points - $goods['exchange_integral'] < 0){
            return returnBad("积分不足，无法兑换", 308);
        }
        M('users')->where(['user_id' => $this->user_id])->save(["pay_points" => ["exp", "pay_points-$exchange_integral"]]);
        // 提交订单
        $OrderLogic = new OrderLogic();
        $orderData = [
            'order_sn' => $OrderLogic->get_order_sn(), // 订单编号
            'user_id' => $this->user_id, // 用户id
            'goods_id' => $goods_id,
            'consignee' => $consignee,
            'mobile' => $mobile,
            'address' => $address,
            'goods_price' =>$exchange_integral,//'商品价格',
            'integral' => $exchange_integral, //'使用积分',
            'total_amount' => $exchange_integral,// 订单总额
            'order_amount' => $exchange_integral,//'应付款金额',
            'add_time' => time(), // 下单时间
            'pay_name' => '积分兑换',
        ];
        $order_id = M('integral_order')->insertGetId($orderData);
        $accountLogData = [
            'user_id' => $this->user_id,
            'user_money' => -0,
            'pay_points' => -$exchange_integral,
            'change_time' => time(),
            'desc' => '积分兑换商品',
            'order_sn'=>$orderData['order_sn'],
        ];
        Db::name('account_log')->insert($accountLogData);
        return returnOk($order_id);
    }

}