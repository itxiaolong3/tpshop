<?php
return array(
    'code'=> 'alipaynew',
    'name' => '新版支付宝快捷登陆',
    'version' => '3.3.0',
    'author' => 'yhj',
    'desc' => '支付宝快捷登陆插件 ',
    'icon' => 'logo.jpg',
    'config' => array(
        array('name' => 'app_id','label'=>'应用appId','type' => 'text',   'value' => ''),
		array('name' => 'app_rsa_private_key','label'=>'应用私钥','type' => 'textarea',   'value' => ''),
        array('name' => 'alipay_rsa_public_key','label'=>'支付宝公钥','type' => 'textarea',   'value' => '')
    )
);