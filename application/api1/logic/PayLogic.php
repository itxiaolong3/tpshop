<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6
 * Time: 10:30
 */

namespace app\api\logic;


class PayLogic
{

    /**
     * 析构流函数
     */
    protected $appid;
    protected $mch_id;
    protected $key;
    protected $openid;
    protected $out_trade_no;
    protected $body;
    protected $total_fee;
    protected $notify_url;
    protected $tui_url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';    //退款请求

    function __construct($openid="",$out_trade_no="",$total_fee=0,$body="下单消费",$notify_url="") {
        $this->appid = 'wxeee285d7fb362acf';
        $this->openid = $openid;
        $this->mch_id = '1521016341';
        $this->key = '3ubv2vSIbLGWLPoLJT94wR7w9mitbsVB';
        $this->out_trade_no = $out_trade_no;
        $this->total_fee = $total_fee;
        $this->body = $body;
        $this->notify_url = $notify_url?$notify_url:url_add_domain('/index.php/api/Weixin/notify/');
    }

    public function pay() {
        //统一下单接口
        $return = $this->weixinapp();
        return $return;
    }

    public function weiReturn($tuiOrder){
        //$out_trade_no=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);//自定义订单号
        //生成请求数据xml
        $data =['appid'=>$this->appid,
            'mch_id'=>$this->mch_id,
            'nonce_str'=>$this->createNoncestr(),
            'out_trade_no'=>$tuiOrder['out_trade_no'],
            'out_refund_no'=>$tuiOrder['out_refund_no'],
            'total_fee'=>$tuiOrder['total_fee']*100,
            'refund_fee'=>$tuiOrder['refund_fee']*100
        ];
        //生成签名sign
        $sign = $this->getSign($data);
        //完善请求数据
        $data['sign'] = $sign;
        //生成请求数据XML
        $xmlStr = $this->arr_to_xml($data);
        //现场退款请求下列方法
        $xmlStrReturn = $this->curls_post_ssl($this->tui_url, $xmlStr);
//         	    var_dump($xmlStr);
//         	    var_dump($this->tui_url);
         	    //var_dump($xmlStrReturn);
         	   // exit();
        if ($xmlStrReturn) {
            //将返回转成数组
            $postArr = $this->xmlToObject($xmlStrReturn);
            /*-----------     生成返回的json串保存以备后用           -----------*/
            $pdata = ['return_code'=>$postArr->return_code,
                'result_code'=>$postArr->result_code,
                'appid'=>$postArr->appid,
                'mch_id'=>$postArr->mch_id,
                'nonce_str'=>$postArr->nonce_str,
                'sign'=>$postArr->sign,
                'transaction_id'=>$postArr->transaction_id,
                'out_trade_no'=>$postArr->out_trade_no,
                'out_refund_no'=>$postArr->out_refund_no,
                'refund_id'=>$postArr->refund_id,
                'refund_fee'=>$postArr->refund_fee,
                'total_fee'=>$postArr->total_fee,
                'err_code_des'=>$postArr->err_code_des
            ];
            return $pdata;
            //$json = json_encode($pdata,true);
            /*---------     生成返回的json串保存以备后用          ----------*/
            //区分是否成功

        }else{
           return [];
        }
    }

    //统一下单接口
    protected function unifiedorder() {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $ip = $_SERVER['REMOTE_ADDR'];
        $parameters = array(
            'appid' => $this->appid, //小程序ID
            //            'body' => 'test', //商品描述
            'body' =>  $this->body,
            'mch_id' => $this->mch_id, //商户号
            'nonce_str' => $this->createNoncestr(), //随机字符串
            'notify_url' => $this->notify_url, //通知地址  确保外网能正常访问
            'openid' => $this->openid, //用户id
//            'out_trade_no' => '2015450806125348', //商户订单号
            'out_trade_no'=> $this->out_trade_no,
            'spbill_create_ip' => "$ip", //终端IP
//            'total_fee' => floatval(0.01 * 100), //总金额 单位 分
            'total_fee' => $this->total_fee,
//            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], //终端IP
            'trade_type' => 'JSAPI'//交易类型
        );
        //统一下单签名
        $parameters['sign'] = $this->getSign($parameters);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));
        return $return;
    }


    protected static function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);


        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }

    //数组转换成xml
    protected function arrayToXml($arr) {
        $xml = "<root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</root>";
        return $xml;
    }


    //xml转换成数组
    protected function xmlToArray($xml) {


        //禁止引用外部xml实体


        libxml_disable_entity_loader(true);


        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);


        $val = json_decode(json_encode($xmlstring), true);


        return $val;
    }


    //微信小程序接口
    public  function weixinapp() {
        //统一下单接口
        $unifiedorder = $this->unifiedorder();
//        print_r($unifiedorder);
        $parameters = array(
            'appId' => $this->appid, //小程序ID
            'timeStamp' => '' . time() . '', //时间戳
            'nonceStr' => $this->createNoncestr(), //随机串
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'], //数据包
            'signType' => 'MD5'//签名方式
        );
        //签名
        $parameters['paySign'] = $this->getSign($parameters);
        return $parameters;
    }


    //作用：产生随机字符串，不长于32位
    protected function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    //作用：生成签名
    public  function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }


    ///作用：格式化参数，签名过程需要使用
    protected function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }


    /**
     *
     * 请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
     * $url 退款请求地址
     * $vars 退款请求数据
     */

    function curls_post_ssl($url, $vars, $second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //以下两种方式需选择一种

        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,PLUGINS_PATH.'payment/weixin/cert/mini/apiclient_cert.pem');
        //return getcwd().'/APP/Api/Common/apiclient_cert.pem';//生成文件路径
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,PLUGINS_PATH.'payment/weixin/cert/mini/apiclient_key.pem');

        //第二种方式，两个文件合成一个.pem文件
        // 	curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }
    /**
     * 将数组装换成XML格式的串;
     */

    public  function arr_to_xml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
            if(is_array($val)){
                $xml.="<".$key.">".$this->arr_to_xml($val)."</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
            }
        }
        $xml.="</xml>";

        return $xml;
    }


    /**
     * 解析xml文档，转化为对象
     * @author
     * @param  String $xmlStr xml文档
     * @return Object         返回Obj对象
     */
    public function xmlToObject($xmlStr) {
        if (!is_string($xmlStr) || empty($xmlStr)) {
            return false;
        }
        // 由于解析xml的时候，即使被解析的变量为空，依然不会报错，会返回一个空的对象，所以，我们这里做了处理，当被解析的变量不是字符串，或者该变量为空，直接返回false
        $postObj = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $postObj = json_decode(json_encode($postObj));
        //$postObj = json_encode($postObj,true);
        //将xml数据转换成对象返回
        return $postObj;
    }

}