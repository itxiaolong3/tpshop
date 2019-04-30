<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./application/admin/view/comment\index.html";i:1540260088;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
        <h3>评价管理</h3>
        <h5>商品交易评价管理</h5>
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
      <li>用户对购买的商品进行评价</li>
      <li>"显示"栏可控制某条评论是否显示或隐藏</li>
      <li>评论排序：按照升序排序，数字越小，越靠前</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>商品评价列表</h3>
        <h5></h5>
      </div>
      <a href=""><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
	  <form class="navbar-form form-inline"  method="post" name="search-form2" id="search-form2">
      <div class="sDiv">
          <div class="sDiv2">
          <input type="text" size="30" name="content" class="qsbox" placeholder="评论内容...">
      </div>
         <div class="sDiv2">
          <input type="text" size="30" name="nickname" class="qsbox" placeholder="搜索用户">
         </div>
         <div class="sDiv2">
          <input type="button" onClick="ajax_get_table('search-form2',1)"  class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox">
        <table cellspacing="0" cellpadding="0">
          <thead>
	        	<tr>
	              <th class="sign" axis="col0" onclick="checkAllSign(this)">
	                <div style="width: 24px;"><i class="ico-check"></i></div>
	              </th>
	              <th align="left" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: left; width: 100px;" class="">用户</div>
	              </th> 
	              <th align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 200px;" class="">评论内容</div>
	              </th>	              
	              <th align="left" abbr="article_show" axis="col5" class="">
	                <div style="text-align: left; width: 200px;" class="">商品</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 50px;" class=""> 显示</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">评论时间</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class=""> ip地址</div>
	              </th>
                   <th align="center" abbr="article_time" axis="col3" class="">
                        <div style="text-align: center; width: 120px;" class=""> 排序</div>
                   </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 160px;" class=""> 操作</div>
	              </th>
	              <th style="width:100%" axis="col7">
	                <div></div>
	              </th>
	            </tr>
	          </thead>
        </table>
      </div>
    </div>
      <div class="tDiv">
          <div class="tDiv2">
              <div class="fbutton">
                  <a href="javascript:;" onclick="publicHandleAll('del')">
                      <div class="add" title="新增品牌">
                          <span>批量删除</span>
                      </div>
                  </a>
              </div>
              <div class="fbutton">
                  <a href="javascript:;" onclick="publicHandleAll('show')">
                      <div class="add" title="新增品牌">
                          <span>显示</span>
                      </div>
                  </a>
              </div>
              <div class="fbutton">
                  <a href="javascript:;" onclick="publicHandleAll('hide')">
                      <div class="add" title="新增品牌">
                          <span>隐藏</span>
                      </div>
                  </a>
              </div>
          </div>
          <div style="clear:both"></div>
      </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0" data-url="<?php echo U('Admin/comment/commentHandle'); ?>">
      </div>
      <div class="iDiv" style="display: none;"></div>
    </div>
    <!--分页位置--> 
   	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	    ajax_get_table('search-form2',1);
	});
	
	// ajax 抓取页面
	function ajax_get_table(tab,page){
	    cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type : "POST",
            url:"/index.php/Admin/Comment/ajaxindex/p/"+page,//+tab,
            data : $('#'+tab).serialize(),// 你的formid
            success: function(data){
                $("#flexigrid").html('');
                $("#flexigrid").append(data);

                 // 表格行点击选中切换
                $('#flexigrid > table>tbody >tr').click(function(){
                    $(this).toggleClass('trSelected');
                });


            }
        });
	}

</script>
</body>
</html>