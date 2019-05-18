<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:41:"./application/admin/view/admin\login.html";i:1546651941;}*/ ?>
<!doctype html>
<meta name=”renderer” content=”webkit”>
<meta http-equiv=”X-UA-Compatible” content=”IE=Edge,chrome=1″ >
<head>
<meta charset="utf-8">
<title>登录页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="icon" href="/public/static/animated_favicon.gif" type="image/gif" />
<link href="/public/static/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.SuperSlide.2.1.2.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.cookie.js"></script>
<!--[if lte IE 8]>
	<script type="Text/Javascript" language="JavaScript">
	    function detectBrowser()
	    {
		    var browser = navigator.appName
		    if(navigator.userAgent.indexOf("MSIE")>0){ 
			    var b_version = navigator.appVersion
				var version = b_version.split(";");
				var trim_Version = version[1].replace(/[ ]/g,"");
			    if ((browser=="Netscape"||browser=="Microsoft Internet Explorer"))
			    {
			    	if(trim_Version == 'MSIE8.0' || trim_Version == 'MSIE7.0' || trim_Version == 'MSIE6.0'){
			    		alert('请使用IE9.0版本以上进行访问');
			    		return false;
			    	}
			    }
		    }
	   }
       detectBrowser();
    </script>
<![endif]-->
<script type="text/javascript">
//若cookie值不存在，则跳出iframe框架
if(!$.cookie('tpshopActionParam') && $.cookie('admin_type') != 1){
	$.cookie('admin_type','1' , {expires: 1 ,path:'/'});
	//top.location.href = location.href;
}
</script>
</head>

<body>
	<div class="login-layout">
    	<!--<div class="logo">-->
            <!--<img src="<?php echo (isset($tpshop_config['shop_info_admin_login_logo']) && ($tpshop_config['shop_info_admin_login_logo'] !== '')?$tpshop_config['shop_info_admin_login_logo']:'/public/static/images/logo/admin_login_logo_default.png'); ?>">-->
        <!--</div>-->
        <form action="" name='theForm' id="theForm" method="post">
            <div class="login-form" style="position: relative">
                <div class="formContent">
                	<div class="title">管理中心</div>
                    <div class="formInfo">
                    	<div class="formText">
                        	<i class="icon icon-user"></i>
                            <input type="text" name="username" autocomplete="off" class="input-text" value="" placeholder="用户名" />
                        </div>
                        <div class="formText">
                        	<i class="icon icon-pwd"></i>
                            <input type="password" name="password" autocomplete="off" class="input-text" value="" placeholder="密  码" />
                        </div>
                        <div class="formText">
                            <i class="icon icon-chick"></i>
                            <input type="text" name="vertify" id="vertify" autocomplete="off" class="input-text chick_ue" value="" placeholder="验证码" />
                            <img src="<?php echo U('Admin/vertify'); ?>" class="chicuele" id="imgVerify" alt="" onclick="fleshVerify()">
                        </div>
                        <div class="formText">
                        	<!--<div class="checkbox">
                            	<div class="cur">
                                    <input type="hidden" value="1" name="remember"/>
                                </div>
                            </div>
                           <span class="span">保存信息</span>-->
                            <a href="<?php echo U('Admin/forget_pwd'); ?>" class="forget_pwd">忘记密码？</a>
                        </div>
						<div class="formText submitDiv">
                          <span class="submit_span">
                          	<input type="button" name="submit" class="sub" value="登录">
                          </span>
                       </div>
                    </div>
                </div>
                <div id="error" style="position: absolute;left:0px;bottom: 12px;text-align: center;width:441px;">

                </div>
            </div>
        </form>
    </div>
    <div id="bannerBox">
        <ul id="slideBanner" class="slideBanner">
            <li><img src="/public/static/images/banner_1.jpg"></li>
            <li><img src="/public/static/images/banner_2.jpg"></li> 
            <li><img src="/public/static/images/banner_3.jpg"></li>
        </ul>
    </div>
    <!--<script type="text/javascript" src="js/jquery.purebox.js"></script> -->   
    <script type="text/javascript">
        $(function(){
            if(self !== top){
                top.location.href = self.location.href;
            }
        });
    	$("#bannerBox").slide({mainCell:".slideBanner",effect:"fold",interTime:3500,delayTime:500,autoPlay:true,autoPage:true,endFun:function(i,c,s){
			$(window).resize(function(){
				var width = $(window).width();
				var height = $(window).height();
				s.find(".slideBanner,.slideBanner li").css({"width":width,"height":height});
			});
		}});
		
		$(function(){
			$(".formText .input-text").focus(function(){
				$(this).parent().addClass("focus");
			});
			
			$(".formText .input-text").blur(function(){
				$(this).parent().removeClass("focus");
			});
			
			$(".checkbox").click(function(){
				if($(this).hasClass("checked")){
					$(this).removeClass("checked");
                    $('input[name=remember]').val(0);
				}else{
					$(this).addClass("checked");
                    $('input[name=remember]').val(1);
				}
			});
			
			$(".formText .input-yzm").focus(function(){
				$(this).prev().show();
			});
			
			$(".formText").blur(function(){
				$(this).prev().hide();
			});			
		});

        $(function(){
            function loginsubmit(){
                var username=true;
                var password=true;
                var vertify=true;

                if($('#theForm input[name=username]').val() == ''){
                    $('#error').html('<span class="error">用户名不能为空!</span>');
                    $('#theForm input[name=username]').focus();
                    username = false;
                    return false;
                }

                if($('#theForm input[name=password]').val() == ''){
                    $('#error').html('<span class="error">密码不能为空!</span>');
                    $('#theForm input[name=password]').focus();
                    password = false;
                    return false;
                }

                if($('#theForm input[name=vertify]').val() == ''){
                    $('#error').html('<span class="error">验证码不能为空!</span>');
                    $('#theForm input[name=vertify]').focus();
                    vertify = false;
                    return false;
                }

                if(vertify && $('#theForm input[name=username]').val() != '' && $('#theForm input[name=password]').val() != ''){
                    $.ajax({
                        async:false,
                        url:'/index.php?m=Admin&c=Admin&a=login&t='+Math.random(),
                        data:{'username':$('#theForm input[name=username]').val(),'password':$('#theForm input[name=password]').val(),vertify:$('#theForm input[name=vertify]').val()},
                        type:'post',
                        dataType:'json',
                        success:function(res){
                            if(res.status != 1){
                                $('#error').html('<span class="error">'+res.msg+'!</span>');
                                fleshVerify();
                                username=false;
                                password=false;
                                return false;
                            }else{
                                top.location.href = res.url;
                            }
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            $('#error').html('<span class="error">网络失败，请刷新页面后重试!</span>');
                        }
                    });
                }else{
                    return false;
                }
            }

            $('.submit_span .sub').on('click',function(){
                $('.code').show();
            });
            $('#theForm input[name=submit]').on('click',function(){
                loginsubmit();
            });
			
			$(document).click(function(e){
				if(e.target.name !='vertify' && !$(e.target).parents("div").is(".submitDiv")){
					$('.code').hide();
				}
			});
            //回车提交
            $(document).keyup(function(event){
                if(event.keyCode ==13){
                    var isFocus=$("#vertify").is(":focus");
                    if(true==isFocus){
                        loginsubmit();
                    }
                }
            });
        });
        
        function fleshVerify(){
            $('#imgVerify').attr('src','/index.php?m=Admin&c=Admin&a=vertify&r='+Math.floor(Math.random()*100));//重载验证码
        }


    </script>
</body>
</html>
