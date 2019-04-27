<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 11:49
 */

namespace app\api\controller;


class Upload extends Base
{
    public function addImgae(){
        $name = I('post.fileName');
        if ($_FILES[$name]['tmp_name']) {
            $file = $this->request->file($name);
            $image_upload_limit_size = config('image_upload_limit_size');
            $validate = ['size'=>$image_upload_limit_size,'ext'=>'jpg,png,gif,jpeg'];
            $dir = UPLOAD_PATH.$name.'/';
            if (!($_exists = file_exists($dir))){
                $isMk = mkdir($dir);
            }
            $parentDir = date('Ymd');
            $info = $file->validate($validate)->move($dir, true);
            if($info){
                return returnOk('/'.$dir.$parentDir.'/'.$info->getFilename());
            }else{
                $this->error($file->getError());//上传错误提示错误信息
            }
        }
    }
}