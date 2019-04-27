<?php
namespace app\admin\validate;
use think\Validate;
use think\Db;
class Team extends Validate
{
    // 验证规则
    protected $rule = [
        'team_id'                   =>'checkTeamId',
        'act_name'                  =>'require|max:50',
        'time_limit'                =>'require|number|gt:0',
        'needer'                    =>'require|number|gt:1|checkNeed',
        'goods_id'                  =>'require',
        'bonus'                     =>'checkBonus',
        'stock_limit'               =>'number|checkStockLimit',
        'buy_limit'                 =>'number|egt:0|lt:10000',
        'virtual_num'               =>'number',
        'share_title'               =>'max:50',
        'share_desc'                =>'max:200',
        'share_img'                 =>'require',
        'team_goods_item'           =>'require|checkTeamGoodsItem'
    ];
    //错误信息
    protected $message  = [
        'act_name.require'          => '拼团标题必填',
        'act_name.max'              => '拼团标题长度不得超过50字符',
        'time_limit.require'        => '成团有效期必填',
        'time_limit.number'         => '成团有效期格式错误',
        'time_limit.gt'             => '成团有效期必须大于0',
        'needer.require'            => '需要成团人数必须',
        'needer.gt'                 => '需要成团人数必须大于1人',
        'goods_id.require'          => '请选择参与拼团的商品',
        'bonus.checkBonus'          => '团长佣金格式错误',
        'stock_limit.number'        => '抽奖限量格式错误',
        'buy_limit.number'          => '购买限制数格式错误',
        'buy_limit.egt'             => '购买限制数范围0~10000',
        'buy_limit.lt'              => '购买限制数范围0~10000',
        'virtual_num.number'        => '虚拟销售基数格式错误',
        'share_title.max'           => '分享标题长度不得超过50字符',
        'share_desc.max'            => '分享描述长度不得超过200字符',
        'share_img.require'         => '分享图片必须上传',
        'team_goods_item.require'   => '请选择参与拼团的商品',
    ];
    protected function checkTeamGoodsItem($value, $rule ,$data){
        $regex = '([1-9]\d*(\.\d*[1-9])?)|(0\.\d*[1-9])';
        foreach($value as $item){
            if(!array_key_exists('team_price', $item)){
                return '拼团价格必须';
            }
            if(!$this->regex($item['team_price'], $regex)){
                return '拼团价格格式错误';
            }
            if($item['item_id'] > 0){
                //商品规格
                $spec_goods_price = Db::name("spec_goods_price")->field('key_name,price')->where(['item_id'=>$item['item_id']])->find();
                if($item['team_price'] > $spec_goods_price['price']){
                    return $spec_goods_price['key_name'].'拼团价格必须低于单买价格';
                }
            }else{
                $goods = Db::name('goods')->field('goods_name,shop_price')->where('goods_id',$item['goods_id'])->find();
                if($item['team_price'] > $goods['shop_price']){
                    return $goods['goods_name'].'拼团价格必须低于单买价格';
                }
            }
        }
        return true;
    }

    /**
     * 检查拼团活动成功一次最少需要库存，假定一人
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkNeed($value, $rule ,$data)
    {
        if($data['team_goods_item'][0]['item_id'] > 0){
            //商品规格
            $item_ids = get_arr_column($data['team_goods_item'],'item_id');
            $store_count = Db::name("spec_goods_price")->where('item_id','IN', $item_ids)->sum('store_count');
        }else{
            $store_count = Db::name('goods')->where('goods_id',$data['goods_id'])->getField('store_count');
        }
        if($data['buy_limit'] > 0){
            $needStoreCount = $data['buy_limit'] * $value;
            if($store_count < $needStoreCount){
                return '单次拼团若每人购买满（限购)'.$data['buy_limit'].'件'.'成团条件'.$value.'人'.',则最少需要库存'.$needStoreCount;
            }else{
                return true;
            }
        }else{
            $needStoreCount = $value;
            if($store_count < $needStoreCount){
                return '单次拼团若每人购买一件,成团条件'.$value.'人'.',则最少需要库存'.$needStoreCount;
            }else{
                return true;
            }
        }
    }

    /**
     * 该活动是否可以编辑
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkTeamId($value, $rule ,$data)
    {
        $isHaveOrder = Db::name('order_goods')->where(['prom_type' => 6, 'prom_id' => $value])->find();
        if($isHaveOrder){
            return '该活动已有用户下单购买不能编辑';
        }else{
            return true;
        }
    }

    protected function checkStockLimit($value, $rule ,$data){
        if($data['team_type'] != 2){
            return true;
        }
        if($value <= 0){
            return '抽奖限量必须大于0';
        }
        if($data['item_id'] > 0){
            //商品规格
            $store_count = Db::name("spec_goods_price")->where(['item_id'=>$data['item_id']])->getField('store_count');
        }else{
            $store_count = Db::name('goods')->where('goods_id',$data['goods_id'])->getField('store_count');
        }
        if($store_count < $value){
            return '商品库存只有'.$store_count.'件,不能满足'.$value.'人购买';
        }else{
            return true;
        }
    }
    protected function checkBonus($value, $rule ,$data){
        $regex = '^(?=.*[1-9])\d+(\.\d{1,2})?$';
        if($data['team_type'] == 1 && !$this->regex($data['bonus'], $regex)){
            return false;
        }else{
            return true;
        }

    }

}