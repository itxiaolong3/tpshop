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
 * Author: yhj
 * Date: 2018-6-27
 */
namespace app\common\model;
use think\Model;
use think\Db;
class MessageActivity extends Model
{
    public function userMessage()
    {
        return $this->hasMany('userMessage', 'message_id', 'message_id');
    }
    public function flashSale()
    {
        return $this->hasOne('flashSale', 'id', 'prom_id');
    }
    public function teamActivity()
    {
        return $this->hasOne('teamActivity', 'team_id', 'prom_id');
    }    
    public function getSendTimeTextAttr($value, $data)
    {

        return time_to_str($data['send_time']);
    }
    
    public function getFinishedAttr($value, $data)
    {
        if (time() > $data['end_time']  && $data['mmt_code'] != 'team_activity' ) {
            return true;
        }
        $return_flag = false;
        switch ($data['mmt_code']) {
            case 'flash_sale_activity':
                // 抢购
                $return_arr = Db::name('flash_sale')->field('end_time,is_end')->where('id', $data['prom_id'])->find();
                if (time() > $return_arr['end_time'] or $return_arr['is_end'] == 1) {
                    $return_flag =  true;
                }
                break;
            case 'group_buy_activity':
                // 团购
                $return_arr = Db::name('group_buy')->field('end_time,is_end')->where('id', $data['prom_id'])->find();
                if (time() > $return_arr['end_time'] or $return_arr['is_end'] == 1) {
                    $return_flag = true;
                }
                break;
            case 'prom_goods_activity':
                // 优惠促销
                $return_arr = Db::name('prom_goods')->field('is_end,end_time')->where('id', $data['prom_id'])->find();
                if (time() > $return_arr['end_time'] or $return_arr['is_end'] == 1) {
                    $return_flag = true;
                }
                break;
            case 'prom_order_activity':
                // 订单促销
                $return_arr = Db::name('prom_order')->field('is_close,end_time')->where('id', $data['prom_id'])->find();
                if (time() > $return_arr['end_time'] or $return_arr['is_close'] == 1) {
                    $return_flag = true;
                }
                break;
            case 'combination_activity':
                // 搭配购 主商品
                $return_arr = Db::name('combination')->field('is_on_sale,end_time')->where('combination_id', $data['prom_id'])->find();
                if (time() > $return_arr['end_time'] or $return_arr['is_on_sale'] == 0) {
                    $return_flag = true;
                }
                break;
            case 'team_activity':
                // 拼团
                $return_arr = Db::name('team_activity')->field('status,is_lottery')->where('team_id', $data['prom_id'])->find();
                if (1 == $return_arr['is_lottery'] or $return_arr['status'] == 0) {
                    $return_flag = true;
                }
                break;
            default:
                $return_flag = false;
                break;
        }
        return $return_flag;
    }
    public function getStartTimeAttr($value, $data)
    {
        switch ($data['mmt_code']) {
            case 'flash_sale_activity':
                // 抢购
                $return_arr = Db::name('flash_sale')->field('start_time')->where('id', $data['prom_id'])->find();

                break;
            case 'group_buy_activity':
                // 团购
                $return_arr = Db::name('group_buy')->field('start_time')->where('id', $data['prom_id'])->find();
                break;
            case 'prom_goods_activity':
                // 优惠促销
                $return_arr = Db::name('prom_goods')->field('start_time')->where('id', $data['prom_id'])->find();

                break;
            case 'prom_order_activity':
                // 订单促销
                $return_arr = Db::name('prom_order')->field('start_time')->where('id', $data['prom_id'])->find();

                break;
            case 'combination_activity':
                // 搭配购 主商品
                $return_arr = Db::name('combination')->field('start_time')->where('combination_id', $data['prom_id'])->find();

                break;
            case 'team_activity':
                // 拼团
                $return_flag = false;
                break;
            default:
                $return_flag = false;
                break;
        }
        if (isset($return_arr) && $return_arr['start_time'] > time()) {
            $return_flag =  date("Y-m-d H:i:s", $return_arr['start_time']);
        }

        return $return_flag;
    }
    public function getGoodsIdAttr($value, $data)
    {

        switch ($data['mmt_code']) {
            case 'flash_sale_activity':
                // 抢购
                $return_arr = Db::name('flash_sale')->field('goods_id,item_id')->where('id', $data['prom_id'])->find(); 
                break;
            case 'group_buy_activity':
                // 团购
                $return_arr = Db::name('group_buy')->field('goods_id,item_id')->where('id', $data['prom_id'])->find(); 
                break;
            case 'prom_goods_activity':
            case 'prom_order_activity':
                // 优惠促销 列表 订单促销
                $return_arr = ['goods_id'=>0, 'item_id'=>0];
                break;
            case 'combination_activity':
                // 搭配购 主商品
                $goods_id = Db::name('combination_goods')->where(['combination_id' => $data['prom_id'], 'is_master' => 1])->value('goods_id'); 
                $return_arr = ['goods_id'=>$goods_id, 'item_id'=>0];
                break;
            case 'team_activity':
                // 拼团
                $return_arr = Db::name('team_activity')->where('team_id', $data['prom_id'])->find(); 
                break;
            default:
                $return_arr = ['goods_id'=>0, 'item_id'=>0];
                break;
        }

        return $return_arr;
    }
    public function getHomeUrlAttr($value, $data)
    {
        switch ($data['mmt_code']) {
            case 'flash_sale_activity':
                // 抢购
                $flash_sale = Db::name('flash_sale')->where('id', $data['prom_id'])->find(); 
                $uri = U("Home/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
                break;
            case 'group_buy_activity':
                // 团购
                $flash_sale = Db::name('group_buy')->where('id', $data['prom_id'])->find(); 
                $uri = U("Home/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
                break;
            case 'prom_goods_activity':
            case 'prom_order_activity':
                // 优惠促销 列表
                $uri = U("Home/Activity/promoteList");
                break;
            case 'combination_activity':
                // 搭配购 主商品
                $goods_id = Db::name('combination_goods')->where(['combination_id' => $data['prom_id'], 'is_master' => 1])->value('goods_id'); 
                $uri = U("Home/Goods/goodsInfo", ['id' => $goods_id]);
                break;
            case 'team_activity':
                // 拼团 只有手机有 Mobile/Goods/goodsInfo/id/309/item_id/0
                $flash_sale = Db::name('team_activity')->where('team_id', $data['prom_id'])->find(); 
                $uri = U("Mobile/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
                break;
            default:
                $uri = '';
                break;
        }

        return $uri;
    } 

    public function getMobileUrlAttr($value, $data)
    {
        if ($data['mmt_code'] == 'flash_sale_activity') {
            // 抢购
            $flash_sale = Db::name('flash_sale')->where('id', $data['prom_id'])->find(); 
            $uri = U("Mobile/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
        } elseif ($data['mmt_code'] == 'group_buy_activity'){
            // 团购
            $flash_sale = Db::name('group_buy')->where('id', $data['prom_id'])->find();
            $uri = U("Mobile/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
        } elseif ($data['mmt_code'] == 'prom_goods_activity' or $data['mmt_code'] == 'prom_order_activity'){
            // 优惠促销 列表
            $uri = U("Mobile/Activity/promote_goods");
        } elseif ($data['mmt_code'] == 'combination_activity'){
            // 搭配购 主商品
            $goods_id = Db::name('combination_goods')->where(['combination_id' => $data['prom_id'], 'is_master' => 1])->value('goods_id'); 
            $uri = U("Mobile/Goods/goodsInfo", ['id' => $goods_id]);
        } elseif ($data['mmt_code'] == 'team_activity'){
            // 拼团
            $flash_sale = Db::name('team_activity')->where('team_id', $data['prom_id'])->find();
            $uri = U("Mobile/Goods/goodsInfo", ['id' => $flash_sale['goods_id'], 'item_id' => $flash_sale['item_id']]);
        } else {
            $uri = '';
        }
        return $uri;
    }
    public function getOrderTextAttr($value, $data)
    {
        return '';
    }
}
