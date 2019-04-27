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
use think\Model;
class FlashSale extends Model {
    //自定义初始化
    protected static function init()
    {
        //TODO:自定义的初始化
    }

    public function specGoodsPrice()
    {
        return $this->hasOne('SpecGoodsPrice','item_id','item_id');
    }

    public function goods()
    {
        return $this->hasOne('goods','goods_id','goods_id');
    }
    //剩余抢购库存
    public function getStoreCountAttr($value, $data)
    {
        return $data['goods_num'] - $data['buy_num'];
    }  
    //状态描述
    public function getStatusDescAttr($value, $data)
    {
        if($data['is_end'] == 1){
            return '已结束';
        }else{
            if($data['buy_num'] >= $data['goods_num']){
                return '已售罄';
            }else{
                if($data['start_time'] > time()){
                    return '未开始';
                }else if ($data['start_time'] < time() && $data['end_time'] > time()) {
                    return '进行中';
                }else{
                    return '已过期';
                }
            }
        }
    }

    /**
     * 是否编辑
     * @param $value
     * @param $data
     * @return int
     */
    public function getIsEditAttr($value, $data)
    {
        if ($data['is_end'] == 1 || $data['start_time'] < time()){
            return 0;
        }
        return 1;
    }

    /**
     * 获取商品的原始价格
     */
    public function getShopPriceAttr($value, $data){
        if($data['item_id']>0){
            //获取规格价格
            $price = $this->specGoodsPrice->price;
            unset($this->specGoodsPrice);
            return $price;
        }else{
            return $value;
        }

    }

    /**
     * 获取抢购百分比
     */
    public function getPercentAttr($value,$data){
        return  round($data['buy_num'] / $data['goods_num'],2) * 100;
    }

}
