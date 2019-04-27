<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view/goods\categoryList.html";i:1545882800;s:48:"E:\web\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default;">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品分类管理</h3>
        <h5>网站文章分类添加与管理</h5>
      </div>
    </div>
  </div>
  <div id="explanation" class="explanation">
    <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span title="收起提示" id="explanationZoom"></span>
    </div>
    <ul>
      <!--<li>温馨提示：顶级分类（一级大类）设为推荐时才会在首页楼层中显示</li>-->
      <!--<li><a>对分类作任何更改后，都需要到 设置 -&gt; 清理缓存 清理商品分类，新的设置才会生效</a></li>-->
      <!--<li>最多只能分类到三级</li>-->
      <!--<li style="color:#ff0000;"><strong>"是否推荐"->设置为推荐之后, 该分类会在首页楼层显示</strong></li>-->
    </ul>
  </div>
  <form method="post">
    <input type="hidden" value="ok" name="form_submit">
    <div class="flexigrid">
      <div class="mDiv">
        <div class="ftitle">
          <h3>商品分类列表</h3>
          <h5></h5>
            <!--<div class="fbutton">-->
            <!--<a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/4.html" target="_blank">-->
                <!--<div class="add" title="帮助">-->
                    <!--<span>帮助</span>-->
                <!--</div>-->
            <!--</a>-->
        <!--</div>-->
        </div>
          <a href=""><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>

      </div>
      <div class="hDiv">
        <div class="hDivBox">
          <table cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <th align="center" axis="col0" class="sign">
                  <div style="text-align: center; width: 24px;"><i class="ico-check"></i></div>
                </th>
                <th align="center" axis="col1" class="handle"><div style="text-align: center; width: 120px;">操作</div></th>
                <th align="center" axis="col2"><div style="text-align: center; width: 60px;">分类id</div></th>
                <th align="center" axis="col3"><div style="text-align: center; width: 200px;">分类名称</div></th>
                <!--<th align="center" axis="col4"><div style="text-align: center; width: 200px;">手机显示名称</div></th>-->
                <th align="center" axis="col5"><div style="text-align: center; width: 80px;">是否推荐</div></th>
                <th align="center" axis="col6"><div style="text-align: center; width: 80px;">是否显示</div></th>
                <th align="center" axis="col8"><div style="text-align: center; width: 60px;">分组</div></th>
                <th align="center" axis="col9"><div style="text-align: center; width: 60px;">排序</div></th>                
                <th axis="col10"><div></div></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="tDiv">
        <div class="tDiv2">
        
          <div class="fbutton">
            <a href="<?php echo U('Goods/addEditCategory'); ?>">
              <div title="新增分类" class="add">
                <span><i class="fa fa-plus"></i>新增分类</span>
              </div>
            </a>  
          </div>
          <div class="fbutton">
            <div class="add" title="收缩分类">
              <span onclick="tree_open(this);"><i class="fa fa-angle-double-up"></i>收缩分类</span>
            </div>
          </div>
        </div>
        <div style="clear:both"></div>
      </div>      
      <div style="height: auto;" class="bDiv" id="flexigrid"  data-url="<?php echo U('Goods/delGoodsCategory'); ?>">
        <table cellspacing="0" cellpadding="0" border="0" id="article_cat_table" class="flex-table autoht">
          <tbody id="treet1">
         <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$vo): ?>
          <tr data-level="<?php echo $vo[level]; ?>" parent_id_path ="<?php echo $vo['parent_id_path']; ?>" class="parent_id_<?php echo $vo['parent_id']; ?>" id="<?php echo $vo['level']; ?>_<?php echo $vo['id']; ?>">
              <td class="sign">
                <div style="text-align: center; width: 24px;"> 
                	<img src="/public/static/images/tv-collapsable-last.gif" fieldid="2" status="open" id="icon_<?php echo $vo['level']; ?>_<?php echo $vo['id']; ?>" onClick="treeClicked(this,<?php echo $vo[id]; ?>)">
                </div>
              </td>
              <td class="handle">
                <div style="text-align:center;   min-width:120px !important; max-width:inherit !important;">
                  <span style="margin-left:<?php echo ($vo[level] * 3); ?>em" class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
                  <ul>
                    <li><a href="<?php echo U('Goods/addEditCategory',array('id'=>$vo['id'])); ?>">编辑分类信息</a></li>
                    <?php if($vo[level] < 3): ?>                  
                    <li><a href="<?php echo U('Goods/addEditCategory',array('parent_id'=>$vo['id'])); ?>">新增下级分类</a></li>
                    <?php endif; ?>
                    <li><a href="javascript:;" onclick="publicHandle(<?php echo $vo['id']; ?>)">删除当前分类</a></li>
                  </ul>
                  </span>
                </div>
              </td>
              <td class="sort">
	              <div style="text-align: center; width: 60px;"><?php echo $vo['id']; ?></div>
              </td>
              <td class="name">
                <div style="text-align: center; width: 200px;">
                  <input type="text" value="<?php echo $vo['name']; ?>" onblur="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','name',this)" style="text-align: left; width:180px;"/>
                </div>
              </td>
              <!--<td class="name">-->
                <!--<div style="text-align: left; width: 200px;">                 -->
                  <!--<input type="text" value="<?php echo $vo['mobile_name']; ?>" onblur="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','mobile_name',this)" style="text-align: left; width:180px;"/>-->
                <!--</div>-->
              <!--</td>-->
                <td align="center" class="">
                  <div style="text-align: center; width: 80px;">
                    <?php if($vo[is_hot] == 1): ?>
                      <span class="yes" onClick="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','is_hot',this)" ><i class="fa fa-check-circle"></i>是</span>
                      <?php else: ?>
                      <span class="no" onClick="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','is_hot',this)" ><i class="fa fa-ban"></i>否</span>
                    <?php endif; ?>
                  </div>
                </td>    
                <td align="center" class="">
                  <div style="text-align: center; width: 80px;">
                    <?php if($vo[is_show] == 1): ?>
                      <span class="yes" onClick="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','is_show',this)" ><i class="fa fa-check-circle"></i>是</span>
                      <?php else: ?>
                      <span class="no" onClick="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','is_show',this)" ><i class="fa fa-ban"></i>否</span>
                    <?php endif; ?>
                  </div>
                </td>                     
              <td class="sort">
                <div style="text-align: center; width: 60px;">
                  <input type="text" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onblur="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','cat_group',this)" size="4" value="<?php echo $vo['cat_group']; ?>" />
                </div>
              </td>     
              <td class="sort">
                <div style="text-align: center; width: 60px;">
                  <input type="text" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onblur="changeTableVal('goods_category','id','<?php echo $vo['id']; ?>','sort_order',this)" size="4" value="<?php echo $vo['sort_order']; ?>" />
                </div>
              </td>                                          
                                                     
              <td style="width: 100%;">
                <div>&nbsp;</div>
              </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; ?>                                 
          </tbody>
        </table>        
      </div>
    </div>
