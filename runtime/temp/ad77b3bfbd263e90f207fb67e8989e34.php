<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view/user\add_mechanism.html";i:1555893583;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>机构管理 - 添加机构</h3>
                <h5>网站系统添加机构</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" method="post" id="add_form">
        <input type="hidden" name="act" value="<?php echo $act; ?>">
        <input type="hidden" name="mechanism_id" value="<?php echo $info['mechanism_id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="company_name"><em>*</em>公司名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="company_name" value="<?php echo $info['company_name']; ?>" id="company_name" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="social_code"><em>*</em>社会信用代码</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="social_code" value="<?php echo $info['social_code']; ?>"  id="social_code" class="input-txt">
                    <span class="err"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="phone"><em>*</em>手机号码</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="phone" value="<?php echo $info['phone']; ?>" id="phone" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">负责人手机号</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="username"><em>*</em>负责人姓名</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="username" value="<?php echo $info['username']; ?>" id="username" class="input-txt">
                    <span class="err"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="idcard">身份证号</label>
                </dt>
                <dd class="opt">
                    <input type="text"  name="idcard" value="<?php echo $info['idcard']; ?>" id="idcard" class="input-txt">
                    <span class="err"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="yinyep_img">营业执照</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_a" class="nyroModal" rel="gal" href="<?php echo $info['yinyep_img']; ?>">
                                <i id="img_i" class="fa fa-picture-o" onmouseover="layer.tips('<img src=<?php echo $info['yinyep_img']; ?>>',this,{tips: [1, '#fff']})" onmouseout="layer.closeAll()"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="yinyep_img" name="yinyep_img" value="<?php echo $info['yinyep_img']; ?>" class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','link','img_call_back')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">营业执照</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="idcard_img">身份证照片</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_b" class="nyroModal" rel="gal" href="<?php echo $info['idcard_img']; ?>">
                                <i id="img_i1" class="fa fa-picture-o" onmouseover="layer.tips('<img src=<?php echo $info['idcard_img']; ?>>',this,{tips: [1, '#fff']})" onmouseout="layer.closeAll()"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="idcard_img" name="idcard_img" value="<?php echo $info['idcard_img']; ?>" class="type-file-text">
                            <input type="button" name="button" id="button2" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','link','img_call_back1')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err"></span>
                    <p class="notic">手持身份证照片</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="auditing">审核状态</label>
                </dt>
                <dd class="opt">
                    <input id="auditing" name="auditing" type="radio" value="1" <?php if($info[auditing] == 1): ?> checked="checked"<?php endif; ?>>直接通过  &nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="auditing" type="radio" value="0" <?php if($info[auditing] == 0): ?> checked="checked"<?php endif; ?>>待审核  &nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="auditing" type="radio" disabled="disabled" value="0" <?php if($info[auditing] == 2): ?> checked="checked"<?php endif; ?>>已拒绝  &nbsp;&nbsp;&nbsp;&nbsp;
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="checkUserUpdate();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function img_call_back(fileurl_tmp)
    {
        $("#yinyep_img").val(fileurl_tmp);
        $("#img_a").attr('href', fileurl_tmp);
        $("#img_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
    function img_call_back1(fileurl_tmp)
    {
        $("#idcard_img").val(fileurl_tmp);
        $("#img_b").attr('href', fileurl_tmp);
        $("#img_i1").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
    var ajax_return_status=1;
    function checkUserUpdate(){
        if (ajax_return_status == 0) {
            return false;
        }
        ajax_return_status = 0;
        var company_name = $('input[name="company_name"]').val();
        var phone = $('input[name="phone"]').val();
        var social_code = $('input[name="social_code"]').val();
        var username = $.trim($('input[name="username"]').val());
        var idcard = $.trim($('input[name="idcard"]').val());
        var yinyep_img = $.trim($('input[name="yinyep_img"]').val());
        var idcard_img = $.trim($('input[name="idcard_img"]').val());
        var error ='';
        if(company_name == ''){
            error += "公司名称不能为空\n";
        }
        if(social_code == ''){
            error += "社会信用代码不能为空\n";
        }
        if(username == ''){
            error += "姓名不能为空\n";
        }
        if(idcard == ''){
            error += "身份证号不能为空\n";
        }
        if(yinyep_img == ''){
            error += "营业执照不能为空\n";
        }
        if(idcard_img == ''){
            error += "身份证照不能为空\n";
        }
        if(!checkMobile(phone) && phone != ''){
            error += "手机号码填写有误\n";
        }

        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            ajax_return_status = 1;
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo U('Admin/User/addEdit_mechanism'); ?>",
            data: $('#add_form').serialize(),
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
                ajax_return_status = 1;
            },
            success: function (data) {
                console.log(data,'后台返回的数据')
                if (data.status === 1) {
                    layer.msg(data.msg, {icon: 1,time: 1000}, function() {
                        location.href = data.url;
                    });
                } else {
                    layer.msg(data.msg, {icon: 2,time: 1000});
                    ajax_return_status = 1;
                }
            }
        });
        //$('#add_form').submit();
    }
</script>
</body>
</html>