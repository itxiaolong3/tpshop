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
 * 
 * Date: 2016-03-09
 */

namespace app\admin\controller;
use think\Page;
use app\admin\logic\GoodsLogic;
use think\Db;

class Distribut extends Base {
    
    /*
     * 初始化操作
     */
    public function _initialize() {
       parent::_initialize();
    }    
    
    /**
     * 分销树状关系
     */
    public function tree(){                
        $where = 'is_distribut = 0 and first_leader = 0';
        if($this->request->param('user_id'))
            $where = "user_id = '{$this->request->param('user_id')}'";
        
        $list = M('users')->where($where)->select();        
        $this->assign('list',$list);
        return $this->fetch();
    }
 
    /**
     * 分销商列表
     */
    public function distributor_list(){
    	$condition['is_distribut']  = 0;
    	$nickname = trim(I('nickname'));
    	$user_id = trim(I('user_id'));
    	if(!empty($nickname)){
    		$condition['nickname'] = array('like',"%$nickname%");
    	}
        if(!empty($user_id)){
            $condition['user_id'] = array('like',"%$user_id%");
        }
    	$count = M('users')->where($condition)->count();
    	$Page = new Page($count,10);
    	$show = $Page->show();
    	$user_list = M('users')->where($condition)->order('distribut_money DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
    	foreach ($user_list as $k=>$val){
    		$user_list[$k]['fisrt_leader'] = M('users')->where(array('first_leader'=>$val['user_id']))->count();
    		$user_list[$k]['second_leader'] = M('users')->where(array('second_leader'=>$val['user_id']))->count();
    		$user_list[$k]['third_leader'] = M('users')->where(array('third_leader'=>$val['user_id']))->count();
    		$user_list[$k]['lower_sum'] = $user_list[$k]['fisrt_leader'] +$user_list[$k]['second_leader'] + $user_list[$k]['third_leader'];
    	}
    	$this->assign('page',$show);
    	$this->assign('pager',$Page);
    	$this->assign('user_list',$user_list);
    	return $this->fetch();
    }
    
    /**
     * 分销设置
     */
    public function set(){
        header("Location:".U('Admin/System/index',array('inc_type'=>'distribut')));
        exit;
    }
    public function goods_list(){
    	$GoodsLogic = new GoodsLogic();
    	$brandList = $GoodsLogic->getSortBrands();
    	$categoryList = $GoodsLogic->getSortCategory();
    	$this->assign('categoryList',$categoryList);
    	$this->assign('brandList',$brandList);
    	$where = ' commission > 0 ';
    	$cat_id = I('cat_id/d');
        $bind = array();
    	if($cat_id > 0)
    	{
    		$grandson_ids = getCatGrandson($cat_id);
    		$where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
    	}
    	$key_word = I('key_word') ? trim(I('key_word')) : '';
    	if($key_word)
    	{
    		$where = "$where and (goods_name like :key_word1 or goods_sn like :key_word2)" ;
            $bind['key_word1'] = "%$key_word%";
            $bind['key_word2'] = "%$key_word%";
    	}
        $brand_id = I('brand_id');
        if($brand_id){
            $where = "$where and brand_id = :brand_id";
            $bind['brand_id'] = $brand_id;
        }
    	$model = M('Goods');
    	$count = $model->where($where)->bind($bind)->count();
    	$Page  = new Page($count,10);
    	$show = $Page->show();
    	$goodsList = $model->where($where)->bind($bind)->order('sales_sum desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $catList = D('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        $this->assign('catList',$catList);
        $this->assign('pager',$Page);
    	$this->assign('goodsList',$goodsList);
    	$this->assign('page',$show);
    	return $this->fetch();
    }
 

    
    /**
     * 分成记录
     */
    public function rebate_log()
    { 
        $model = M("rebate_log"); 
        $status = I('status');
        $user_id = I('user_id/d');
        $order_sn = I('order_sn');        
        $create_time = I('create_time');
        $create_time = $create_time  ? $create_time  : date('Y-m-d',strtotime('-1 year')).' - '.date('Y-m-d',strtotime('+1 day'));
                       
        $create_time2 = explode(' - ',$create_time);
        $where = " create_time >= '".strtotime($create_time2[0])."' and create_time <= '".strtotime($create_time2[1])."' ";
        
        if($status === '0' || $status > 0)
            $where .= " and status = $status ";
        $user_id && $where .= " and user_id = $user_id ";
        $order_sn && $where .= " and order_sn like '%{$order_sn}%' ";
                        
        $count = $model->where($where)->count();
        $Page  = new Page($count,16);        
        $list = $model->where($where)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        if(!empty($list)){
        	$get_user_id = get_arr_column($list, 'user_id'); // 获佣用户
        	$buy_user_id = get_arr_column($list, 'buy_user_id'); //购买用户
        	$user_id_arr = array_merge($get_user_id,$buy_user_id);
        	//剔除空元素
            $user_id_arr=array_filter($user_id_arr,create_function('$v','return !empty($v);'));
            //var_dump($user_id_arr);die();
        	$user_arr = M('users')->where("user_id in (".  implode(',', $user_id_arr).")")->getField('user_id,mobile,nickname,email');
        	$this->assign('user_arr',$user_arr);
        }
        $this->assign('create_time',$create_time);        
        $show  = $Page->show();                 
        $this->assign('show',$show);
        $this->assign('list',$list);
        C('TOKEN_ON',false);
        return $this->fetch();
    }
    
    /**
     * 获取某个人下级元素
     */    
    public  function ajax_lower()
    {
        $id = $this->request->param('id');
        $list = M('users')->where("first_leader =".$id)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    
    /**
     * 修改编辑 分成 
     */
    public  function editRebate(){        
        $id = I('id');
        $rebate_log = DB::name('rebate_log')->where('id',$id)->find();
        if (IS_POST) {
            $data = I('post.');
            // 如果是确定分成 将金额打入分佣用户余额
            if ($data['status'] == 3 && $rebate_log['status'] != 3) {
                accountLog($data['user_id'], $rebate_log['money'], 0, "订单:{$rebate_log['order_sn']}分佣", $rebate_log['money']);
            }
            DB::name('rebate_log')->update($data);
            $this->success("操作成功!!!", U('Admin/Distribut/rebate_log'));
            exit;
        }                      
       
       $user = M('users')->where("user_id = {$rebate_log[user_id]}")->find();       
            
       if($user['nickname'])        
           $rebate_log['user_name'] = $user['nickname'];
       elseif($user['email'])        
           $rebate_log['user_name'] = $user['email'];
       elseif($user['mobile'])        
           $rebate_log['user_name'] = $user['mobile'];            
       
       $this->assign('user',$user);
       $this->assign('rebate_log',$rebate_log);
       return $this->fetch();
    }


    public function reward_month(){
        $users = Db::name("users")->where(["level"=>7, "first_leader"=>["neq",""]])->getField("user_id,first_leader,nickname");
        $where["status"]=3;
        $firstday=mktime(0,0,0,date('m'),1,date('Y'));
        $lastday=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $where["confirm_time"]=[">=",$firstday];
        $where["confirm_time"]=[$where["confirm_time"],["<=",$lastday]];
        $count = Db::name("rebate_log")->where($where)->where(["type"=>3])->count();
        if($count){
            $this->error("本月已经完成上月分成");
        }

        $firstday = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));
        $lastday = strtotime(date('Y-m-t 23:59:59', strtotime('-1 month')));
        $where["confirm_time"]=[">=",$firstday];
        $where["confirm_time"]=[$where["confirm_time"],["<=",$lastday]];
        $where["type"]=1;
        foreach ($users as $k => $v){
            $where["user_id"]=$k;
            $money = Db::name("rebate_log")->where($where)->sum("money");//获取上月总佣金
            if($money>1){
                $income = $money * 0.03;
                $data = array(
                    'user_id' =>$v['first_leader'],
                    'buy_user_id'=>$v['user_id'],
                    'nickname'=>$v['nickname'],
                    'goods_price' => $money,
                    'money' => $income,
                    'level' => 2,
                    'create_time' => time(),
                    'confirm_time' => time(),
                    'status' => 3,
                    'type' => 3,
                    'detail' => "战略合作伙伴专享上月业绩奖励",
                );
                M('rebate_log')->add($data);
                /* 插入帐户变动记录 */
                $account_log = array(
                    'user_id'       => $v['first_leader'],
                    'user_money'    => $income,
                    'change_time'   => time(),
                    'desc'   => "战略合作伙伴专享上月业绩奖励",
                );
                M('account_log')->add($account_log);
                Db::name('users')->where(["user_id"=> $v['first_leader']])->save(["distribut_money"=>['exp','distribut_money+'.$income]]);
            }
        }
        $this->success("操作成功!!!", U('Admin/Distribut/rebate_log'));
    }
            

}