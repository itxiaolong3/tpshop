<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19
 * Time: 9:29
 */
namespace app\api\model;

class Goods extends BaseModel
{
    public function goodsList($page,$where){
        $where['is_on_sale'] = 1;
        $list = $this->where($where)->field("goods_id,goods_name,shop_price,original_img,sales_sum")->page($page, config('limit'))->select();
        $next = $this->where($where)->page($page + 1, config('limit'))->column('goods_id');
        $goods_list['list'] = [];
        if(!collection($list)->isEmpty()){
            $goods_list['list'] = collection($list)->toArray();
        }
        $next ? $goods_list['next_page'] = $page + 1 : $goods_list['end'] = true;
        return $goods_list;
    }

}