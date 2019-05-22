<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view/admin\role_info.html";i:1540260088;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
				<h3>管理员 - 编辑角色</h3>
				<h5>网站系统角色管理</h5>
			</div>
		</div>
	</div>
	<form class="form-horizontal" action="<?php echo U('Admin/Admin/roleSave'); ?>" id="roleform" method="post">
		<div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
					<label for="role_name"><em>*</em>角色名称</label>
				</dt>
				<dd class="opt">
					<input type="text" name="data[role_name]" id="role_name" value="<?php echo $detail['role_name']; ?>" class="input-txt">
					<span class="err" id="name_err" style="display: none">导航名称不能为空!!</span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="role_desc">角色描述</label>
				</dt>
				<dd class="opt">
					<textarea id="role_desc" name="data[role_desc]" class="tarea" rows="6"><?php echo $detail['role_desc']; ?></textarea>
					<span class="err" id="err_tpl_content" style="display:none;">短信内容不能为空</span>
					<p class="notic"></p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
					<label for="cls_full">权限分配</label>
				</dt>
				<dd style="margin-left:200px;">
					<div class="ncap-account-container" style="border-top:none;">
						<h4>
							<input id="cls_full" onclick="choosebox(this)" type="checkbox">
							<label>全选</label>
						</h4>
					</div>
					<?php if(is_array($modules) || $modules instanceof \think\Collection || $modules instanceof \think\Paginator): if( count($modules)==0 ) : echo "" ;else: foreach($modules as $kk=>$menu): ?>
						<div class="ncap-account-container" style="border-top:none;">
							<h4>
								<label><?php echo $group[$kk]; ?></label>
								<input value="1" cka="mod-<?php echo $kk; ?>" type="checkbox">
								<label>全部</label>
							</h4>
							<ul class="ncap-account-container-list">
								<?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): if( count($menu)==0 ) : echo "" ;else: foreach($menu as $key=>$vv): ?>
									<li>
										<label><input class="checkbox" name="right[]" value="<?php echo $vv['id']; ?>" <?php if($vv['enable'] == 1): ?>checked<?php endif; ?> ck="mod-<?php echo $kk; ?>" type="checkbox"><?php echo $vv['name']; ?></label>
									</li>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</dd>
			</dl>

			<div class="bot"><a href="JavaScript:void(0);" onclick="roleSubmit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
		</div>
		<input type="hidden" name="role_id" value="<?php echo $detail['role_id']; ?>">
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(":checkbox[cka]").click(function(){
			var $cks = $(":checkbox[ck='"+$(this).attr("cka")+"']");
			if($(this).is(':checked')){
				$cks.each(function(){$(this).prop("checked",true);});
			}else{
				$cks.each(function(){$(this).removeAttr('checked');});
			}
		});
	});

	function choosebox(o){
		var vt = $(o).is(':checked');
		if(vt){
			$('input[type=checkbox]').prop('checked',vt);
		}else{
			$('input[type=checkbox]').removeAttr('checked');
		}
	}

	function roleSubmit(){
		if($('#role_name').val() == '' ){
			layer.alert('角色名称不能为空', {icon: 2});
			return false;
		}
		$('#roleform').submit();
	}
</script>
</body>
</html>