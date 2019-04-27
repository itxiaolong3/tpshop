<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */

namespace app\mobile\controller;

use app\common\logic\ActivityLogic;
use app\common\logic\GoodsLogic;
use app\common\logic\GoodsPromFactory;
use app\common\model\Combination;
use app\common\model\SpecGoodsPrice;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Page;
use think\Db;

class Goods extends MobileBase
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 分类列表显示
     */
    public function categoryList()
    {
        return $this->fetch();
    }

    /**
     * 商品列表页
     */
    public function goodsList()
    {
        $filter_param = array(); // 帅选数组
        $id = I('id/d', 1); // 当前分类id
        $brand_id = I('brand_id/d', 0);
        $spec = I('spec', 0); // 规格
        $attr = I('attr', ''); // 属性
        $sort = I('sort', 'sort'); // 排序
        $sort_asc = I('sort_asc', 'desc'); // 排序
        $price = I('price', ''); // 价钱
        $start_price = trim(I('start_price', '0')); // 输入框价钱
        $end_price = trim(I('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $spec && ($filter_param['spec'] = $spec); //加入帅选条件中
        $attr && ($filter_param['attr'] = $attr); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中

        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = M('GoodsCategory')->where("id", $id)->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.U('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);

        // 帅选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson($id);
        $goods_where = ['is_on_sale' => 1, 'exchange_integral' => 0, 'cat_id' => ['in', $cat_id_arr]];
        $filter_goods_id = Db::name('goods')->where($goods_where)->cache(true)->getField("goods_id", true);

        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if ($spec)// 规格
        {
            $goods_id_2 = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if ($attr)// 属性
        {
            $goods_id_3 = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_3); // 获取多个帅选条件的结果 的交集
        }

        //筛选网站自营,入驻商家,货到付款,仅看有货,促销商品
        $sel = I('sel');
        if ($sel) {
            $goods_id_4 = $goodsLogic->getFilterSelected($sel, $cat_id_arr);
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_4);
        }

        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'goodsList'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'goodsList'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'goodsList'); // 获取指定分类下的帅选品牌
        $filter_spec = $goodsLogic->get_filter_spec($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选规格
        $filter_attr = $goodsLogic->get_filter_attr($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选属性

        $count = count($filter_goods_id);
        $page = new Page($count, C('PAGESIZE'));
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'desc' : 'asc'; // 防注入
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort'; // 防注入

            $goods_list = M('goods')->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow . ',' . $page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = M('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        $this->assign('goods_list', $goods_list);
        $this->assign('goods_category', $goods_category);
        $this->assign('goods_images', $goods_images);  // 相册图片
        $this->assign('filter_menu', $filter_menu);  // 帅选菜单
        $this->assign('filter_spec', $filter_spec);  // 帅选规格
        $this->assign('filter_attr', $filter_attr);  // 帅选属性
        $this->assign('filter_brand', $filter_brand);// 列表页帅选属性 - 商品品牌
        $this->assign('filter_price', $filter_price);// 帅选的价格期间
        $this->assign('goodsCate', $goodsCate);
        $this->assign('cateArr', $cateArr);
        $this->assign('filter_param', $filter_param); // 帅选条件
        $this->assign('cat_id', $id);
        $this->assign('page', $page);// 赋值分页输出
        $this->assign('sort_asc', $sort_asc == 'asc' ? 'desc' : 'asc');
        C('TOKEN_ON', false);
        if (input('is_ajax'))
            return $this->fetch('ajaxGoodsList');
        else
            return $this->fetch();
    }

    /**
     * 商品列表页 ajax 翻页请求 搜索
     */
    public function ajaxGoodsList()
    {
        $where = '';

        $cat_id = I("id/d", 0); // 所选择的商品分类id
        if ($cat_id > 0) {
            $grandson_ids = getCatGrandson($cat_id);
            $where .= " WHERE cat_id in(" . implode(',', $grandson_ids) . ") "; // 初始化搜索条件
        }

        $result = DB::query("select count(1) as count from __PREFIX__goods $where ");
        $count = $result[0]['count'];
        $page = new AjaxPage($count, 10);

        $order = " order by sort desc"; // 排序
        $limit = " limit " . $page->firstRow . ',' . $page->listRows;
        $list = DB::query("select *  from __PREFIX__goods $where $order $limit");

        $this->assign('lists', $list);
        $html = $this->fetch('ajaxGoodsList'); //return $this->fetch('ajax_goods_list');
        exit($html);
    }

    /**
     * 领取优惠券
     */
    public function couponList(){
        $p = input('p', 1);
        $cat_id = input('cat_id', 0);
        $goods_id = input('goods_id', 0);
        $user = session('user');

        $activityLogic = new ActivityLogic();
        $result = $activityLogic->getCouponCenterList($cat_id, $user['user_id'], $p,$goods_id);
        $return = array(
            'status' => 1,
            'msg' => '获取成功',
            'result' => $result ,
        );
        $this->ajaxReturn($return);
    }

    public function checkGoodsPromotionType(){
        $where['goods_id'] =I("get.goods_id/d");
        $where['item_id'] = I("get.item_id/d",0);
        $prom_id = DB::name('prom_goods_item')->where($where)->value('prom_id');

        $return = array(
            'status' => -1,
            'msg' => '暂无参与促销活动'
        );

        if($prom_id){
            $check = DB::name('prom_goods')->where(['id'=>$prom_id,'is_end'=>0])->find();
            if($check){
                $return = array(
                    'status' => 1,
                    'msg' => '正在参与促销活动',
                    'result' => $check['type']
                );
            }
        }
        return json($return);
    }

    /**
     * 商品详情页
     */
    public function goodsInfo()
    {
        C('TOKEN_ON', true);
        $goodsLogic = new GoodsLogic();
        $goods_id = I("get.id/d");
        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::get($goods_id);
        if (empty($goods) || ($goods['is_on_sale'] == 0)) {
            $this->error('此商品不存在或者已下架');
        }
        if(($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())){
            $goods->save(['is_on_sale' => 0]);
            $this->error('此商品不存在或者已下架');
        }
        $user_id = cookie('user_id');
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
            $collect = db('goods_collect')->where(array("goods_id" => $goods_id, "user_id" => $user_id))->count(); //当前用户收藏
            $this->assign('collect', $collect);
        }

        $recommend_goods = M('goods')->where("is_recommend=1 and is_on_sale=1 and cat_id = {$goods['cat_id']}")->cache(7200)->limit(9)->field("goods_id, goods_name, shop_price")->select();
        $this->assign('recommend_goods', $recommend_goods);
        $this->assign('goods', $goods);
        return $this->fetch();
    }

    public function goodsInfo2()
    {
        C('TOKEN_ON',true);        
        $goodsLogic = new GoodsLogic();
        $goods_id = I("post.id/d");

        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::get($goods_id);

        if(empty($goods) || ($goods['is_on_sale'] == 0) || ($goods['is_virtual']==1 && $goods['virtual_indate'] <= time())){
            $this->error('此商品不存在或者已下架');
        }
        if (cookie('user_id')) {
            $goodsLogic->add_visit_log(cookie('user_id'), $goods);
        }
        if($goods['brand_id']){
            $brnad = M('brand')->where("id", $goods['brand_id'])->find();
            $goods['brand_name'] = $brnad['name'];
        }

        $goods_images_list = M('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品属性表
        $filter_spec = $goodsLogic->get_spec($goods_id);
        $spec_goods_price  = M('spec_goods_price')->where("goods_id", $goods_id)->getField("key,price,store_count,item_id"); // 规格 对应 价格 库存表
        $this->assign('spec_goods_price', json_encode($spec_goods_price,true)); // 规格 对应 价格 库存表
        $goods['sale_num'] = M('order_goods')->where(['goods_id'=>$goods_id,'is_send'=>1])->count();
        //当前用户收藏
        $user_id = cookie('user_id');
        $collect = M('goods_collect')->where(array("goods_id"=>$goods_id ,"user_id"=>$user_id))->count();
        $goods_collect_count = M('goods_collect')->where(array("goods_id"=>$goods_id))->count(); //商品收藏数

        $this->assign('collect',$collect);
        $this->assign('goods_attribute',$goods_attribute);//属性值     
        $this->assign('goods_attr_list',$goods_attr_list);//属性列表
        $this->assign('filter_spec',$filter_spec);//规格参数
        $this->assign('goods_images_list',$goods_images_list);//商品缩略图
        $this->assign('goods',$goods->toArray());
        //积分规则修改后的逻辑
        $kf_config['im_choose'] = tpCache('basic.im_choose');
        $this->assign('kf_config',$kf_config);

        $point_rate = tpCache('integral.point_rate');
        //$point_rate = tpCache('shopping.point_rate');
        $this->assign('goods_collect_count',$goods_collect_count); //商品收藏人数
        $this->assign('point_rate', $point_rate);
        return $this->fetch('goodsinfotwo');
    }

    public function activity()
    {
        $goods_id = input('goods_id/d');//商品id
        $item_id = input('item_id/d', 0);//规格id
        $goods_num = input('goods_num/d');//欲购买的商品数量
        $goodsPromFactory = new GoodsPromFactory();
        $goodsLogic = new GoodsLogic();
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::get($goods_id, '');

        $goods['activity_is_on'] = 0;
        $specGoodsPrice = SpecGoodsPrice::get($item_id, '');
        if($specGoodsPrice){
            $goods->shop_price = $specGoodsPrice->price;
        }
        $goods->shop_price = $goodsLogic->getGoodsPriceByLadder($goods_num, $goods['shop_price'], $goods['price_ladder']);//先使用价格阶梯
        if ($goodsPromFactory->checkPromType($goods['prom_type'])) {
            $goodsPromLogic = $goodsPromFactory->makeModule($goods, $specGoodsPrice);
            if ($goodsPromLogic->checkActivityIsAble()) {
                $goods = $goodsPromLogic->getActivityGoodsInfo();
                $goods['activity_is_on'] = 1;
                $goods['shop_price'] = round($goods['shop_price'],2);
                $this->ajaxReturn(['status' => 1, 'msg' => '该商品参与活动', 'result' => ['goods' => $goods]]);
            }
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '该商品没有参与活动', 'result' => ['goods' => $goods]]);
    }

    /*
     * 商品评论
     */
    public function comment()
    {
        $goods_id = I("goods_id/d", 0);
        $this->assign('goods_id', $goods_id);
        return $this->fetch();
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
            $where = array('is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => 0, 'ceil((deliver_rank + goods_rank + service_rank) / 3)' => ['in', $typeArr[$commentType]]);
        }
        $count = M('Comment')->where($where)->count();
        $page_count = C('PAGESIZE');
        $page = new AjaxPage($count, $page_count);
        $list = M('Comment')
            ->alias('c')
            ->join('__USERS__ u', 'u.user_id = c.user_id', 'LEFT')
            ->where($where)->field('c.*,ceil((deliver_rank + goods_rank + service_rank) / 3) as goods_rank ,u.head_pic')
            ->order("add_time desc")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $replyList = M('Comment')->where(['goods_id' => $goods_id, 'parent_id' => ['>', 0]])->order("add_time desc")->select();
        foreach ($list as $k => $v) {
            $list[$k]['img'] = unserialize($v['img']); // 晒单图片
            $replyList[$v['comment_id']] = M('Comment')->where(['is_show' => 1, 'goods_id' => $goods_id, 'parent_id' => $v['comment_id']])->order("add_time desc")->select();
            $list[$k]['reply_num'] = Db::name('reply')->where(['comment_id' => $v['comment_id'], 'parent_id' => 0])->count();
        }
        $this->assign('goods_id', $goods_id);//商品id
        $this->assign('commentlist', $list);// 商品评论
        //$this->ajaxReturn($list);
        $this->assign('commentType', $commentType);// 1 全部 2好评 3 中评 4差评 5晒图
        $this->assign('replyList', $replyList); // 管理员回复
        $this->assign('count', $count);//总条数
        $this->assign('page_count', $page_count);//页数
        $this->assign('current_count', $page_count * I('p'));//当前条
        $this->assign('p', I('p'));//页数
        return $this->fetch();
    }

    /*
     * 获取商品规格
     */
    public function goodsAttr()
    {
        $goods_id = I("get.goods_id/d", 0);
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品属性表
        $this->assign('goods_attr_list', $goods_attr_list);
        $this->assign('goods_attribute', $goods_attribute);
        return $this->fetch();
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

        $this->assign('goods_list', $goods_list);
        $this->assign('page', $page->show());
        $this->assign('goods_list_count', $goods_list_count);
        $this->assign('goods_category', $goods_category);//商品1级分类
        $this->assign('point_rate', $point_rate);//兑换率
        $this->assign('totalPages', $page->totalPages);//总页数
        if (IS_AJAX) {
            return $this->fetch('ajaxIntegralMall'); //获取更多
        }
        return $this->fetch();
    }

    /**
     * 商品搜索列表页
     */
    public function search()
    {
        $filter_param = array(); // 帅选数组
        $id = I('get.id/d', 0); // 当前分类id
        $brand_id = I('brand_id/d', 0);
        $sort = I('sort', 'sort'); // 排序
        $sort_asc = I('sort_asc', 'desc'); // 排序
        $price = I('price', ''); // 价钱
        $start_price = trim(I('start_price', '0')); // 输入框价钱
        $end_price = trim(I('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        $q = urldecode(trim(I('q', ''))); // 关键字搜索
        $q && ($_GET['q'] = $filter_param['q'] = $q); //加入帅选条件中
        $qtype = I('qtype', '');
        $where = array('is_on_sale' => 1);
        if ($qtype) {
            $filter_param['qtype'] = $qtype;
            $where[$qtype] = 1;
        }
        if ($q) $where['goods_name'] = array('like', '%' . $q . '%');

        $goodsLogic = new GoodsLogic();
        $filter_goods_id = M('goods')->where($where)->cache(true)->getField("goods_id", true);

        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }

        //筛选网站自营,入驻商家,货到付款,仅看有货,促销商品
        $sel = I('sel');
        if ($sel) {
            $goods_id_4 = $goodsLogic->getFilterSelected($sel);
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_4);
        }

        $filter_menu = $goodsLogic->get_filter_menu($filter_param, 'search'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'search'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'search'); // 获取指定分类下的帅选品牌

        $count = count($filter_goods_id);
        $page = new Page($count, 12);
        if ($count > 0) {
            $sort_asc = $sort_asc == 'asc' ? 'asc' : 'desc';
            $sort_arr = ['sales_sum','shop_price','is_new','comment_count','sort'];
            if(!in_array($sort,$sort_arr)) $sort='sort';
            $goods_list = D('goods')->where("goods_id", "in", implode(',', $filter_goods_id))->order([$sort => $sort_asc])->limit($page->firstRow . ',' . $page->listRows)->field('goods_id,goods_name,comment_count,shop_price')->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if ($filter_goods_id2)
                $goods_images = M('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        $this->assign('goods_list', $goods_list);
        $this->assign('goods_category', $goods_category);
        $this->assign('goods_images', $goods_images);  // 相册图片
        $this->assign('filter_menu', $filter_menu);  // 帅选菜单
        $this->assign('filter_brand', $filter_brand);// 列表页帅选属性 - 商品品牌
        $this->assign('filter_price', $filter_price);// 帅选的价格期间
        $this->assign('filter_param', $filter_param); // 帅选条件
        $this->assign('page', $page);// 赋值分页输出
        $this->assign('sort_asc', $sort_asc == 'asc' ? 'desc' : 'asc');
        C('TOKEN_ON', false);
        if (input('is_ajax'))
            return $this->fetch('ajaxSearchList');
        else
            return $this->fetch();
    }

    /**
     * 商品搜索列表页
     */
    public function ajaxSearch()
    {
        return $this->fetch();
    }

    /**
     * 品牌街
     */
    public function brandstreet()
    {
        $getnum = 9;   //取出数量
        $goods = M('goods')->where(array('is_recommend' => 1, 'is_on_sale' => 1,'brand_id'=>['gt',0]))->page(1, $getnum)->cache(true, TPSHOP_CACHE_TIME)->select(); //推荐商品
        for ($i = 0; $i < ($getnum / 3); $i++) {
            //3条记录为一组
            $recommend_goods[] = array_slice($goods, $i * 3, 3);
        }
        $where = array(
            'is_hot' => 1,  //1为推荐品牌
        );
        $count = M('brand')->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20);
        $brand_list = M('brand')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('sort desc')->select();
        $this->assign('recommend_goods', $recommend_goods);  //品牌列表
        $this->assign('brand_list', $brand_list);            //推荐商品
        $this->assign('listRows', $Page->listRows);
        if (I('is_ajax')) {
            return $this->fetch('ajaxBrandstreet');
        }
        return $this->fetch();
    }

    /**
     * 用户收藏某一件商品
     */
    public function collect_goods()
    {
        $goods_id = I('goods_id/d');
        $goodsLogic = new GoodsLogic();
        $result = $goodsLogic->collect_goods(cookie('user_id'), $goods_id);
        $this->ajaxReturn($result);
    }

    /**
     * 搭配详情页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function combination_details()
    {
        $goods_id = input('id/d');
        $item_id = input('item/d', 0);
        $combination_id = input('combination/d', 0);

        if (empty($goods_id)) {
            $this->error('参数错误');
        }
        $goodsModel = new \app\common\model\Goods();
        $goods = $goodsModel::get($goods_id);
        if (empty($goods) || ($goods['is_on_sale'] == 0) || ($goods['is_virtual'] == 1 && $goods['virtual_indate'] <= time())) {
            $this->error('此商品不存在或者已下架');
        }
        $combination = new \app\common\logic\Combination();
        $combination->setCombinationId($combination_id);
        $combination_list = [];
        try {
            $combination_list = $combination->getCombinationDetails();
        } catch (TpshopException $t) {
            $error = $t->getErrorArr();
            $this->error($error['msg']);
        }
        $this->assign('combination', $combination_list);
        $this->assign('goods_id', $goods_id);
        $this->assign('item_id', $item_id);
        return $this->fetch();
    }


}