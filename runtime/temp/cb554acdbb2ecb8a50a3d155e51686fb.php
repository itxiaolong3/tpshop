<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./application/admin/view/user\mechanism.html";i:1556435215;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>店铺管理</h3>
                <!--<h5>网站系统会员索引与管理</h5>-->
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>功能介绍等.</li>
            <li>怎么介绍呢.</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>店铺管理</h3>
                <h5>(共<span id="user_count"></span>条记录)</h5>
            </div>
            <div class="tDiv">
                <div class="tDiv2">
                    <!--<div class="fbutton">-->
                        <!--<a href="<?php echo U('User/add_mechanism'); ?>">-->
                            <!--<div class="add" title="添加店铺">-->
                                <!--<span><i class="fa fa-plus"></i>添加店铺</span>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</div>-->
                    <!--<div class="fbutton">-->
                    <!--<a href="javascript:;" onclick="exportUser()">-->
                    <!--<div class="add" title="导出会员">-->
                    <!--<span><i class="fa fa-share"></i>导出会员</span>-->
                    <!--</div>-->
                    <!--</a>-->
                    <!--</div>-->
                    <!--<div class="fbutton">-->
                    <!--<a onclick="send_message();">-->
                    <!--<div class="add" title="发送站内信">-->
                    <!--<span><i class="fa fa-send"></i>发送站内信</span>-->
                    <!--</div>-->
                    <!--</a>-->
                    <!--</div>-->
                    <!--<div class="fbutton">-->
                    <!--<a onclick="send_mail();">-->
                    <!--<div class="add" title="发送邮件">-->
                    <!--<span><i class="fa fa-send-o"></i>发送邮件</span>-->
                    <!--</div>-->
                    <!--</a>-->
                    <!--</div>-->
                </div>
                <div style="clear:both"></div>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <form class="navbar-form form-inline" method="post"  id="search-form2">
                <input type="hidden" name="order_by" value="addtime">
                <input type="hidden" name="sort" value="desc">
                <input type="hidden" name="phone" value="" id="phone" />
                <input type="hidden" name="company_name" value="" id="company_name" />
                <input type="hidden" name="user_ids" value="" id="user_ids" />
                <div class="sDiv">
                    <div class="sDiv2">
                        <select class="select" name="search_type" id="search_type">
                            <option value="phone">手机号</option>
                            <option value="company_name">公司名称</option>
                        </select>
                        <input type="text" id="search_key" size="30" class="qsbox" placeholder="查询"
                               value="<?php echo \think\Request::instance()->param('search_key'); ?>">
                        <input type="button" class="btn" onclick="ajax_get_table('search-form2',1)" value="搜索">
                    </div>
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="sign" axis="col0">
                            <div style="width: 24px;"><i class="ico-check"></i></div>
                        </th>
                        <th align="left" abbr="mechanism_id" axis="col3" class="">
                            <div style="text-align: center; width: 40px;" class="">ID</div>
                        </th>
                        <th align="left" abbr="company_name" axis="col4" class="">
                            <div style="text-align: center; width: 150px;" class="">公司名称</div>
                        </th>
                        <th align="center" abbr="social_code" axis="col5" class="">
                            <div style="text-align: center; width: 120px;" class="">社会信用代码</div>
                        </th>
                        <th align="center" abbr="username" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">负责人姓名</div>
                        </th>
                        <th align="center" abbr="phone" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">手机号码</div>
                        </th>
                        <th align="center" abbr="idcard" axis="col6" class="">
                            <div style="text-align: center; width: 180px;" class="">身份证号</div>
                        </th>
                        <th align="center" abbr="yinyep_img" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">营业执照</div>
                        </th>
                        <th align="center" abbr="yinyep_img" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">身份证照片</div>
                        </th>
                        <th align="center" abbr="addtime" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">审核状态</div>
                        </th>
                        <th align="center" abbr="addtime" axis="col6" class="">
                            <div style="text-align: center; width: 120px;" class="">申请日期</div>
                        </th>
                        <th align="center" axis="col1" class="handle">
                            <div style="text-align: center; width: 200px;">操作</div>
                        </th>
                        <th style="width:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="bDiv" style="height: auto;" id="ajax_return">
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        // 点击刷新数据
        var ssort = 'addtime';
        var on_sclick = 0;
        $('.hDivBox > table>thead>tr>th').hover(
            function () {
                if(typeof($(this).attr('abbr')) == "undefined"){
                    return false;
                }
                $(this).addClass('thOver');
                if($(this).hasClass('sorted')){
                    if(ssort == 'addtime'){
                        $(this).find('div').removeClass('sdesc');
                        $(this).find('div').addClass('sasc');
                    }else{
                        $(this).find('div').removeClass('sasc');
                        $(this).find('div').addClass('sdesc');
                    }
                }else{
                    $(this).find('div').addClass(ssort);
                }
            }, function () {
                    if(typeof($(this).attr('abbr')) == "undefined"){
                        return false;
                    }
                    if(on_sclick == 0){
                        if($(this).hasClass('sorted')){
                            if(ssort == 'addtime'){
                                $(this).find('div').removeClass('sasc');
                                $(this).find('div').addClass('sdesc');
                            }else{
                                $(this).find('div').removeClass('sdesc');
                                $(this).find('div').addClass('sasc');
                            }
                        }else{
                            $(this).find('div').removeClass(ssort);
                        }
                    }
                    $(this).removeClass("thOver");
                    on_sclick = 0;
            }
        );
        $('.hDivBox > table>thead>tr>th').click(function(){
            if(typeof($(this).attr('abbr')) == "undefined"){
                return false;
            }
            if($(this).hasClass('sorted')){
                $(this).find('div').removeClass(ssort);
                if(ssort == 'addtime'){
                    ssort = 'sasc';
                }else{
                    ssort = 'sdesc';
                }
                $(this).find('div').addClass(ssort);
                on_sclick = 1;
            }else{
                $('.hDivBox > table>thead>tr>th').removeClass('sorted');
                $('.hDivBox > table>thead>tr>th').find('div').removeClass(ssort);
                $(this).addClass('sorted');
                $(this).find('div').addClass(ssort);
                var hDivBox_th_index = $(this).index();
                var flexigrid_tr =   $('#flexigrid > table>tbody>tr')
                flexigrid_tr.each(function(){
                    $(this).find('td').removeClass('sorted');
                    $(this).children('td').eq(hDivBox_th_index).addClass('sorted');
                });
            }
            sort($(this).attr('abbr'));
        });

        $('.fa-refresh').click(function(){
            location.href = location.href;
        });
        ajax_get_table('search-form2',1);

    });
    //选中全部
    $('.hDivBox .sign').click(function(){
        var sign = $('#flexigrid > table>tbody>tr');
       if($(this).parent().hasClass('trSelected')){
           sign.each(function(){
               $(this).removeClass('trSelected');
           });
           $(this).parent().removeClass('trSelected');
       }else{
           sign.each(function(){
               $(this).addClass('trSelected');
           });
           $(this).parent().addClass('trSelected');
       }
    })

    // ajax 抓取页面
    function ajax_get_table(tab,page){
        var search_key = $.trim($('#search_key').val());
        var search_type = $.trim($('#search_type').val());
        if(search_key.length > 0){
            if(search_type == 'company_name'){
                $('#company_name').val(search_key);
                $('#phone').val('');
            }else{
                $('#phone').val(search_key);
                $('#company_name').val('');
            }
        }else{
            $('#company_name').val('');
            $('#phone').val('');
        }
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type : "POST",
            url:"/index.php/Admin/user/ajaxindexforjg/p/"+page,//+tab,
            data : $('#'+tab).serialize(),// 你的formid
            success: function(data){
                console.log(data,'ajax得到的数据')
                $("#ajax_return").html('');
                $("#ajax_return").append(data);
            }
        });
    }


    // 点击排序
    function sort(field)
    {
        $("input[name='order_by']").val(field);
        var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
        $("input[name='sort']").val(v);
        ajax_get_table('search-form2',cur_page);
    }
    /**
     * 回调函数
     */
    function call_back(v) {
        layer.closeAll();
        if (v == 1) {
            layer.msg('发送成功',{icon:1});
        } else {
            layer.msg('发送失败',{icon:2});
        }
    }

</script>
</body>
</html>