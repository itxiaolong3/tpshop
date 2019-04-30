<?php

namespace app\api\controller;

use app\api\logic\UserLogic;
use think\Controller;
use think\Cookie;
use think\Session;
use think\Db;

class Base extends Controller
{

    public $user_id = ''; //用户id
    public $user = '';  //用户信息
    public $token = '';  //用户token
    public $tpshop_config = array();

    /**
     * 检验签名是否正确
     * @param string $Sign
     * @return bool
     */
    public function _initialize()
    {
        parent::_initialize();
        //$this->checkSign(); //验证签名

        //设置跨域
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods:POST,GET");
        header("Access-Control-Allow-Headers:x-requested-with,content-type,openid");
//        header("Content-type:text/json;charset=utf-8");
        $this->user = Cookie::get('user');
        $this->user_id = Cookie::get('user_id');
        $this->token = I("token");
        if ((!$this->user || !$this->user_id) && $this->token || $this->token != $this->user["token"]) {
            $userLogic = new UserLogic();
            $this->user = $userLogic->getuser($this->token);
            $this->user_id = $this->user['user_id'];
            Cookie::set('user', $this->user);
            Cookie::set('user_id', $this->user_id);
            Session::set('user', $this->user);
            Session::set('user_id', $this->user_id);
        }
        //var_dump($_SERVER["REDIRECT_URL"]);die;
        if (Self::checkAction() === true) {
            return true;//不需要检验token的接口
        } else {
            if (empty($this->token)) {
                return true;
                //exit(json_encode(['code' => 401, 'status' => 0, 'msg' => '请先登录', 'return_url' => 'http://'.$_SERVER['SERVER_NAME'].'/api/login/index']));
            }
            if ($this->checkUserToken($this->token) === false) {//需要检验token的接口
                exit(json_encode(['code' => 401, 'status' => 0, 'msg' => 'token过期,请重新登陆', 'return_url' =>'http://'.$_SERVER['SERVER_NAME'].'/api/login/index']));
            }
            $userinfo = M('users')->where(['token'=>$this->token])->find();

            if(empty($userinfo)){
                return returnBad('请求token有误！',303);
            }else{
                $this->user=$userinfo;
                $this->user_id = $userinfo['user_id'];
            }
        }
    }

    public static function checkUserToken($token){
        $res = Db::name('users')->where(array('token'=>$token))->find();
        if(!$res || !$token){
            //return returnBad('请求token不存在！',303);
            exit(json_encode(['code' => 303, 'status' => 0, 'msg' => '请求token不存在']));
        }
        if(self::checkTokenExpress($token)===false){
            //return returnBad('请求token已过期！',303);
            exit(json_encode(['code' => 303, 'status' => 0, 'msg' => '请求token已过期']));
        };
        return true;
    }

    //检查token是否过期
    public static function checkTokenExpress($token){
        $res = M('users')->where(['token'=>$token])->find();
        if($res['token_express'] < time()){
            return false;
        } else{
            return true;
        }
    }

    /**
     * 验证签名
     * @throws CommException
     */
    public function checkSign()
    {
        $sign = request()->header('sign');
        $time = request()->header('time');
        //file_put_contents("1.txt",$sign);

        $mysign = md5(md5($time . C('secure.sign_salt')));
        if (empty($sign) || empty($time)) {
            exit(json_encode(['code' => 302, 'msg' => '请求参数缺失']));
        }
        if ($sign != $mysign) {
            exit(json_encode(['code' => 301, 'msg' => '调用错误']));
        }
        if ($time + 2150000 - time() < 0) {
            exit(json_encode(['code' => 301, 'msg' => '调用错误']));
        }
    }

    /**
     * 检验接口方法是否需要token
     * @return bool
     */
    public static function checkAction()
    {
        $action_name = ACTION_NAME;
        $config = ['login'];
        if (in_array($action_name, $config)) {
            return true;
        } else {
            return false;
        }
    }

}