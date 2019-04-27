<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model;

use app\common\logic\FlashSaleLogic;
use app\common\logic\GroupBuyLogic;
use think\Db;
use think\Model;
use app\common\logic\PromGoodsLogic;

class Shop extends Model
{
    //自定义初始化
    protected static function init()
    {
        //TODO:自定义的初始化
    }
    public function suppliers()
    {
        return $this->hasOne('Suppliers','suppliers_id','suppliers_id');
    }
    public function shopImages()
    {
        return $this->hasMany('shopImages','shop_id','shop_id');
    }
    public function getAreaListAttr($value, $data)
    {
        $area_list = Db::name('region')->where('id', 'IN', [$data['province_id'], $data['city_id'], $data['district_id']])->order('level asc')->select();
        return $area_list;
    }

    public function getWorkDayAttr($value, $data)
    {
        $arr = [];
        if ($data['monday'] == 1) {
            array_push($arr, '周一');
        }
        if ($data['tuesday'] == 1) {
            array_push($arr, '周二');
        }
        if ($data['wednesday'] == 1) {
            array_push($arr, '周三');
        }
        if ($data['thursday'] == 1) {
            array_push($arr, '周四');
        }
        if ($data['friday'] == 1) {
            array_push($arr, '周五');
        }
        if ($data['saturday'] == 1) {
            array_push($arr, '周六');
        }
        if ($data['sunday'] == 1) {
            array_push($arr, '周日');
        }
        $desc = implode('、', $arr);
        return $desc;
    }
    /**
     * 设置添加时间
     * @param $value
     * @return string
     */
    public function setAddTimeAttr($value){
        return time();
    }
    public function getPhoneAttr($value, $data){
        if($data['shop_phone_code'] == '' || empty($data['shop_phone_code'])){
            return $data['shop_phone'];
        }else{
            return $data['shop_phone_code'] . '-' . $data['shop_phone'];
        }
    }
    public function getWorkTimeAttr($value, $data){
       return $data['work_start_time'] . '-' .$data['work_end_time'];
    }
}
