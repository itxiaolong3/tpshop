<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 15:56
 */

/**
 * 成功返回数据
 * @param $msg
 * @param array $data
 * @return \think\response\Json
 */
function returnOk($data = []){
    return json([
        'code' => 200,
        'status'     => true,
        'data'       => $data
    ]);
}

/**
 * 失败返回数据，返回数组给异常类
 * @param $msg
 * @param int $errorCode
 * @return array
 */
function returnBad($msg,$errorCode = 10000){
    return json([
        'code' => $errorCode,
        'msg'        => $msg
    ]);
}

//验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！
function express_search($order_id){
    $res = M('delivery_doc')->where("order_id", $order_id)->find();
    $company_name = $res['shipping_name'];
    if($company_name == '快捷速递'){
        $company='kuaijiesudi';
    }elseif ($company_name == '快捷快递'){
        $company='kuaijiesudi';
    }elseif ($company_name == '国通快递'){
        $company='guotongkuaidi';
    }elseif ($company_name == '申通'){
        $company='shentong';
    }elseif ($company_name == '顺丰'){
        $company='shunfeng';
    }elseif ($company_name == '天天快递'){
        $company='tiantian';
    }elseif ($company_name == '万象物流'){
        $company='wanxiangwuliu';
    }elseif ($company_name == '邮政包裹挂号信'){
        $company='youzhengguonei';
    }elseif ($company_name == '圆通速递'){
        $company='yuantong';
    }elseif ($company_name == '圆通快递'){
        $company='yuantong';
    }elseif ($company_name == '韵达快运'){
        $company='yunda';
    }elseif ($company_name == '汇通快运'){
        $company='huitongkuaidi';
    }elseif ($company_name == '佳怡物流'){
        $company='jiayiwuliu';
    }elseif ($company_name == '全峰快递'){
        $company='quanfengkuaidi';
    }elseif ($company_name == '中通速递'){
        $company='zhongtong';
    }elseif ($company_name == '中邮物流'){
        $company='zhongyouwuliu';
    }elseif ($company_name == '宅急送'){
        $company='zhaijisong';
    }elseif ($company_name == '优速物流'){
        $company='youshuwuliu';
    }else {
        $company=$company_name;
    }
    $express_id = trim($res['invoice_no']);

    //参数设置
    $post_data = array();
    $post_data["customer"] = '82B5CD739EC6C6F53B805AE6FC4281D7';
    $key= 'HVlXlNTD2005';
    $post_data["param"] = '{"com":"'.$company.'","num":"'.$express_id.'"}';//"com":"yuantong","num":"885705113048636419"
    $url='http://poll.kuaidi100.com/poll/query.do';
    $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
    $post_data["sign"] = strtoupper($post_data["sign"]);
    $o="";
    foreach ($post_data as $k=>$v)
    {
        $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
    }
    $post_data=substr($o,0,-1);

    $output = curl_opt($url, $post_data);
    $data1 = json_decode($output,true);
    //print_r($output);
    /*0在途中、1已揽收、2疑难、3已签收*/
    if($data1['state']==0){
        $data1['lastResult']['message']='在途中';
    }elseif($data1['state']==1){
        $data1['lastResult']['message']='已揽收';
    }elseif($data1['state']==2){
        $data1['lastResult']['message']='疑难';
    }elseif($data1['state']==3){
        $data1['lastResult']['message']='已签收';
    }
    $data1['name'] = $company_name;
    return $data1;
}

/**
 * [cUrl cURL(支持HTTP/HTTPS，GET/POST)]
 * @param     [string]     $url    [请求地址]
 * @param     [Array]      $header [HTTP Request headers array('Content-Type'=>'application/x-www-form-urlencoded')]
 * @param     [Array]      $data   [参数数据 array('name'=>'value')]
 * @return    [type]               [如果服务器返回xml则返回xml，不然则返回json]
 */
function curl_opt($url, $data = null, $header=null)
{
    //初始化curl
    $curl = curl_init();
    //设置cURL传输选项
    if(is_array($header)){
        curl_setopt($curl, CURLOPT_HTTPHEADER  , $header);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){//post方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    //获取采集结果
    $output = curl_exec($curl);
    //关闭cURL链接
    curl_close($curl);
    return $output;
//    //解析json
//    $json=json_decode($output,true);
//    //判断json还是xml
//    if ($json) {
//        return $json;
//    }else{
//        #验证xml
//        libxml_disable_entity_loader(true);
//        #解析xml
//        $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
//        return $xml;
//    }
}

function getUserNo()
{
    $user_no = mt_rand(10000000,99999999);
    $count = \think\Db::name("users")->where(["user_no" => $user_no])->count();
    if($count || empty($user_no)){
        getUserNo();
    }else {
        return $user_no;
    }
}