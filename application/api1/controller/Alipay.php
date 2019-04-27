<?php
namespace app\api\controller;

use think\Controller;

class Alipay extends Controller{
    //异步回调
    public  function  notify_url(){
        require VENDOR_PATH.'Ali/configWeb.php';
        require VENDOR_PATH.'Ali/wappay/service/AlipayWebService.php';

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
         1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //将返回的结果转成json记录下来
            $json = json_encode($arr,true);
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            file_put_contents('retuen1.txt','异步订单号：'.$out_trade_no.PHP_EOL,FILE_APPEND);

            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            $logic = new LogicController($out_trade_no);
            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                $orderObj = M('order');
                $json = json_encode($_POST,true);
                $orderData = $orderObj->where(['order_no'=>$out_trade_no])->find();
                if ($orderData['pay_status'] == 0) {
                    $orderObj->where(['id'=>$orderData['id'],'order_no'=>$out_trade_no])->save(['pay_type'=>1,'pay_status'=>1,'pay_time'=>time(),'return'=>$json]);
//                    if ($orderData['type'] == 1) {
//                        file_put_contents('retuen1.txt','异步充值：'.PHP_EOL,FILE_APPEND);
//                        //添加充值所得金额
//                        $logic->recharge();
//                    }elseif($orderData['type'] == 2){
//                        $logic->doubao();
//                    }else if($orderData['type'] == 3){
//                        $logic->buying();
//                    }else if($orderData['type'] == 4){//商家加盟
//                        $logic->ruzhu();
//                    }else if($orderData['type'] == 5){//加盟升级
//                        $logic->ruzhuUp();
//                    }else if($orderData['type'] == 6){
//                        $logic->collection();
//                    }else if($orderData['type'] == 10){//用户缴纳会员费
//                        $logic->addMember();
//                    }else{
                        M('order')->where(['order_no'=>$out_trade_no])->save(['pay_status'=>2,'pay_time'=>time(),'return'=>$json]);

                   // }
                }
            }else{
                M('order')->where(['order_no'=>$out_trade_no])->save(['pay_status'=>2,'pay_time'=>time(),'return'=>$json]);
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";		//请不要修改或删除
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
    }
    //同步跳转
    public function return_url(){
//	    file_put_contents('retuen1.txt','同步'.PHP_EOL,FILE_APPEND);
        require VENDOR_PATH.'Ali/configWeb.php';
        require VENDOR_PATH.'Ali/wappay/service/AlipayWebService.php';

        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
         1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            //商户订单号

            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

            $logic = new LogicController($out_trade_no);
            $orderObj = M('order');
            $json = json_encode($_GET,true);

            file_put_contents('retuen1.txt','同步订单号：'.$out_trade_no.PHP_EOL,FILE_APPEND);
            $orderData = $orderObj->where(['order_no'=>$out_trade_no])->find();
            file_put_contents('retuen1.txt','同步订单查询数量：'.count($orderData),FILE_APPEND);

            if ($orderData['pay_status'] == 0) {
                $orderObj->where(['id'=>$orderData['id'],'order_no'=>$out_trade_no])->save(['pay_status'=>1,'pay_time'=>time(),'return'=>$json]);
                if ($orderData['type'] == 1) {//充值

                    //添加充值所得金额
                    $user = M('user');
                    $user->where(['uid'=>$orderData['uid']])->setInc('silver',$orderData['money']);
                    //增加充值量
                    $user->where(['uid'=>$orderData['uid']])->setInc('recharge',$orderData['money']);
                    //添加充值记录
                    jfadd('充值',$orderData['money'],$orderData['uid']);
                    jyadd('充值',$orderData['money'],$orderData['uid']);
                    //M('mrecord')->add(['uid'=>$orderData['uid'],'type'=>1,'updown'=>1,'money'=>$orderData['money'],'createtime'=>time()]);
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>1,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //增加积分
                    //M('integral')->add(['uid'=>$orderData['uid'],'type'=>1,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    //充值返回
                    redirect(U('Web/User/myJinBi'));
                }elseif($orderData['type'] == 2){//斗宝
                    //斗宝返回
                    //修改斗宝订单状态
                    $doubao_order = M('doubao_order')->where(['ordernum'=>$out_trade_no])->save(['pay_status'=>1]);
                    //添加充值记录
                    jfadd('斗宝',$orderData['money'],$orderData['uid']);
                    jyadd('斗宝',$orderData['money'],$orderData['uid']);
                    //M('mrecord')->add(['uid'=>$orderData['uid'],'type'=>6,'updown'=>2,'money'=>$orderData['money'],'createtime'=>time()]);
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>4,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //增加积分
                    //M('integral')->add(['uid'=>$orderData['uid'],'type'=>2,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    //查询跳转信息
                    $jumpData = M('doubao_order')->field('dou_id,ordernum')->where(['ordernum'=>$out_trade_no,'pay_status'=>1])->find();
                    redirect(U('Canjia/uploadingBaby',['id'=>$jumpData['dou_id'],'order'=>$jumpData['ordernum']]));
                }else if($orderData['type'] == 4){//商家加盟
                    //给用户添加商家权限
                    if ($orderData['jiameng_id'] == 1) {
                        $nums = 5;
                    }elseif($orderData['jiameng_id'] == 2){
                        $nums = 10;
                    }else{
                        $nums = 20;
                    }
                    M('shangpu')->add(['uid'=>$orderData['uid'],
                        'user_sf'=>$orderData['jiameng_id'],
                        'energy_total'=>$nums,
                        'energy_sy'=>$nums]);
                    M('user')->where(['uid'=>$orderData['uid']])->save(['user_status'=>1]);
                    //添加加盟支付记录
                    jfadd('加盟',$orderData['money'],$orderData['uid']);
                    jyadd('加盟',$orderData['money'],$orderData['uid']);
                    //M('mrecord')->add(['uid'=>$orderData['uid'],'type'=>7,'updown'=>2,'money'=>$orderData['money'],'createtime'=>time()]);
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>5,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //增加积分
                    //M('integral')->add(['uid'=>$orderData['uid'],'type'=>4,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    redirect(U('Ruzhu/applyformerchant'));
                }else if($orderData['type'] == 5){//加盟升级
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>6,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //增加积分
                    jfadd('加盟升级',$orderData['money'],$orderData['uid']);
                    jyadd('加盟升级',$orderData['money'],$orderData['uid']);
                    //M('integral')->add(['uid'=>$orderData['uid'],'type'=>7,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    //修改会员身份等级
                    $shangpuObj = M('shangpu');
                    $shangpuData = $shangpuObj->where(['uid'=>$orderData['uid'],'status'=>1])->find();
                    if($orderData['sheng_duan'] == 2){
                        $energy_total = 10;
                        $num = 5;
                    }else if($orderData['sheng_duan'] == 3){
                        if($shangpuData['user_sf'] == 1){
                            $num = 15;
                        }else if($shangpuData['user_sf'] == 2){
                            $num = 10;
                        }
                        $energy_total = 20;
                    }
                    $shangpuObj->where(['id'=>$shangpuData['id']])->save(['user_sf'=>$orderData['sheng_duan'],'energy_total'=>$energy_total]);
                    $shangpuObj->where(['id'=>$shangpuData['id']])->setInc('energy_sy',$num);
                    //刷新藏品出货率

                    M('collection')->where(['is_ok'=>['neq',9],'fid'=>$shangpuData['id'],'is_song'=>0])->save(['is_jianchu'=>0]);

                    redirect(U('Web/Ruzhu/merchant'));
                }else if($orderData['type'] == 10){//用户缴纳会员费
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>10,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //修改用户状态
                    M('user')->where(['uid'=>$orderData['uid']])->setField(['j_user'=>0]);
                    //增加积分
                    //M('integral')->add(['uid'=>$orderData['uid'],'type'=>10,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    //*****************************************
                    $uname = M('user')->where(['uid'=>$orderData['uid']])->getField('uname');
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    $ip=get_client_ip();
                    $sname=md5('xb'.$_SERVER['HTTP_USER_AGENT']);
                    session($sname,$orderData['uid']);

                    $salt = C("COOKIE_SALT");
                    $ua = $_SERVER['HTTP_USER_AGENT'];
                    $auth = password($orderData['uid'].$uname.$ua.$salt);
                    //设置前台cookie
                    cookie('hauth', $auth, 3600 * 24);//记住我

                    //修改最后登陆时间和ＩＰ
                    M('user')->where(['uid'=>$orderData['uid']])->setField(['last_login'=>$_SERVER['REQUEST_TIME'],'last_ip'=>$ip]);

                    //*****************************************
                    redirect(U('User/index'));
                }else {//其他

                    //检测是商城买东西的时候减少库存
                    $order_detail = M('order_detail')->field('is_huan_mai,cid,good_num,sid')->where(['order_no'=>$out_trade_no,'order_status'=>0])->find();
                    if($order_detail['is_huan_mai'] == 1){//购买
                        M('good')->where(['cang_id'=>$order_detail['cid']])->setDec('good_number',$order_detail['good_num']);
                        //2018.05.31 cang_id
                    }else if($order_detail['is_huan_mai'] == 2){//换取
                        M('user_collection')->where(['uid'=>$orderData['uid'],'cid'=>$order_detail['cid']])->save(['status'=>0]);
                    }
                    //修改详情订单状态已支付
                    M('order_detail')->where(['order_no'=>$out_trade_no,'order_status'=>0])->save(['pay_state'=>1]);
                    //添加支付记录
                    M('mrecord')->add(['uid'=>$orderData['uid'],'type'=>4,'updown'=>2,'money'=>$orderData['money'],'createtime'=>time()]);
                    //添加支付状态
                    $orderObj->where(['id'=>$orderData['id'],'order_no'=>$out_trade_no])->save(['pay_type'=>1]);
                    //添加消费统计记录
                    M('consumption')->add(['uid'=>$orderData['uid'],'type'=>3,'money'=>$orderData['money'],'c_time'=>date('Ym'),'c_times'=>time()]);
                    //增加积分
// 					M('integral')->add(['uid'=>$orderData['uid'],'type'=>10,'num'=>$orderData['money'],'add_time'=>time(),'ym'=>date('Ym',time())]);
                    redirect(U('Order/myorder',array('status'=>10)));
                }
            }else if($orderData['pay_status'] == 1){

                if ($orderData['type'] == 1) {
                    //充值返回
                    redirect(U('User/index'));
                }elseif($orderData['type'] == 2){
                    //斗宝返回
                    //首先查询跳转信息
                    $jumpData = M('doubao_order')->field('dou_id,ordernum')->where(['ordernum'=>$out_trade_no,'pay_status'=>1])->find();
                    redirect(U('Canjia/uploadingBaby',['id'=>$jumpData['dou_id'],'order'=>$jumpData['ordernum']]));
                }elseif($orderData['type'] == 4){//商家加盟
                    redirect(U('Ruzhu/applyformerchant'));
                }else if($orderData['type'] == 5){//会员升级
                    redirect(U('Ruzhu/merchant'));
                }elseif($orderData['type'] == 10){//用户缴纳会员费
                    //*****************************************
                    $uname = M('user')->where(['uid'=>$orderData['uid']])->getField('uname');
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    $ip=get_client_ip();
                    $sname=md5('xb'.$_SERVER['HTTP_USER_AGENT']);
                    session($sname,$orderData['uid']);

                    $salt = C("COOKIE_SALT");
                    $ua = $_SERVER['HTTP_USER_AGENT'];
                    $auth = password($orderData['uid'].$uname.$ua.$salt);
                    //设置前台cookie
                    cookie('hauth', $auth, 3600 * 24);//记住我
                    //修改最后登陆时间和ＩＰ
                    M('user')->where(['uid'=>$orderData['uid']])->setField(['last_login'=>$_SERVER['REQUEST_TIME'],'last_ip'=>$ip]);
                    //*****************************************
                    redirect(U('User/index'));
                }else{//其他
                    redirect(U('Order/myorder',array('status'=>10)));
                }
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }else {
            //验证失败
            file_put_contents('retuen1.txt','同步:验证失败'.PHP_EOL,FILE_APPEND);
            echo "验证失败";
        }
    }
}
