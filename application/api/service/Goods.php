<?php





namespace app\api\service;



use app\common\logic\ActivityLogic;
use think\Cache;

use think\Exception;

use think\Request;

use app\common\logic\GoodsLogic;

use think\db;

use think\Page;

class Goods

{
    /**
     * 商品列表页
     */
    public static function getGoodsList(){
        $filter_param = array(); // 帅选数组
        $id = I('post.catid/d',0); // 当前分类id

        //$logoid = I('post.logoid',1);
       // $price = I('post.price');
        $keyword = I('post.keyword');
        $sort = I('post.sort') ? I('post.sort') : 'sort'; // 排序
//        $price_arr = explode('-',$price);
//        $price_arr[0]?$start_price = $price_arr[0]:$start_price = 0;
//        $price_arr[1]?$end_price = $price_arr[1]:$end_price = 1000000;
        //$active = M('activelogo')->find($logoid);

        $info['name'] = '全部宝贝';
        if($keyword){
            $goods_where['goods_name'] = array('LIKE', $keyword."%");
            $info['name'] = "搜索";
        }
//        if($price){
//            $goods_where['shop_price'] = array('BETWEEN',[$start_price,$end_price]);
//            $info['name'] = '价格：'.$start_price.'-'.$end_price;
//        }
        if($sort=="on_time"){
            $info['name'] = "本周最新";
        }
        if($sort=="click_count"){
            $info['name'] = "人气必备";
        }
        $filter_param['id'] = $id; //加入帅选条件中
        $goodsLogic = new GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = M('GoodsCategory')->where("id", $id)->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.U('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);
        // 帅选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson ($id);
        if($id > 0){
            $goods_where['is_on_sale'] = 1;
            $goods_where['cat_id'] = ['in' ,$cat_id_arr];
            $info['name'] = $goodsCate['name'];
        }

        $filter_goods_id = Db::name('goods')->where($goods_where)->cache(true)->getField("goods_id",true);
        // 过滤帅选的结果集里面找商品
        $count = count($filter_goods_id);
        $page = new Page($count,10);
        $field = "goods_id,cat_id as catid,original_img as image,shop_price,market_price,goods_name,is_new";
        if($count > 0)
        {
            $goods_list = M('goods')->field($field)->where("goods_id","in", implode(',', $filter_goods_id))->order([$sort => "desc"])->limit($page->firstRow.','.$page->listRows)->select();
            $info ['is_new'] =  M('goods')->field($field)->where("goods_id","in", implode(',', $filter_goods_id))->find();
            foreach ($goods_list as $k=>$v){
                if($v['is_new']==1){
                    $info['is_new'] =  M('goods')->field($field)->where(array('goods_id'=>$v['goods_id']))->find();
                }
            }
            //var_dump($goods_list);die;
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
           // var_dump($filter_goods_id2);die;
            if($filter_goods_id2)
                $goods_images = M('goods_images')->where("goods_id", "in", implode(',', $filter_goods_id2))->cache(true)->select();
        }
        $info['goods_list'] = $goods_list;
        return $info;
    }

    /**
     * 商品详情页
     */
    public static function goodsInfo($user_id){
        //C('TOKEN_ON',true);
        $goodsLogic = new GoodsLogic();
        $goods_id = I("get.goods_id/d");
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::get($goods_id);
        if(empty($goods) || ($goods['is_on_sale'] == 0) ){
            returnApiError('该商品已经下架');
        }
        //相关产品
        $select_goods_id = $goods->select_goods_id;
        $select_goods = M('goods')->field('goods_id,goods_name,shop_price,market_price,original_img as image,logoid')->where(['goods_id'=>['in',$select_goods_id]])->select();
        foreach ($select_goods as $k=>$v){
            $select_goods[$k]['image'] = 'http://'.$_SERVER["HTTP_HOST"].$v['image'];
            $select_goods[$k]['original_img'] = 'http://'.$_SERVER["HTTP_HOST"].$v['original_img'];
           // $logo = M('activelogo')->find($v['logoid']);
//            if($logo){
//                $select_goods[$k]['logo'] = 'http://'.$_SERVER["HTTP_HOST"].$logo['src'];
//            }else{
//                $select_goods[$k]['logo'] = '';
//            }
        }
        if($goods->logoid){
            $logo = M('activelogo')->find($goods->logoid)['src'];
        }else{
            $logo = '';
        }

        $filter_spec = $goodsLogic->get_spec($goods_id);

        $spec_goods_price  = M('spec_goods_price')->where("goods_id", $goods_id)->getField("key,item_id,price,store_count"); // 规格 对应 价格 库存表
        M('Goods')->where("goods_id", $goods_id)->save(array('click_count'=>$goods['click_count']+1 )); //统计点击数
        //$commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计
//        if($goods['prom_type']==3){
//            $active = M('prom_goods')->where(['id'=> $goods['prom_id'], 'start_time'=>['<=',time()], 'end_time'=>['>=',time()]])->getField('title');
//        }

        //商品优惠券
        $activityLogic = new ActivityLogic();
        $coupon = $activityLogic->getCouponCenterList(0,$user_id, 1);
        //1.商品促销
        $activity = $activityLogic->getGoodsPromInfo($goods_id);
        foreach ($coupon as $k => $v){
            $couponList[] = $v;
        }
        //当前用户收藏
        $collect = M('goods_collect')->where(array("goods_id"=>$goods_id ,"user_id"=>$user_id))->count();
        //$goods_collect_count = M('goods_collect')->where(array("goods_id"=>$goods_id))->count(); //商品收藏数

        foreach ($filter_spec as $k => $v){
            if($v["id"]==3){
                $combination = $v["data"];
                unset($filter_spec[$k]);
            }
        }

        $price = get_arr_column($spec_goods_price, "price");
        if(!empty($price)){
            $minprice = min($price);
            $maxprice = max($price);
        }


        $data = array(
            'goods_id'     => $goods->goods_id,
            'good_name'    => $goods->goods_name,
            'content'      => $goods->goods_content,
            'shop_price'   => $price?"$minprice-$maxprice":$goods->shop_price, //$goods->shop_price,
            'market_price' => $goods->market_price,
            'attr_group'   => $filter_spec,
            'relevant'     => $select_goods,
            'image'         => 'http://'.$_SERVER["HTTP_HOST"].$goods['original_img'],
            'active_list'  => $activity,
            'logo'         => $logo,
            'couponList' => $couponList,
            'spec_goods_price' => $spec_goods_price,
            "combination" => $combination,
            'collect' => $collect
        );
        returnApiSuccess('请求成功',$data);
    }
}