<?php
namespace plugins\Qrcode;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30 0030
 * Time: 下午 3:53
 */
include 'phpqrcode.php';
class CreateCode{

    /**
     * 创建二维码图片
     * @param $value:二维码内容
     * @param $path:二维码路径
     * @param $name:图片文件名
     * @param $source:原文件
     * @param $type:类型（只创建二维码:1；合成图片:2）
     * @param int $matrixPointSize:图片大小
     * @param string $logo:二维码中logo
     * @param string $compound:合成后图片路径
     * @param string $compoundname:合成后图片名
     * @return array
     */
    public static function qrCodeRecommend($value,$path,$name,$source,$type,$matrixPointSize=15,$logo="",$compound="",$compoundname=""){
        echo 123;die;
        //返回结果
        $result = array("status"=>0,"msg"=>"请求成功","data"=>"");
        $errorCorrectionLevel = 'L';//容错级别
        //判断必要参数是否正常
        if(empty($value) || empty($path) || empty($name) || empty($type) || empty($matrixPointSize)){
            $result["status"]=1;
            $result["msg"]= "参数错误";
            return $result;
        }
        if($type==2){
            if(empty($compound) || empty($compoundname)  || empty($source)){
                $result["status"]=1;
                $result["msg"]= "参数错误";
                return $result;
            }
        }
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        //判断文件是否存在
        if(!is_file($path.$name)){
            //如不存在二维码文件则进行生产最新二维码图片
            $file_qrcode= self::getQrcode($value, $path.$name, $errorCorrectionLevel, $matrixPointSize,$logo);
        }else{
            //如存在二维码图片则进行判断文件时间是否已经超过7天
            $file_time = filemtime($path.$name);
            if(time()-$file_time>7*3600*24){
                $file_qrcode= self::getQrcode($value, $path.$name, $errorCorrectionLevel, $matrixPointSize,$logo);
            }else{
                $file_qrcode = $path.$name;
            }
        }
        //判断文件是否需要进行合成
        if($type==1){
            $result["data"] = $file_qrcode;
            return $result;
        }else{
            //合成路径是否存在
            if (!is_dir($compound)) {
                mkdir($compound, 0777, true);
                chmod($compound, 0777);
            }
            //判断文件是否存在
            if(!is_file($compound.$compoundname)){
                $result["data"] = self::imagesMerge($source,$file_qrcode,$compound,$compoundname);
            }else{
                //如存在推广二维码图片则进行判断文件时间是否已经超过7天
                $file_time_compound = filemtime($compound.$compoundname);
                if(time()-$file_time_compound>7*3600*24){
                    //超过7天重新生成二维码
                    $file_qrcode_compound= self::getQrcode($value, $path.$name, $errorCorrectionLevel, $matrixPointSize,$logo);
                }else{
                    //直接使用原图片
                    $file_qrcode_compound = $compound.$compoundname;
                }
                $result["data"] = $file_qrcode_compound;
            }
            return $result;
        }
    }


    /**
     * 创建二维码图片
     * @param $value:二维码内容
     * @param $file_qrcode:二维码路径
     * @param $errorCorrectionLevel:二维码识别等级
     * @param $matrixPointSize:二维码大小
     * @param $logo:二维码logo
     * @return mixed
     */
    public static function getQrcode($value, $file_qrcode, $errorCorrectionLevel, $matrixPointSize,$logo){
        //新建二维码图片
        QRcode::png($value, $file_qrcode, $errorCorrectionLevel, $matrixPointSize, 0);
        if ($logo ===false) {
            $QR = imagecreatefromstring(file_get_contents($file_qrcode));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);          //二维码图片宽度
            $QR_height = imagesy($QR);             //二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            imagepng($QR, $file_qrcode);
        }
        return $file_qrcode;
    }

    /**
     * 合成图片
     * @param $source 源文件
     * @param $source_2 源文件
     * @param $compound 合成后文件路径
     * @param $compoundname 合成后文件名
     * @return bool
     */
    public static function imagesMerge($source,$source_2,$compound,$compoundname){
        //原合成文件形成画布
        $image_1 = imagecreatefrompng($source);
        //需要合成的文件形成画布
        $image_2 = imagecreatefrompng($source_2);
        // 开始合成二维码文件
        imagecopymerge($image_1, $image_2, 340, 730, 0, 0, imagesx($image_2), imagesy($image_2), 100);
        // 输出合成图片并保存
        imagejpeg($image_1,$compound.$compoundname);
        //返回二维码图片
        return $compound.$compoundname;

    }
}