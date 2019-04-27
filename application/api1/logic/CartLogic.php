<?php
/**
 --------------------------------------------------
 空间类型   购物车模型
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace Api\service;
use Think\Model;
class CartModel
{
	/*添加到购物车*/
	public function addcart($post)
    {
        $gid = $post['gid'];          //商品id
        $spec_id = $post['spec_id'];  //规格串
        $num = $post['num'];          //数量
        $openid = $post['openid'];    //用户openid
        $cart = M('cart');            //购物车表
        if (empty($gid)) {
            return ['code' => '302', 'msg' => '参数错误'];
        }
        $user = M("user")->where(['openid' => $openid])->find();
        if (empty($spec_id)) {
            $spec_type = M('goods_specifications')->field('id')->where(array('goods_id' => $gid))->group('specifications')->select();
            if ($spec_type) {
                return ['code' => '302', 'msg' => '请选择商品规格'];
//                foreach ($spec_type as $value){
//                    $spec_id .= $value['id'].','; //拼接规格id串
//                }
//                $spec_id=substr($spec_id, 0, -1);
            }
            $exist = $cart->where(array('goods_id' => $gid, 'user_id' => $user['id']))->find();
        } else {
            $exist = $cart->where(array('goods_id' => $gid, 'specifications_id' => $spec_id, 'user_id' => $user['id']))->find();
        }
        if ($num) {
            if ($exist) {
                $result = $cart->where(array('id' => $exist['id']))->save(array('number' => array('exp', "number+$num")));
            } else {
                $result = $cart->add(array('goods_id' => $gid, 'specifications_id' => $spec_id, 'user_id' => $user['id'], 'number' => $num, 'username' => $user['username'], 'add_time' => time()));
            }
        } else {
            if ($exist) {
                $result = $cart->where(array('id' => $exist['id']))->save(array('number' => array('exp', "number+1")));
            } else {
                $result = $cart->add(array('goods_id' => $gid, 'specifications_id' => $spec_id, 'user_id' => $user['id'], 'number' => 1, 'username' => $user['username'], 'add_time' => time()));
            }
        }
        return $result;

    }

    /*修改购物车商品数量*/
    public function saveNum($post){
        $cart = M('cart')->where(array('id' => $post['id'], 'user_id' => $post['uid']))->save(array('number' => $post['number']));
        return $cart;
    }

	/*购物车列表*/
	public function cartList($post){
        $openid = $post['openid'];        //用户标识openid
        $page= $post['page'];            //分页
	       if(empty($page)){
	       	$page = 1;
	       }
        
        $cart=M('cart');    //购物车表
        $goods=M('goods');  //商品表
        $user = M("user")->where(['openid'=>$openid])->find();
        $cart_list=$cart->alias('c')
            ->join('zk_goods as g on c.goods_id=g.id')
            ->field('c.id as cid,c.specifications_id,c.number,g.id as gid,g.goods,g.price,g.pic,g.shop_price')
            ->where(array('g.state' => 1 , 'c.user_id'=>$user['id']))
            ->group('c.goods_id,c.specifications_id')
            ->order('c.add_time')
            ->limit(($page-1)*10,10)
            ->select();
           foreach ($cart_list as $key => $value){
           	if($value['pic']){
              $value['pic']='http://' . $_SERVER['HTTP_HOST'] .__ROOT__.$value['pic'];
           	}
            if(!empty($value['specifications_id'])){
                if( strrchr($value['specifications_id'],',') == ',' ){
                    $fid_list=substr($value['specifications_id'], 0, -1); 
                }else {
                    $fid_list=$value['specifications_id'];
                }
                $fid_arr= explode(",", $fid_list);  //--转换成数组--
                $spec[$key]=M('goods_specifications')->field('specifications,money')->where(array('id' => array('IN',$fid_arr)))->select();
                
                    foreach ($spec[$key] as $k =>$v){
                        $spec1[$key] .=$v['specifications'].',';   //拼接商品规格
                        if($v['money'] > 0){
                            $money=$v['money'];
                        }
                    }
                    
                    $value['spec']=substr($spec1[$key], 0, -1);
                    $value['money']=$money;
            }
            $aim_arr[]=$value;
        }
        $aim_arr =!empty($aim_arr)?$aim_arr:1;
        unset($key,$value);
        return $aim_arr;
	}

	/*删除购物车*/
	public function delCart($post){
        $arr_cid=$post['arr_cid'];  //购物车id数组集合
        $id = json_decode($arr_cid,true);
        $cart=M('cart');    //购物车表
        foreach($id as $k=>$v){ 
          $result=$cart->where(array('id'=>$v))->delete(); 
           }
        return $result;
	}

    //确认订单(立即购买)
    public function order(){
        $post=I("post.");
        $ar = getallheaders();
        $token = $ar['Token'];
        $result = M('token')->where(['token'=>$token])->find();
        if(!$result){
            $this->ajaxReturn(['code'=>'301','msg'=>'你未登录，请登录']);
            exit();
        }
        if ($result['add_time']+24*3600*7 < time()){
            $this->ajaxReturn(['code'=>'302','msg'=>'你未登录，请登录']);
            exit();
        }
        $id =  $result['user_id'];
        $re11 = M('user')->where('id='.$result['user_id'])->find();
        if(!$re11){
            $this->ajaxReturn(['code'=>'303','msg'=>'用户不存在']);
            exit();
        }

        //查询出默认地址
        $addr = M("address")->where("user_id = $id and type = 1")->find();
        if(!$addr){
            $aa = M("address")->where("user_id = $id")->select();
            if($aa[0]){
                $addr = $aa[0];
            }else{
                $addr = '';
            }

        }
//        var_dump($addr);die;

        $state = $post['state'];
        $good = $post['goods'];//购买商品的情况
        $arr = json_decode(urldecode($good), true);
//        var_dump($arr);dump($state);die;
        if($state == 2){//一件商品-立即购买
            $goods_id = $arr[0]['goods_id'];
//            echo $goods_id;die;
            if(!$goods_id){
                $this->ajaxReturn(['code'=>'308','msg'=>'商品ID没有传']);
                exit();
            }
            $number = $arr[0]['number'];
            if(!$number){
                $this->ajaxReturn(['code'=>'309','msg'=>'商品数量没有传']);
                exit();
            }
            $specifications = $arr[0]['specifications_id'];
            $re = M("goods")->where("id= $goods_id")->find();
            $pic = M('goods_picture')->where("goods_id=".$goods_id)->select();
            $price = (float)round($re['price']*$re['preferential'],2);
            if($re['sp']==1){
                if(!$specifications){
                    $this->ajaxReturn(['code'=>'310','msg'=>'商品规格没有传']);
                    exit();
                }
                $r = M("goods_specifications")->where("id = $specifications and goods_id = $goods_id")->find();
                $gospecifications = $r['specifications'];
                $price = (float)round($r['money']*$re['preferential'],2);
            }else{
                $gospecifications = '';
                $specifications = '';
            }
            $money = $price*$number;
            $goods_arr[0]['goods_id'] = $goods_id;
            $goods_arr[0]['price'] = $price*$number;
            $goods_arr[0]['number'] = "$number";
            $goods_arr[0]['goods']=$re['goods'];
            $goods_arr[0]['picture'] = $pic[0]['url'];
            $goods_arr[0]['specifications'] = $gospecifications;
            $goods_arr[0]['specifications_id'] = $specifications;

        }elseif($state == 1){//购物车
            $money = 0;
            $goods_arr = array();
            foreach($arr as $k=>$v){
                if($v['specifications_id']){
                    $cc = M("cart")->where("user_id = $id and specifications_id=".$v['specifications_id']." and goods_id = ".$v['goods_id'])->find();
                }else{
                    $cc = M("cart")->where("user_id = $id and goods_id = ".$v['goods_id'])->find();
                }
//                var_dump($v['number']);die;
                $ggo = M("goods")->where("id=".$v['goods_id'])->find();
                if(!$cc){
                    $this->ajaxReturn(['code'=>'311','msg'=>'购物车商品'.$ggo['goods'].'不存在']);
                    exit();
                }
                if($cc['number'] != $v['number']){
                    $cc['number'] = $v['number'];
                }

                $pic = M('goods_picture')->where("goods_id=".$cc['goods_id'])->select();
                $goods_arr[$k]['number'] = $cc['number'];
                $goods_arr[$k]['goods_id'] = $cc['goods_id'];
                $goods_arr[$k]['goods'] = $ggo['goods'];
                $goods_arr[$k]['picture'] = $pic[0]['url'];
                if(!$ggo){
                    M("cart")->where("user_id = $id and goods_id = ".$v['goods_id'])->delete();
                    $this->ajaxReturn(['code'=>'330','msg'=>'商品'.$ggo['goods'].'不存在']);
                    exit();
                }
                if($cc['specifications_id']){
                    $spp = M("goods_specifications")->where("id='".$cc['specifications_id']."' and goods_id=".$cc['goods_id'])->find();
                    if(!$spp){
                        M("cart")->where("user_id = $id and goods_id = ".$v['goods_id'])->delete();
                        $this->ajaxReturn(['code'=>'320','msg'=>'该规格商品已不存在，请重新购物']);
                        exit();
                    }
                    $pricenumber = ((float)round($spp['money']*$ggo['preferential'],2))*$cc['number'];
                    $goods_arr[$k]['price'] = "$pricenumber";
                    $goods_arr[$k]['specifications_id'] = $spp['id'];
                    $goods_arr[$k]['specifications'] = $spp['specifications'];
                    $money += ((float)round($spp['money']*$ggo['preferential'],2))*$cc['number'];
                }else{
                    if($ggo['sp']==1){
                        $msg = '商品'.$ggo['goods'].'有规格，请重新选择';
                        M("cart")->where("user_id = $id and goods_id = ".$v['goods_id'])->delete();
                        $this->ajaxReturn(['code'=>'338','msg'=>$msg]);
                        exit();
                    }
//                    var_dump($ggo);die;
                    $pricenumber = ((float)round($ggo['price']*$ggo['preferential'],2))*$cc['number'];
                    $goods_arr[$k]['price'] = "$pricenumber";
                    $goods_arr[$k]['specifications_id'] = '';
                    $goods_arr[$k]['specifications'] = '';
                    $money += ((float)round($ggo['price']*$ggo['preferential'],2))*$cc['number'];
                }



            }

        }else{
            $this->ajaxReturn(['code'=>'500','msg'=>'非法操作']);
            exit();
        }
//        var_dump($goods_arr);die;
        $date['goods'] = $goods_arr;
        $coupon_id = $post['coupon_id'];
//        echo $id;die;
        if($coupon_id){
            $coupon = M("coupon_list")->where("user_id=$id and cid=$coupon_id and use_time is null")->find();
        }else{
            $coupon_id = '';
        }
//        var_dump($coupon);die;
        $cmoney = 0;
        if($coupon){
            $cou = M("coupon")->where("id=".$coupon['cid'])->find();
//            var_dump($cou);die;
            if($cou['use_end_time']){
                if($cou['use_end_time']>time()){
//                    echo $cou['condition'];die;
                    if(($cou['condition']>$money)){
                        $this->ajaxReturn(['code'=>'340','msg'=>'优惠券不可用']);
                        exit();
                    }else{
                        $cmoney += $cou['money'];
                    }

                }else{
                    $this->ajaxReturn(['code'=>'340','msg'=>'优惠券不可用']);
                    exit();
                }
            }

        }
//        echo  $cmoney;die;
        $date['coupon'] = "$cmoney";
        $date['coupon_id'] = $coupon_id;
        $date['money1'] = "$money";
        $mon2 = $money-$cmoney;
        $date['money2'] = "$mon2";
        if($addr){
            $date['address_id'] = $addr['id'];
            $date['username'] = $addr['consignee'];
            $date['phone'] = $addr['tel'];
            $date['addr'] = $addr['addrcity'].$addr['address'];
        }else{
            $date['address_id'] = '';
            $date['username'] = '';
            $date['phone'] = '';
            $date['addr'] = '';
        }
        $date['freight'] = '0.00';


        $this->ajaxReturn(['code'=>'200','msg'=>'查询成功','data'=>$date]);
        exit();
    }

