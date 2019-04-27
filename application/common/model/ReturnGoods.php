<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25
 * Time: 14:26
 */

namespace app\common\model;
use think\Model;

class ReturnGoods extends Model {


    public function getGoodsNameAttr($value, $data){
        return db('order_goods')->where(['goods_id'=>$data['goods_id'],'order_id'=>$data['order_id']])->value('goods_name');

    }

}