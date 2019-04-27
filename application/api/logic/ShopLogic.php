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
use think\Db;

class ShopLogic {

    protected static $carousel = null;

    protected static $hot_goods = null;

    public function __construct()
    {
        //首页轮播图片11
        if(self::$carousel == null){
            self::$carousel = M("ad")->order("orderby asc")->where('pid = 537')->column('ad_code');
            if(!empty(self::$carousel)){
                foreach (self::$carousel as $k => $v){
                    self::$carousel[$k] = url_add_domain($v);
                }
            }else{
                self::$carousel = [];
            }
        }
        if(self::$hot_goods == null){
            self::$hot_goods =  Db::name('ad')->field('ad_id,ad_name,ad_link,ad_code')->where(['pid'=>538])->order("orderby asc")->limit(3)->select();
            if(!empty(self::$hot_goods)){
                foreach (self::$hot_goods as $k => $v){
                    self::$hot_goods[$k]['ad_code'] = url_add_domain($v['ad_code']);
                }
            }else{
                self::$hot_goods = [];
            }
        }
    }

    /*商城首页*/
    public function getIndex($post)
    {
        $goodsModel = new Goods();
        $page = $post['p'];
        $where['is_recommend'] = 0;
        $goods_list = $goodsModel->goodsList($page,$where);
        if(!empty($goods_list['list'])){
            foreach ($goods_list['list'] as $key => $val){
                $goods_list['list'][$key]['original_img'] = url_add_domain($val['original_img']);
            }
        }
        $data = [
            'carousel'  => self::$carousel,
            'notice'     => tpCache("shop_info.store_desc"),
            'hotGoods'     => self::$hot_goods,
            'goods_list' => $goods_list
        ];
        return $data;
    }
}