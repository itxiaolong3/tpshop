
<?php
/* *
 * 功能：快捷登录接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */
use think\Model;
class alipaynew extends Model{
    public $appId; // 应用appid
    public $rsaPrivateKey; // 应用私钥
    public $alipayrsaPublicKey; // 支付宝公钥
    public $return_url;

    public function __construct($config){
        $this->appId = $config['app_id'];
        $this->rsaPrivateKey = $config['app_rsa_private_key'];
        $this->alipayrsaPublicKey = $config['alipay_rsa_public_key'];

        $this->return_url = $this->getHttp() ."://".$_SERVER['HTTP_HOST'].U('LoginApi/callback',array('oauth'=>'alipaynew'));
    }

    //构造要请求的参数数组，无需改动
    public function login(){
        $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id={$this->appId}&scope=auth_user&redirect_uri=".urlencode($this->return_url)."&state=init";
        echo("<script> top.location.href='" . $url . "'</script>");
        exit;
    }
    private function getHttp() {
        if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return 'https';
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return 'https';
        } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return 'https';
        }
        return 'http';
    }
    public function respon(){

        if(isset($_GET['auth_code']) && isset($_GET['app_id'])){

            if (!defined("AOP_SDK_WORK_DIR"))
            {
                //define("AOP_SDK_WORK_DIR", "/tmp/");
//                define('AOP_SDK_WORK_DIR', __DIR__  . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR );
                $file = dirname(dirname(dirname(dirname(__FILE__))))  .  '/runtime/tmp';
                if(!file_exists($file)){
                    mkdir ($file,0777,true);
                }
                define('AOP_SDK_WORK_DIR', $file.'/' );
            }

            /**
             * 是否处于开发模式
             * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
             * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
             */
            if (!defined("AOP_SDK_DEV_MODE"))
            {
                define("AOP_SDK_DEV_MODE", true);
            }

            $lotusHome = dirname(__FILE__) . DIRECTORY_SEPARATOR . "lotusphp_runtime" . DIRECTORY_SEPARATOR;
            include($lotusHome . "Lotus.php");
            $lotus = new Lotus;
            $lotus->option["autoload_dir"] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'aop';
            $lotus->devMode = AOP_SDK_DEV_MODE;
            $lotus->defaultStoreDir = AOP_SDK_WORK_DIR;
            $lotus->init();




            $aop = new AopClient ();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = $_GET['app_id'];
            $aop->rsaPrivateKey = $this->rsaPrivateKey;
            $aop->alipayrsaPublicKey = $this->alipayrsaPublicKey;
            $aop->apiVersion = '1.0';
            $aop->signType = 'RSA2';
            $aop->postCharset='UTF-8';
            $aop->format='json';
            $request = new AlipaySystemOauthTokenRequest ();
            $request->setGrantType("authorization_code"); // authorization_code authorization_code
            $request->setCode($_GET['auth_code']);
            $result = $aop->execute ( $request);
            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            $access_token = $result->$responseNode->access_token;
            //$refresh_token = $result->$responseNode->refresh_token;
            if($access_token){
                $user_info['openid'] = $result->$responseNode->user_id;//支付宝用户号
				$user_info['oauth'] = 'alipay';
                $user_info['unionid'] = $result->$responseNode->alipay_user_id;
                // 返回用户信息
                $AlipayUserInfoShareRequest = new AlipayUserInfoShareRequest();
                $result2 = $aop->execute($AlipayUserInfoShareRequest, $access_token);
                $responseNode2 = str_replace(".", "_", $AlipayUserInfoShareRequest->getApiMethodName()) . "_response";
                $resultCode = $result2->$responseNode2->code;
                if(!empty($resultCode) && $resultCode == 10000){
                    $user_info['nickname'] = !empty($result2->$responseNode2->nick_name) ? $result2->$responseNode2->nick_name : '支付宝用户';
                    $user_info['province'] = $result2->$responseNode2->province;
                    $user_info['city'] = $result2->$responseNode2->city;
                    $user_info['head_pic'] = $result2->$responseNode2->avatar;
                }
                $user_info['oauth'] = 'alipay';
                return $user_info;
            } else {
                exit("no access_token");
            }
        }else{
            exit("get? No auth_code and no app_id");
        }
    }

}


?>
