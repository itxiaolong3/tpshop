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
use think\Db;
use think\Model;
class OrderGoods extends Model {

    protected $table='';

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

    public function goods()
    {
        return $this->hasOne('goods','goods_id','goods_id');
    }
    public function getMemberGoodsPriceAttr($value, $data){
        if($data['prom_type'] == 4){
            return $data['goods_price'];
        }else{
            return $value;
        }
    }

    public function getTotalMemberGoodsPriceAttr($value, $data){
        if($data['prom_type'] == 4){
            return $data['goods_num'] * $data['goods_price'];
        }else{
            return $data['goods_num'] * $data['member_goods_price'];
        }
    }

    public function returnGoods()
    {
        return $this->hasOne('ReturnGoods', 'rec_id', 'rec_id');
    }
}
