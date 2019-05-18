<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view/article\category.html";i:1545824733;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i
                class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>文章分类</h3>
                <h5>网站文章分类添加与管理</h5>
            </div>
        </div>
    </div>
    <form method="post" class="form-horizontal" id="category_form">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>分类名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" class="input-txt" name="cat_name" value="<?php echo $cat_info['cat_name']; ?>">
                    <span class="err" id="err_cat_name"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label for="parent_id">上级分类</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<select class="small form-control" style="width:200px" tabindex="1" name="parent_id" id="parent_id">-->
                        <!--<option value="0">顶级分类</option>-->
                        <!--<?php if(empty($cat_info[cat_type]) || (($cat_info[cat_type] instanceof \think\Collection || $cat_info[cat_type] instanceof \think\Paginator ) && $cat_info[cat_type]->isEmpty())): ?>-->
                            <!--<?php echo $cat_select; ?>-->
                        <!--<?php endif; ?>-->
                    <!--</select>-->
                    <!--<span class="err"></span>-->
                    <!--<p class="notic">如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>导航显示</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<div class="onoff">-->
            <!--<label for="article_show1" class="cb-enable <?php if($cat_info[show_in_nav] == 1): ?>selected<?php endif; ?>">是</label>-->
            <!--<label for="article_show0" class="cb-disable <?php if($cat_info[show_in_nav] == 0): ?>selected<?php endif; ?>">否</label>-->
            <!--<input id="article_show1" name="show_in_nav" value="1" type="radio" <?php if($cat_info[show_in_nav] == 1): ?> checked="checked"<?php endif; ?>>-->
            <!--<input id="article_show0" name="show_in_nav" value="0" type="radio" <?php if($cat_info[show_in_nav] == 0): ?> checked="checked"<?php endif; ?>>-->
            <!--</div>-->
            <!--<p class="notic"></p>-->
            <!--</dd>-->
            <!--</dl>		       -->
            <dl class="row">
                <dt class="tit">
                    <label for="ac_sort">排序</label>
                </dt>
                <dd class="opt">
                    <input type="text" placeholder="排序" name="sort_order" value="<?php echo $cat_info['sort_order']; ?>"
                           class="input-txt" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label for="ac_sort">搜索关键字</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" placeholder="关键字" name="keywords" value="<?php echo $cat_info['keywords']; ?>" class="input-txt">-->
            <!--<span class="err"></span>-->
            <!--<p class="notic"></p>-->
            <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label for="ac_sort">搜索描述</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" placeholder="搜索描述" name="cat_desc" value="<?php echo $cat_info['cat_desc']; ?>" class="input-txt">-->
            <!--<span class="err"></span>-->
            <!--<p class="notic"></p>-->
            <!--</dd>-->
            <!--</dl>            -->
            <div class="bot"><a href="JavaScript:void(0);" onClick="submitForm()" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn">确认提交</a></div>
        </div>
        <input type="hidden" name="act" value="<?php echo $act; ?>">
        <input type="hidden" name="cat_id" value="<?php echo $cat_info['cat_id']; ?>">
    </form>
</div>
<script>
    function submitForm() {
        $('span.err').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo U('Article/categoryHandle'); ?>",
            data: $('#category_form').serialize(),
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status === 1) {
                    layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                        location.href = "<?php echo U('Article/categoryList'); ?>";
                    });
                } else if (data.status === 0) {
                    layer.msg(data.msg, {icon: 2, time: 1000});
                    $.each(data.result, function (index, item) {
                        $('#err_' + index).text(item).show();
                    });
                } else {
                    layer.msg(data.msg, {icon: 2, time: 1000});
                }
            }
        });
    }
</script>
</body>
</html>