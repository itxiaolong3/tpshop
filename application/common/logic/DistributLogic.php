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
 * 
 * TPshop 公共逻辑类  将放到Application\Common\Logic\   由于很多模块公用 将不在放到某个单独模下面
 */

namespace app\common\logic;

use think\Model;
//use think\Page;

/**
 * 分销逻辑层
 * Class CatsLogic
 * @package Home\Logic
 */
class DistributLogic //extends Model
{
     public function hello(){
        echo 'function hello(){'; 
     }
     
     /**
      * 生成分销记录
      */
     public function rebate_log($order)
     {       
         $user = M('users')->where("user_id", $order['user_id'])->find();
                           
         $pattern = tpCache('distribut.pattern'); // 分销模式  
         $first_rate = tpCache('distribut.first_rate'); // 一级比例
         $second_rate = tpCache('distribut.second_rate'); // 二级比例  
         $third_rate = tpCache('distribut.third_rate'); // 三级比例  
         
         //按照商品分成 每件商品的佣金拿出来
         if($pattern  == 0) 
         {
            // 获取所有商品分类 
             $cat_list =  M('goods_category')->getField('id,parent_id,commission_rate');             
             $order_goods = M('order_goods')->master()->where("order_id", $order['order_id'])->select(); // 订单所有商品
             $commission = 0;
             foreach($order_goods as $k => $v)
             {
                    $tmp_commission = 0;
                    $goods = M('goods')->where("goods_id", $v['goods_id'])->find(); // 单个商品的佣金
                    $tmp_commission = $goods['commission'];
                    // 如果商品没有设置分佣,则找他所属分类看是否设置分佣
                    if($tmp_commission == 0)
                    {
                       $commission_rate = $cat_list[$goods['cat_id']]['commission_rate']; // 查看分类分佣比例
                       
                       if($commission_rate == 0) // 如果它没有设置分类则找他老爸分类看看是否设置分佣
                       {
                           // 找出他老爸
                           $parent_id = $cat_list[$goods['cat_id']]['parent_id'];
                           $commission_rate = $cat_list[$parent_id]['commission_rate']; // 查看 老爸分类分佣比例
                       } 
                       if($commission_rate == 0) // 如果它老爸没有设置分类则找他爷爷分类看看是否设置分佣
                       {
                           // 找出他爷爷
                           $grandfather_id = $cat_list[$parent_id]['parent_id'];
                           $commission_rate = $cat_list[$grandfather_id]['commission_rate']; // 查看爷爷分类分佣比例
                       } 
                       
                       $tmp_commission = $v['member_goods_price'] * ($commission_rate / 100); // 这个商品的分佣 =  商品价 诚意商品分类设置的分佣比例
                     }
                                        
                    $tmp_commission = $tmp_commission  * $v['goods_num']; // 单个商品的分佣乘以购买数量                    
                    $commission += $tmp_commission; // 所有商品的累积佣金
             }                        
         }else{
             $order_rate = tpCache('distribut.order_rate'); // 订单分成比例  
             $commission = $order['goods_price'] * ($order_rate / 100); // 订单的商品总额 乘以 订单分成比例
         }
                  
         // 如果这笔订单没有分销金额
         if($commission == 0)
             return false;

            $first_money = $commission * ($first_rate / 100); // 一级赚到的钱
            $second_money = $commission * ($second_rate / 100); // 二级赚到的钱
            $thirdmoney = $commission * ($third_rate / 100); // 三级赚到的钱
                  
          // 一级 分销商赚 的钱. 小于一分钱的 不存储
         if($user['first_leader'] > 0 && $first_money > 0.01)
         {
            $data = array(             
                'user_id' =>$user['first_leader'],
                'buy_user_id'=>$user['user_id'],
                'nickname'=>$user['nickname'],
                'order_sn' => $order['order_sn'],
                'order_id' => $order['order_id'],
                'goods_price' => $order['goods_price'],
                'money' => $first_money,
                'level' => 1,
                'remark'    => "订单佣金",
                'create_time' => time(),
                'type' => 1
            );                  
            M('rebate_log')->add($data);
         }
          // 二级 分销商赚 的钱.
         if($user['second_leader'] > 0 && $second_money > 0.01)
         {         
            $data = array(
                'user_id' =>$user['second_leader'],
                'buy_user_id'=>$user['user_id'],
                'nickname'=>$user['nickname'],
                'order_sn' => $order['order_sn'],
                'order_id' => $order['order_id'],
                'goods_price' => $order['goods_price'],
                'money' => $second_money,
                'level' => 2,
                'remark'    => "订单佣金",
                'create_time' => time(),
                'type' => 1
            );                  
            M('rebate_log')->add($data);
         }
          // 三级 分销商赚 的钱.
         if($user['third_leader'] > 0 && $thirdmoney > 0.01)
         {                  
            $data = array(
                'user_id' =>$user['third_leader'],
                'buy_user_id'=>$user['user_id'],
                'nickname'=>$user['nickname'],
                'order_sn' => $order['order_sn'],
                'order_id' => $order['order_id'],
                'goods_price' => $order['goods_price'],
                'money' =>$thirdmoney,
                'level' => 3,
                'remark'    => "订单佣金",
                'create_time' => time(),
                'type' => 1
            );                  
            M('rebate_log')->add($data);
         }
         M('order')->where("order_id", $order['order_id'])->save(array("is_distribut"=>1));  //修改订单为已经分成
     }

