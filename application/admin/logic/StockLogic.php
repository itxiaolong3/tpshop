<?php
/**
 * Created by PhpStorm.
 * User: lpy
 * Date: 2018/8/16
 * Time: 18:13
 */

namespace app\admin\logic;
use think\Page;
use think\AjaxPage;
use app\common\util\TpshopException;
class StockLogic
{
    private $stockChangeType = [
        0=>'订单出库',
        1=>'商品录入',
        2=>'退货入库',
        3=>'盘点更新'
    ];
    /**
     * 获取出入库日志
     * @return array
     */
    public function getStockList()
    {
        $map = array();
        $map['stock'] = array('gt',0); //默认获取入库日志
        $mtype = I('mtype');
        if(-1 == $mtype){
            $map['stock'] = array('lt',0);
        }
        $goods_name = I('goods_name');
        if($goods_name){
            $map['goods_name'] = array('like',"%$goods_name%");
        }
        $ctime = urldecode(I('ctime'));
        if($ctime){
            $gap = explode(' - ', $ctime);
            $map['ctime'] = array(array('gt',strtotime($gap[0])),array('lt',strtotime($gap[1])));
        }
        $model = M('stock_log');
        $count = $model->where($map)->count();
        $Page  = new Page($count,20);
        $show = $Page->show();
        $stock_list = $model->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('pager'=>$Page,'page'=>$show, 'stock_list'=>$stock_list, 'stockChangeType'=>$this->stockChangeType);

    }


