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
namespace app\api\controller;

use Think\Controller;


class Address extends Base{


    //***************************
    //  获取省份数据接口
    //***************************
    public function get_province(){
        //所有省份
        $china_city=M("china_city");
        $list = $china_city->where('tid=0')->field('id,name')->select();

        $this->ajaxReturn(['code' => '200', 'msg' => '成功','data'=>$list]);exit();
    }

    //***************************
    //  获取城市数据接口
    //***************************
    public function get_city(){
        $sheng=intval($_REQUEST['sheng']);
        if (!$sheng){
            $this->ajaxReturn(['code' => '300', 'msg' => '请选择省份']);exit();
        }
        
        //所有省份
        $china_city=M("china_city");
        $list = $china_city->where('tid=0')->field('id,name')->select();
        $city = $china_city->where('tid='.intval($list[$sheng-1]['id']))->field('id,name')->select();
        $this->ajaxReturn(['code' => '200', 'msg' => '成功','data'=>['city_list'=>$city,'sheng'=>intval($list[$sheng-1]['id'])]]);
    
    }

    //***************************
    //  获取区域数据接口
    //***************************
    public function get_area(){
        $city=intval($_REQUEST['city']);
        if (!$city){
             $this->ajaxReturn(['code' => '300', 'msg' => '请选择城市']);exit();
        }
        //所有省份
        $china_city=M("china_city");
        $list = $china_city->where('tid='.intval($_REQUEST['sheng']))->field('id,name')->select();
        $area = $china_city->where('tid='.intval($list[$city-1]['id']))->field('id,name')->select();
        $this->ajaxReturn(['code' => '200', 'msg' => '成功','data'=>['area_list'=>$area,'city'=>intval($list[$city-1]['id'])]]);
    }

    //***************************
    //  获取邮政编号接口
    //***************************
    public function get_code(){
        $quyu=intval($_REQUEST['quyu']);
        //所有省份
        $china_city=M("china_city");
        $list = $china_city->where('tid='.intval($_REQUEST['city']))->field('id,name')->select();
        $code = $china_city->where('id='.intval($list[$quyu-1]['id']))->getField('code');
        $this->ajaxReturn(['code' => '200', 'msg' => '成功','data'=>['code'=>$code,'area'=>intval($list[$quyu-1]['id'])]]);
    }


    
}