    /**
     * 生成分销记录
     */
    public function rebate_wyg_log($order)
    {
        $user = M('users')->where("user_id", $order['user_id'])->find();
        //获取上级的折扣率
        $first_user = M('users')->where("user_id", $user['first_leader'])->find();
        //按照商品分成 每件商品的佣金拿出来
        if($first_user['level'] > $user['level'])
        {
            $order_goods = M('order_goods')->master()->where("order_id", $order['order_id'])->select(); // 订单所有商品
            $commission = 0;
            foreach($order_goods as $k => $v)
            {
                $shop_price = $v['shop_price'];
                $tmp_price = $shop_price  * $v['goods_num'] * $user['discount']; // 单个商品的折扣乘以购买数量
                $first_price = $shop_price  * $v['goods_num'] * $first_user['discount']; // 单个商品的折扣乘以购买数量
                $tmp_commission = $first_price - $tmp_price;// 单个商品的差价乘以购买数量
                $commission += $tmp_commission; // 所有商品的累积佣金
            }
        }
        // 如果这笔订单没有分销金额
        if($commission == 0)
            return false;

        $first_money = $commission; // 一级赚到的钱

        // 一级 分销商赚 的钱. 小于一分钱的 不存储
        if($user['first_leader'] > 0 && $first_money > 0.01)
        {
            $data = array(
                'user_id' =>$user['first_leader'],
                'buy_user_id'=>$user['user_id'],
                'nickname'=>$user['nickname'],
                'order_sn' => $order['order_sn'],
                'order_id' => $order['order_id'],
                'goods_price' => $order['goods_price'],
                'money' => $first_money,
                'level' => 1,
                'create_time' => time(),
                'type' => 1
            );
            M('rebate_log')->add($data);
        }
        // 二级 分销商赚 的钱.
//        if($user['second_leader'] > 0 && $second_money > 0.01)
//        {
//            $data = array(
//                'user_id' =>$user['second_leader'],
//                'buy_user_id'=>$user['user_id'],
//                'nickname'=>$user['nickname'],
//                'order_sn' => $order['order_sn'],
//                'order_id' => $order['order_id'],
//                'goods_price' => $order['goods_price'],
//                'money' => $second_money,
//                'level' => 2,
//                'create_time' => time(),
//            );
//            M('rebate_log')->add($data);
//        }

        M('order')->where("order_id", $order['order_id'])->save(array("is_distribut"=>1));  //修改订单为已经分成
    }
     
     /**
      * 自动分成 符合条件的 分成记录
      */
     function auto_confirm(){
         
         $switch = tpCache('distribut.switch');
         if($switch == 0)
             return false;
         
         $today_time = time();
         $distribut_date = tpCache('distribut.date');
         $distribut_time = $distribut_date * (60 * 60 * 24); // 计算天数 时间戳
         $rebate_log_arr = M('rebate_log')->where("status = 2 and ($today_time - confirm) >  $distribut_time")->select();
         foreach ($rebate_log_arr as $key => $val)
         {
             accountLog($val['user_id'], $val['money'], 0,"订单:{$val['order_sn']}分佣",$val['money']);             
             $val['status'] = 3;
             $val['confirm_time'] = $today_time;
             $val['remark'] = $val['remark']."满{$distribut_date}天,程序自动分成.";
             M("rebate_log")->where("id", $val['id'])->save($val);
         }
     }
}