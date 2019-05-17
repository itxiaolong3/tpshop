<?php
return	array(	
	'index'=>array('name'=>'首页','child'=>array(
			array('name' => '概览','child' => array(
					array('name' => '模板设置', 'act'=>'index', 'op'=>'index'),
			)),
	)),

	'system'=>array('name'=>'设置','child'=>array(
				array('name' => '系统','child' => array(
						array('name'=>'商城设置','act'=>'index','op'=>'System'),
						
						//array('name'=>'支付方式','act'=>'index1','op'=>'System'),
						array('name'=>'地区&配送','act'=>'region','op'=>'Tools'),
//						array('name'=>'短信模板','act'=>'index','op'=>'SmsTemplate'),
						array('name'=>'消息通知','act'=>'index','op'=>'MessageTemplate'),
						//array('name'=>'接口对接','act'=>'index3','op'=>'System'),
						//array('name'=>'验证码设置','act'=>'index4','op'=>'System'),
						array('name'=>'友情链接','act'=>'linkList','op'=>'Article'),
						array('name'=>'清除缓存','act'=>'cleanCache','op'=>'System'),
						array('name' => '运费模板', 'act'=>'index', 'op'=>'Freight'),
						array('name' => '快递公司', 'act'=>'index', 'op'=>'Shipping'),
                        array('name'=>'轮播图','act'=>'bannerlist','op'=>'Article'),
				)),

//				array('name' => '支付','child'=>array(
//					array('name' => '支付配置', 'act'=>'index', 'op'=>'Plugin'),
//				)),
			


			array('name' => '权限','child'=>array(
						array('name' => '管理员列表', 'act'=>'index', 'op'=>'Admin'),
						array('name' => '角色管理', 'act'=>'role', 'op'=>'Admin'),
						array('name'=>'权限资源列表','act'=>'right_list','op'=>'System'),
						//array('name' => '管理员日志', 'act'=>'log', 'op'=>'Admin'),
//						array('name' => '供应商列表', 'act'=>'supplier', 'op'=>'Admin'),
				)),
			
//				array('name' => '数据','child'=>array(
//						array('name' => '数据备份', 'act'=>'index', 'op'=>'Tools'),
//						array('name' => '数据还原', 'act'=>'restore', 'op'=>'Tools'),
//                        array('name' => '清空演示数据', 'act'=>'clear_demo_data', 'op'=>'Tools'),
//						//array('name' => 'ecshop数据导入', 'act'=>'ecshop', 'op'=>'Tools'),
//						//array('name' => '淘宝csv导入', 'act'=>'taobao', 'op'=>'Tools'),
//						//array('name' => 'SQL查询', 'act'=>'log', 'op'=>'Admin'),
//				)),

	)),
		
//	'decorate'=>array('name'=>'装修','child'=>array(
//		array('name' => '模板','child'=>array(
//				array('name' => '模板分类管理', 'act'=>'template_class', 'op'=>'block', 'admin_saas'=>1),
//				//array('name' => '首页装修', 'act'=>'templateList', 'op'=>'Template'),
//				array('name' => '首页装修', 'act'=>'templateList', 'op'=>'block'),
//				array('name' => '行业模板设置', 'act'=>'templateList2', 'op'=>'block',  'admin_saas'=>1),
//				array('name' => '自定义页面', 'act'=>'pageList', 'op'=>'Block'),
//				array('name' => '会员中心自定义', 'act'=>'user_center_menu', 'op'=>'System'),
//				array('name' => '模板切换', 'act'=>'change', 'op'=>'Template'),
//		)),
//		array('name' => '导航','child' => array(
//			array('name'=>'PC端导航栏','act'=>'navigationList','op'=>'System'),
//		)),
//	)),


	'shop'=>array('name'=>'商城','child'=>array(
				array('name' => '商品','child' => array(
				    array('name' => '商品列表', 'act'=>'goodsList', 'op'=>'Goods'),
//				    array('name' => '淘宝导入', 'act'=>'index', 'op'=>'Import'),
					array('name' => '商品分类', 'act'=>'categoryList', 'op'=>'Goods'),
					array('name' => '库存管理', 'act'=>'stockList', 'op'=>'Goods'),
					array('name' => '商品模型', 'act'=>'type_list', 'op'=>'Goods'),
//					array('name' => '品牌列表', 'act'=>'brandList', 'op'=>'Goods'),
					array('name' => '评论列表', 'act'=>'index', 'op'=>'Comment'),
//					array('name' => '商品咨询', 'act'=>'ask_list', 'op'=>'Comment'),
			)),
			array('name' => '订单','child'=>array(
					array('name' => '订单列表', 'act'=>'index', 'op'=>'Order'),
//					array('name' => '虚拟订单', 'act'=>'virtual_list', 'op'=>'Order'),
					array('name' => '发货单', 'act'=>'delivery_list', 'op'=>'Order'),
					array('name' => '退款单', 'act'=>'refund_order_list', 'op'=>'Order'),
					array('name' => '退换货', 'act'=>'return_list', 'op'=>'Order'),
//					array('name' => '添加订单', 'act'=>'add_order', 'op'=>'Order'),
					array('name' => '订单日志','act'=>'order_log','op'=>'Order'),
//					array('name' => '发票管理','act'=>'index', 'op'=>'Invoice'),
			        array('name' => '拼团列表','act'=>'team_list','op'=>'Team'),
			        array('name' => '拼团订单','act'=>'order_list','op'=>'Team'),
//			        array('name' => '上门自提','act'=>'index','op'=>'ShopOrder'),
			)),

			array('name' => '广告','child' => array(
					array('name'=>'广告列表','act'=>'adList','op'=>'Ad'),
					array('name'=>'广告位置','act'=>'positionList','op'=>'Ad'),
			)),

			array('name' => '文章','child'=>array(
					array('name' => '文章列表', 'act'=>'articleList', 'op'=>'Article'),
					array('name' => '文章分类', 'act'=>'categoryList', 'op'=>'Article'),
				//	array('name' => '帮助管理', 'act'=>'help_list', 'op'=>'Article'),
					array('name'=>'友情链接','act'=>'linkList','op'=>'Article'),
//					array('name' => '会员协议', 'act'=>'agreement', 'op'=>'Article'),
					//array('name' => '公告管理', 'act'=>'notice_list', 'op'=>'Article'),
//					array('name' => '专题列表', 'act'=>'topicList', 'op'=>'Topic'),
					array('name' => '答题列表', 'act'=>'questionlist', 'op'=>'Article'),
			)),

//			array('name' => '新闻','child'=>array(
//					array('name' => '新闻列表', 'act'=>'newsList', 'op'=>'News'),
//					array('name' => '新闻分类', 'act'=>'categoryList', 'op'=>'News'),
//			)),
	)),
		
	'marketing'=>array('name'=>'营销','child'=>array(
			array('name' => '促销活动','child' => array(
//					array('name' => '抢购管理', 'act'=>'flash_sale', 'op'=>'Promotion'),
//					array('name' => '团购管理', 'act'=>'group_buy_list', 'op'=>'Promotion'),
					//array('name' => '优惠促销', 'act'=>'prom_goods_list', 'op'=>'Promotion'),
					array('name' => '优惠促销', 'act'=>'prom_order_list', 'op'=>'Promotion'),
//					array('name' => '预售管理','act'=>'index', 'op'=>'PreSell'),
					array('name' => '拼团管理','act'=>'index', 'op'=>'Team'),
//					array('name' => '搭配购管理','act'=>'index', 'op'=>'Combination'),
			)),
			array('name' => '优惠积分','child' => array(
					array('name' => '优惠券','act'=>'index', 'op'=>'Coupon'),
					array('name' => '积分兑换','act'=>'index', 'op'=>'IntegralMall'),
			)),
	)),
		
	'distribution'=>array('name'=>'分销','child'=>array(
			array('name' => '分销管理','child' => array(
//					array('name' => '分销商品', 'act'=>'goods_list', 'op'=>'Distribut'),
					array('name' => '分销商列表', 'act'=>'distributor_list', 'op'=>'Distribut'),
				//	array('name' => '分销关系', 'act'=>'tree', 'op'=>'Distribut'),
				//	array('name' => '分销商等级', 'act'=>'grade_list', 'op'=>'Distribut'),
				    //array('name' => '分销设置', 'act'=>'distribut', 'op'=>'System'),
				    array('name' => '分销设置', 'act'=>'system', 'op'=>'MySystem'),//我新增的分销设置
					array('name' => '分成日志', 'act'=>'rebate_log', 'op'=>'Distribut'),
			)),
	     
//    	    array('name' => '微信接入','child' => array(
//    	        array('name' => '公众号配置', 'act'=>'index', 'op'=>'Wechat'),
//    	        array('name' => '微信菜单管理', 'act'=>'menu', 'op'=>'Wechat'),
//    	        array('name' => '自动回复', 'act'=>'auto_reply', 'op'=>'Wechat'),
//                array('name' => '粉丝列表', 'act'=>'fans_list', 'op'=>'Wechat'),
//                array('name' => '模板消息', 'act'=>'template_msg', 'op'=>'Wechat'),
//                array('name' => '素材管理', 'act'=>'materials', 'op'=>'Wechat'),
//    	    )),
	)),

 	'member'=>array('name'=>'会员','child'=>array(
		array('name' => '会员管理','child'=>array(
			array('name'=>'会员列表','act'=>'index','op'=>'User'),
//            array('name'=>'退押金单','act'=>'return_deposit','op'=>'User'),
          //  array('name'=>'代理商申请表','act'=>'agent_apply','op'=>'User'),
			array('name'=>'会员等级','act'=>'levelList','op'=>'User'),
            array('name'=>'会员提成','act'=>'benfitsList','op'=>'User'),
            array('name'=>'会员星级','act'=>'memberstar','op'=>'User'),
            array('name'=>'店铺入驻','act'=>'mechanism','op'=>'User'),
		)),
		array('name' => '充值提现','child'=>array(
			array('name'=>'充值记录','act'=>'recharge','op'=>'User'),
			array('name'=>'提现申请','act'=>'withdrawals','op'=>'User'),
			array('name'=>'提现设置','act'=>'cash','op'=>'System'),
		)),
		array('name' => '签到管理','child'=>array(
			array('name'=>'签到记录','act'=>'signList','op'=>'User'),
			array('name'=>'签到规则','act'=>'signRule','op'=>'User'),
		)),
	)),

//	'store'=>array('name'=>'门店','child'=>array(
//		array('name' => '门店管理','child'=>array(
//			array('name'=>'门店列表','act'=>'index','op'=>'Shop'),
//		)),
//	)),

	'data'=>array('name'=>'数据','child'=>array(
			array('name' => '统计','child' => array(
					array('name' => '销售概况', 'act'=>'index', 'op'=>'Report'),
					array('name' => '销售排行', 'act'=>'saleTop', 'op'=>'Report'),
					array('name' => '会员排行', 'act'=>'userTop', 'op'=>'Report'),
					array('name' => '销售明细', 'act'=>'saleList', 'op'=>'Report'),
					array('name' => '会员统计', 'act'=>'user', 'op'=>'Report'),
					array('name' => '运营概览', 'act'=>'finance', 'op'=>'Report'),
//					array('name' => '平台支出记录','act'=>'expense_log','op'=>'Report'),
			)),
//            array('name' => 'App','child' => array(
//				array('name' => '安卓APP管理', 'act'=>'index', 'op'=>'MobileApp'),
//                array('name' => '苹果APP管理', 'act'=>'ios_audit', 'op'=>'MobileApp'),
//			))
	)),
);