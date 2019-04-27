<?php

/**

 * 用户支付成功后回调处理

 *

 */

namespace  Common\Service;



use Think\Controller;





class LogicController extends  Controller{

    public $id;//订单id

    public $order_no;//唯一标识，订单号

    public $uid;//用户id

    public $type;//消费类型

    public $money;//消费金额

    public $jiameng_id;//加盟等级

    public $duan;//加盟升级升段

    public function __construct($order_no)

    {

        $this->order_no = $order_no;

        $info = $this->getOrderInfo($this->order_no);

        $this->type = $info['type'];

        $this->money = $info['money'];

        $this->uid = $info['uid'];

        $this->jiameng_id = $info['jiameng_id'];

        $this->duan = $info['sheng_duan'];

        $this->id = I('get.order_id')?:$info['id'];

    }

    //用户充值

    public function recharge(){

        //添加充值所得金额

        $user = M('user');

        $user->where(['uid'=>$this->uid])->setInc('silver',$this->money);

        //增加充值量

        $user->where(['uid'=>$this->uid])->setInc('recharge',$this->money);

        //添加充值记录

        jyadd('充值',$this->money,$this->uid);

        //添加消费统计记录

        M('consumption')->add(['uid'=>$this->uid,'type'=>1,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

        //增加积分

        jfadd('充值',$this->money,$this->uid);

    }





    //加盟

    public function ruzhu(){

        //给用户添加商家权限

        if ($this->jiameng_id == 1) {

            $nums = 5;

        }elseif($this->jiameng_id == 2){

            $nums = 10;

        }else{

            $nums = 20;

        }

        M('shangpu')->add(['uid'=>$this->uid,

            'user_sf'=>$this->jiameng_id,

            'energy_total'=>$nums,

            'energy_sy'=>$nums]);

        M('user')->where(['uid'=>$this->uid])->save(['user_status'=>1]);

        //添加加盟支付记录

        jfadd('加盟',$this->money,$this->uid);

        jyadd('加盟',$this->money,$this->uid);

        //M('mrecord')->add(['uid'=>$this->uid,'type'=>7,'updown'=>2,'money'=>$this->money,'createtime'=>time()]);

        //添加消费统计记录

        M('consumption')->add(['uid'=>$this->uid,'type'=>5,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

    }



    //加盟升级

    public function ruzhuUp(){

        //添加消费统计记录

        M('consumption')->add(['uid'=>$this->uid,'type'=>6,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

        //增加积分

        jfadd('加盟升级',$this->money,$this->uid);

        jyadd('加盟升级',$this->money,$this->uid);

        //M('integral')->add(['uid'=>$this->uid,'type'=>7,'num'=>$this->money,'add_time'=>time(),'ym'=>date('Ym',time())]);

        //修改会员身份等级

        $shangpuObj = M('shangpu');

        $shangpuData = $shangpuObj->where(['uid'=>$this->uid,'status'=>1])->find();

        if($this->duan == 2){

            $energy_total = 10;

            $num = 5;

        }else if($this->duan == 3){

            if($shangpuData['user_sf'] == 1){

                $num = 15;

            }else if($shangpuData['user_sf'] == 2){

                $num = 10;

            }

            $energy_total = 20;

        }

        $shangpuObj->where(['id'=>$shangpuData['id']])->save(['user_sf'=>$this->duan,'energy_total'=>$energy_total]);

        $shangpuObj->where(['id'=>$shangpuData['id']])->setInc('energy_sy',$num);

        //刷新藏品出货率

        M('collection')->where(['is_ok'=>['neq',9],'fid'=>$shangpuData['id'],'is_song'=>0])->save(['is_jianchu'=>0]);

        $may = M('may')->where(['c_type'=>1])->find();

        $jianchu = round($energy_total*($may['chuhuo']/100));

        $collectionTotalOk = M('collection')->where(['fid'=>$shangpuData['id'],'is_ok'=>9])->count();

        if($jianchu <= $collectionTotalOk){//达到出宝率

            $res = M('collection')->where(['fid'=>$shangpuData['id'],'is_jianchu'=>0,'is_sale'=>1,'is_song'=>0,])->save(['is_jianchu'=>1]);

            $msg = 'fid='.$shangpuData['id'].'uid='.$this->uid.'chuhuo='.$jianchu.'num='.$collectionTotalOk.'res='.$res;

            addlog($msg.'/101');

        }





    }



    //斗宝返回

    public function doubao(){

        //修改斗宝订单状态

        $doubao_order = M('doubao_order')->where(['ordernum'=>$this->order_no])->save(['pay_status'=>1]);

        //添加积分记录

        jfadd('斗宝',$this->money,$this->uid);

        //添加交易记录

        jyadd('斗宝',$this->money,$this->uid);

        M('consumption')->add(['uid'=>$this->uid,'type'=>4,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

        $jumpData = M('doubao_order')->field('dou_id,ordernum')->where(['ordernum'=>$this->order_no,'pay_status'=>1])->find();

    }







    //收藏

    public function collection(){

        $order_detail = M('order_detail')->where(['order_no'=>$this->order_no,'order_status'=>['neq',4]])->find();

        jyadd('收藏',$this->money,$this->uid);

        M('announced')->where(['uid'=>$this->uid,'cid'=>$order_detail['cid'],'type'=>3])->save(['type'=>0]);

        $re = M('user_collection')->where(['uid'=>$this->uid,'cid'=>$order_detail['cid']])->save(['status'=>0]);

        //判断是否商家植入藏品，并添加收益记录

        $res = M('collection')->where(['id'=>$order_detail['cid'],'fid'=>['neq',0]])->find();

        $shang_uid = M('shangpu')->find($res['fid'])['uid'];

        if($res){

            jyadd('加盟收益',$res['price_zhi'],$shang_uid);

        }

        M('order_detail')->where(['order_no'=>$this->order_no,'order_status'=>0])->save(['pay_state'=>1]);

        M('consumption')->add(['uid'=>$this->uid,'type'=>3,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);



    }





    //商城交易

    public function buying(){

        //购买处理---

        $where['order_no'] = $this->order_no;

        $where['order_status'] = 0;

        $order_detail = M('order_detail')->field('order_money,is_huan_mai,cid,good_num,uid,id,order_no,sid')->where(['order_no'=>$this->order_no,'order_status'=>0])->select();

        foreach ($order_detail as $k=>$v){

            M('good')->where(['cang_id'=>$v['cid']])->setDec('good_number',1);//商城good表库存减1

            $res = M('good')->where(['cang_id'=>$v['cid']])->find();

            if($v['sid']>0){

                jyadd('商城出售',$res['good_price'],$v['sid']);

            }

        }

        jyadd('商城交易',$this->money,$this->uid);

        M('order_detail')->where($where)->save(['pay_state'=>1]);

        //添加消费统计记录

        M('consumption')->add(['uid'=>$this->uid,'type'=>3,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

    }



    //用户缴纳会员费

    public function addMember(){

        //添加消费统计记录

        M('consumption')->add(['uid'=>$this->uid,'type'=>10,'money'=>$this->money,'c_time'=>date('Ym'),'c_times'=>time()]);

        //修改用户状态

        M('user')->where(['uid'=>$this->uid])->setField(['j_user'=>0]);

    }







    public function getOrderInfo($order_no = ''){

        $data = M('order')->where(['order_no'=>$order_no])->find();

        return $data;

    }





}