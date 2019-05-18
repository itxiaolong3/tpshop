<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"./application/admin/view/distribut\distributor_list.html";i:1532673503;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<script type="text/javascript" src="/public/static/js/layer/laydate/laydate.js"></script>

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分销商列表</h3>
        <h5>分销关系管理</h5>
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
      <li>点击查看操作将显示当前用户上三级分销ID和该用户的下三级分销商数量</li>
      <li>列表可根据用户ID和昵称搜索</li>
      <li>列表中的三级分销商数量可点击, 点击后显示该级别下的会员列表</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>分销商列表</h3>
        <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
      </div>
      <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
	  <form class="navbar-form form-inline"  method="post" action=""  name="search-form2" id="search-form2">  
      <div class="sDiv">
		<div class="sDiv2">
			<input type="text" size="30" id="user_id"  value="<?php echo $user_id; ?>" name="user_id" class="qsbox" placeholder="用户id" >
		</div>
		<div class="sDiv2">	 
			<input type="text" size="30" id="nickname"  value="<?php echo $nickname; ?>" name="nickname" class="qsbox" placeholder="用户昵称" >
		</div>
        <div class="sDiv2">	 
          <input type="submit" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox" id="ajax_return">
        <table cellspacing="0" cellpadding="0">
          <thead>
	        	<tr>
	              <th class="sign" axis="col0">
	                <div style="width: 24px;"><i class="ico-check"></i></div>
	              </th>
	              <th align="left" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: left; width: 160px;" class="">编号</div>
	              </th>
	              <th align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class="">微信昵称</div>
	              </th>
	              <th align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 120px;" class="">可用资金</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">冻结资金</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">总分成金额</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">下线会员总数</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">一级会员数</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">二级会员数</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">三级会员数</div>
	              </th>
	              <th align="center" axis="col1" class="handle">
	                <div style="text-align: center; width: 150px;">操作</div>
	              </th>
	              <th style="width:100%" axis="col7">
	                <div></div>
	              </th>
	            </tr>
	          </thead>
        </table>
      </div>
    </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
        	<table cellspacing="0" cellpadding="0">
	          <tbody>
	          <?php if(empty($user_list) == true): ?>
		 		<tr data-id="0">
			        <td class="no-data" align="center" axis="col0" colspan="50">
			        	<i class="fa fa-exclamation-circle"></i>没有符合条件的记录
			        </td>
			     </tr>
			<?php else: if(is_array($user_list) || $user_list instanceof \think\Collection || $user_list instanceof \think\Paginator): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
		        	<tr>
		              <td class="sign" axis="col0">
		                <div style="width: 24px;"><i class="ico-check"></i></div>
		              </td>
		              <td align="left" abbr="order_sn" axis="col3" class="">
		                <div style="text-align: left; width: 160px;" class=""><?php echo $v['user_id']; ?></div>
		              </td>
		              <td align="left" abbr="consignee" axis="col4" class="">
		                <div style="text-align: left; width: 120px;" class=""><?php echo $v['nickname']; ?></div>
		              </td>
		              <td align="center" abbr="article_show" axis="col5" class="">
		                <div style="text-align: center; width: 120px;" class=""><?php echo $v['user_money']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 120px;" class=""><?php echo $v['frozen_money']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 120px;" class=""><?php echo $v['distribut_money']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 80px;" class=""><?php echo $v['lower_sum']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
			                <div style="text-align: center; width: 80px;" class="">
				                <?php if($v[fisrt_leader] > 0): ?>
	                            	<a href="<?php echo U('Admin/User/index',array('first_leader'=>$v['user_id'])); ?>"><?php echo $v['fisrt_leader']; ?></a>
	                            <?php else: ?>
	                            	<?php echo $v['fisrt_leader']; endif; ?>
			                </div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
			                <div style="text-align: center; width: 80px;" class="">
				                <?php if($v[second_leader] > 0): ?>
	                            	<a href="<?php echo U('Admin/User/index',array('second_leader'=>$v['user_id'])); ?>"><?php echo $v['second_leader']; ?></a>
	                            <?php else: ?>
	                            	<?php echo $v['second_leader']; endif; ?>
			                </div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 80px;" class="">
				                <?php if($v[third_leader] > 0): ?>
	                            	<a href="<?php echo U('Admin/User/index',array('third_leader'=>$v['user_id'])); ?>"><?php echo $v['third_leader']; ?></a>
	                            <?php else: ?>
	                            	<?php echo $v['third_leader']; endif; ?>
			                </div>
		              </td>
		              <td align="center" axis="col1" class="handle">
		                <div style="text-align: center; width: 150px;">
							<a class="btn green" href="<?php echo U('Admin/User/detail',array('id'=>$v[user_id])); ?>"><i class="fa fa-list-alt"></i>查看</a>
						</div>
		              </td>
		              <td style="width:100%" axis="col7">
		                <div></div>
		              </td>
		            </tr>
		            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
		          </tbody>
	        </table>
      </div>
      <div class="iDiv" style="display: none;"></div>
    </div>
    <!--分页位置--> 
    <div class="row">
	    <div class="col-sm-6 text-left"></div>
	    <div class="col-sm-6 text-right"><?php echo $page; ?></div>
	</div>
   	</div>
</div>
<script type="text/javascript">

	 
    $(document).ready(function(){	
	   
      
		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
		 
		$('.ico-check ' , '.hDivBox').click(function(){
			$('tr' ,'.hDivBox').toggleClass('trSelected' , function(index,currentclass){
	    		var hasClass = $(this).hasClass('trSelected');
	    		$('tr' , '#flexigrid').each(function(){
	    			if(hasClass){
	    				$(this).addClass('trSelected');
	    			}else{
	    				$(this).removeClass('trSelected');
	    			}
	    		});  
	    	});
		});
	});
     
</script>
</body>
</html>