//    //查询可用优惠券
//    public function coupon(){
//
//    }

    //生成订单
    public function addorder(){
        $post=I("post.");

        $ar = getallheaders();
        $token = $ar['Token'];
        $result = M('token')->where(['token'=>$token])->find();
//        echo $token;die;
        if(!$result){
            $this->ajaxReturn(['code'=>'301','msg'=>'你未登录，请登录']);
            exit();
        }
        if ($result['add_time']+24*3600*7 < time()){
            $this->ajaxReturn(['code'=>'302','msg'=>'你未登录，请登录']);
            exit();
        }
        $id =  $result['user_id'];
        $u = M('user')->where(['id' => $id])->find();
        if(!$u){
            $this->ajaxReturn(['code'=>'303','msg'=>'会员不存在']);
            exit();
        }

        //选择地址
        $address_id = $post["address_id"];
        if(!$address_id){
            $this->ajaxReturn(['code'=>'317','msg'=>'地址没有选择']);
            exit();
        }
        $addr = M('address')->where("id=$address_id and user_id =$id")->find();
        if(!$addr){
            $this->ajaxReturn(['code'=>'318','msg'=>'地址不存在']);
            exit();
        }
        $particulars = $post["particulars"];
        $type = $post["type"];
        if(!$type){
            $this->ajaxReturn(['code'=>'319','msg'=>'支付方式没有选择']);
            exit();
        }
        $good = $post['goods'];//购买商品的情况
        if(!$good){
            $this->ajaxReturn(['code'=>'308','msg'=>'商品没有传']);
            exit();
        }
//        $arr = json_decode($good);
        $arr = json_decode(urldecode($good), true);
//        var_dump($arr);die;
        $state = $post["state"];
        $coupon_id = $post['coupon_id'];
        if($coupon_id){
            //查询出优惠券情况
            $coupon = M("coupon_list")->alias('a')
                ->field("a.*,c.use_end_time,c.use_start_time,c.money,c.condition")
                ->join('left join __COUPON__ as c on c.id=a.cid')
                ->where("a.cid='$coupon_id' and a.user_id = $id and a.use_time is null")
                ->find();
            if($coupon['use_start_time']>time()){
                $this->ajaxReturn(['code'=>'340','msg'=>'优惠券还不可以使用']);
                exit();
            }
            if($coupon['use_end_time']){
                if($coupon['use_end_time']<time()){
                    $this->ajaxReturn(['code'=>'340','msg'=>'优惠券已不可以使用']);
                    exit();
                }
            }

        }

//        $u = M('user')->where(['id' => $id])->find();



        //进行分销划分收益
        //查询分销比例-分销比例表
        $distrubution = M('distribution')->find();
        //获取经销商信息-经销商表
//        $addrstr =  $addr['addrcity'];
        $total1 = M('total')->where("province='".$addr['province']."' and type=1")->find();
        $total2 = M('total')->where("province='".$addr['province']."' and city='".$addr['city']."' and type=2")->find();
        $total3 = M('total')->where("province='".$addr['province']."' and city='".$addr['city']."' and county='".$addr['county']."' and type=3")->find();
//        foreach($total as $v){
//            $addressstr1 = $v[].$v[].$v[]
//            if($addrstr == ){
//                $total1 =
//            }
//        }

        if($state==1){//从购物车过来
            $res = M("cart")->where("user_id = $id")->select();
            if(!$res){
                $this->ajaxReturn(['code'=>'316','msg'=>'购物车中没有商品']);
                exit();
            }
            //查询商品现在的价格并判断商品库存量是否充足
            foreach($arr as $v){

                $g = M("goods")->where("id='".$v['goods_id']."'")->find();
                $ca = M("cart")->where("goods_id = '".$v['goods_id']."' and user_id = $id")->find();

                if(!$ca){
                    $this->ajaxReturn(['code'=>'316','msg'=>'购物车中没有商品'.$g['goods']]);
                    exit();
                }
//                echo $v['specifications_id'];die;
                if($v['specifications_id']){
//                    var_dump($ca);die;
                    if($g['sp']==1){
                        $gs = M("goods_specifications")->where("id='".$v['specifications_id']."'")->find();
//                        var_dump($gs);die;
                        $nnn = M("order")->where("goods_id='".$v['goods_id']."' and state=1 and specifications_id ='".$v['specifications_id']."'")->select();
                        $numm = 0;
                        if($nnn){
                            foreach($nnn as $vo){
                                $numm += $vo['number'];
                            }
                        }
//                        dump($numm);die;
                        if(($gs['number']-$numm)<$v['number']){//判断商品库存量是否充足
                            $this->ajaxReturn(['code'=>'312','msg'=>'商品库存量不足']);
                            exit();
                        }
                        $m1 = (float)round($gs['money']*$g['preferential'],2);
                        $caprice = (float)$ca["price"];
                        if($caprice != $m1 || $v['number'] != $ca['number']){
//                            echo $m1;
                            $cc = M("cart")->where("id='".$ca['id']."'")->save(array("price"=>$m1,"number"=>$v['number']));
//                            dump($ca['id']);
                            if(!$cc){
//                                die;
                                $this->ajaxReturn(['code'=>'400','msg'=>'系统出错，请重试']);
                                exit();
                            }

                        }

                    }else{
                        $this->ajaxReturn(['code'=>'320','msg'=>'该规格商品已不存在，请重新购物']);
                        exit();
                    }
                }else{
                    $nnn = M("order")->where("goods_id='".$v['goods_id']."' and state=1 and specifications_id is null")->select();
                    $numm = 0;
                    foreach($nnn as $vo){
                        $numm += $vo['number'];
                    }
                    if(($g['number']-$numm)<$v['number']){//判断商品库存量是否充足
                        $this->ajaxReturn(['code'=>'312','msg'=>'商品库存量不足']);
                        exit();
                    }
                    $round = (float)round($g['price']*$g['preferential'],2);
                    $pprices =  (float)$ca['price'];

                    if($pprices != $round || $v['number'] != $ca['number']){
                        $cc = M("cart")->where("id='".$ca['id']."'")->save(array("price"=>($g['price']*$g['preferential']),"number"=>$v['number']));

                        if(!$cc){
                            $this->ajaxReturn(['code'=>'400','msg'=>'系统出错，请重试']);
                            exit();
                        }
                    }
                }

            }
//            var_dump($arr);die;
            foreach($arr as $v){
                $resuu[] = M("cart")->where("user_id = $id and goods_id='".$v['goods_id']."'")->find();
            }

            $order_id = date('Ymdhis') . time() . mt_rand(1000, 9999);

            M()->startTrans();
            $s = true;
            $allmoney = 0;
//            var_dump($resuu);die;
//            var_dump($addr);die;
            foreach($resuu as $v){
                $amp = array(
                    "order_id" => $order_id,
                    'goods' => $v['goods'],
                    'goods_id' => $v['goods_id'],
                    'specifications_id' => $v['specifications_id'],
                    'price' => $v['price'],
                    'user_id' => $u['id'],
                    'username' => $u['username'],
                    'number' => $v["number"],
                    'address' => $addr['address'],
                    'tel' => $addr['tel'],
                    'consignee' => $addr['consignee'],
                    'city'  => $addr['addrcity'],
                    'type' => $type,
                    'state' => 1,
                    'coupon_id' => $coupon_id,
                    'particulars' => $particulars,
                    'add_time' => time()
                );
//                var_dump($amp);die;
                $re = M("order")->add($amp);
//                var_dump($re);die;
                if(!$re){
                    $s = false;
                }
                $allmoney  += ($v['price']*$v['number']);

            }
//            var_dump($resuu);die;
            foreach($resuu as $v){
                //删除
                $ccart = M("cart")->where("id=".$v["id"])->delete();
                if(!$ccart){
                    $s = false;
                }
            }
            if($coupon_id){
                $lis = M("coupon_list")->where("user_id='".$u['id']."' and cid='$coupon_id'")->save(array("use_time"=>time(),"order_id"=>$order_id));
                if(!$lis){
                    $s = false;
                }

                $coupp = M("coupon")->where("id=$coupon_id")->setInc("use_num");
                if(!$coupp){
                    $s = false;
                }

                if($coupon['condition']<$allmoney){
                    if(($allmoney-$coupon['money'])>0){
                        $allmoney -= $coupon['money'];
                    }
                }else{
                    $s = false;
                }

            }
            $save = M("order")->where("order_id='$order_id' and user_id = ".$u['id'])->save(array('allmoney'=>$allmoney));
            if(!$save){
                $s = false;
            }
            $u = M('user')->where(['id' => $id])->find();
            if(($type!=1) &&($type!=2)){
                $moneyy = $u["balance"] - $allmoney;
                if($moneyy<0){
                    $this->ajaxReturn(['code' => '300', 'msg' => '余额不足']);
                    exit();
                }
            }

            $earning = array(
                'order_id' => $order_id,
                'user_id' => $u['id'],
                'username' => $u['username'],
                'fid1' => $u['fid'],
                'fusername1' => $u['fname'],
                'fid2' => $u['fid2'],
                'fusername2' => $u['fname2'],
                'fid3' => $u['fid3'],
                'fusername3' => $u['fname3'],
                'money1' => $allmoney*$distrubution['first_per'],
                'money2' => $allmoney*$distrubution['second_per'],
                'money3' => $allmoney*$distrubution['third_per'],
                'price' => $allmoney,
                'total_id1' => $total1['user_id'],
                'total_name1' => $total1['username'],
                'goods_total' => $allmoney*$distrubution['first_total_per'],
                'total_id2' => $total2['user_id'],
                'total_name2' => $total2['username'],
                'goods_tota2' => $allmoney*$distrubution['second_total_per'],
                'total_id3' => $total3['user_id'],
                'total_name3' => $total3['username'],
                'goods_tota3' => $allmoney*$distrubution['third_total_per'],
                'add_time' => time()
            );
            $ea = M('earnings')->add($earning);
            if(!$ea){
                $s = false;
            }

            if($s){

//                var_dump($resuu);die;


                if($type == 1){
                    M()->commit();
//					$url = U('Api/Wallet/index',array('order_id'=>$order_id));
//                    $url = U('Api/Alipay/index');
                    $a = array('order'=>$order_id);
                    $this->ajaxReturn(['code'=>'200','msg'=>'订单生成成功','data'=>$a]);die;
//                    $url = U('Api/Wallet/index',array('order_id'=>$order_id,'money'=>$allmoney,'Token'=>$token));
//                    $url = U('Api/Alipay/index');
                }elseif($type == 2){
                    M()->commit();
                    $url = U('Api/Weipay/index',array('order_id'=>$order_id,'money'=>$allmoney,'Token'=>$token));
//                    $url = U('Api/Weipay/index');
                }else{
                    $u = M('user')->where(['id' => $id])->find();
                    $moneyy = $u["balance"] - $allmoney;
                    if($moneyy<0){
                        $this->ajaxReturn(['code' => '300', 'msg' => '余额不足']);
                        exit();
                    }

//                    M('user')->startTrans();
                    $re1 = M('user')->where("id = $id")->save(array("balance" => $moneyy));
                    $re2 = M("order")->where(['user_id'=>$id ,'order_id'=> $order_id])->save(['state' =>2]);
                    if($re1 && $re2){
                        M()->commit();
                        $this->ajaxReturn(['code' => '200', 'msg' => '余额支付成功，等待卖家发货！']);
                        exit();
                    }else{
                        M()->rollback();
                        $this->ajaxReturn(['code' => '300', 'msg' => '支付失败']);
                        exit();
                    }
                }
//                $data = array('order_id'=>"$order_id",'money'=>"$allmoney",'url'=>"$url");
//                $this->ajaxReturn(['code'=>'200','msg'=>'订单生成成功','data'=>$data]);
//                exit();
//                echo $url;die;
                redirect($url);
                exit();
            }else{
                M()->rollback();
                $this->ajaxReturn(['code'=>'321','msg'=>'订单生成失败']);
                exit();
            }
        }elseif($state==2){
//            var_dump($arr);die;
            $g = M("goods")->where("id='".$arr[0]['goods_id']."'")->find();
            if($arr[0]['specifications_id']){
                if($g['sp']==1){
                    $gs = M("goods_specifications")->where("id='".$arr[0]['specifications_id']."'")->find();
                    $nnn = M("order")->where("goods='".$g['goods']."' and state=1 and specifications_id ='".$g['specifications_id']."'")->select();
                    $numm = 0;
                    foreach($nnn as $vo){
                        $numm += $vo['number'];
                    }
                    if(($gs['number']-$numm)<$arr[0]['number']){//判断商品库存量是否充足
                        $this->ajaxReturn(['code'=>'312','msg'=>'商品库存量不足']);
                        exit();
                    }
                    $mm1 = (float)round($gs['money']*$g['preferential'],2);

                }else{
                    $this->ajaxReturn(['code'=>'320','msg'=>'该规格商品已不存在，请重新购物']);
                    exit();
                }
            }else{
                $nnn = M("order")->where("goods_id='".$arr[0]['goods_id']."' and state=1 and specifications_id is null")->select();
                $numm = 0;
                foreach($nnn as $vo){
                    $numm += $vo['number'];
                }
                if(($g['number']-$numm)<$arr[0]['number']){//判断商品库存量是否充足
                    $this->ajaxReturn(['code'=>'312','msg'=>'商品库存量不足']);
                    exit();
                }
                $mm1 = (float)round($g['price']*$g['preferential']*$arr[0]['number'],2);
            }
            $order_id = date('Ymdhis') . time() . mt_rand(1000, 9999);
            $mm2 = $mm1;
//            echo $mm2;die;
            if($coupon_id){
                $lis = M("coupon_list")->where("user_id='".$u['id']."' and cid=$coupon_id")->save(array("use_time"=>time(),"order_id"=>$order_id));
                if(!$lis){
                    $this->ajaxReturn(['code'=>'321','msg'=>'订单生成失败']);
                    exit();
                }

                $coupp = M("coupon")->where("id=$coupon_id")->setInc("use_num");
                if(!$coupp){
                    $this->ajaxReturn(['code'=>'321','msg'=>'订单生成失败']);
                    exit();
                }

                if($coupon['condition']<$mm1){
                    if(($mm1-$coupon['money'])>0){
                        $mm1 -= $coupon['money'];
                    }
                }else{
                    $this->ajaxReturn(['code'=>'340','msg'=>'订单生成失败']);
                    exit();
                }

            }


            $amp = array(
                "order_id" => $order_id,
                'goods' => $g['goods'],
                'goods_id' => $g['id'],
                'specifications_id' => $arr[0]['specifications_id'],
                'price' => "$mm2",
                'user_id' => $id,
                'username' => $u['username'],
                'number' => $arr[0]["number"],
                'address' => $addr['address'],
                'tel' => $addr['tel'],
                'consignee' => $addr['consignee'],
                'city'  => $addr['addrcity'],
                'type' => $type,
                'state' => 1,
                'coupon_id' => $coupon_id,
                'particulars' => $particulars,
                'add_time' => time()
            );
            $u = M('user')->where(['id' => $id])->find();
            if(($type!=1) && ($type!=2)){
                $moneyy = $u["balance"] - $mm1;
                if($moneyy<0){
                    $this->ajaxReturn(['code' => '300', 'msg' => '余额不足']);
                    exit();
                }
            }

//            var_dump($amp);die;
            M()->startTrans();
            $re = M("order")->add($amp);
            $save = M("order")->where("order_id='$order_id' and user_id = ".$u['id'])->save(array('allmoney'=>$mm1));
            if(!$save){
                $this->ajaxReturn(['code'=>'321','msg'=>'订单生成失败']);
                exit();
            }
            //进行分销划分收益
            $earning = array(
                'order_id' => $order_id,
                'user_id' => $u['id'],
                'username' => $u['username'],
                'fid1' => $u['fid'],
                'fusername1' => $u['fname'],
                'fid2' => $u['fid2'],
                'fusername2' => $u['fname2'],
                'fid3' => $u['fid3'],
                'fusername3' => $u['fname3'],
                'money1' => $mm1*$distrubution['first_per'],
                'money2' => $mm1*$distrubution['second_per'],
                'money3' => $mm1*$distrubution['third_per'],
                'price' => $mm1,
                'total_id1' => $total1['user_id'],
                'total_name1' => $total1['username'],
                'goods_total' => $mm1*$distrubution['first_total_per'],
                'total_id2' => $total2['user_id'],
                'total_name2' => $total2['username'],
                'goods_tota2' => $mm1*$distrubution['second_total_per'],
                'total_id3' => $total3['user_id'],
                'total_name3' => $total3['username'],
                'goods_tota3' => $mm1*$distrubution['third_total_per'],
                'add_time' => time()
            );
//            echo $mm1;
//            var_dump($earning);die;
            $ea = M('earnings')->add($earning);

            if($re){
                if($type == 1){
                    M()->commit();
//                    $url = U('Api/Wallet/index',array('order_id'=>$order_id));
//                    $url = U('Api/Alipay/index');
                    $a = array('order'=>$order_id);
                    $this->ajaxReturn(['code'=>'200','msg'=>'订单生成成功','data'=>$a]);die;
                }elseif($type == 2){
                    M()->commit();
                    $url = U('Api/Weipay/index',array('order_id'=>$order_id,'money'=>$mm1,'Token'=>$token));
//                    $url = U('Api/Weipay/index');
                }else{
                    $u = M('user')->where(['id' => $id])->find();
                    $moneyy = $u["balance"] - $mm1;
                    if($moneyy<0){
                        $this->ajaxReturn(['code' => '300', 'msg' => '余额不足']);
                        exit();
                    }

//                    M('user')->startTrans();
                    $re1 = M('user')->where("id = $id")->save(array("balance" => $moneyy));
                    $re2 = M("order")->where(['user_id'=>$id ,'order_id'=> $order_id])->save(['state' =>2]);
                    if($re1 && $re2){
                        M()->commit();
                        $this->ajaxReturn(['code' => '200', 'msg' => '余额支付成功，等待卖家发货！']);
                        exit();
                    }else{
                        M()->rollback();
                        $this->ajaxReturn(['code' => '300', 'msg' => '支付失败']);
                        exit();
                    }
                }
//                $data = array('order_id'=>"$order_id",'money'=>"$mm1",'url'=>"$url");
//                $this->ajaxReturn(['code'=>'200','msg'=>'订单生成成功','data'=>$data]);
//                exit();
//                echo $url;die;
                redirect($url);
                exit();
            }else{
                M()->rollback();
                $this->ajaxReturn(['code'=>'321','msg'=>'订单生成失败']);
                exit();
            }
        }else{
            $this->ajaxReturn(['code'=>'500','msg'=>'非法操作']);
            exit();
        }



    }

}