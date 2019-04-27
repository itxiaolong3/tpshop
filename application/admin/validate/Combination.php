<?php
namespace app\admin\validate;

use think\Validate;
use think\Db;

class Combination extends Validate
{
    // 验证规则
    protected $rule = [
        'title' => 'require|max:36',
        'desc' => 'require|max:255',
        'start_time' => 'require',
        'end_time' => 'require|checkEndTime',
        'combination_goods' => 'require|array|checkCombinationGoods',
    ];
    //错误信息
    protected $message = [
        'title.require' => '搭配购标题必须',
        'title.max' => '搭配购标题长度不得超过30字符',
        'desc.require' => '搭配购描述必须',
        'desc.max' => '搭配购描述长度不得超过255字符',
        'combination_goods.require' => '请选择搭配购商品',
        'combination_goods.array' => '参数错误',
        'start_time.require' => '请选择活动开始时间',
        'end_time.require' => '请选择活动截止时间',
    ];

    /**
     * 检查搭配购商品是否符合标准
     * @param $value |验证数据
     * @param $rule |验证规则
     * @param $data |全部数据
     * @return bool|string
     */
    protected function checkCombinationGoods($value, $rule, $data)
    {
        $count = count($data['combination_goods']);
        if($count <= 1){
            return "请选择两件以上(含两件)商品";
        }
        $where['title']=$data['title'];
        if($data['combination_id']){
            $where['combination_id']=array('<>',$data['combination_id']);
        }
        if(Db::name('combination')->where($where)->find()){
            return "存在相同的搭配购标题为".$data['title'];
        }
        $is_master_count = 0;
        $is_master_goods = [];
        foreach($data['combination_goods'] as $combination_goods){
            if($combination_goods['is_master'] == 1){
                $is_master_count++;
                $is_master_goods['a.goods_id'] = $combination_goods['goods_id'];
                $is_master_goods['b.item_id'] = $combination_goods['item_id'];
            }
            if (!array_key_exists('price', $combination_goods)) {
                return "参数错误";
            }
            if($combination_goods['price'] <= 0){
                return "优惠价格不能小于等于0";
            }
            if(!array_key_exists('item_id',$combination_goods) && !array_key_exists('goods_id',$combination_goods)){
                return "参数错误";
            }
            $goods = Db::name('goods')->where('goods_id', $combination_goods['goods_id'])->find();
            if(empty($goods)){
                return "选择的商品不存在";
            }
            if($combination_goods['item_id']){
                $spec_goods_price = Db::name('spec_goods_price')->where('item_id', $combination_goods['item_id'])->find();
                if(empty($spec_goods_price)){
                    return "选择的商品(" . $goods['goods_name'] . ")规格不存在";
                }
                if($combination_goods['price'] > $spec_goods_price['price']){
                    return $goods['goods_name'].'规格:'.$spec_goods_price['key_name'].'优惠价格'.$combination_goods['price'].'￥大于商城价格'.$spec_goods_price['price'].'￥';
                }
            }else{
                if($combination_goods['price'] > $goods['shop_price']){
                    return $goods['goods_name'].'优惠价格'.$combination_goods['price'].'￥大于商城价格'.$goods['shop_price'].'￥';
                }
            }
        }
        if($is_master_count == 0){
            return "每个组合套餐，必须设置一个主商品";
        }
        if($is_master_count > 1){
            return "每个组合套餐，只能设置一个主商品";
        }
        if($data['combination_id'] > 0){
            $is_master_goods['a.combination_id'] = ['neq',$data['combination_id']];
        }
        $combination_goods_list = Db::name('combination_goods')->alias('a')->join('__COMBINATION_GOODS__ b', 'b.combination_id = a.combination_id')->field('b.*')->where($is_master_goods)->select();
        if(!empty($combination_goods_list)){
            $combination_goods_count = count($data['combination_goods']);
            for ($i = ($combination_goods_count - 1); $i >= 0; $i--) {
                $search_goods = $data['combination_goods'][$i];
                for ($j = ($i - 1); $j >= 0; $j--) {
                    if($search_goods['item_id'] > 0){
                        if($search_goods['item_id'] == $data['combination_goods'][$j]['item_id']){
                            unset($data['combination_goods'][$i]);
                            break;
                        }
                    }else{
                        if($search_goods['goods_id'] == $data['combination_goods'][$j]['goods_id']){
                            unset($data['combination_goods'][$i]);
                            break;
                        }
                    }
                }
            }
            $combination_goods_count = count($data['combination_goods']);
            $combination_goods_list_count = count($combination_goods_list);
            if($combination_goods_count == $combination_goods_list_count){
                $have_combination_goods_count = 0;
                foreach($combination_goods_list as $goods_item){
                    foreach($data['combination_goods'] as $combination_item){
                        if($goods_item['goods_id'] == $combination_item['goods_id'] && $goods_item['item_id'] == $combination_item['item_id']){
                            $have_combination_goods_count++;
                        }
                    }
                }
                if($have_combination_goods_count == $combination_goods_count){
                    $have_combination = Db::name('combination')->where('combination_id', $combination_goods_list[0]['combination_id'])->find();
                    return "存在相同的搭配购，搭配购标题为".$have_combination['title'];
                }
            }
        }

        return true;
    }
    /**
     * 检查活动时间
     * @param $value |验证数据
     * @param $rule |验证规则
     * @param $data |全部数据
     * @return bool|string
     */
    protected function checkEndTime($value, $rule, $data)
    {
        $start_time = strtotime($data['start_time']);
        $end_time = strtotime($data['end_time']);
        if ($start_time >= $end_time) {
            return '您输入了一个无效的时间，活动结束时间不能早于或等于活动开始时间！';
        }
        return true;
    }

}