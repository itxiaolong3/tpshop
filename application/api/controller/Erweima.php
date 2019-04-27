<?php
namespace api\controller;
use plugins\Qrcode;

class Erweima{
    public function index(){
       echo 111;die;
   }

    //进门码
    public function entry_code(){

        $CreateCode = new CreateCode();
        echo "123";die;
        $key = urlencode(base64_encode(Des::getInstance()->encrypt($user_arr["id"])));
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/api/user/test?key=" .$key ;//二维码内容
        $path = "uploads/qrcode/students/"; //用户二维码路径
        $name = md5($user_arr["id"]).".png";   //用户二维码图片
        $source = "";  //推广背景图
        $type = 1;    //图片二维码
        $compound = "";   //合成推广图路径
        $compoundname =  "";  //合成推广图片
        $logo =  $user_arr["header_images"];        //用户logo
        $result = CreateCode::qrCodeRecommend($url,$path,$name,$source,$type,$matrixPointSize=10,$logo,$compound,$compoundname);
        if($result["status"]==0){
            $this->data["image"] = "/".$result["data"];
        }else{
            $this->data["image"] = "/static/img/generalize.png";
        }

        $key2 = urlencode(base64_encode(Des::getInstance()->encrypt($user_arr["id"])));
        $url2 = "http://" . $_SERVER['HTTP_HOST'] . "/recommend?key=" .$key2 ;//二维码内容
        $path2 = "uploads/qrcode/students/"; //用户二维码路径
        $name2 = md5($user_arr["id"]).".png";   //用户二维码图片
        $source2 = "static/img/generalize.png";  //推广背景图
        $type2 = 2;    //图片二维码
        $compound2 = "uploads/qrcode/generalize/";   //合成推广图路径
        $compoundname2 =  md5($user_arr["id"]."generalize").".png";  //合成推广图片
        $logo2 =  $user_arr["header_images"];        //用户logo
        $result2 = CreateCode::qrCodeRecommend($url2,$path2,$name2,$source2,$type2,$matrixPointSize=10,$logo2,$compound2,$compoundname2);
        $share_image = "/".$result2["data"];

        $this->data["share_image"] = $share_image;


        $refer_id = urlencode(base64_encode(Des::getInstance()->encrypt($user_arr["id"])));
        $this->data['request_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/index'."?key=".$refer_id;

        //$res= httpRequest(ECODE,"POST");
//       if($res['status'] == 'success'){
//
//       }else{
//           return returnBad('门卡开启失败！');
//       }
    }

    public function test(){
        echo 111;die;
        vendor('endroid.qrCode.QrCode');
        $qrCode = new QrCode();
        $qrCode
            ->setText('Life is too short to be generating QR codes')
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('Scan the code')
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG)
        ;

// now we can directly output the qrcode
        header('Content-Type: '.$qrCode->getContentType());
        $qrCode->render();

// or create a response object
        $response = new Response($qrCode->get(), 200, array('Content-Type' => $qrCode->getContentType()));
    }
}