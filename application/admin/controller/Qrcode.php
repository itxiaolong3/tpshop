<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/13
 * Time: 11:50
 */
namespace app\admin\controller;
use think\controller;
class Qrcode extends base
 {
     //生成二维码1
     public function qr_code_create(){
     vender('phpqrcode.phpqrcode');
         $content=input('get.content');
         $value = 'http://www.cnblogs.com/txw1958/'; //二维码内容
         $errorCorrectionLevel = 'L';//容错级别 L/H/M/Q
         $matrixPointSize = 6;//生成图片大小
         //实例化
         $qr = new \QRcode(); //注意前面的反斜杠，因为插件中的类是没有命名空间的，要在前面加一个反斜杠
         $filename = "4.png";
         //打开缓冲区
         ob_start();
         $res= $qr::png($content,$filename,$errorCorrectionLevel,$matrixPointSize);
         $qrcode=base64_encode(ob_get_contents());
         //会清除缓冲区的内容，并将缓存区关闭，但不会输出内容
         ob_end_clean();
         var_dump($qrcode);die;
         $this->assign('qr_code_create',$qrcode);
         return view();
     }
     public function create_qrcode(){

     }

 }
 ?>