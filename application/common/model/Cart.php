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
use think\Model;
use app\common\logic\PromGoodsLogic;
class Cart extends Model {
    //自定义初始化
    protected static function init()
    {
        //TODO:自定义的初始化
    }
    public function promGoods()
    {
        return $this->hasOne('PromGoods', 'id', 'prom_id')->cache(true,10);
    }

    public function goods()
    {
        return $this->hasOne('Goods', 'goods_id', 'goods_id')->cache(true,10)->field('goods_id,cat_id,store_count,is_on_sale,prom_type,prom_id,weight,original_img')->bind([
            'cat_id'	=> 'cat_id','store_count'=>'store_count','is_on_sale'=>'is_on_sale','weight'=>'weight'
        ]);
    }

    //能加入购物车的商品都是不是虚拟商品。
    public function getIsVirtualAttr($value, $data)
    {
        return 0;
    }


    public function getSpecKeyNameArrAttr($value, $data)
    {
        if ($data['spec_key_name']) {
            $specKeyNameArr = explode(' ', $data['spec_key_name']);
            return $specKeyNameArr;
        } else {
            return [];
        }
    }


    public function combinationCart()
    {
        return $this->hasMany('Cart', 'combination_group_id', 'id');
    }
    public function combination()
    {
        return $this->hasOne('combination', 'combination_id', 'prom_id');
    }
    public function combinationGoods()
    {
        return $this->hasMany('combination_goods', 'combination_id', 'prom_id')->order('is_master','desc');
    }

    /**
     * 商品优惠总额
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getGoodsFeeAttr($value, $data)
    {
        $goods_fee = round($data['goods_num'] * $data['member_goods_price'], 2);
        return $goods_fee;
    }
    /**
     * 商品总额
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getTotalFeeAttr($value, $data)
    {
        $total_fee = round($data['goods_num'] * $data['goods_price'], 2);
        return $total_fee;
    }
    /**
     * 商品总额优惠
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getCutFeeAttr($value, $data)
    {
        $cut_fee = $data['goods_num'] * ($data['goods_price'] - $data['member_goods_price']);
        return $cut_fee;
    }

    /**
     * 限购数量
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getLimitNumAttr($value, $data)
    {
        $spec_goods_price = null;
        $goods = Goods::get($data['goods_id'], '', 20);
        //有规格
        if ($data['spec_key']) {
            $spec_goods_price = SpecGoodsPrice::get(['goods_id'=>$data['goods_id'],'key' => $data['spec_key']]);
            if ($data['prom_type'] == 1) {
                $FlashSaleLogic = new FlashSaleLogic($goods, $spec_goods_price);
                $limitNum = $FlashSaleLogic->getUserFlashResidueGoodsNum($data['user_id']);
            } else if ($data['prom_type'] == 2) {
                $groupBuyLogic = new GroupBuyLogic($goods, $spec_goods_price);
                $limitNum = $groupBuyLogic->getPromotionSurplus();//团购剩余库存
            }else if ($data['prom_type'] == 3) {
                $promoGoodsLogic = new PromGoodsLogic($goods, $spec_goods_price);
                $limitNum = $promoGoodsLogic->getPromoGoodsResidueGoodsNum($data['user_id']);
            } else {
                $limitNum = $spec_goods_price['store_count'];
            }
        }else{
            //没有规格
            if ($data['prom_type'] == 1) {
                $FlashSaleLogic = new FlashSaleLogic($goods, null);
                $limitNum = $FlashSaleLogic->getUserFlashResidueGoodsNum($data['user_id']);
            } else if ($data['prom_type'] == 2) {
                $groupBuyLogic = new GroupBuyLogic($goods, null);
                $limitNum = $groupBuyLogic->getPromotionSurplus();//团购剩余库存
            }else if ($data['prom_type'] == 3) {
                $promoGoodsLogic = new PromGoodsLogic($goods, null);
                $limitNum = $promoGoodsLogic->getPromoGoodsResidueGoodsNum($data['user_id']);
            } else {
                $limitNum = $goods['store_count'];
            }
        }
        return $limitNum;
    }
}
