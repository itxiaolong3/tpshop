<?php
namespace app\admin\validate;

use think\Validate;
use think\Db;

class Goods extends Validate
{

    // 验证规则
    protected $rule = [
        'goods_id' => 'checkGoodsId',
        'goods_name' => 'require|min:3|max:150|unique:goods',
        'cat_id' => 'number|gt:0',
        'goods_sn' => 'unique:goods|max:20',
        'shop_price' => ['require', 'regex' => '([1-9]\d*(\.\d*[1-9])?)|(0\.\d*[1-9])'],
        'market_price' => 'require|regex:\d{1,10}(\.\d{1,2})?$|checkMarketPrice',
        'weight' => 'regex:\d{1,10}(\.\d{1,2})?$',
        'give_integral' => 'regex:^\d+$',
//        'is_virtual' => 'checkVirtualIndate',
        'exchange_integral' => 'checkExchangeIntegral',
        'is_free_shipping' => 'require|checkShipping',
        'commission' => 'checkCommission',
        'ladder_amount' => 'checkLadderAmount',
        'ladder_price' => 'checkLadderPrice',
        'virtual_limit' => 'checkVirtualLimit',
    ];
    //错误信息
    protected $message = [
        'goods_name.require' => '商品名称必填',
        'goods_name.min' => '名称长度至少3个字符',
        'goods_name.max' => '名称长度至多50个汉字',
        'goods_name.unique' => '商品名称重复',
        'cat_id.number' => '商品分类必须填写',
        'cat_id.gt' => '商品分类必须选择',
        'goods_sn.unique' => '商品货号重复',
        'goods_sn.max' => '商品货号超过长度限制',
        'goods_num.checkGoodsNum' => '抢购数量不能大于库存数量',
        'shop_price.require' => '本店售价必填',
        'shop_price.regex' => '本店售价格式不对',
        'market_price.require' => '市场价格必填',
        'market_price.regex' => '市场价格式不对',
        'market_price.checkMarketPrice' => '市场价不得小于本店价',
        'weight.regex' => '重量格式不对',
        'give_integral.regex' => '赠送积分必须是正整数',
        'exchange_integral.checkExchangeIntegral' => '积分抵扣金额不能超过商品总额',
        'is_virtual.checkVirtualIndate' => '虚拟商品有效期不得小于当前时间11111111',
        'is_free_shipping.require' => '请选择商品是否包邮',
        'virtual_limit.checkVirtualLimit' => '虚拟商品购买上限1~10之间的数字',
    ];

    //检查阶梯价格中的库存
    protected function checkLadderAmount($value, $rule, $data)
    {
        if(min($value) != '' && min($value) <= 0){
            return  '您没有输入有效的库存阶梯！';
        }
        return true;
    }

    //检查阶梯价格中的价格
    protected function checkLadderPrice($value, $rule, $data)
    {
        if(max($value) >= $data['shop_price']){
            return '价格阶梯最大金额不能大于商品原价！';
        }
        if(min($value) != '' && min($value) <= 0){
            return '您没有输入有效的价格阶梯！';
        }
        return true;
    }
    // 检查积分兑换
    protected function checkExchangeIntegral($value, $rule, $data)
    {
        if ($value > 0) {
            $goods = Db::name('goods')->where('goods_id', $data['goods_id'])->find();
            if (!empty($goods)) {
                if ($goods['prom_type'] > 0) {
                    return '该商品参与了其他活动。设置兑换积分无效，请设置为零';
                }
            }
        }
        //积分规则修改后的逻辑
        $point_rate_value = tpCache('integral.point_rate');
        //$point_rate_value = tpCache('shopping.point_rate');
        if ($data['item']) {
            $count = count($data['item']);
            $item_arr = array_values($data['item']);
            $minPrice = $item_arr[0]['price'];
            for ($i = 0; $i < ($count - 1); $i++) {
                if ($item_arr[$i + 1]['price'] < $minPrice) {
                    $minPrice = $item_arr[$i + 1]['price'];
                }
            }
            $goods_price = $minPrice;
        } else {
            $goods_price = $data['shop_price'];
        }

        $point_rate_value = empty($point_rate_value) ? 0 : $point_rate_value;
        if ($value > ($goods_price * $point_rate_value)) {
            return '积分抵扣金额不能超过商品总额';
        } else {
            return true;
        }
    }

    //检查是否有商品规格参加活动，若有则不能编辑商品
    protected function checkGoodsId($value)
    {
        $spec_goods_price = Db::name('spec_goods_price')->where('goods_id', $value)->where('prom_type', 'gt', 0)->find();
        if ($spec_goods_price) {
            return '商品规格为' . $spec_goods_price['key_name'] . '正在参与活动，不能编辑该商品信息';
        }
        $goods = Db::name('goods')->where('goods_id', $value)->find();
//        if ($goods['prom_type'] > 0) {
//            return '该商品正在参与活动，不能编辑该商品信息';
//        }
        return true;
    }

    //检查市场价
    protected function checkMarketPrice($value, $rule, $data)
    {
        if ($value < $data['shop_price']) {
            return false;
        } else {
            return true;
        }
    }
    protected function checkVirtualLimit($value, $rule, $data)
    {
        if ($data['is_virtual'] == 1){
            if($value > 0 && $value < 11){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    //检查虚拟商品有效时间
    protected function  checkVirtualIndate($value, $rule, $data)
    {
        $virtualIndate = strtotime($data['virtual_indate']);
        if ($value == 1 && time() > $virtualIndate) {
            return false;
        } else {
            return true;
        }
    }

    protected function checkShipping($value, $rule, $data)
    {
        if ($value == 0 && empty($data['template_id'])) {
            return '请选择运费模板';
        } else {
            return true;
        }
    }
    protected function checkCommission($value, $rule, $data)
    {
        if ($value >= $data['shop_price']) {
            return '商品分销的分成金额不能大于等于本店售价金额';
        } else {
            return true;
        }
    }
}