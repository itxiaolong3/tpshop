<include file="public/layout" />
<style>
.ncm-goods-gift {
	text-align: left;
}
.ncm-goods-gift ul {
    display: inline-block;
    font-size: 0;
    vertical-align: middle;
}
.ncm-goods-gift li {
    display: inline-block;
    letter-spacing: normal;
    margin-right: 4px;
    vertical-align: top;
    word-spacing: normal;
}
.ncm-goods-gift li a {
    background-color: #fff;
    display: table-cell;
    height: 30px;
    line-height: 0;
    overflow: hidden;
    text-align: center;
    vertical-align: middle;
    width: 30px;
}
.ncm-goods-gift li a img {
    max-height: 30px;
    max-width: 30px;
}

a.green{
	
	background: #fff none repeat scroll 0 0;
    border: 1px solid #f5f5f5;
    border-radius: 4px;
    color: #999;
    cursor: pointer !important;
    display: inline-block;
    font-size: 12px;
    font-weight: normal;
    height: 20px;
    letter-spacing: normal;
    line-height: 20px;
    margin: 0 5px 0 0;
    padding: 1px 6px;
    vertical-align: top;
}

a.green:hover { color: #FFF; background-color: #1BBC9D; border-color: #16A086; }

.ncap-order-style .ncap-order-details{
	margin:20px auto;
}
.contact-info h3,.contact-info .form_class{
  display: inline-block;
  vertical-align: middle;
}
.form_class i.fa{
  vertical-align: text-bottom;
}
.dd{
  width:150px;
  min-height:50px;
  float:left;
  border-bottom:1px solid #f5eded;
  border-left:1px solid #f5eded;
  border-right:1px solid #f5eded;
  line-height:50px;
  text-align:center
}
.sp{
  width:500px;
  float:left;
  border-bottom: solid 1px #f5eded;
  line-height:50px;
  overflow: hidden
}
.kd{
  width:150px;
  min-height:50px;
  float:left;
  border-bottom:1px solid #f5eded;
  border-left:1px solid #f5eded;
  border-right:1px solid #f5eded;
  line-height:50px;
  text-align: center
}
.goods{text-align:left;padding-left:10px;overflow: hidden;  text-overflow: ellipsis;  white-space: nowrap;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.go(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>订单批量发货</h3>
        <h5>订单批量发货编辑</h5>
      </div>
      <div class="subject" style="width:62%">
	
      </div>
    </div>
      
  </div>
  <div class="ncap-order-style">
    <div class="titile">
      <h3></h3>
    </div>
<!---------------------------->

<div style="margin-top:10px">

<form id="send_form" action="{:U('Order/delivery_batch_handle')}" method="post">
<div style="width:704px;height:50px;background-color:#F5F5F5">
    <div style="line-height:50px;text-indent:20px;float:left">
    配送方式:
      <select name="shipping_code" id="shipping_code" onchange="ShippingName()">
        <option value="">请选择</option>
        <volist name="shipping_list" id="shipping">
           <option value="{$shipping.shipping_code}" >{$shipping.shipping_name}</option>
        </volist>
      </select>
      <input type="hidden" id="shipping_name" name="shipping_name" value="">
    </div>

    <div style="line-height:50px;float:right;margin-right:10px">
      <input id="express_num" style="vertical-align:middle" type="text" placeholder="设置配送起始单号" value="">
      &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="express();" class="ncap-btn-big ncap-btn-green">分配单号</a>
    </div>
</div>

<ul style="float:left">
  <li class="dd" style="border-top:1px solid #f5eded">订单号</li>
  <li class="sp" style="border-top:1px solid #f5eded;text-align:center">商品(数量)</li>
  <li class="kd" style="border-top:1px solid #f5eded">配送单号</li>
</ul>

<volist name="orders" id="vo" key="k">
<ul style="float:left">
  <if condition="$vo['goods_num'] gt 1">
  <li class="dd" style="height:{$vo.goods_num*31}px;line-height:{$vo.goods_num*31}px">{$vo.order_sn}</li>
  <else />
  <li class="dd">{$vo.order_sn}</li>
  </if>

  <li class="sp">
    <volist name="$vo['orderGoods']" id="vo2">

    <if condition="$vo['goods_num'] gt 1">
    <div class="goods" style="height:31px;line-height:31px">{$vo2.goods_name}（{$vo2.goods_num}）</div>
    <input type="hidden" name="order[{$k-1}][goods][{$i-1}]" value="{$vo2.rec_id}">
    <else />
    <div class="goods">{$vo2.goods_name}（{$vo2.goods_num}）</div>
    <input type="hidden" name="order[{$k-1}][goods][{$i-1}]" value="{$vo2.rec_id}">
    </if>

    </volist>
  </li>

  <if condition="$vo['goods_num'] gt 1">
  <li class="kd" style="height:{$vo.goods_num*31}px;line-height:{$vo.goods_num*31}px">
  <else />
  <li class="kd">
  </if>
    <input id="num_{$k}" name="order[{$k-1}][invoice_no]" type="text" value="">
  </li>
</ul>
   <input type="hidden" name="order[{$k-1}][shipping]" value="{$vo.shipping_status}">
   <input type="hidden" name="order[{$k-1}][order_id]" value="{$vo.order_id}">
</volist>
</form>

<div style="width:704px;height:50px;background-color:#F5F5F5;float:left;text-align:center;line-height:50px">
<a class="ncap-btn-big ncap-btn-green"  onclick="dosubmit()">确认发货</a>
</div>

</div>  
<!---------------------------->
  </div>
  
</div>
<script type="text/javascript">
  var count='{$num}';
  function express(){
    var num_start=$('#express_num').val();
    if(!num_start){
      layer.msg('起始单号不能为空', {icon: 2, time: 1000});
      return false;
    }
    if(!IsNum(num_start)){
      layer.msg('请填入正确的起始单号', {icon: 2, time: 1000});
      return false;
    }

    var tmp=0;
    for (var i = 1; i <= count; i++) {
      tmp=(parseInt(num_start) + i) - 1;
      $('#num_'+i).val(tmp);
    };
  }

  function IsNum(num){
     var reNum=/^\d*$/;
     return(reNum.test(num));
  }

  function ShippingName(){
    var checkText=$("#shipping_code").find("option:selected").text();
    $('#shipping_name').val(checkText);
  }

  function dosubmit(){
    var shipping_code=$('#shipping_code').val();
    if(!shipping_code){
      layer.msg('请选择配送方式', {icon: 2, time: 1000});
      return false;
    }

    for (var i = 1; i <= count; i++) {
      if(!$('#num_'+i).val()){
        layer.msg('快递单号不能为空', {icon: 2, time: 1000});
        return false;
      }
    };

    $('#send_form').submit();
  }
</script>
</body>
</html>