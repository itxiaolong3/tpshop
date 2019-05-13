<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"./application/admin/view/distribut\rebate_log.html";i:1548212325;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
        <h3>分成记录列表</h3>
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
      <li>点击"订单编号"查看订单详情</li>
      <li>根据用户ID、订单编号和时间查询</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>分成记录列表</h3>
        <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
      </div>
      <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
	  <form class="navbar-form form-inline"  method="post" action=""  name="search-form2" id="search-form2">  
      <div class="sDiv">
		<div class="sDiv2">	 
			<input type="text" size="30" id="user_id" placeholder="获佣用户id"  value="<?php echo $user_id; ?>" name="user_id" class="qsbox">
		</div>
		<div class="sDiv2">	 
			<input type="text" size="30" id="order_sn" placeholder="订单编号" value="<?php echo $order_sn; ?>" name="order_sn" class="qsbox" >
		</div>
		<div class="sDiv2">	 
			<input type="text" size="30" id="start_time" placeholder="生成日志开始时间" value="<?php echo $start_time; ?>" name="start_time" class="qsbox" >
		</div>
		<div class="sDiv2">	 
			<input type="text" size="30" id="end_time" placeholder="生成日志结束时间" value="<?php echo $end_time; ?>" name="end_time" class="qsbox">
		</div>
		<div class="sDiv2">	 
			<select class="select" id="status" name="status">                       
                    <option value="">全部</option>                    
                    <option value="0"<?php if($_REQUEST['status'] === '0'): ?>selected<?php endif; ?>>未付款</option>
                    <option value="1"<?php if($_REQUEST['status'] == 1): ?>selected<?php endif; ?>>已付款</option>
                    <option value="2"<?php if($_REQUEST['status'] == 2): ?>selected<?php endif; ?>>等待分成</option>
                    <option value="3"<?php if($_REQUEST['status'] == 3): ?>selected<?php endif; ?>>已分成</option>
                    <option value="4"<?php if($_REQUEST['status'] == 4): ?>selected<?php endif; ?>>已取消</option>
                  </select>
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
	                <div style="text-align: left; width: 120px;" class="">ID</div>
	              </th>
	              <th align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class="">下单会员</div>
	              </th>
	              <th align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class="">获佣用户</div>
	              </th>
	              <th align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 160px;" class="">订单编号</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">获佣金额</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">订单金额</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">获佣用户级别</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 160px;" class="">记录生成时间</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 90px;" class="">状态</div>
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
				  <a href="<?php echo U('Admin/Distribut/reward_month'); ?>"><div class="add" title="战略伙伴上月业绩分成"><span><i class="fa fa-plus"></i>战略伙伴上月业绩分成</span></div></a>
			  </div>
		  </div>
		  <div style="clear:both"></div>
	  </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
        	<table cellspacing="0" cellpadding="0">
	          <tbody>
	          <?php if(empty($list) == true): ?>
		 		<tr data-id="0">
			        <td class="no-data" align="center" axis="col0" colspan="50">
			        	<i class="fa fa-exclamation-circle"></i>没有符合条件的记录
			        </td>
			     </tr>
			<?php else: if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
		        	<tr>
		              <td class="sign" axis="col0">
		                <div style="width: 24px;"><i class="ico-check"></i></div>
		              </td>
		              <td align="left" abbr="order_sn" axis="col3" class="">
		                <div style="text-align: left; width: 120px;" class=""><?php echo $v['id']; ?></div>
		              </td>
		              <td align="left" abbr="consignee" axis="col4" class="">
		                <div style="text-align: left; width: 120px;" class="">
							<a href="<?php echo U('Admin/user/detail',array('id'=>$v[buy_user_id])); ?>">
					     		<?php if($user_arr[$v[buy_user_id]][nickname] != ''): ?>
                                      <?php echo $user_arr[$v[buy_user_id]][nickname]; elseif($user_arr[$v[buy_user_id]][email] != ''): ?>
                                       <?php echo $user_arr[$v[buy_user_id]][email]; else: ?><?php echo $user_arr[$v[buy_user_id]][mobile]; endif; ?>
							</a>
						</div>
		              </td>
		              <td align="left" abbr="consignee" axis="col4" class="">
		                <div style="text-align: left; width: 120px;" class="">
							<a href="<?php echo U('Admin/user/detail',array('id'=>$v[user_id])); ?>">
					     		<?php if($user_arr[$v[user_id]][nickname] != ''): ?>
                                      <?php echo $user_arr[$v[user_id]][nickname]; elseif($user_arr[$v[user_id]][email] != ''): ?>
                                       <?php echo $user_arr[$v[user_id]][email]; else: ?><?php echo $user_arr[$v[user_id]][mobile]; endif; ?>
							</a>
						</div>
		              </td>
		              <td align="center" abbr="article_show" axis="col5" class="">
		                 <div style="text-align: center; width: 160px;" class="">
		                	<a href="<?php echo U('Admin/order/detail',array('order_id'=>$v[order_id])); ?>">
                           		<?php echo $v['order_sn']; ?>
                             </a>
                          </div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 120px;" class=""><?php echo $v['money']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 120px;" class=""><?php echo $v['goods_price']; ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 120px;" class="">
			                <?php if($v[level] == 1): ?> 一级分销商
							<?php elseif($v[level] == 2): ?>二级分销商
							<?php else: ?> 三级分销商
							<?php endif; ?>
		                </div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 160px;" class=""><?php echo date("Y-m-d H:i:s",$v['create_time']); ?></div>
		              </td>
		              <td align="center" abbr="article_time" axis="col6" class="">
		                <div style="text-align: center; width: 90px;" class="">
		                	<?php if($v[status] == 0): ?>未付款<?php endif; if($v[status] == 1): ?>已付款<?php endif; if($v[status] == 2): ?>等待分成<?php endif; if($v[status] == 3): ?>已分成<?php endif; if($v[status] == 4): ?>已取消<?php endif; ?>  
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
	    <div class="col-sm-6 text-right"><?php echo $show; ?></div>
	</div>
   	</div>
</div>
<script type="text/javascript">

	 
    $(document).ready(function(){	
	   
      
		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
		
		$('#start_time').layDate(1); 
     	$('#end_time').layDate(1);
		 
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