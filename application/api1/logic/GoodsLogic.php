<?php
/**
 --------------------------------------------------
 空间类型   商品模型
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace app\api\logic;

use app\api\model\Goods;
use app\common\logic\ActivityLogic;
use think\Db;

class GoodsLogic {

    /*商品分类*/
    public function shop_class($post){
        $page =$post['page'];
        $classification = $post['classification'];
        if(!$classification){
            $classification = '全部';
        }
        //分页
        if(!$page){
            $page = 1;
        }
        //排序1.综合，2.销量，3好评，4价格
        $sort = $post['sort'];
        if(empty($sort)){
            $sort =  1;
        }
        if($sort ==1){
            $sort = "shop_num desc,goodbrief desc,price asc";
        }elseif($sort ==2){
            $sort = "shop_num desc";
        }elseif($sort ==3){
            $sort = "goodbrief desc";
        }elseif($sort ==4){
            $sort = "price asc";
        }

        $keys = $post['keys'];
     
       $fields = "id,goods,price,shop_price,pic";
       $field = "id,classification";
       $where['state'] = 1;
       if(!empty($keys)){
       $where['goods'] = array('like','%_'.$keys.'_%');
       }
       $class = M("goods_classification")->where(['state'=>1])->order("sort asc")->field($field)->select();
       if($classification =='全部'){
        $shop = M("goods")->where($where)->order($sort)->field($fields)->limit(($page-1)*10,10)->select();
       }else{
        $where['classification'] = $classification;
        $shop = M("goods")->where($where)->order($sort)->field($fields)->limit(($page-1)*10,10)->select();
       }
       if(!empty($shop)){
        foreach($shop as $key=>$val){
         $shop[$key]['pic'] = 'http://' . $_SERVER['HTTP_HOST'] .__ROOT__.$val['pic'];
        }
        }else{
         $shop = array();   
        }
       $all= array(
        'classification' =>$classification,
        'goods_list'=>$shop,
        );
       $data = array(
        'class_type'=>$class,
        'shop_arr'=>$all
        );
       return $data;
    }

    //获取商品可用优惠券列表
    public function getShopAvailableCoupon($user_id,$goods_id){
        $activityLogic = new ActivityLogic();
        $result = $activityLogic->getCouponCenterList(0,$user_id, 1,$goods_id);
        return $result;
    }



    public function click_collection($post){
        $uid = $post['uid'];
        $gid = $post['goods_id'];
        $model = M("collection");
        $collection = $model->where(['uid'=>$uid,'goods_id'=>$gid])->find();
        if($collection){
            $delete = $model->where(['uid'=>$uid,'goods_id'=>$gid])->delete();
            return 2;
        }else{
            $data = array(
            'goods_id'=>$gid,
            'uid'     =>$uid,
            'add_time'=>time()
            );
            $add = $model->add($data);
            return 1;
        }
    }
}