<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./application/admin/view/user\detail.html";i:1540260088;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
<link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
<link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="/public/static/js/admin.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
<script src="/public/js/myFormValidate.js"></script>
<script src="/public/js/myAjax2.js"></script>
<script src="/public/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data.status==1){
                            layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                location.href = '';
//                                $(obj).parent().parent().parent().remove();
                            });
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function get_help(obj){

		window.open("http://www.tp-shop.cn/");
		return false;

        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'),
        });
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  

</head>
<style>
    td{height:40px;line-height:40px; padding-left:20px;}
    .span_1{
        float:left;
        margin-left:0px;
        height:130px;
        line-height:130px;
    }
    .span_1 ul{list-style:none;padding:0px;}
    .span_1 ul li{
        border:1px solid #CCC;
        height:40px;
        padding:0px 10px;
        margin-left:-1px;
        margin-top:-1px;
        line-height:40px;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>会员管理 - 会员信息</h3>
                <h5>网站系统会员管理会员信息</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="user_form" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>会员昵称</label>
                </dt>
                <dd class="opt">
                    <input class="input-txt valid" name="nickname" value="<?php echo $user['nickname']; ?>" readonly="" type="text">
                    <p class="notic">会员昵称不可修改。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员资产</label>
                </dt>
                <dd class="opt">
                	<strong class="red"><?php echo $user['user_money']; ?></strong>&nbsp;余额 &nbsp;&nbsp;
                	<strong class="red"><?php echo $user['pay_points']; ?></strong>&nbsp;积分 
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员等级</label>
                </dt>
                <dd class="opt">
                	<select id="level" name="level">
                		<?php
                                   
                                $md5_key = md5("select * from `__PREFIX__user_level`");
                                $result_name = $sql_result_v = S("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\Db::query("select * from `__PREFIX__user_level`"); 
                                    S("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $k=>$v): ?>
                		<option value="<?php echo $v[level_id]; ?>" <?php if($v['level_id'] == $user['level']): ?>selected<?php endif; ?>><?php echo $v[level_name]; ?></option>
                		<?php endforeach; ?>
                	</select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="email"><em>*</em>电子邮箱</label>
                </dt>
                <dd class="opt">
                    <input id="email"  name="email" value="<?php echo $user['email']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic">请输入常用的邮箱，将用来找回密码、接受订单通知等。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="mobile"><em>*</em>手机号码</label>
                </dt>
                <dd class="opt">
                    <input id="mobile"  name="mobile" value="<?php echo $user['mobile']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic">请输入常用的手机号码，将用来找回密码、接受订单通知等。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password">新密码</label>
                </dt>
                <dd class="opt">
                    <input id="password" name="password" class="input-txt" type="password">
                    <span class="err"></span>
                    <p class="notic">留空表示不修改密码</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password2">确认密码</label>
                </dt>
                <dd class="opt">
                    <input id="password2" name="password2" class="input-txt" type="password">
                    <span class="err"></span>
                    <p class="notic">留空表示不修改密码</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>性别</label>
                </dt>
                <dd class="opt">
                    <input value="0" name="sex" id="member_sex0" type="radio" <?php if($user['sex'] == 0): ?>checked<?php endif; ?>>
                    <label for="member_sex0">保密</label>
                    <input value="1" name="sex" id="member_sex1" type="radio" <?php if($user['sex'] == 1): ?>checked<?php endif; ?>>
                    <label for="member_sex1">男</label>
                    <input value="2" name="sex" id="member_sex2" type="radio" <?php if($user['sex'] == 2): ?>checked<?php endif; ?>>
                    <label for="member_sex2">女</label>
                    <span class="err"></span> </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label class="qq">QQ</label>
                </dt>
                <dd class="opt">
                    <input id="qq" name="qq" value="<?php echo $user['qq']; ?>" class="input-txt" type="text">
                    <span class="err"></span> </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>注册时间</label>
                </dt>
                <dd class="opt"><?php echo date('Y-m-d H:i',$user['reg_time']); ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>冻结会员</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="is_lock1" class="cb-enable <?php if($user['is_lock'] == 1): ?>selected<?php endif; ?>"><span>开启</span></label>
                        <label for="is_lock2" class="cb-disable <?php if($user['is_lock'] == 0): ?>selected<?php endif; ?>"><span>关闭</span></label>
                        <input id="is_lock1" name="is_lock" value="1" type="radio" <?php if($user['is_lock'] == 1): ?>checked<?php endif; ?>>
                        <input id="is_lock2" name="is_lock" value="0" type="radio" <?php if($user['is_lock'] == 0): ?>checked<?php endif; ?>>
                    </div>
                    <p class="notic">如果冻结会员，会员将不能操作资金。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>分销功能</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="is_distribut1" class="cb-enable <?php if($user['is_distribut'] == 1): ?>selected<?php endif; ?>"><span>开启</span></label>
                        <label for="is_distribut2" class="cb-disable <?php if($user['is_distribut'] == 0): ?>selected<?php endif; ?>"><span>关闭</span></label>
                        <input id="is_distribut1" name="is_distribut" value="1" type="radio" <?php if($user['is_distribut'] == 1): ?>checked<?php endif; ?>>
                        <input id="is_distribut2" name="is_distribut" value="0" type="radio" <?php if($user['is_distribut'] == 0): ?>checked<?php endif; ?>>
                    </div>
                    <p class="notic">如果开启，会员参与分销。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                </dt>
                <dd class="opt">
                    <div style="height:160px;">
                        <span class="span_1">
                            <ul>
                                <li>用户余额</li>
                                <li>上一级编号</li>
                                <li>上二级编号</li>
                                <li>上三级编号</li>
                            </ul>
                        </span>
                        <span class="span_1">
                            <ul>
                                <li><strong class="red"><?php echo $user['user_money']; ?></strong>&nbsp;元 </li>
                                <li>
                                    <?php if($user[first_leader] > 0): ?>
                                        <a href="<?php echo U(detail,array('id'=>$user[first_leader])); ?>"><?php echo $user['first_leader']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['first_leader']; endif; ?>
                                </li>
                                <li>
                                    <?php if($user[second_leader] > 0): ?>
                                        <a href="<?php echo U(detail,array('id'=>$user[second_leader])); ?>"><?php echo $user['second_leader']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['second_leader']; endif; ?>
                                </li>
                                <li>
                                    <?php if($user[third_leader] > 0): ?>
                                        <a href="<?php echo U(detail,array('id'=>$user[third_leader])); ?>"><?php echo $user['third_leader']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['third_leader']; endif; ?>
                                </li>
                            </ul>
                        </span>
                        <span class="span_1">
                            <ul>
                                <li>累积分佣金额</li>
                                <li>一级下线数</li>
                                <li>二级下线数</li>
                                <li>三级下线数</li>
                            </ul>
                        </span>
                        <span class="span_1">
                            <ul>
                                <li><strong class="red"><?php echo $user['distribut_money']; ?></strong>&nbsp;元 </li>
                                <li>
                                    <?php if($user[first_lower] > 0): ?>
                                        <a href="<?php echo U(index,array('first_leader'=>$user[user_id])); ?>"><?php echo $user['first_lower']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['first_lower']; endif; ?>
                                </li>
                                <li>
                                    <?php if($user[second_lower] > 0): ?>
                                        <a href="<?php echo U(index,array('second_leader'=>$user[user_id])); ?>"><?php echo $user['second_lower']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['second_lower']; endif; ?>
                                </li>
                                <li>
                                    <?php if($user[third_lower] > 0): ?>
                                        <a href="<?php echo U(index,array('third_leader'=>$user[user_id])); ?>"><?php echo $user['third_lower']; ?></a>
                                        <?php else: ?>
                                        <?php echo $user['third_lower']; endif; ?>
                                </li>
                            </ul>
                        </span>
                        <div style="clear:both;"></div>
                    </div>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="checkUserUpdate();" class="ncap-btn-big ncap-btn-green">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function checkUserUpdate(){
        var email = $('input[name="email"]').val();
        var mobile = $('input[name="mobile"]').val();
        var password = $('input[name="password"]').val();
        var password2 = $('input[name="password2"]').val();

        var error ='';
        if(password != password2){
            error += "两次密码不一样\n";
        }
        if(!checkEmail(email) && email != ''){
            error += "邮箱地址有误\n";
        }
        if(!checkMobile(mobile) && mobile != ''){
            error += "手机号码填写有误\n";
        }
        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        $('#user_form').submit();
    }
</script>
</body>
</html>