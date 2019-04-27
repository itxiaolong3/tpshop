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

use app\api\logic\ShopLogic;

class Index extends Base{
    /* 
     * 获取主页数据
     *  */
//    public function index(){
//        //$post = $this->check_post();
//        $shop = new ShopLogic();
//        $data = $shop->getIndex($post);
//        if($data){
//            return returnOk($data);
//        }else {
//            return returnBad('获取商品数据失败',302);
//        }
//    }
    public function index()
    {
        //获取首页产品轮播图片
        $product = M('goods')
            ->field('goods_id,original_img as good_img')
            ->where(['is_recommend' => 1,'is_on_sale'=>1]) //上架 推荐
            ->whereOr(['is_hot'=>1]) //热卖
            ->order('sort')
            ->select();
      var_dump($product);die;
         if(!empty($product)){
             foreach($product as $k =>$v){
                 $product[$k]["good_img"] = url_add_domain($v["good_img"]);
                 unset($product[$k]["image"]);
             }
         }
        //var_dump($product);die;
        $category = M('goods_category')
            ->field('name,image,id as catid')
            ->where(['is_hot' => 1])
            ->order('sort_order')
            ->limit(4)
            ->select();
        if(!empty($category)){
            foreach($category as $k =>$v){
                $category[$k]["image"] = url_add_domain($v["image"]);
            }
        }
        $arr['category'] = $category;
        $arr['banner'] = $product;
        returnApiSuccess($arr);
    }
}