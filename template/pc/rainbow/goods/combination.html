<!--<script src="__PUBLIC__/js/viewer/viewer.min.js"></script>-->
<!--<link rel="stylesheet" href="__PUBLIC__/css/viewer.min.css">-->

<div class="w1224">
    <div class="set-meal-wrap">
        <form action="" method="">
            <!--组合nav---s-->
            <div class="set-meal-nav">
                <ul class="p">
                    <volist name="combination" id="goods">
                    <li class="fl {$key==0?'meal-nav-li':''}">{$goods.title}</li>
                    </volist>

                </ul>
            </div>
            <!--组合nav---e-->
            <!--组合套餐内容---s-->
            <volist name="combination" id="goods">
                <div class="set-meal-cont">
                    <div class="set-meal-list p">
                        <div class="fl meal-one">
                            <input type="hidden" class="combination_goods_ids" value="{$goods['combination_goods'][0]['goods_id']}">
                            <input type="hidden" class="combination_item_id" value="{$goods['combination_goods'][0]['item_id']}">
                            <input type="hidden" class="combination_id" value="{$goods['combination_id']}">
                            <a href="#">
                                <div class="meal-img">
                                    <img src="{$goods['combination_goods'][0]['original_img']}" />
                                </div>
                                <div class="meal-name">
                                    {$goods['combination_goods'][0]['goods_name']}{$goods['combination_goods'][0]['key_name']}
                                </div>
                                <div class="meal-price original_price_one">
                                    ￥<span>{$goods['combination_goods'][0]['original_price']}</span>
                                </div>
                            </a>
                        </div>
                        <div class="fl jia-icon-wrap">
                            <div class="meal-jia-icon">
                            </div>
                        </div>
                        <div class="fl meal-jia-list">
                            <div class="at-Bou-wrap mr_frbox">
                                <div class="at-Boutique mr_frUl  at-que">
                                    <ul class="p ">
                                        <volist name="goods.combination_goods" id="v" key="k">
                                            <if condition="$key!=0">
                                            <li class="fl">
                                                <div class="bou-img">
                                                    <img src="/public/images/icon_goods_thumb_empty_300.png" />
                                                </div>
                                                <div class="pror-title">
                                                    <h3><a href="">{$v.goods_name}{$v.key_name}</a></h3>
                                                </div>
                                                <div class="meal-price">
                                                    <div class="meal-price-radio" >
                                                        <input type="checkbox" data-id="{$v['goods_id']}" data-item="{$v['item_id']}" onclick="clickGetPrice(this,{$v.price},{$v['original_price']-$v['price']})" {$v['selected']?'checked':''} id="price-radio{$v['goods_id']}{$v['combination_id']}" />
                                                        <label for="price-radio{$v['goods_id']}{$v['combination_id']}" ></label>
                                                    </div>
                                                    ￥<span>{$v.price}</span>
                                                </div>
                                                <if condition="$v['original_price']-$v['price']!=0">
                                                    <div class="Collocations-money">搭配省:￥<span>{$v['original_price']-$v['price']}</span></div>
                                                </if>
                                            </li>
                                            </if>
                                        </volist>
                                    </ul>
                                    <div  class="at-lef mr_frBtnL prev at-iconbts">
                                        <i></i>
                                    </div>
                                    <div  class="at-rig mr_frBtnR next  at-iconbts">
                                        <i ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl jia-icon-wrap">
                            <div class="meal-jia-icon jia-icon-dengyu">
                            </div>
                        </div>
                        <div class="fl set-meal-right">
                            <p class="set-meal-slect">已选择<span>0</span>件</p>
                            <div class="Combination-price">组合价:<span>￥<i>0</i></span></div>
                            <div class="Price-saving">共节省:￥<span>0</span></div>
                            <div class="Purchase-immediately-btn">
                                <input type="submit" name="" id="" value="立即购买" />
                                <label></label>
                            </div>
                            <div class="add-Shopping-btn">
                                <input type="submit" name="" id="" value="加入购物车" />
                                <label></label>
                            </div>
                        </div>
                    </div>
                </div>
            </volist>
        </form>
    </div>
</div>

