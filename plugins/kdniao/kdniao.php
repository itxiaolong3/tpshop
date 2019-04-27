<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 */
class Kdniao{
    private $ebusinessid;//商户ID
    private $appkey;     //商户秘钥
    private $request_type;//请求类型
    private $request_url; //请求URL
    /**
     * 构造函数
     */
    public function __construct($request_type = 1002){
        $express_config = tpCache('express');
        $is_use = $express_config['express_switch'];
        if($is_use == 1){
            $this->ebusinessid = $express_config["kdniao_id"];
            $this->appkey = $express_config["kdniao_key"];
        }else{
        	$this->ebusinessid = 'tpshop';
        	$this->appkey = 'tpshop';
        }
        $this->request_type = $request_type;
        $apiUrl = array(
        	1001=>'http://api.kdniao.cc/api/OOrderService',//在线下单预约取件接口正式地址http://api.kdniao.cc/api/OOrderService	
        	1002=>'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx',//即时查询接口地址
        	1007=>'http://api.kdniao.cc/api/EOrderService',//电子面单正式接口地址http://api.kdniao.cc/api/EOrderService
        	1008=>'http://testapi.kdniao.cc:8081/api/dist',//物流跟踪正式接口地址http://testapi.kdniao.cc:8081/api/dist
        	2002=>'http://testapi.kdniao.cc:8081/Ebusiness/EbusinessOrderHandle.aspx',//单号识别接口地址，只需要录入单号即可完成查询http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx
        );
        $this->request_url = $apiUrl[$request_type];
    }
    
    
    //---------------------------------------------
    
    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson($requestData){
        //$requestData= "{'OrderCode':'20180129145532','ShipperCode':'ZTO','LogisticCode':'780096744736'}";
        $datas = array(
            'EBusinessID' => $this->ebusinessid,
            'RequestType' => $this->request_type,
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->appkey);
        $result = $this->sendPost($this->request_url, $datas);

        //根据公司业务处理返回的信息......
        return $result;
    }
    
    
    function submitEOrder($requestData){
    	$datas = array(
    		'EBusinessID' => $this->ebusinessid,
    		'RequestType' => $this->request_type,
    		'RequestData' => urlencode($requestData),
    		'DataType' => '2',
    	);
    	$datas['DataSign'] = $this->encrypt($requestData, $this->appkey);
    	$result = $this->sendPost($this->request_url, $datas);
    	//根据公司业务处理返回的信息......
    	return $result;
    }
    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }

        fclose($fd);
    
        return $gets;
    }
    
    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}
