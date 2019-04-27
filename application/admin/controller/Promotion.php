<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃
 * 专题管理
 * Date: 2016-03-09
 */

namespace app\admin\controller;

use app\common\model\FlashSale;
use app\common\model\GoodsActivity;
use app\common\model\GroupBuy;
use app\admin\logic\GoodsLogic;
use app\common\model\Goods;
use app\common\model\PromGoods;
use app\common\model\PromGoodsItem;
use app\common\model\PromOrder;
use app\common\logic\MessageTemplateLogic;
use app\common\logic\MessageFactory;
use think\AjaxPage;
use think\Page;
use think\Loader;
use think\Db;

class Promotion extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 商品活动列表
     */
    public function prom_goods_list()
    {
        $PromGoods = new PromGoods();
        $count = $PromGoods->count();
        $Page = new Page($count, 10);
        $prom_list = $PromGoods->order('start_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('page',$Page);
        $this->assign('prom_list', $prom_list);
        return $this->fetch();
    }

    public function prom_goods_info()
    {
        $level = M('user_level')->select();
        $this->assign('level', $level);
        $prom_id = I('id');
        $info['start_time'] = time();
        $info['end_time'] = time() + 3600 * 60 * 24;
        $info['is_edit'] = 1;
        if ($prom_id > 0) {
            $PromGoodsModel = new PromGoods();
            $info = $PromGoodsModel->find($prom_id);
            $PromGoodsItem =  new PromGoodsItem();
            $prom_goods = $PromGoodsItem->with('spec_goods_price')->where('prom_id',$prom_id)->select();
            $this->assign('prom_goods', $prom_goods);
        }
        $coupon_list = M('coupon')->where(['type'=>0,'status'=>1,'use_start_time'=>['lt',time()],'use_end_time'=>['gt',time()]])->select();
        $this->assign('coupon_list',$coupon_list);
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d H:i:s'));
        $this->initEditor();
        return $this->fetch();
    }

    public function change_prom_is_end()
    {
        $id = input('id/d');
        $flashSale = new FlashSale();
        $flash_sale = $flashSale->find($id);
        if ($flash_sale['end_time'] < time()) {
            $this->ajaxReturn(['status'=>0,'msg'=>'该活动已经过期']);
        }
        $flash_sale['is_end'] == 0 ? $flash_sale['is_end'] = 1 : $flash_sale['is_end'] = 0;
        $flash_sale->save();
        $this->ajaxReturn(['status'=>1,'msg'=>'成功']);
    }
    public function change_group_buy_is_end()
    {
        $id = input('id/d');
        $groupBuy = new GroupBuy();
        $flash_sale = $groupBuy->find($id);
        if ($flash_sale['end_time'] < time()) {
            $this->ajaxReturn(['status'=>0,'msg'=>'该活动已经过期']);
        }
        if ($flash_sale['is_end'] == 1) {
            if ($flash_sale['item_id']) {
                db('spec_goods_price')->where('item_id',$flash_sale['item_id'])->update(['prom_id'=>$flash_sale['id'],'prom_type'=>2]);
            }else{
                db('goods')->where('goods_id',$flash_sale['goods_id'])->update(['prom_id'=>$flash_sale['id'],'prom_type'=>2]);
            }
            $flash_sale['is_end'] = 0;
        }else{
            if ($flash_sale['item_id']) {
                db('spec_goods_price')->where('prom_id',$id)->where('prom_type',2)->update(['prom_id'=>0,'prom_type'=>0]);
            }else{
                db('goods')->where('goods_id',$flash_sale['goods_id'])->update(['prom_id'=>0,'prom_type'=>0]);
            }
            $flash_sale['is_end'] = 1;
        }

        $flash_sale->save();
        $this->ajaxReturn(['status'=>1,'msg'=>'成功']);
    }

    public function prom_goods_save()
    {
        $prom_id = I('id/d');
        $data = I('post.');
        $title = input('title');
        $promGoods = $data['goods'];
        $promGoodsValidate = Loader::validate('PromGoods');
        if(!$promGoodsValidate->batch()->check($data)){
            $error = '';
            foreach ($promGoodsValidate->getError() as $value){
                $error .= $value.'！';
            }
            $this->ajaxReturn(['status' => 0,'msg' =>$error,'token'=>\think\Request::instance()->token()]);
        }
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
//        $data['group'] = (empty($data['group'])) ? '' : implode(',', $data['group']); //前台暂时不用这个功能，先注释
        $goods_ids = [];
        $item_ids = [];
        foreach ($promGoods as $goodsKey => $goodsVal) {
            if (array_key_exists('goods_id', $goodsVal)) {
                array_push($goods_ids, $goodsVal['goods_id']);
            }
            if (array_key_exists('item_id', $goodsVal)) {
                $item_ids = array_merge($item_ids, $goodsVal['item_id']);
            }
        }
        if ($prom_id) {
            M('prom_goods')->where(['id' => $prom_id])->save($data);
            $last_id = $prom_id;
            adminLog("管理员修改了商品促销 " . $title);
        } else {
            $last_id = M('prom_goods')->add($data);
            adminLog("管理员添加了商品促销 " . $title);

            // 优惠促销消息通知
            if ($last_id) {
                if($data['mmt_message_switch'] == 1) {
                    $send_data = [
                        'message_title' => $data['title'],
                        'message_content' => $data['description'],
                        'img_uri' => $data['prom_img'], // 优惠促销ok 订单促销，图片是空的
                        'end_time' => $data['end_time'],
                        'mmt_code' => 'prom_goods_activity',
                        'prom_type' => 3,
                        'users' => [],
                        'message_val' => [],
                        'category' => 1,
                        'prom_id' => $last_id
                    ];
                    $messageFactory = new MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }
            }
        }


        $save_data = [];
        $n = 0;
        foreach ($promGoods as $k => $v){
            $data = db('goods')->where('goods_id',$v['goods_id'])->field('shop_price,original_img,goods_name')->find();
            $save_data[$n]['goods_id'] = $v['goods_id'];
            $save_data[$n]['prom_id'] = $last_id;
            $save_data[$n]['image'] = $data['original_img'];
            $save_data[$n]['goods_name'] = $data['goods_name'];
            $save_data[$n]['price'] = $data['shop_price'];
            if ($v['item_id']) {
                foreach ($v['item_id'] as $vv){
                    $item_data = db('spec_goods_price')->where('item_id',$vv)->field('price,spec_img')->find();
                    $save_data[$n]['item_id'] = $vv;
                    $save_data[$n]['goods_id'] = $v['goods_id'];
                    $save_data[$n]['prom_id'] = $last_id;
                    $save_data[$n]['goods_name'] = $data['goods_name'];
                    $save_data[$n]['price'] = $item_data['price'];
                    if ($item_data['spec_img']) {
                        $save_data[$n]['image'] = $item_data['spec_img'];
                    }
                    $n ++;
                }
            }else{
                $save_data[$n]['item_id'] = 0;
            }
            $n ++;
        }
        db('prom_goods_item')->where(['prom_id'=>$prom_id])->delete();
        model('prom_goods_item')->saveAll($save_data);
        M("goods")->where(['prom_id' => $prom_id, 'prom_type' => 3])->save(array('prom_id' => 0, 'prom_type' => 0));
        M("goods")->where("goods_id", "in", $goods_ids)->save(array('prom_id' => $last_id, 'prom_type' => 3));
        Db::name('spec_goods_price')->where(['prom_id' => $prom_id, 'prom_type' => 3])->update(['prom_id' => 0, 'prom_type' => 0]);
        Db::name('spec_goods_price')->where('item_id','IN',$item_ids)->update(['prom_id' => $last_id, 'prom_type' => 3]);
        $this->ajaxReturn(['status'=>1,'msg'=>'编辑促销活动成功','result']);
    }

    public function prom_goods_del()
    {
        $prom_id = I('id');
        $order_goods = M('order_goods')->where("prom_type = 3 and prom_id = $prom_id")->find();
        if (!empty($order_goods)) {
            $this->ajaxReturn(['status'=>-1,'msg'=>"该活动有订单参与不能删除!"]);
        }
        // 删除优惠通知消息
        $messageFactory = new MessageFactory();
        $messageLogic = $messageFactory->makeModule(['category' => 1]);
        $messageLogic->deletedMessage($prom_id, 3);


        M("goods")->where("prom_id=$prom_id and prom_type=3")->save(array('prom_id' => 0, 'prom_type' => 0));
        Db::name('spec_goods_price')->where(['prom_type'=>3,'prom_id'=>$prom_id])->save(array('prom_id'=>0,'prom_type'=>0));
        M('prom_goods')->where("id=$prom_id")->delete();
        $this->ajaxReturn(['status'=>1,'msg'=>'删除活动成功']);
    }


    /**
     * 活动列表
     */
    public function prom_order_list()
    {
        $parse_type = array('0' => '满额打折', '1' => '满额优惠金额', '2' => '满额送积分', '3' => '满额送优惠券');
        $level = M('user_level')->select();
        if ($level) {
            foreach ($level as $v) {
                $lv[$v['level_id']] = $v['level_name'];
            }
        }
        $count = M('prom_order')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $prom_list = model('prom_order')->limit($Page->firstRow . ',' . $Page->listRows)->order('id desc')->select();
        $this->assign('pager', $Page);// 赋值分页输出
        $this->assign('page', $show);// 赋值分页输出
        $this->assign("parse_type", $parse_type);
        $this->assign('prom_list', $prom_list);
        return $this->fetch();
    }

    public function prom_order_info()
    {
        $this->assign('min_date', date('Y-m-d H:i:s'));
        $level = M('user_level')->select();
        $this->assign('level', $level);
        $prom_id = I('id');
        $info['start_time'] = time();
        $info['end_time'] = time() + 3600 * 24 * 60;
        $info['is_edit'] = 1;
        if ($prom_id > 0) {
            $promOrderModel = new promOrder();
            $info = $promOrderModel->find($prom_id);
        }
        $act = empty($prom_id) ? 'add' : 'edit';
        $this->assign('act',$act);
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d H:i:s'));
        $this->initEditor();
        return $this->fetch();
    }

    public function prom_order_save()
    {
        $data = I('post.');
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $data['group'] = $data['group'] ? implode(',', $data['group']) : '';
        $prom_id = $data['id'];
        $promOrderValidate = Loader::validate('PromOrder');
        if(!$promOrderValidate->scene($data['act'])->batch()->check($data)){
            $error = '';
            foreach ($promOrderValidate->getError() as $value){
                $error .= $value.'！';
            }
            $this->ajaxReturn(['status' => -1,'msg'=>$error]);
        }
        $promOrderModel = new PromOrder();
        if ($data['act']=='edit') {
            $promOrderModel->where("id=$prom_id")->save($data);
            adminLog("管理员id【 ".$this->admin_id." 】修改了订单促销 ID【".$data['id']."】");
        } else {
            $add_id = $promOrderModel->insertGetId($data);
            adminLog("管理员id【 ".$this->admin_id." 】添加了订单促销 ID【".$add_id."】");
            if($data['mmt_message_switch'] == 1) {

                $money = $data['money'];
                $expression = $data['expression']; // 优惠券名称
                $start_time = date("Y-m-d H:i:s", $data['start_time']);
                $end_time = date("Y-m-d H:i:s", $data['end_time']);
                $text = "为答谢广大顾客，活动期间 {$start_time} ~ {$end_time}，凡在本商场消费的顾客，均可获得优惠：";
                switch ($data['type']){
                    case 0:
                        // 直接打折
                        $expression /= 10;
                        $text2 = "每笔订单满{$money}元, 打{$expression}折。";
                        break;
                    case 1:
                        //减价优惠
                        $text2 = "每笔订单满{$money}元, 立减{$expression}元。";
                        break;
                    case 2:
                        // 满额送积分
                        $text2 = "每笔订单满{$money}元, 送{$expression}积分。";
                        break;
                    case 3:
                        $expression_name = Db::name('coupon')->where('id', $expression)->value('name');
                        //买就赠代金券
                        $text2 = "每笔订单满{$money}元, 送{$expression_name}优惠券。";
                        break;
                    default:
                        $text2 = '';
                        break;
                }
                // 订单促销
                $send_data = [
                    'message_title' => $data['name'],
                    'message_content' => $text . $text2 . $data['description'],
                    'img_uri' => '/template/pc/rainbow/static/images/activimg.png',
                    'end_time' => $data['end_time'],
                    'mmt_code' => 'prom_order_activity',
                    'prom_type' => 9,
                    'users' => [],
                    'category' => 1,
                    'message_val' => [],
                    'prom_id' => $add_id
                ];
                $messageFactory = new MessageFactory();
                $messageLogic = $messageFactory->makeModule($send_data);
                $messageLogic->sendMessage();
            }
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'编辑促销活动成功']);
    }

    public function prom_order_del()
    {
        $prom_id = I('id');
        $order = Db::name('order')->where("order_prom_id = $prom_id")->find();
        if (!empty($order)) {
            $this->ajaxReturn(['status'=>-1,'msg'=>"该活动有订单参与不能删除!"]);
        }
        $r = Db::name('prom_order')->where("id", $prom_id)->delete();
        if($r){
            // 删除订单促销
            $messageFactory = new MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 1]);
            $messageLogic->deletedMessage($prom_id, 9);

            adminLog("管理员id【 ".$this->admin_id." 】删除了订单促销 ID【".$prom_id."】");
            $this->ajaxReturn(['status'=>1,'msg'=>'删除活动成功', 'url'=>U('Promotion/prom_order_list')]);
        }
        $this->ajaxReturn(['status'=>-1,'msg'=>'删除活动失败']);
    }

    public function group_buy_list()
    {
        $GroupBuy = new GroupBuy();
        $count = $GroupBuy->where('')->count();
        $Page = new Page($count, 10);
        $list = $GroupBuy->where('')->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $Page);
        return $this->fetch();
    }

    public function group_buy()
    {
        $act = I('GET.act', 'add');
        $groupbuy_id = I('get.id/d');
        $group_info = array();
        $group_info['start_time'] = date('Y-m-d H:i:s');
        $group_info['end_time'] = date('Y-m-d H:i:s', time() + 3600 * 365);
        $group_info['is_edit'] = 1;
        if ($groupbuy_id) {
            $GroupBy = new GroupBuy();
            $group_info = $GroupBy->with('specGoodsPrice,goods')->find($groupbuy_id);
            $group_info['start_time'] = date('Y-m-d H:i:s', $group_info['start_time']);
            $group_info['end_time'] = date('Y-m-d H:i:s', $group_info['end_time']);
            $act = 'edit';
        }
        $this->assign('min_date', date('Y-m-d H:i:s'));
        $this->assign('info', $group_info);
        $this->assign('act', $act);
        return $this->fetch();
    }

    public function groupbuyHandle()
    {
        $data = I('post.');
        $data['groupbuy_intro'] = htmlspecialchars(stripslashes($this->request->param('groupbuy_intro')));
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['act'] == 'del') {

            $spec_goods = Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->find();
            //有活动商品规格
            if($spec_goods){
                Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                //商品下的规格是否都没有活动
                $goods_spec_num = Db::name('spec_goods_price')->where(['prom_type' => 2, 'goods_id' => $spec_goods['goods_id']])->find();
                if(empty($goods_spec_num)){
                    //商品下的规格都没有活动,把商品回复普通商品
                    Db::name('goods')->where(['goods_id' => $spec_goods['goods_id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                }
            }else{
                //没有商品规格
                Db::name('goods')->where(['prom_type' => 2, 'prom_id' => $data['id']])->save(array('prom_id' => 0, 'prom_type' => 0));
            }
            $r = D('group_buy')->where(['id' => $data['id']])->delete();

            // 删除团购通知消息
            $messageFactory = new MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 1]);
            $messageLogic->deletedMessage($data['id'], 2);



            if ($r) exit(json_encode(1));
        }
        $groupBuyValidate = Loader::validate('GroupBuy');
        if($data['item_id'] > 0){
            $spec_goods_price = Db::name("spec_goods_price")->where(['item_id'=>$data['item_id']])->find();
            $data['goods_price'] = $spec_goods_price['price'];
            $data['store_count'] = $spec_goods_price['store_count'];
        }else{
            $goods = Db::name("goods")->where(['goods_id'=>$data['goods_id']])->find();
            $data['goods_price'] = $goods['shop_price'];
            $data['store_count'] = $goods['store_count'];
        }
        if(!$groupBuyValidate->batch()->check($data)){
            $return = ['status' => 0,'msg' =>'操作失败','result' => $groupBuyValidate->getError() ];
            $this->ajaxReturn($return);
        }
        $data['rebate'] = number_format($data['price'] / $data['goods_price'] * 10, 1);
        if ($data['act'] == 'add') {
            $r = Db::name('group_buy')->insertGetId($data);
            if($data['item_id'] > 0){
                //设置商品一种规格为活动
                Db::name('spec_goods_price')->where('item_id',$data['item_id'])->update(['prom_id' => $r, 'prom_type' => 2]);
                Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => 0, 'prom_type' => 2));
            }else{
                Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $r, 'prom_type' => 2));
            }
            if ($r) {

                if($data['mmt_message_switch'] == 1) {
                    //发送团购活动通知消息
                    $goods_original_img = Db::name('goods')->where("goods_id", $data['goods_id'])->value('original_img');
                    $send_data = [
                        'message_title' => $data['title'],
                        'message_content' => $data['intro'],
                        'img_uri' => $goods_original_img,
                        'end_time' => $data['end_time'],
                        'mmt_code' => 'group_buy_activity',
                        'prom_type' => 2,
                        'users' => [],
                        'message_val' => [],
                        'category' => 1,
                        'prom_id' => $r
                    ];
                    $messageFactory = new MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }

            }
        }
        if ($data['act'] == 'edit') {
            $r = Db::name('group_buy')->where(['id' => $data['id']])->update($data);
            if($data['item_id'] > 0){
                //设置商品一种规格为活动
                Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->update(['prom_id' => 0, 'prom_type' => 0]);
                Db::name('spec_goods_price')->where('item_id', $data['item_id'])->update(['prom_id' => $data['id'], 'prom_type' => 2]);
                M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => 0, 'prom_type' => 2));
            }else{
                M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $data['id'], 'prom_type' => 2));
            }
        }
        if ($r !== false) {

            $this->ajaxReturn(['status' => 1,'msg' =>'操作成功','result' => '']);
        } else {
            $this->ajaxReturn(['status' => 0,'msg' =>'操作失败','result' =>'']);
        }
    }

    public function get_goods()
    {
        $prom_id = I('id/d');
        $prom_where = ['prom_id' => $prom_id];
        $count = db("prom_goods_item")->where($prom_where)->count();
        $Page = new Page($count, 10);
        $goodsList = db("prom_goods_item")->where($prom_where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        if($goodsList){
            foreach ($goodsList as $k=>$v){
                if($v['item_id']){
                    $item = db('spec_goods_price')->where('item_id',$v['item_id'])->field('key_name,store_count')->find();
                    $goodsList[$k]['goods_name'] = $v['goods_name'].'-'.$item['key_name'];
                    $goodsList[$k]['store_count'] = $item['store_count'];
                }else{
                    $goodsList[$k]['store_count'] = db('goods')->where('goods_id',$v['goods_id'])->field('store_count')->find()['store_count'];
                }
            }
        }
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('count', $count);
        $this->assign('goodsList', $goodsList);
        return $this->fetch();
    }

    public function search_goods()
    {
        $goods_id = input('goods_id');
        $intro = input('intro');
        $cat_id = input('cat_id');
        $brand_id = input('brand_id');
        $keywords = input('keywords');
        $prom_id = input('prom_id');
        $tpl = input('tpl', 'search_goods');
        $where = ['is_on_sale' => 1, 'store_count' => ['gt', 0],'exchange_integral'=>0];
        $prom_type = input('prom_type/d',0);
        if ($prom_type != 0) {//指定商品优惠券 可以看到虚拟商品
            $where = ['is_on_sale' => 1, 'store_count' => ['gt', 0],'is_virtual'=>0,'exchange_integral'=>0];
        }
        if($goods_id){
            $where['goods_id'] = ['notin',trim($goods_id,',')];
        }
        if($intro){
            $where[$intro] = 1;
        }
        if($cat_id){
            $grandson_ids = getCatGrandson($cat_id);
            $where['cat_id'] = ['in',implode(',', $grandson_ids)];
        }
        if ($brand_id) {
            $where['brand_id'] = $brand_id;
        }
        if($keywords){
            $where['goods_name|keywords'] = ['like','%'.$keywords.'%'];
        }
        $Goods = new Goods();
        $count = $Goods->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if(in_array($prom_type,[3,6])){
                //优惠促销,拼团
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if($prom_type == 7){
                //
                $query->where([ 'prom_type' => $prom_type])->whereor('prom_type', 0);
            }else if(in_array($prom_type,[1,2])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type])->where('prom_type',0);
            }else{
                $query->where('prom_type',0);
            }
        })->count();
        $Page = new Page($count, 10);
        $goodsList = $Goods->with('specGoodsPrice')->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if(in_array($prom_type,[3,6])){
                //优惠促销
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => $prom_type])->whereor('prom_id', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if($prom_type == 7){
                //
                $query->where([ 'prom_type' => $prom_type])->whereor('prom_type', 0);
            }else if(in_array($prom_type,[1,2])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type])->where('prom_type',0);
            }else{
                $query->where('prom_type',0);
            }
        })->order('goods_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $GoodsLogic = new GoodsLogic;
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        $this->assign('brandList', $brandList);
        $this->assign('categoryList', $categoryList);
        $this->assign('page', $Page);
        $this->assign('goodsList', $goodsList);
        return $this->fetch($tpl);
    }

    //限时抢购
    public function flash_sale()
    {
        $condition = array();
        $FlashSale = new FlashSale();
        $count = $FlashSale->where($condition)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $prom_list = $FlashSale->append(['status_desc'])->where($condition)->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('prom_list', $prom_list);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        return $this->fetch();
    }

    public function flash_sale_info()
    {
        if (IS_POST) {
            $data = I('post.');
            $data['start_time'] = strtotime($data['start_time'].' '.$data['start_time_h'].':0:0');
            $data['end_time'] = $data['start_time']+7200;
            $flashSaleValidate = Loader::validate('FlashSale');
            if (!$flashSaleValidate->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '操作失败', 'result' => $flashSaleValidate->getError()];
                $this->ajaxReturn($return);
            }
            if (empty($data['id'])) {
                $flashSaleInsertId = Db::name('flash_sale')->insertGetId($data);
                if($data['item_id'] > 0){
                    //设置商品一种规格为活动
                    Db::name('spec_goods_price')->where('item_id',$data['item_id'])->update(['prom_id' => $flashSaleInsertId, 'prom_type' => 1]);
                    Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id'=>0,'prom_type' => 1));
                }else{
                    Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $flashSaleInsertId, 'prom_type' => 1));
                }
                adminLog("管理员添加抢购活动 " . $data['name']);
                if ($flashSaleInsertId !== false) {

                    if($data['mmt_message_switch'] == 1) {
                        $goods_original_img = Db::name('goods')->where("goods_id", $data['goods_id'])->value('original_img');
                        // 发送抢购活动通知消息
                        $send_data = [
                            'message_title' => $data['title'],
                            'message_content' => $data['description'],
                            'img_uri' => $goods_original_img,
                            'end_time' => $data['end_time'],
                            'mmt_code' => 'flash_sale_activity',
                            'prom_type' => 1,
                            'users' => [],
                            'message_val' => [],
                            'category' => 1,
                            'prom_id' => $flashSaleInsertId
                        ];
                        $messageFactory = new MessageFactory();
                        $messageLogic = $messageFactory->makeModule($send_data);
                        $messageLogic->sendMessage();
                    }
                    $this->ajaxReturn(['status' => 1, 'msg' => '添加抢购活动成功', 'result' => '']);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '添加抢购活动失败', 'result' => '']);
                }
            } else {
                $r = M('flash_sale')->where("id=" . $data['id'])->save($data);
                M('goods')->where(['prom_type' => 1, 'prom_id' => $data['id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                if($data['item_id'] > 0){
                    //设置商品一种规格为活动
                    Db::name('spec_goods_price')->where(['prom_type' => 1, 'prom_id' => $data['item_id']])->update(['prom_id' => 0, 'prom_type' => 0]);
                    Db::name('spec_goods_price')->where('item_id', $data['item_id'])->update(['prom_id' => $data['id'], 'prom_type' => 1]);
                    M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => 0, 'prom_type' => 1));
                }else{
                    M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $data['id'], 'prom_type' => 1));
                }
                if ($r !== false) {
                    $this->ajaxReturn(['status' => 1, 'msg' => '编辑抢购活动成功', 'result' => '']);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '编辑抢购活动失败', 'result' => '']);
                }
            }
        }
        $id = I('id');
        $info['start_time_h'] = 0;
        $now_time = strtotime(date('Y-m-d'));
        $info['start_time'] = $now_time;
        $info['is_edit'] = 1;
        if ($id > 0) {
            $FlashSale = new FlashSale();
            $info = $FlashSale->with('specGoodsPrice,goods')->find($id);
            $info['start_time_h'] = date('H',$info['start_time']);
        }
        $this->assign('info', $info);
        return $this->fetch();
    }

    public function flash_sale_del()
    {
        $id = I('del_id/d');
        if ($id) {
            $spec_goods = Db::name('spec_goods_price')->where(['prom_type' => 1, 'prom_id' => $id])->find();
            //有活动商品规格
            if($spec_goods){
                Db::name('spec_goods_price')->where(['prom_type' => 1, 'prom_id' => $id])->save(array('prom_id' => 0, 'prom_type' => 0));
                //商品下的规格是否都没有活动
                $goods_spec_num = Db::name('spec_goods_price')->where(['prom_type' => 1, 'goods_id' => $spec_goods['goods_id']])->find();
                if(empty($goods_spec_num)){
                    //商品下的规格都没有活动,把商品回复普通商品
                    Db::name('goods')->where(['goods_id' => $spec_goods['goods_id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                }
            }else{
                //没有商品规格
                Db::name('goods')->where(['prom_type' => 1, 'prom_id' => $id])->save(array('prom_id' => 0, 'prom_type' => 0));
            }
            M('flash_sale')->where(['id' => $id])->delete();
            // 删除抢购消息
            $messageFactory = new MessageFactory();
            $messageLogic = $messageFactory->makeModule(['category' => 1]);
            $messageLogic->deletedMessage($id, 1);


            exit(json_encode(1));
        } else {
            exit(json_encode(0));
        }
    }

    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp', array('savepath' => 'promotion')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp', array('savepath' => 'promotion')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage', array('savepath' => 'promotion')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager', array('savepath' => 'promotion')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie', array('savepath' => 'promotion')));
        $this->assign("URL_Home", "");
    }

}