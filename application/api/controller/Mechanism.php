<?php
/**
 --------------------------------------------------
 空间类型   商品控制器
 --------------------------------------------------
 Copyright(c) 2017 时代万网 www.agewnet.com
 --------------------------------------------------
 开发人员: lichao  <729167563@qq.com>
 --------------------------------------------------

 */
namespace app\api\controller;
use think\Db;

class Mechanism extends Base{

    public function index()
    {
        $company_name=I('post.company_name');
        $social_code=I('post.social_code');
        $idcard=I('post.idcard');
        $phone=I('post.phone');
        $username=I('post.username');
        $idcard_img=I('post.idcard_img');
        $yinyep_img=I('post.yinyep_img');
        $uid=I('post.uid');
        if (empty($company_name)){
            return  returnBad('公司名称不可为空');
        }else if(empty($social_code)){
            return  returnBad('社会信用代码不可为空');
        }else if(empty($idcard)){
            return  returnBad('身份证号不可为空');
        }else if(empty($phone)){
            return  returnBad('手机号不可为空');
        }else if(empty($username)){
            return  returnBad('姓名不可为空');
        }else if(empty($idcard_img)){
            return  returnBad('请上传身份证照片');
        }else if(empty($yinyep_img)){
            return  returnBad('请上传营业执照照片');
        }else if(empty($uid)){
            return  returnBad('请带上uid');
        }
        $data['addtime']=time();
        $data['company_name']=$company_name;
        $data['social_code']=$social_code;
        $data['idcard']=$idcard;
        $data['phone']=$phone;
        $data['username']=$username;
        $data['idcard_img']=$idcard_img;
        $data['yinyep_img']=$yinyep_img;
        $data['uid']=$uid;
        //判断是否已申请过
        $issq=Db::name('user_mechanism')->where('uid',$uid)->find();
        if ($issq){
            return returnBad('已申请过！');
        }else{
            $inserid=Db::name('user_mechanism')->insert($data);
            if ($inserid){
                return returnOk('申请成功');
            }else{
                return  returnBad('申请失败');
            }
        }

    }
}