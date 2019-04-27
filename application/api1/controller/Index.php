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
    public function index(){
        $shop = new ShopLogic();

        $data = $shop->getIndex($post);
        if($data){
            return returnOk($data);
        }else {
            return returnBad('获取商品数据失败',302);
        }
    }
}