</div>
  <script>
      $(document).ready(function(){	
	    // 表格行点击选中切换
	    $('.bDiv > table>tbody >tr').click(function(){
		    $(this).toggleClass('trSelected');
		});				
	 });
      
      // 点击展开 收缩节点
      function  tree_open(obj)
  	{
  		var tree = $('#article_cat_table tr[id^="2_"], #article_cat_table tr[id^="3_"] '); //,'table-row'
  		if(tree.css('display')  == 'table-row')
  		{
  			$(obj).html("<i class='fa fa-angle-double-down'></i>展开分类");
  			tree.css('display','none');
  			$("img[id^='icon_']").attr('src','/public/static/images/tv-expandable.gif');
  		}else
  		{
  			$(obj).html("<i class='fa fa-angle-double-up'></i>收缩分类");
  			tree.css('display','table-row');
  			$("img[id^='icon_']").attr('src','/public/static/images/tv-collapsable-last.gif');
  		}
  	}
       
       function treeClicked(obj,cat_id){
  		 var src = $(obj).attr('src');
  		 if(src == '/public/static/images/tv-expandable.gif')
  		 {
  			 $(".parent_id_"+cat_id).show();
  			 $(obj).attr('src','/public/static/images/tv-collapsable-last.gif');
  		 }else{			 
  			 $(obj).attr('src','/public/static/images/tv-expandable.gif');			 
  			 
  			 // 如果是点击减号, 遍历循环他下面的所有都关闭
  			 var tbl = document.getElementById("article_cat_table");
  			 cur_tr = obj.parentNode.parentNode.parentNode;
  			 var fnd = false;
  			  for (i = 0; i < tbl.rows.length; i++)
  			  {
  				  var row = tbl.rows[i];
  				  
  				  if (row == cur_tr)
  				  {
  					  fnd = true;         
  				  }
  				  else
  				  {
  					  if (fnd == true)
  					  {
  						 
  						  var level = parseInt($(row).data('level'));
  						  var cur_level = $(cur_tr).data('level');
  						 
  						  if (level > cur_level)
  						  {
  							  $(row).hide();		
  							  $(row).find('img').attr('src','/public/static/images/tv-expandable.gif');
  						  }
  						  else
  						  {
  							  fnd = false;
  							  break;
  						  }
  					  }
  				  }
  			  }			 
  		 }		 
  	 }  	
  </script>
</body>
</html>