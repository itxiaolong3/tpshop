<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-09
 */
namespace app\admin\controller; 
use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use app\common\logic\MessageFactory;
use think\Db;
class Index extends Base {

    public function index(){
        $this->pushVersion();
        $admin_info = getAdminInfo(session('admin_id'));
        $order_amount = M('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();
        $this->assign('order_amount',$order_amount);
        $this->assign('admin_info',$admin_info);             
        $this->assign('menu',getMenuArr());   //view2
        return $this->fetch();
    }
   
    public function welcome(){
    	$this->assign('sys_info',$this->get_sys_info());
//    	$today = strtotime("-1 day");
    	$today = strtotime(date("Y-m-d"));
    	$count['handle_order'] = M('order')->where("order_status=0 and (pay_status=1 or pay_code='cod')")->count();//待处理订单
    	$count['new_order'] = M('order')->where("add_time>=$today")->count();//今天新增订单
    	$count['goods'] =  M('goods')->where("1=1")->count();//商品总数
    	$count['article'] =  M('article')->where("1=1")->count();//文章总数
    	$count['users'] = M('users')->where("1=1")->count();//会员总数
    	$count['today_login'] = M('users')->where("last_login>=$today")->count();//今日访问
    	$count['new_users'] = M('users')->where("reg_time>=$today")->count();//新增会员
    	$count['comment'] = M('comment')->where("is_show=0")->count();//最新评论
    	$this->assign('count',$count);
        return $this->fetch();
    }
    
    public function get_sys_info(){
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off		
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
		$sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';	
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();
		$sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
		$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
		$sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
		$sys_info['memory_limit']   = ini_get('memory_limit');	                                
        $sys_info['version']   	    = file_get_contents(APP_PATH.'admin/conf/version.php');
		$mysqlinfo = Db::query("SELECT VERSION() as version");
		$sys_info['mysql_version']  = $mysqlinfo[0]['version'];
		if(function_exists("gd_info")){
			$gd = gd_info();
			$sys_info['gdinfo'] 	= $gd['GD Version'];
		}else {
			$sys_info['gdinfo'] 	= "未知";
		}
		return $sys_info;
    }
    
    // 在线升级系统
    public function pushVersion()
    {            
        if(!empty($_SESSION['isset_push']))
            return false;    
        $_SESSION['isset_push'] = 1;    
        error_reporting(0);//关闭所有错误报告
        $app_path = dirname($_SERVER['SCRIPT_FILENAME']).'/';
        $version_txt_path = $app_path.'/application/admin/conf/version.php';
        $curent_version = file_get_contents($version_txt_path);

        $vaules = array(            
                'domain'=>$_SERVER['SERVER_NAME'], 
                'last_domain'=>$_SERVER['SERVER_NAME'], 
                'key_num'=>$curent_version, 
                'install_time'=>INSTALL_DATE,
                'serial_number'=>SERIALNUMBER,
         );     
         $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&".http_build_query($vaules);
         stream_context_set_default(array('http' => array('timeout' => 3)));
         file_get_contents($url);         
    }
    
    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal(){  
            $table = I('table'); // 表名
            $id_name = I('id_name'); // 表主键id名
            $id_value = I('id_value'); // 表主键id值
            $field  = I('field'); // 修改哪个字段
            $value  = I('value'); // 修改字段值
            M($table)->where([$id_name => $id_value])->save(array($field=>$value)); // 根据条件保存修改的数据

            // 是否启动拼团，设置发拼团站内消息
            if ($table == 'team_activity') {
                $where_message_activity = [
                    'prom_id' => $id_value,
                    'mmt_code' => 'team_activity'
                ];
                $message_id = Db::name('message_activity')->where($where_message_activity)->value('message_id');
                if (!$message_id && ($value == 1)) {
                    $team_activity = Db::name('team_activity')->where('team_id', $id_value)->find();
                    $send_data = [
                        'message_title' => $team_activity['act_name'],
                        'message_content' => $team_activity['share_desc'],
                        'img_uri' => $team_activity['share_img'],
                        'end_time' => 0,
                        'send_time' => time(),
                        'mmt_code' => 'team_activity',
                        'prom_type' => 6,
                        'users' => [],
                        'message_val' => [],
                        'category' => 1,
                        'prom_id' => $id_value
                    ];
                    $send_data['end_time'] = $send_data['send_time'] + $team_activity['time_limit'];
                    $messageFactory = new MessageFactory();
                    $messageLogic = $messageFactory->makeModule($send_data);
                    $messageLogic->sendMessage();
                }
            }

    }
    public function about(){
    	return $this->fetch();
    }

    public function test(){
        vendor("phpqrcode.phpqrcode");
        $data ='http://www.baidu.com'; //跳转链接
        $outfile=ROOT_PATH."public/qrcode/".time().'.jpg';
        $level = 'L';
        $size =4;
        $QRcode = new \QRcode();
        ob_start();
        $QRcode->png($data,$outfile,$level,$size,2);
        ob_end_clean();
        return time();
    }
    public function test1(){
       $result= create_qrcode();
        //方法一
        //readfile("http://img.php.cn/upload/article/000/000/003/5a9675a3b2106284.jpg");
         //       //方法二:
//                header("content-type:image/jpeg");
//              // 初始化
//                $pic = curl_init();
//               // 设置选项
//                curl_setopt($pic, CURLOPT_URL, "http://img.php.cn/upload/article/000/000/003/5a9675a3b2106284.jpg");
//                // 执行获取到的内容
//                curl_exec($pic);
//               // 释放curl句柄
//                curl_close($pic);
        //方法三：通过file_get_contents来获取图片
        //$result=str_replace("/","\\",$result);
//        $result="E:\kevin\huayun\public\qrcode\\"."1552457179.jpg";
        //echo $result;die;
//        echo file_get_contents($result);
//        echo $result;die;
        //方法四：通过fopen系列函数获取图片
        // 打开图片文件
//        echo $result;die;
//        $file = fopen("$result", 'rb+');
//      // 读取图片文件
//        echo (fread($file, filesize("$result")));
//     // 关闭文件句柄
//        fclose($file);
        //输出图片
        echo $result;
    }
}