    /**
     * 获取库存预警列表
     * @return array
     */
    public function getAjaxLowStockWarn()
    {
        $map = $this->get_map();
        $warning_storage = tpCache('basic.warning_storage') ?: 10; //获取库存预警值，默认为10
        $goods_model = M('Goods');
        $map['store_count'] = array('Lt', $warning_storage);
        //获取无规格的的商品库存小于预警值的数量
        $num1 = $goods_model->alias(a)
            ->whereNotExists('select `goods_id` from `tp_spec_goods_price` WHERE `goods_id`= a.goods_id')
            ->where($map)->count();
        //查询无规格的库存小于预警值的商品信息
        $sql = $goods_model->alias(a)
            ->whereNotExists('select `goods_id` from `tp_spec_goods_price` WHERE `goods_id`= a.goods_id')
            ->where($map)->field('goods_id, goods_sn, goods_name, cat_id, brand_id, store_count ,   NULL as key_name, NULL as item_id')
            ->fetchSql(true)->select();
        unset($map['store_count']);    //无需再判断goods中的store_count
        //获取有规格库存小于预警值的数量
        $num2 = $goods_model->alias(a)
            ->join('spec_goods_price b',"a.goods_id=b.goods_id AND b.store_count < $warning_storage")
            ->where($map)->count();
        $count = $num1 + $num2;
        $Page  = new  AjaxPage($count,20);
        $show = $Page->show();
        //合并查询有无规格的库存小于预警值的商品
        $goodsList = $goods_model->alias(a)
            ->join('spec_goods_price b',"a.goods_id=b.goods_id AND b.store_count < $warning_storage")
            ->field('a.goods_id, a.goods_sn, a.goods_name, a.cat_id, a.brand_id, b.store_count, b.key_name, b.item_id')
            ->union($sql)->where($map)->order('store_count asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $catList = M('goods_category')->field('id,name')->select();
        $brandList = M('brand')->field('id,name')->select();
        $catList = convert_arr_key($catList, 'id');
        $brandList= convert_arr_key($brandList, 'id');
        return array('pager'=>$Page, 'page'=>$show, 'catList'=>$catList, 'brand_list'=>$brandList, 'goodsList'=>$goodsList);
    }


    /**
     * 库存盘点（ajax获取库存列表）
     */
    public function getAjaxAlterStock()
    {
        $goods_model = M('Goods');
        $map = $this->get_map();
        //获取无规格的的商品数量
        $num1 = $goods_model->alias(a)
            ->whereNotExists('select `goods_id` from `tp_spec_goods_price` WHERE `goods_id`= a.goods_id')
            ->where($map)->count();
        //获取有规格数量
        $num2 = $goods_model->alias(a)
            ->join('spec_goods_price b',"a.goods_id=b.goods_id")
            ->where($map)->count();
        $count = $num1 + $num2;
        $Page  = new  AjaxPage($count,20);
        $show = $Page->show();
        //查询无规格的商品
        $sql = $goods_model->alias(a)
            ->whereNotExists('select `goods_id` from `tp_spec_goods_price` WHERE `goods_id`= a.goods_id')
            ->where($map)->field('goods_id, goods_sn, goods_name, cat_id, brand_id, store_count ,   NULL as key_name, NULL as item_id')
            ->fetchSql(true)->select();
        //合并查询有无规格商品
        $goodsList = $goods_model->alias(a)
            ->join('spec_goods_price b',"a.goods_id=b.goods_id")
            ->field('a.goods_id, a.goods_sn, a.goods_name, a.cat_id, a.brand_id, b.store_count, b.key_name, b.item_id')
            ->union($sql)->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $catList = M('goods_category')->field('id,name')->select();
        $brandList = M('brand')->field('id,name')->select();
        $catList = convert_arr_key($catList, 'id');
        $brandList= convert_arr_key($brandList, 'id');

        return array('pager'=>$Page, 'page'=>$show, 'catList'=>$catList, 'brand_list'=>$brandList, 'goodsList'=>$goodsList);
    }

    /**
     * 库存盘点（快速修改库存）
     */
    public function doChangeStockVal($admin_id)
    {
        $ids = I('ids');
        $data = I('data/d',''); //有值则为批量修改
        $num = I('num/d');
        if('' !== $data){
            $num = $data;
        }
        if(!stripos($ids, ',')){  //ids参数中必带有
           return array('status'=>0, 'msg'=>'修改失败');
        }

        $temp_arr = explode(',', $ids);
        $item_ids= array_filter($temp_arr,function($val){if(stripos($val, '_')) return true;});
        $goods_ids =array_filter(array_diff($temp_arr, $item_ids), function ($val){if(is_numeric($val)) return true;}); //保存无规格商品的goods_id  array
        $item_ids = array_map(function($val){return substr($val,5);}, $item_ids);//保存规格商品的item_id
        $item_ids = implode(',',$item_ids);

        //批量修改
        try{
            $goods_model = M('Goods');
            $stock_log1=$stock_log2 =array();
            $ctime = time();
            if($item_ids){ //对规格商品进行库存修改
                $spec_model =  M('SpecGoodsPrice');
                $spec_info = $spec_model->alias('a')
                    ->where('a.item_id','in',$item_ids)->join('Goods g', 'a.goods_id=g.goods_id')
                    ->field('a.goods_id, a.store_count, a.key_name, g.goods_name')
                    ->select();  //获取规格商品信息
                $stock_log1 = array_map(function($val) use ($num, $admin_id, $ctime){  //生成规格商品的stock_log记录
                    $val['stock'] = $num - $val['store_count']; //库存修改量
                    $val['muid'] = $admin_id;
                    $val['goods_spec'] = $val['key_name'];
                    $val['change_type'] = 3;  //操作类型：3库存盘点
                    $val['ctime'] = $ctime;
                    unset($val['key_name']);
                    unset($val['store_count']);
                    return $val;
                },$spec_info);
                $spec_model->where('item_id','in', $item_ids)->update(['store_count'=>$num]);  //更新spec_goods_price的store_count
                $goods_id = $spec_model->where('item_id','in',$item_ids)->field('goods_id')->select(); //找出规格商品对应的goods_id
                $goods_id = array_unique(get_arr_column($goods_id,'goods_id')); //找出需要修改的规格商品的goods_id
                $data = array();
                foreach ($goods_id as $value) //获取规格商品的总库存
                {
                    $count = $spec_model->where('goods_id',$value)->sum('store_count');
                    $data[] = array('store_count'=>$count,'goods_id'=>$value);
                }
                D('Goods')->saveAll($data); //更新规格商品的总库存
            }

            if($goods_ids){ //对无规格商品进行库存修改
                $goods_info = $goods_model->where('goods_id','in', implode(',',$goods_ids))->field('goods_id, store_count, goods_name')->select();//获取原先库存量
                $stock_log2 = array_map(function($val) use ($num, $admin_id, $ctime){  //生成无规格商品的stock_log记录
                    $val['stock'] = $num - $val['store_count']; //库存修改量
                    $val['muid'] = $admin_id;
                    $val['change_type'] = 3;  //操作类型：3库存盘点
                    $val['ctime'] = $ctime;
                    unset($val['store_count']);
                    return $val;
                },$goods_info);

                $goods_model->where('goods_id','in', implode(',',$goods_ids))->update(['store_count'=>$num]); //更新库存
            }
            //TODO stock_log表添加字段change_type `change_type` tinyint(2) NOT NULL COMMENT '更改操作类型  （默认）0订单出库 1商品录入 2退货入库 3盘点更改'

            //记录stock_log
            $stock_log = array_merge($stock_log1,$stock_log2);
            D('stock_log')->saveAll($stock_log);
        }catch (TpshopException $t){
            $error = $t->getErrorArr();
            return array('status'=>0 , 'msg'=>$error);
        }
        return array('status'=>1 , 'msg'=>'修改成功');

    }

    /**
     * 获取搜索条件  在ajaxLowStockWarn，ajaxAlterStock中使用
     */
    private function get_map(){
        $map = array();
        $intro = I('intro/s','');
        $brand_id = I('brand_id/d','');
        $is_on_sale = I('is_on_sale/d','');
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        $cat_id = I('cat_id/d','');
        if(!empty($intro))
        {
            $map[$intro] = array('EQ', 1);
        }
        if(!empty($brand_id)){
            $map['brand_id'] = array('EQ', $brand_id);
        }
        if('' !==$is_on_sale){
            $map['is_on_sale'] = array('EQ', $is_on_sale);
        }
        if(!empty($key_word ))
        {
            $map['goods_name|goods_sn'] = array('LIKE', "%$key_word%");
        }
        if(!empty($cat_id))
        {
            $grandson_ids = getCatGrandson($cat_id);
            $map['cat_id'] = array('IN',$grandson_ids);
        }
        return $map;
    }



}