<script type="text/javascript">
    //			组合套餐商品轮播
    //选中数量
    (function(){
        var nowIndex=0;
        var timer=null;
        var pretime=null;
        var noxttime=null;
        $(".at-lef").click(function(){
            var this_obj=$(this);
            clearTimeout(noxttime);//清除时间
            noxttime=setTimeout(function () {
                oleft(this_obj);
            },300)
        });
        $(".at-rig").click(function(){
            var this_obj=$(this);
            clearTimeout(pretime);
            pretime=setTimeout(function () {
                oright(this_obj);
            },300)
        });
        //点击往后
        function oright(this_obj){
            this_obj.siblings("ul").find("li:last").insertBefore(this_obj.siblings("ul").find("li:first"));
            this_obj.siblings("ul").animate({"left":"-165px"});
            this_obj.siblings("ul").animate({"left":0},1000,"backOut");
            nowIndex--;
            if(nowIndex<0){
                nowIndex=this_obj.siblings("ul").find("li").length-1;
            }
        }
        //点击往前
        function oleft(this_obj) {
            this_obj.siblings("ul").animate({"left":"-165px"},1000,"backIn",function(){
                this_obj.siblings("ul").find("li:first").appendTo(this_obj.siblings("ul"));
                this_obj.siblings("ul").animate({"left":"0"},0);
            });
            nowIndex++;
            if(nowIndex>this_obj.siblings("ul").find("li").length-1){
                nowIndex=0;
            }
        }
        //			导航切换
        $(".set-meal-cont").eq(0).show();
        CombinationPrice = $('.original_price_one').eq(0).find('span').text();
        eachIput($('.at-que').eq(0).find('input[type=checkbox]'));
        $('.Combination-price').eq(0).find('i').text(CombinationPrice);
        $('.Price-saving').eq(0).find('span').text(PriceSaving);
        $('.set-meal-slect').eq(0).find('span').text(num);
        $(".set-meal-nav li").click(function  () {
            var index =$(this).index();
            CombinationPrice = $('.original_price_one').eq(index).find('span').text();
            eachIput($('.at-que').eq(index).find('input[type=checkbox]'));
            $('.Combination-price').eq(index).find('i').text(CombinationPrice);
            $('.Price-saving').eq(index).find('span').text(PriceSaving);
            $('.set-meal-slect').eq(index).find('span').text(num);
            $(".set-meal-nav li").removeClass("meal-nav-li");
            $(this).addClass("meal-nav-li");
            $(".set-meal-cont").hide();
            $(".set-meal-cont").eq(index).show()

        })

    })();


    function eachIput(data) {
        PriceSaving = 0;
        num = 0;
        data.each(function(i){
            var checked = $(this).context.checked;
            if(checked){
                var val = $(this).parents(".meal-price").find('span').text();
                var saving = $(this).parents(".meal-price").next().find('span').text();
                CombinationPrice = (CombinationPrice-0)+(val-0);
                PriceSaving = (PriceSaving-0)+(saving-0);
                num++;

            }

        });
    }


    function clickGetPrice(e,price,saving) {

        var CombinationCount = $(e).parents(".set-meal-cont").find('.Combination-price').find('i').text();
        var PriceCount = $(e).parents(".set-meal-cont").find('.Price-saving').find('span').text();
        if($(e).attr('checked')){
            $(e).removeAttr('checked')
            $(e).parents(".set-meal-cont").find('.Combination-price').find('i').text((CombinationCount-price).toFixed(2));
            $(e).parents(".set-meal-cont").find('.Price-saving').find('span').text((PriceCount-saving).toFixed(2));
            num--
            $(e).parents(".set-meal-cont").find('.set-meal-slect').find('span').text(num);
        }else{
            $(e).attr('checked','checked')
            $(e).parents(".set-meal-cont").find('.Combination-price').find('i').text((CombinationCount-0+price).toFixed(2));
            $(e).parents(".set-meal-cont").find('.Price-saving').find('span').text((PriceCount-0+saving).toFixed(2));
            num++
            $(e).parents(".set-meal-cont").find('.set-meal-slect').find('span').text(num);
        }

    }
</script>

