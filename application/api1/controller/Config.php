<?php
/**
 --------------------------------------------------
 势像空间   商品控制器
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 Author: ytz <9323336103@qq.com>
 --------------------------------------------------
 问君能有几多愁，拖工资要多愁有多愁
 --------------------------------------------------
 小楼昨夜又东风，明天就得去喝西北风
 --------------------------------------------------
 */
namespace app\api\controller;

use think\Db;

class Config extends Base{
    
    public  function  get_openid(){
        $post = $this->check_post();
        $code = $post['code'];
        $appid =C('APPID');
        $secret = C('SECRET');
        $model =  M('users');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";
        $return = file_get_contents($url);
        if ($return){
            $data = json_decode($return,true);
            if ($data['openid']){
                $where = array('openid' => $data['openid']);
                /*查询该用户是否存在*/
                $user = $model->where($where)->field('user_id,first_leader,second_leader,third_leader')->find();
                if(!$user){
                    //过滤特殊字符串
                    $post['nickName'] && $post['nickName'] = replaceSpecialStr($post['nickName']);
                    $userinfo = array(
                        'nickname' => $post['nickName'],
                        'head_pic' => $post['avatarUrl'],
                        'province' => $post['province'],
                        'city'     => $post['city'],
                        'openid'    =>$data['openid'],
                        'first_leader' => $post['first_leader'],
                        'oauth' =>'weixin',
                        'sex' => $post['gender'],
                        'reg_time'=> time(),
                    );
                    // 如果找到他老爸还要找他爷爷等
                    if($post['first_leader'])
                    {
                        $first_leader = M('users')->where("user_id", $post['first_leader'])->find();
                        $userinfo['second_leader'] = $first_leader['first_leader'];
                        //他上线分销的下线人数要加1
                        M('users')->where(array('user_id' => $userinfo['first_leader']))->setInc('underling_number');
                        M('users')->where(array('user_id' => $userinfo['second_leader']))->setInc('underling_number');
                    }else
                    {
                        $userinfo['first_leader'] = 0;
                    }
                    $userinfo['user_no'] = getUserNo();
                    $row_id = Db::name("users")->insertGetId($userinfo);
                    // 会员注册送优惠券
                    $coupon = M('coupon')->where("send_end_time > ".time()." and ((createnum - send_num) > 0 or createnum = 0) and type = 4")->select();
                    foreach ($coupon as $key => $val)
                    {
                        // 送券
                        M('coupon_list')->add(array('cid'=>$val['id'],'type'=>$val['type'],'uid'=>$row_id,'send_time'=>time()));
                        M('Coupon')->where("id", $val['id'])->setInc('send_num'); // 优惠券领取数量加一
                    }
                }
                return returnOk($data);
            }else{
                return returnBad("获取失败", 301);
            }
        }else{
            return returnBad("获取失败", 300);
        }
        
       /*  //正常返回的JSON数据包
        {
            "openid": "OPENID",
            "session_key": "SESSIONKEY",
            "unionid": "UNIONID"
        }
        //错误时返回JSON数据包(示例为Code无效)
        {
            "errcode": 40029,
            "errmsg": "invalid code"
        } */
        
    }

    public function invitation(){
        $post = $this->check_post();
        if (empty($post['openid'])) {
            return returnBad("用户openid不能为空", 303);
        }
    }
}
