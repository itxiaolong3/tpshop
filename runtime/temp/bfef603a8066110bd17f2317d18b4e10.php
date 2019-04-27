<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:52:"./application/admin/view/goods\ajax_spec_select.html";i:1540260088;}*/ ?>
<table class="table table-bordered" id="goods_spec_table1">                                
    <tr>
        <td colspan="2"><b>商品规格:</b></td>
    </tr>
    <?php if(is_array($specList) || $specList instanceof \think\Collection || $specList instanceof \think\Paginator): if( count($specList)==0 ) : echo "" ;else: foreach($specList as $k=>$vo): ?>
    <tr>
        <td><?php echo $vo[name]; ?>:</td>
        <td>                            
            <?php if(is_array($vo[spec_item]) || $vo[spec_item] instanceof \think\Collection || $vo[spec_item] instanceof \think\Paginator): if( count($vo[spec_item])==0 ) : echo "" ;else: foreach($vo[spec_item] as $k2=>$vo2): ?>                            
                <button type="button" data-spec_id='<?php echo $vo[id]; ?>' data-item_id='<?php echo $k2; ?>' class="btn <?php 
                             if(in_array($k2,$items_ids))                         
                                    echo 'btn-success'; 
                             else 
                                echo 'btn-default';                          
                             ?>" >
                    <?php echo $vo2; ?>
                </button>
                <span data-img_id="<?php echo $k2; ?>">
                <?php if($vo['is_upload_image'] == 1): ?>
                    <img width="35" height="35" src="<?php echo (isset($specImageList[$k2]) && ($specImageList[$k2] !== '')?$specImageList[$k2]:'/public/images/add-button.jpg'); ?>" id="item_img_<?php echo $k2; ?>" onclick="GetUploadify3('<?php echo $k2; ?>');"/>
                    <input type="hidden" name="item_img[<?php echo $k2; ?>]" value="<?php echo $specImageList[$k2]; ?>" />
                    <?php if($specImageList[$k2]): ?>
                        <span class="" onclick="deleteItemImage('<?php echo $k2; ?>');">×</span>
                    <?php endif; endif; ?>
                </span>
            <?php endforeach; endif; else: echo "" ;endif; ?>         
        </td>
    </tr>                                    
    <?php endforeach; endif; else: echo "" ;endif; ?>    
</table>
<div id="goods_spec_table2"> <!--ajax 返回 规格对应的库存--> </div>

<script>
    function deleteItemImage(cur_item_id) {
        var html = " <img width=\"35\" height=\"35\" src=\"/public/images/add-button.jpg\" id=\"item_img_"+cur_item_id+"\" onclick=\"GetUploadify3('"+cur_item_id+"');\"/>\n" +
            "             <input type=\"hidden\" name=\"item_img["+cur_item_id+"]\" value=\"<?php echo $specImageList["+cur_item_id+"]; ?>\" />";
        $("span[data-img_id="+cur_item_id+"]").html(html)
    }
    // 上传规格图片
    function GetUploadify3(k){        
        cur_item_id = k; //当前规格图片id 声明成全局 供后面回调函数调用
        GetUploadify(1,'','goods','call_back3');
    }
    
    
    // 上传规格图片成功回调函数
    function call_back3(fileurl_tmp){
        $("#item_img_"+cur_item_id).attr('src',fileurl_tmp); //  修改图片的路径
        $("input[name='item_img["+cur_item_id+"]']").val(fileurl_tmp); // 输入框保存一下 方便提交
        var html = "<img width=\"35\" height=\"35\" src=\""+fileurl_tmp+"\" id=\"item_img_"+cur_item_id+"\" onclick=\"GetUploadify3('"+cur_item_id+"');\"/>\n" +
            "\n" +
            "                            <span class=\"\" onclick=\"deleteItemImage('"+cur_item_id+"');\"\">×</span>\n" +
            "\n" +
            "                        <input type=\"hidden\" name=\"item_img["+cur_item_id+"]\" value=\""+fileurl_tmp+"\" />";
        $("span[data-img_id="+cur_item_id+"]").html(html)
    }    
    
   // 规格按钮切换 class
   $("#ajax_spec_data button").click(function(){
	   if($(this).hasClass('btn-success'))
	   {
		   $(this).removeClass('btn-success');
		   $(this).addClass('btn-default');
	   }else{
		   $(this).removeClass('btn-default');
		   $(this).addClass('btn-success');
	   }
	   ajaxGetSpecInput();	  	   	 
});
	

/**
*  点击商品规格触发下面输入框显示
*/
function ajaxGetSpecInput()
{
//	  var spec_arr = {1:[1,2]};// 用户选择的规格数组 	  
//	  spec_arr[2] = [3,4]; 
	  var spec_arr = {};// 用户选择的规格数组 	  	  
	// 选中了哪些属性	  
	$("#goods_spec_table1  button").each(function(){
	    if($(this).hasClass('btn-success'))	
		{
			var spec_id = $(this).data('spec_id');
			var item_id = $(this).data('item_id');
			if(!spec_arr.hasOwnProperty(spec_id))
				spec_arr[spec_id] = [];
		    spec_arr[spec_id].push(item_id);
			//console.log(spec_arr);
		}		
	});
		ajaxGetSpecInput2(spec_arr); // 显示下面的输入框
	
}

    $(function () {
        $(document).on("click", '.delete_item', function (e) {
            if($(this).text() == '无效'){
                $(this).parent().parent().find('input').attr('disabled','disabled');
                $(this).text('有效');
            }else{
                $(this).text('无效');
                $(this).parent().parent().find('input').removeAttr('disabled');
            }
        })
    })
	
/**
* 根据用户选择的不同规格选项 
* 返回 不同的输入框选项
*/
function ajaxGetSpecInput2(spec_arr) {

    var goods_id = $("input[name='goods_id']").val();
    $.ajax({
        type: 'POST',
        data: {spec_arr: spec_arr, goods_id: goods_id},
        url: "/index.php/admin/Goods/ajaxGetSpecInput",
        success: function (data) {
            $("#goods_spec_table2").html('').append(data);
            hbdyg();  // 合并单元格
            $("#spec_input_tab").find('tr').each(function (index, item) {
                var price = $(this).find("input[name$='[price]']").val();
                var store_count = $(this).find("input[name$='[store_count]']").val();
                if(store_count == 0 && price == 0){
                    $(this).find(".delete_item").trigger('click');
                }
            });
        }
    });
}
	
 // 合并单元格
 function hbdyg() {
            var tab = document.getElementById("spec_input_tab"); //要合并的tableID
            var maxCol = 2, val, count, start;  //maxCol：合并单元格作用到多少列  
            if (tab != null) {
                for (var col = maxCol - 1; col >= 0; col--) {
                    count = 1;
                    val = "";
                    for (var i = 0; i < tab.rows.length; i++) {
                        if (val == tab.rows[i].cells[col].innerHTML) {
                            count++;
                        } else {
                            if (count > 1) { //合并
                                start = i - count;
                                tab.rows[start].cells[col].rowSpan = count;
                                for (var j = start + 1; j < i; j++) {
                                    tab.rows[j].cells[col].style.display = "none";
                                }
                                count = 1;
                            }
                            val = tab.rows[i].cells[col].innerHTML;
                        }
                    }
                    if (count > 1) { //合并，最后几行相同的情况下
                        start = i - count;
                        tab.rows[start].cells[col].rowSpan = count;
                        for (var j = start + 1; j < i; j++) {
                            tab.rows[j].cells[col].style.display = "none";
                        }
                    }
                }
            }
        }
</script> 