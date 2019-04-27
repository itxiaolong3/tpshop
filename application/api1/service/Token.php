<?php


namespace app\api\service;

use think\Cache;
use think\Exception;
use think\Request;
use app\api\model\Users;

class Token
{

    // 生成令牌
    public static function generateToken()
    {
        $timestamp = date('Y-m-d',time());
        $tokenSalt = config('secure.token_salt');
        return md5( $timestamp . $tokenSalt);
    }

    //生成用户Token
    public static function makeUserToken(){
        $randChar = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }


    public static function checkToken($token = ''){
        if($token != Token::generateToken()){
            return false;
        }else{
            return true;
        }
    }



    public static function checkUserToken($token){
        $res = Users::get(['token'=>$token]);
        if(!$res || !$token){
            returnApiError('请求token不存在！');
        }

        if(self::checkTokenExpress($token)===false){
            returnApiError('请求token已过期！','',303);
        };
        return true;
    }

    //检查token是否过期
    public static function checkTokenExpress($token){
        $res = Users::get(['token'=>$token]);
        if($res->token_express < time()){
            return false;
        } else{
            return true;
        }
    }


    //更新token过期时间
    public static function updateTokenExpress($token,$time = ''){
        if($time == ''){
            $new_time = time()+config('secure.express');
        }else{
            $new_time = time() + $time;
        }
        $new_token = self::makeUserToken();
        $res = Users::where('token',$token)->update(['token_express'=>$new_time,'token'=>$new_token]);
        if($res){
            return ['express'=>$new_time,'token'=>$new_token];
        }else{
            return false;
        }
    }


}