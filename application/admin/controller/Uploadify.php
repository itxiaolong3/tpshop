<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-22
 */
 
namespace app\admin\controller;

class Uploadify extends Base{
   
    public function upload(){
        $func = I('func');
        $path = I('path','temp');
		$image_upload_limit_size = config('image_upload_limit_size');
        $fileType = I('fileType','Images');  //上传文件类型，视频，图片
        if($fileType == 'Flash'){
            $upload = U('Admin/Ueditor/videoUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'video'));
            $type = 'mp4,3gp,flv,avi,wmv';
        }else if($fileType == 'Music'){
            $upload = U('Admin/Ueditor/musicUp',array('savepath'=>$path,'musictitle'=>'banner','dir'=>'music'));
            $type = 'jpg,png,gif,wav,mp3';
        }else{
            $upload = U('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images'));
            $type = 'jpg,png,gif,jpeg';
        }
        $info = array(
        	'num'=> I('num/d'),
        	'fileType'=> $fileType,
            'title' => '',
            'upload' =>$upload,
        	'fileList'=>U('Admin/Uploadify/fileList',array('path'=>$path)),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' =>$type,
            'input' => I('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }

    //自定义海报专用上传图片
    public function poster_upload(){
        $func = I('func');
        $path = I('path','temp');
        $image_upload_limit_size = config('image_upload_limit_size');
        $fileType = I('fileType','Images');  //上传文件类型，视频，图片
        if($fileType == 'Flash'){
            $upload = U('Admin/Ueditor/videoUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'video'));
            $type = 'mp4,3gp,flv,avi,wmv';
        }else{
            $upload = U('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images'));
            $type = 'jpg,png,gif,jpeg';
        }
        $info = array(
            'num'=> I('num/d'),
            'fileType'=> $fileType,
            'title' => '',
            'upload' =>$upload,
            'fileList'=>U('Admin/Uploadify/fileList',array('path'=>$path)),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' =>$type,
            'input' => I('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }


    /**
     * 删除上传的图片,视频
     */
    public function delupload(){
        $action = I('action','del');
        $filename= I('filename');
        $filename= empty($filename) ? I('url') : $filename;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if($action=='del' && !empty($filename) && file_exists($filename)){
            $filetype = strtolower(strstr($filename,'.'));
            $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP文件
            $erasable_type = C('erasable_type');  //可删除文件
            if(!in_array($filetype,$erasable_type) || $phpfile){

                exit;
            }
            if(unlink($filename)){
                $this->deleteWechatImage(I('url'));
                echo 1;
            }else{
                echo 0;
            }
            exit;
        }
    }
    
    public function fileList()
    {
    	/* 判断类型 */
    	$type = I('type','Images');
    	switch ($type){
    		/* 列出图片 */
    		case 'Images' : $allowFiles = 'png|jpg|jpeg|gif|bmp';break;
    	
    		case 'Flash' : $allowFiles = 'mp4|3gp|flv|avi|wmv|flash|swf';break;
    		case 'Music' : $allowFiles = 'mp3|wav';break;

    		/* 列出文件 */
    		default : $allowFiles = '.+';
    	}

    	$path = UPLOAD_PATH.I('path','temp');
    	//echo file_exists($path);echo $path;echo '--';echo $allowFiles;echo '--';echo $key;exit;
    	$listSize = 100000;
    	
    	$key = empty($_GET['key']) ? '' : $_GET['key'];
    	
    	/* 获取参数 */
    	$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
    	$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
    	$end = $start + $size;
    	
    	/* 获取文件列表 */
    	$files = $this->getfiles($path, $allowFiles, $key,['public/upload/goods/thumb']);
    	if (!count($files)) {
    		echo json_encode(array(
    				"state" => "没有相关文件",
    				"list" => array(),
    				"start" => $start,
    				"total" => count($files)
    		));
    		exit;
    	}
    	
    	/* 获取指定范围的列表 */
    	$len = count($files);
    	for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    		$list[] = $files[$i];
    	}
    	
    	/* 返回数据 */
    	$result = json_encode(array(
    			"state" => "SUCCESS",
    			"list" => $list,
    			"start" => $start,
    			"total" => count($files)
    	));
    	
    	echo $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, $key,$ignore = array(), &$files = array()){
    	if (!is_dir($path)) return null;
    	if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    	$handle = opendir($path);
    	while (false !== ($file = readdir($handle))) {
    		if ($file != '.' && $file != '..') {
    			$path2 = $path . $file;
    			if (is_dir($path2) && !in_array($path2,$ignore)) {
                    $this->getfiles($path2, $allowFiles, $key,array(), $files);
    			} else {
    				if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
    					$files[] = array(
    						'url'=> '/'.$path2,
    						'name'=> $file,
    						'mtime'=> filemtime($path2)
    					);
    				}
    			}
    		}
    	}
    	return $files;
    }

	public function preview(){

		// 此页面用来协助 IE6/7 预览图片，因为 IE 6/7 不支持 base64
		$DIR = 'preview';
		// Create target dir
		if (!file_exists($DIR)) {
			@mkdir($DIR);
		}

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		if ($cleanupTargetDir) {
			if (!is_dir($DIR) || !$dir = opendir($DIR)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
				// Remove temp file if it is older than the max age and is not the current file
				if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}

		$src = file_get_contents('php://input');
		if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {
			$previewUrl = sprintf(
					"%s://%s%s",
					isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']
			);
			$previewUrl = str_replace("preview.php", "", $previewUrl);
			$base64 = $matches[2];
			$type = $matches[1];
			if ($type === 'jpeg') {
				$type = 'jpg';
			}

			$filename = md5($base64).".$type";
			$filePath = $DIR.DIRECTORY_SEPARATOR.$filename;

			if (file_exists($filePath)) {
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			} else {
				$data = base64_decode($base64);
				$filePathLower = strtolower($filePath);
				if (strstr($filePathLower, '../') || strstr($filePathLower, '..\\') || strstr($filePathLower, '.php')) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "文件上传格式错误 error ！"}}');
				}
				file_put_contents($filePath, $data);
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			}
		} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}');
		}
	}

    public function wechatImageList($listSize, $get)
    {
        $size = isset($get['size']) ? intval($get['size']) : $listSize;
        $start = isset($get['start']) ? intval($get['start']) : 0;

        $logic = new \app\common\logic\WechatLogic;
        return $logic->getPluginImages($size, $start);
    }

    public function deleteWechatImage($file_path)
    {
        $logic = new \app\common\logic\WechatLogic;
        $logic->deleteImage($file_path);
    }
}