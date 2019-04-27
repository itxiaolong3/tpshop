<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 18:26
 */

namespace app\common\model;


use think\Model;

class PromGoodsItem extends Model
{

    public function specGoodsPrice(){
        return $this->hasOne('specGoodsPrice','item_id','item_id');
    }
    public function goods(){
        return $this->hasOne('goods','goods_id','goods_id');
    }
}