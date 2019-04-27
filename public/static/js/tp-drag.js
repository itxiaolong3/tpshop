/**
 * Created by soubao on 2017/11/30.
 */
/***********************************************************************************************************/
    var gol='';   //全局变量 记录当前上传图片的节点对象
    var link='';  //全局变量 记录当前点击添加链接操作的节点对象
    var url_tmp=''; //全局变量 用来保存弹窗里选择的链接
    var url_type=''; //链接类型
    var item='1'; //橱窗模式下编辑操作的li元素序号 默认为1
    var tab_item=0; //商品列表选项卡序号 
    var coupon_num=0;   //记录选择的优惠券数量
    var coupon_tmp=[];  //记录所选优惠券的数据
    var footer_item=0;  //记录底部上传图片时的索引
    var news_tag='';    //记录是点击哪个节点选择新闻链接(用于图文列表)
    $(document).on('click','.tpd-wstyle-wrap .tpd-wstyle-uploadimg,.tpd-nav-ico',function(){
        //console.log(choice_type);return false;
        getpath(this);
    })
    $('.tpd-blist-js').on('click','.tpd-wstyle-uploadimg',function(){
        getpath(this);
    })

    function getpath(a){
        GetUploadify(1,'','','img_call_back');
        gol=a;
    }

    //上传图片后的各组件操作集合
    function img_call_back(fileurl_tmp){
        if(choice_type=='10'){  //公告板块上传公告图片
            block[divid]['notice_pic']=fileurl_tmp;  //公告板块的公告图片
            $(gol).html('公告图片 : <a href="javascript:;"><img src="'+fileurl_tmp+'" alt="" /></a>');
            $('.tpd-edits-hidden.tpd-editing .tpdm-mes-logo').find('img').attr('src',fileurl_tmp);  //JS遍历左边节点更换公告图片
            return false;
        }

        if(choice_type=='0'){
            $(gol).html('<img style="width:210px;height:130px" src="'+fileurl_tmp+'">');
            $('.tpd-edits-hidden.tpd-editing .js-code-wrap').find('img').attr('src',fileurl_tmp);  //JS遍历左边节点更换公告图片

            //把编辑内容按顺序存入block数组
            block[divid]['pic']=fileurl_tmp;//数据存入block数组
        }

        if(choice_type=='1'){
            $(gol).html('<img style="width:210px;height:130px" src="'+fileurl_tmp+'">');

            var li=$(gol).parents('li');
            var j=li.index();
            var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpdm-carousel');
            viewUl.find('li').eq(j+1).find('img').attr('src',fileurl_tmp);
            if(j==0){
               viewUl.find('li:last').find('img').attr('src',fileurl_tmp); 
            }
            if(j==li.length-1){
               viewUl.find('li').eq(0).find('img').attr('src',fileurl_tmp); 
            }

            //把编辑内容按顺序存入block数组
            block[divid].nav[j].pic=fileurl_tmp;
        }

        if(choice_type=='4'){
            item=parseInt($(gol).parents('li').attr('li-item'));
            $(gol).html('<img style="width:210px;height:130px" src="'+fileurl_tmp+'">');

            //编辑图片后左边视图实时显示
            $('.tpd-edits-hidden.tpd-editing .tpdm-wstyle'+item).find('img').attr('src',fileurl_tmp); 

            //把编辑内容按顺序存入block数组
            var i=item-1;//最开始节点命名问题 没有从0开始
            block[divid].nav[i].pic=fileurl_tmp;
        }

        if(choice_type=='2'){
            item=parseInt($(gol).parents('li').index());

            var li=$(gol).parents('li');
            var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpdm-navstyle');
            viewUl.find('a').eq(li.index()).find('.tpdm-navstyle-ico>img').attr('src',fileurl_tmp);

            $(gol).html('<img style="width:80px;height:80px" src="'+fileurl_tmp+'">');

            block[divid].nav[item].pic=fileurl_tmp;

            //把编辑内容按顺序存入block数组
            //block[divid].pic[item]=fileurl_tmp;
        }
        // 视频封面
        if(choice_type == '14'){
            var video_url = block[divid].video_url;
            if($("#video_edit").val() != ''){
                video_url = $("#video_edit").val();
            }
            set_video(video_url,fileurl_tmp);
        }
        //图文列表
        if(choice_type == '15'){
            $(gol).html('<img style="width:210px;height:130px" src="'+fileurl_tmp+'">');
            if(parseInt(block[divid]['article_type'])<3){
                var j=$(gol).index();
                var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpd-graphicList-img');
                viewUl.find('img').attr('src',fileurl_tmp);
                block[divid].nav[j].pic=fileurl_tmp;
            }else{
                var j=$(gol).index();
                var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpd-graphicList-img');
                viewUl.find('img').eq(j).attr('src',fileurl_tmp);
                block[divid].nav[j].pic=fileurl_tmp; 
            }
        }
        //底部
        if(choice_type == '11'){
            footer_item=parseInt($(gol).parents('li').index());
            var j=parseInt($(gol).index());
            $(gol).find('img').attr('src',fileurl_tmp);
            //block[divid].nav[item].pic=fileurl_tmp;
            if(j==0){
                block[divid].nav[footer_item].pic1=fileurl_tmp;
            }
            if(j==1){
                block[divid].nav[footer_item].pic2=fileurl_tmp;
            }

            $('.tpdm-footer-false.tpdm-footf-edit .tpd-footer').find('a').eq(footer_item).find('i').eq(j).find("img").attr('src',fileurl_tmp);

        }
    }

    //海报板块高度样式选择
    $('#height0').click(function(){
        block[divid]['height_style']=0;//海报板块固定高度
    });
    $('#height1').click(function(){
        block[divid]['height_style']=1;//海报板块自定义高度
    });

    //轮播图时间间隔修改
    function roll_time(b){
        block[divid]['roll_time']=$(b).html();
    }

    //快捷入口导航名称修改
    function tit_name(b){
        var i=$(b).parents('li').index();
        //导航名称修改后左边视图实时显示
        var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpdm-navstyle');
        viewUl.find('a').eq(i).find('.tpdm-navstyle-cont').html($(b).val());
        block[divid].nav[i].title_name=$(b).val();
    }

    //底部组件修改标题名称
    function tit_name2(b){
        var i=$(b).parents('li').index();
        var viewUl=$('.tpdm-footer-false.tpdm-footf-edit');
        viewUl.find('a').eq(i).find('span').html($(b).val());
        block[divid].nav[i].title_name=$(b).val();
    }

    //底部菜单栏样式选择的
    function change_footer(c){
        block[divid]['footer_type']=c;
    }

    //公告板块标签、内容编辑
    function notice_edit(a,b,c){
        if(b=='tit'){   //公告标签
            block[divid].nav[c].notice_title=$(a).val();
            $('.tpd-edits-hidden.tpd-editing .tpdm-mesname'+(c+1)).html($(a).val());  //公告标签编辑后左边实时显示*/
        }

        if(b=='con'){   //公告内容
            block[divid].nav[c].notice_info=$(a).val();
            $('.tpd-edits-hidden.tpd-editing .tpdm-mescont'+(c+1)).html($(a).val());  //公告标签编辑后左边实时显示*/
        }
    }

    //文本导航设置
    function txt_title(a){
        block[divid]['txt_title']=$(a).val();
        $('.tpd-edits-hidden.tpd-editing').find('.txtnav-list-cont').html($(a).val());  //公告内容编辑后左边实时显示
    }

    //商品列表列表名称修改
    function tab_title(a){
        var i=$(a).parents('li').index();
        block[divid].nav[i].tab_title=$(a).val();
        var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.tpdm-goods-nav');
        viewUl.find('li').eq(i).html($(a).val());
    }

    //多图滑动列表名称修改
    function tab_title2(a){
        block[divid].tab_title=$(a).val();
        var viewUl=$('.tpd-edits-hidden.tpd-editing').find('.containers-slider-title');
        viewUl.html($(a).val());
    }

    //营销活动类型切换
    function activity_choice(a){
        if($(a).index()==0){
            block[divid].activity_type=0;
            get_activity_0();
        }
        if($(a).index()==1){
            block[divid].activity_type=1;
            get_activity_1();
        }
    }

    //营销活动标题修改
    function activity_title(a){
        block[divid].tab_title=$(a).val();
        $('.tpd-edits-hidden.tpd-editing .tpdm-activity-title').html($(a).val());
    }

    //拼团活动显示布局选择
    function activity_tit_set(a){
        block[divid].show_type=a;
    }

    //图文列表修改标题
    function article_title(a){
        block[divid]['title']=$(a).val();
        $('.tpd-edits-hidden.tpd-editing .tpd-graphicList-cont h3').html($(a).val());
    }

    //图文列表内容修改
    function article_content(a){
        block[divid]['content']=$(a).val();
        $('.tpd-edits-hidden.tpd-editing .tpd-graphicList-cont p').html($(a).val());
    }

    //新闻标题修改
    function news_title(){
        block[divid]['new_title']=$('#news_title').val();
        $('.tpd-edits-hidden.tpd-editing .hot-titles h5').html($('#news_title').val()+'<a href="/mobile/news/news_list">更多>></a>');
    }




/**********************************************************************************************************************/

if($('#json_str').html()){
    var str_json = $('#json_str').html()
    str_json = str_json.replace(/&amp;/g, '&');
    var info = $.parseJSON(str_json);
    console.log(info);
}


if(info){
    var block=info;
    if(block.is_footer){
        if(block.footer_type=='0'){
            var str1='<div class="tpd-footer tpd-footer1">';
            var str2='</div>';
            $('.tpdm-footf-js').html(str1+$('#footer1').html()+str2);
            $('.tpdm-footer-false').addClass('tpdm-footerf-show');
        }
        if(block.footer_type=='1'){
            var str1='<div class="tpd-footer tpd-footer2">';
            var str2='</div>';
            $('.tpdm-footf-js').html(str1+$('#footer2').html()+str2);
            $('.tpdm-footer-false').addClass('tpdm-footerf-show');
        }
    }
}else{
    var block={};       //用来存放编辑数据的全局数组
    block.is_notice=0;  //是否已存在公告板块 0不存在  1存在
    block.is_header=0;  //是否已存在店铺头部 0不存在  1存在
    block.is_footer=0;  //是否已存在底部板块 0不存在  1存在
    block.is_search=0;  //是否已存在搜索板块 0不存在  1存在
    block.is_shop=0;  //是否已存在门店板块 0不存在  1存在
}


//var block={};



var divid='';               //时间戳 拖拽生成板块时赋予唯一id
var choice_type='';         //记录当前点击的板块类型
var time_id='';             //记录当前点击的板块id
var window_type=0;          //橱窗模式的显示类型 默认为0
var shape_type=0;           //快捷入口模式下样式选择  0正方形  1圆形
var footer_type=0;          //底部菜单显示风格
var article_type=0;         //图文板块的排列样式 默认为0


var objH=(function getEle() {
    var obj={};
    obj.viewScroll=$('.tpd-mobile-scroll'); //手机视图滚动元素
    //手机视图容器元素
    obj.view=$('#tpd-mobile-views');
    obj.x0=obj.view.offset().left;
    obj.y0=obj.view.offset().top;
    obj.x1=obj.x0+375;
    obj.y1=obj.y0+667;
    obj.y2=obj.y1-40;
    obj.y3=obj.y0+40;
    //滚动条滚动标记
    obj.tog=0;
    //拖拽模块
    obj.dragM=null;
    obj.dragM2=null;
    return obj;
})();

/*添加模块时拖拽*/
function dragAdd() {
    $('.tpd-tool-list>li').mousedown(function (e){
        var _this=this; //保存选择元素
        $('.tpd-tool-list>li').removeClass('draging-li');//去掉其它标记
        $(this).addClass('draging-li');//自己增加标记
        $('body').append('<div class="drag-module" id="drag-module"></div>'); //创建拖拽代替模块
        var __dragM__=$('#drag-module');
        var xS=$(this).offset().left;
        var yS=$(this).offset().top-$(document).scrollTop();
//      console.log(yS)
        __dragM__.css({'left':xS,'top':yS});
        var offsetX=e.pageX-xS;
        var offsetY=e.pageY-yS-$(document).scrollTop();
        $(document).mousemove(function (e){
//      	console.log(objH)
            __dragM__.css({'left':e.pageX-offsetX,'top':e.pageY-offsetY});
            if(e.pageX>objH.x0&&e.pageX<objH.x1&&e.pageY>objH.y0&&e.pageY<objH.y1){
                isCollision(objH.view,e.pageY,e.pageY+84,1);
                //鼠标驱动滚动条判断
                judgePos(e.pageY,objH.viewScroll,objH.view);
            }else{
                $('#guide-box').remove();
            }
        });
        $(document).mouseup(function (e){
            $(document).unbind('mousemove').unbind('mouseup');
            if(e.pageX>objH.x0&&e.pageX<objH.x1&&e.pageY>objH.y0&&e.pageY<objH.y1){
                objH.view.find('.tpd-edits-hidden').removeClass('tpd-editing');
                //  创建模块
                createModule($(_this).attr('data-eidtid'));
            }
            __dragM__.remove();
        });
        return false;
    });
}
dragAdd();

/*拖拽时创建模板*/
function createModule(eidtid){
    if(eidtid==10){
        if(block.is_notice==1){
            $('#guide-box').remove();
            layer.msg('只能添加一个滚动公告', {icon: 2, time: 1000});
            return false;
        }
    }
    if(eidtid==18){
        if(block.is_shop==1){
            $('#guide-box').remove();
            layer.msg('只能添加一个多门店店铺', {icon: 2, time: 1000});
            return false;
        }
    }
    if(eidtid==11){
        if(block.is_footer==1){
            $('#guide-box').remove();
            layer.msg('只能添加一个底部菜单', {icon: 2, time: 1000});
            return false;
        }
    }
    divid=Date.parse(new Date());
    choice_type=eidtid;     //拖拽创建时记录板块类型

    var html=diyHtml[dataEidtid[eidtid]][0];
    var box=$('#guide-box');



    if(eidtid==8){ //搜索栏
        //$('.tpdm-head-wrap>.tpdm-head-js').addClass('tpdm-head-scale').html(html);
        //$(".tpdm-head-wrap").addClass('tpdm-head-edit')
        
         $(".tpdm-footer-false").removeClass("tpdm-footf-edit").find(".tpd-edit-module").hide();
        
    }else if(eidtid==11){ //底部菜单
    	console.log(html)
        $('.tpdm-footer-false').addClass('tpdm-footerf-show tpdm-footf-edit').find('.tpdm-footf-js').html(html);
        $(".tpdm-head-wrap").removeClass("tpdm-head-edit").find('.tpd-edit-module').hide();
      	// $('.tpdm-footer-wrap').html(html);
    }else{
        var ele=$('<div class="tpd-edits-hidden tpd-editing" data-timeid="'+divid+'" data-eidtid="'+eidtid+'"><div class="js-code-wrap"></div><i class="tpd-editing-close"></i><div class="tpd-edit-module"></div></div>');
        ele.find('.js-code-wrap').html(html);
        box.after(ele);
        if(eidtid==1){  //轮播图
            $('.tpdm-carousel').each(function () {
                if(!$(this).attr('id')){
                    var timestamp = Date.parse(new Date())/1000;
                    $(this).attr('id','carousel'+timestamp);
                    initSlide($(this).parents('.js-code-wrap'),'carousel'+timestamp,3000);
                }
            })
        }
        
        $(".tpdm-head-wrap,.tpdm-footer-false").removeClass("tpdm-head-edit tpdm-footf-edit");
        $(".tpdm-footer-false,.tpdm-head-wrap").find(".tpd-edit-module").hide();
    }
    box.remove();
    $('.tpd-diy-js').removeClass('diy-ac');
    $('.tpd-diy-js.'+dataEidtid[eidtid]).addClass('diy-ac');
    /*
    * eidtid 板块类型
    * 0海报    1轮播广告   2快捷入口   3商品列表   4橱窗    5文本导航   6营销活动   7优惠券    8搜索栏    9店铺头部
    * 10公告    11底部菜单   12新闻  13轮播多图  14视频  15图文列表  16富文本  17单文本  18多门店店铺
    */
   
    block[divid]={};
    block[divid].timeid=divid;          //板块时间戳id 用来区分生成的div块

    if(eidtid!='8'){
        block[divid].spacing=10;       //生成板块时默认下间距为 10px  
    }

    block[divid]['block_type']=eidtid;    //生成板块时记录板块类型

    if(eidtid=="0"){    //生成海报板块时默认样式选择为固定高度,改为自适应
        block[divid]['height_style']=1;
        $("#height1").prop("checked",true)
    }

    if(eidtid=='1'){    //生成轮播板块时默认轮播间隔为3s
        block[divid]['roll_time']='3s';
        block[divid]['nav']=[
        {
            "pic":"",
            "url":""
        },
        {
            "pic":"",
            "url":""
        },
        ];
    }


    if(eidtid=='2'){    //生成快捷入口板块时默认样式选择为方形  导航块赋初始值
        block[divid]['shape_type']=0;
        block[divid]['nav']=[
            {
                "pic":"/public/static/images/ico-nav1.jpg",
                "title_name":"分类",
                "url":""
            },
            {
                "pic":"/public/static/images/ico-nav2.jpg",
                "title_name":"关注",
                "url":""
            },
            {
                "pic":"/public/static/images/ico-nav3.jpg",
                "title_name":"收藏",
                "url":""
            },
            {
                "pic":"/public/static/images/ico-nav4.jpg",
                "title_name":"购物车",
                "url":""
            },
            {
                "pic":"/public/static/images/ico-nav5.jpg",
                "title_name":"个人中心",
                "url":""
            }
        ];
        li_split();
    }

    if(eidtid=='3'){
        block[divid].show_type=0;   //显示布局  0橱窗式  1列表式  2海报式
        block[divid].num=4;
        block[divid].name_show=1;   //列表名称显示  0不显示  1显示
        block[divid].order=0;       //商品排序
        $('#goods-name').find('label').find('input').eq(0)[0].checked = true
        block[divid]['nav']=[
            {"tab_title":"列表名称1","str":"","sql_where":{'num':4,'order':0}},
            {"tab_title":"列表名称2","str":"","sql_where":{'num':4,'order':0}},
            {"tab_title":"列表名称3","str":"","sql_where":{'num':4,'order':0}}
        ];

        //拖拽时就按默认条件读取商品
        $.post("/index.php/Admin/Block/goods_list_block",{'num':block[divid].num,'order':block[divid].order},function(res){
            $('.tpd-edits-hidden.tpd-editing').find('.tpdm-goods-list ul').html(res.result);
        },'JSON');
    }



    if(eidtid=='4'){    //生成橱窗板块时默认显示风格赋值
        block[divid]['window_style']=window_type; 
        block[divid]['nav']=[
            {
                "pic":"",
                "url":""
            },
            {
                "pic":"",
                "url":""
            },
            {
                "pic":"",
                "url":""
            },
            {
                "pic":"",
                "url":""
            },
        ];
    }

    if(eidtid=='5'){
        //生成文本导航板块时默认文本颜色赋值
        //block[divid]['align']=0;    //字体对齐方式 0居左 1居中 2居右
        //block[divid]['bgcolor']='#ffffff'; //背景颜色
        //block[divid]['color']='#000000';   //字体颜色
        //block[divid]['size']='14';    //字体大小
        //block[divid]['txt_title']='';   //文本标题
        //block[divid]['url']='';

        block[divid].txt_title = '添加一个文本内容';
        block[divid].text_align = 'left';
        block[divid].text_bg_color = '#ffffff';  // 背景色
        block[divid].text_font_color = '#000000'; // 字体颜色
        block[divid].text_font = '14'; // 字体大小
        block[divid].url = ''; // 跳转链接
        block[divid].spacing = '10';
        show_text_5();

    }

    if(eidtid=='6'){    //生成营销活动板块时默认营销活动赋值
        block[divid].activity_type=0;       //营销活动类型 0拼团  1秒杀
        //拼团类型时才有以下3个属性值
        block[divid].tab_type=1;            //活动标题是否显示 0不使用  1使用
        block[divid].tab_title='';          //活动标题内容
        block[divid].show_type=0;           //显示布局  0橱窗式  1列表式 

        get_activity_0();   
    }

    if(eidtid=='7'){    //生成优惠券板块时默认显示风格赋值
        block[divid].coupon_style='0';
        block[divid]['nav']=[];
        $('.tpd-coupon-list .tpd-addlink-target .coupon_span').text(3)
        $('#coupon-style').find('label').find('input').eq(0)[0].checked = true
    }

    if(eidtid=='8'){    //生成搜索栏板块时默认风格赋值
        block[divid]['search_style']=0;
        block[divid]['div_order']=88888;//APP使用这个排序字段
        block.is_search=1; // 是否显示搜索栏板块 1显示，0不显示
        block.search_id=divid;
        block.search_style=0; //搜索栏默认风格0
        $('.tpdm-head-wrap>.tpdm-head-js').addClass('tpdm-head-scale').html(html);
        $('.tpdm-head-wrap .tpd-edit-module').show();
        $(".tpdm-head-wrap").addClass('tpdm-head-edit')
        
    }
    if(eidtid=='9'){
        block.is_header=1;
    }
    // 滚动公告
    if(eidtid=='10'){   //生成公告板块时默认文本颜色赋值
        block.is_notice = 1;
        block[divid].text_font = '14'; // 字体大小
        block[divid].msg_bg_color = '#ffffff'; // 背景色
        block[divid].msg_font_color = '#000000'; // 字体颜色
        block[divid].notice_pic = $(".tpd-mes-tpWrap ul li img").eq(0).attr('src'); // 图标地址 默认第一个
        block[divid]['nav'] = [
        {
            "notice_title":"", // 标题没有
            "notice_info":"此处填写公告内容", // 显示内容
            "url":"" //链接地址
        },
        {
            "notice_title":"",
            "notice_info":"此处填写公告内容",
            "url":""
        }
        ];
        show_msg();
    }

    if(eidtid=='11'){   //生成底部板块时默认底部风格为0
        block.is_footer=1;   //是否有底部 0无 1有
        block.footer_id=divid;  //底部对应的divid
        block[divid]['div_order']=99999;    //底部默认排序
        block[divid]['nav']=[
            {
                "pic1":"/template/mobile/rainbow/static/images/home1.png",
                "pic2":"/template/mobile/rainbow/static/images/home2.png",
                "title_name":"首页",
                "url":"/index.php/Mobile/Index/index"
            },
            {
                "pic1":"/template/mobile/rainbow/static/images/category1.png",
                "pic2":"/template/mobile/rainbow/static/images/category2.png",
                "title_name":"分类",
                "url":"/index.php/Mobile/Goods/categoryList"
            },
            {
                "pic1":"/template/mobile/rainbow/static/images/find1.png",
                "pic2":"/template/mobile/rainbow/static/images/find2.png",
                "title_name":"发现",
                "url":"/index.php/Mobile/Goods/ajaxSearch"
            },
            {
                "pic1":"/template/mobile/rainbow/static/images/cart1.png",
                "pic2":"/template/mobile/rainbow/static/images/cart2.png",
                "title_name":"购物车",
                "url":"/index.php/Mobile/Cart/index"
            },
            {
                "pic1":"/template/mobile/rainbow/static/images/user1.png",
                "pic2":"/template/mobile/rainbow/static/images/user2.png",
                "title_name":"我的",
                "url":"/index.php/Mobile/User/index"
            }
        ];
        //set_words()
    }

    if(eidtid=='13'){
        block[divid].num=3;
        block[divid].name_show=1;   //列表名称显示  0不显示  1显示
        block[divid].order=0;       //商品排序
        block[divid].tab_title="列表名称";
        //拖拽时就按默认条件读取商品
        $.post("/index.php/Admin/Block/goods_list_block",{'num':block[divid].num,'order':block[divid].order,'block_type':block[divid].block_type},function(res){
            $('.tpd-edits-hidden.tpd-editing').find('.containers-slider-cont').html(res.result);
        },'JSON');
    }

    // 视频
    if(eidtid=='14'){
        set_video('http://192.168.0.146:1005/public/upload/goods/2018/08-10/65fee884ee7a48977e8284750bd7f98f.mp4','');
    }


    //图文列表
    if(eidtid=='15'){   //生成图文板块时默认风格赋值
        block[divid]['article_type']=article_type; 
        block[divid]['url']='';
        block[divid]['title']='';
        block[divid]['content']='';
        block[divid]['nav']=[
            {
                "pic":""
            },
            {
                "pic":""
            },
            {
                "pic":""
            },
        ];
    }

    //富文本
    if(eidtid=='16'){
        block[divid]['url']='';
        block[divid]['bgcolor']='#ffffff';
        block[divid]['content']='';
        show_msg_16();
    }
    // 单文本
    if(eidtid=='17'){
        block[divid].txt_title = '添加一个文本内容';
        block[divid].text_align = 'left';
        block[divid].text_bg_color = '#ffffff';  // 背景色
        block[divid].text_font_color = '#000000'; // 字体颜色
        block[divid].text_font = '14'; // 字体大小
        block[divid].url = ''; // 跳转链接
        block[divid].spacing = '10';
        show_text();
    }
    // 多门店
    if(eidtid=='18'){
        block.is_shop = 1;
        block[divid].search_radius = '18'; //搜索半径
        block[divid].top_title = '附近商家'; //顶部标题
        block[divid].distance_sort = 0; // 距离排序 1排 0不排
        block[divid].url = ''; // 跳转链接
        block[divid].cur_title = '';
        block[divid].sql_where ={'num':4,'order':0}
            /*{
                "shop_desc":"", // 店描述
                "shop_title":"", // 店标题
                "shop_img":"", //店图片
                "shop_addr":"", //店地址
                "shop_distance":"" //店距离
            }*/
        show_multipleStores();
    }

    //新闻
    if(eidtid=='12'){   //生成新闻板块
        block[divid]['new_num']=2;  //默认新闻数量
        block[divid]['new_title']='新闻资讯';   //默认新闻标题
        block[divid]['ids']=''; //选择新闻时记录的新闻id

        $.post("/index.php/Admin/Block/get_news_list",{'num':block[divid].new_num},function(res){
            $('.tpd-edits-hidden.tpd-editing').find('.newlist-item ul').html(res.result);
        },'JSON');
    }
    console.log('createModule',eidtid,divid)
}
var dragM='<div class="tpd-guide-box" id="guide-box">模块将移动到此处</div>';
/*碰撞检测*/
function isCollision(ele,h1,h2,type){
    if ($('#guide-box').length<1){
        ele.find('.tpdm-footer-wrap').before(dragM);
    }
    ele.find('.tpd-edits-hidden').each(function () {
        var t=$(this).offset().top;
        var b=t+$(this).height();
        if(h1>b||h2<t) {
            if($(this).index()==ele.find('.tpd-edits-hidden').length-1&&type){
                $('#guide-box').remove();
                $(this).after(dragM);
                return false;
            }
        }else{
            $('#guide-box').remove();
            if(h1-t>$(this).height()/2){
                $(this).after(dragM);
            }else{$(this).before(dragM);}
            return false;
        }
    })
}
/*判断鼠标位置*/
function judgePos(pageY,scroll,view) {
    if(pageY>objH.y2){
        if(objH.tog==1){ return false;}
        objH.tog=1;
    }else if(pageY<objH.y3){
        if(objH.tog==-1){ return false;}
        objH.tog=-1;
    }else{
        if(objH.tog==0){ return false;}
        objH.tog=0;
    }
    scrollMove(objH.tog,scroll,view);
}
/*鼠标驱动滚动条*/
function scrollMove(tog,scroll,view){
    if(tog==0){
        scroll.stop();
        return false;
    }else if(tog==1){
        scroll.stop().animate({scrollTop:view.height()},2000);
    }else if(tog==-1){
        scroll.stop().animate({scrollTop:0},2000);
    }
}
/*站内板块拖拽、点击事件*/
function dragModlue(){
    objH.view.on('mouseenter','.tpd-edits-hidden',function (e){
        $(this).addClass('tpd-edit-hover').siblings().removeClass('tpd-edit-hover');
    });
    objH.view.on('mouseleave','.tpd-edits-hidden',function (e){
        $(this).removeClass('tpd-edit-hover');
    });

//	console.log(objH.view);
    //底部专用点击事件
     $(document).on('mousedown','#tpd-mobile-views .tpdm-footer-false',function (e){
//      console.log(block[divid])
        if (e.target==$(this).find('.tpd-editing-close')[0]) {
        	$('.tpd-diy-js').removeClass('diy-ac');
            $('.tpdm-footer-false').removeClass('tpdm-footerf-show').find('.tpdm-footf-js').html('');
            $('.tpdm-footer-wrap').html('');
            block.is_footer=0;
            delete block.footer_type;
            delete block[block.footer_id];
            delete block.footer_id;
        };
        if ($(this).hasClass('tpdm-footf-edit')) {
        	$(this).removeClass('tpdm-footf-edit');
        	$('.tpd-diy-js').removeClass('diy-ac');
        	$(this).find(".tpd-edit-module").hide();
            $('.tpd-tool-list>li').removeClass('draging-li');
		}else{
			$(this).find(".tpd-edit-module").show();
			$(this).addClass('tpdm-footf-edit');
			$(".tpd-edits-hidden").removeClass("tpd-editing");
			$(".tpdm-head-wrap").removeClass("tpdm-head-edit").find('.tpd-edit-module').hide();
			$('.tpd-diy-js').removeClass('diy-ac');
            $('.tpd-diy-js.'+dataEidtid[11]).addClass('diy-ac');
            $('.tpd-tool-list>li').removeClass('draging-li');
            $(".tpdm-head-wrap.tpdm-head-edit").find('.tpd-edit-module').hide();
            $('.tpd-tool-list>li[data-eidtid="11"]').addClass('draging-li');
		}

        //板块点击事件
        divid=block.footer_id;   //底部的timeid
        choice_type=11;          //底部类型
        footer_split();
        set_words();
    });

 $(document).on('mousedown','#tpd-mobile-views .tpd-edits-hidden',function (e){
        //判断触发事件
        if(e.target==$(this).find('.tpd-editing-close')[0]){ //删除事件
        	$('.tpd-diy-js').removeClass('diy-ac');
            if(block[divid].block_type=='9'){
                block.is_header=0;
            }
            if(block[divid].block_type=='10'){
                block.is_notice=0;
            }
            if(block[divid].block_type=='18'){
                block.is_shop=0;
            }
            delete block[divid];
            $(this).remove();
            return;
        }

        //添加编辑标记
        var editid=$(this).attr('data-eidtid');
        if($(this).hasClass('tpd-editing')){
            $(this).removeClass('tpd-editing');
            $('.tpd-diy-js').removeClass('diy-ac');
            $('.tpd-tool-list>li').removeClass('draging-li');
        }else{
            $(this).addClass('tpd-editing').siblings().removeClass('tpd-editing');
            $(".tpdm-footer-false").removeClass("tpdm-footf-edit").find(".tpd-edit-module").hide();
			$(".tpdm-head-wrap.tpdm-head-edit").find('.tpd-edit-module').hide();
            $('.tpd-diy-js').removeClass('diy-ac');
            $('.tpd-diy-js.'+dataEidtid[editid]).addClass('diy-ac');
            $('.tpd-tool-list>li').removeClass('draging-li');
            $('.tpd-tool-list>li[data-eidtid="'+editid+'"]').addClass('draging-li');
        
            //板块点击事件
            divid=$(this).attr('data-timeid');   //当前点击板块的timeid
            choice_type=$(this).attr('data-eidtid'); //当前点击板块的类型

            $('.tpd-diy-bb.tpd-cdiy-font28').find('input').val(block[divid].spacing);  //点击时间距设置显示
            console.log('mousedown choice_type',choice_type);
            console.log(block[divid]);
            switch(choice_type){


                case '0':
                    if(block[divid].height_style==0){
                        $('#height0').attr('checked','checked').prop('checked','checked');
                        $('#height1').removeAttr('checked');
                    }else{
                        $('#height1').attr('checked','checked').prop('checked','checked');
                        $('#height0').removeAttr('checked');
                    }

                    if(block[divid]['pic']){
                        $('.tpd-diy-js').eq(0).find('.tpd-wstyle-uploadimg').eq(0).html('<img style="width:210px;height:130px" src="'+block[divid]['pic']+'">');
                    }
                    if(block[divid]['url']){
                        $('.tpd-diy-js').eq(0).find('.tpd-addlink-target').eq(0).html(block[divid]['url']);
                    }
                    break;

                case '1':
                    $('#banner-time-js').html(block[divid].roll_time);
                    roll_split();
                    break;

                case '2':
                    $('#shape_type').find('input').each(function(){
                        if($(this).val()==block[divid].shape_type){
                            $(this).attr('checked','checked').prop('checked','checked');
                        }else{
                            $(this).removeAttr('checked');
                        }
                    })
                    li_split();
                    $('.tpd-wstyle-wrap .tpd-wstyle-uploadimg,.tpd-nav-ico').click(function(){
                        getpath(this);
                    })
                    break;

                case '3':
                    $('#goods-name').find('input').each(function(){
                        if($(this).val()==block[divid].name_show){
                            $(this).attr('checked','checked').prop('checked','checked');
                        }else{
                            $(this).removeAttr('checked');
                        }
                    })

                    $('#goods-style').find('input').each(function(){
                        if($(this).val()==block[divid].show_type){
                            $(this).attr('checked','checked').prop('checked','checked');
                        }else{
                            $(this).removeAttr('checked');
                        }
                    })

                    $('#goods-num').find('input').each(function(){
                        if($(this).val()==block[divid].num){
                            $(this).attr('checked','checked').prop('checked','checked');
                        }else{
                            $(this).removeAttr('checked');
                        }
                    });
                    goods_list_edit();
                    show_edit_3();
                    break;
                case '4'://橱窗类型
                    var num=block[divid]['window_style'];
                    siblings('.tpd-window-style','li','style-ac');
                    siblings('.tpd-wstyle-wrap','.tpd-wstyle-list','wstyle-ac');
                    function siblings(ele1,ele2,class1){
                        $(ele1).find(ele2).removeClass(class1);
                        $(ele1).find(ele2).eq(num).addClass(class1);
                    }

                    //橱窗模式点击左边板块 右边显示内容切换
                    for(var a in block[divid]['nav']){
                        if(block[divid]['nav'][a]){
                            if(block[divid]['nav'][a]['pic']){
                                $('.tpd-wstyle-list.wstyle-ac').find('li').eq(a).find('.tpd-wstyle-uploadimg').html('<img style="width:210px;height:130px" src="'+block[divid]['nav'][a]['pic']+'">');
                            }
                            if(block[divid]['nav'][a]['url']){
                                $('.tpd-wstyle-list.wstyle-ac').find('li').eq(a).find('.tpd-addlink-target').html(block[divid]['nav'][a]['url']);
                            }
                        }
                    }
                    break;
                case '5':
                    /*$('#color_choice').find('li').each(function(){
                        if($(this).attr('data-color')==block[divid]['color']){
                            $('#color_show').html($(this).html());
                        }
                    })

                    if(block[divid].txt_title){
                        $('#txt_nav').html(block[divid].txt_title);
                    }
                    if(block[divid].url){
                        $('#txt_url').html(block[divid].url);
                    }*/
                    show_text_5();
                    break;

                case '6':
                    if(block[divid].activity_type=='0'){

                        $('#activity_show').html('拼团');
                        //显示布局
                        if(block[divid].show_type=='0'){
                            $('#activity-style').find('input').eq(0).attr('checked','checked');
                            $('#activity-style').find('input').eq(1).removeAttr('checked');
                        }else{
                            $('#activity-style').find('input').eq(1).attr('checked','checked');
                            $('#activity-style').find('input').eq(0).removeAttr('checked');
                        }

                        //活动标题
                        if(block[divid].tab_type=='0'){
                            $('#activity-title').find('input').eq(0).attr('checked','checked');
                            $('#activity-title').find('input').eq(1).removeAttr('checked');
                        }else{
                            $('#activity-title').find('input').eq(1).attr('checked','checked');
                            $('#activity-title').find('input').eq(0).removeAttr('checked');
                            $('#activity-title').find('input').eq(2).val(block[divid].tab_title);
                        }
                        $(".yxhd-6").addClass('tpd-activity-ac')
                        $('#activity-title').show();
                        $('#activity-style').show();
                    }else if(block[divid].activity_type=='1'){
                        $(".yxhd-6").removeClass('tpd-activity-ac')
                        $('#activity_show').html('秒杀');
                        $('#activity-title').hide();
                        $('#activity-style').hide();
                    }
                    show_edit_6();
                    break;

                case '7':
                    if(block[divid].coupon_style=='0'){
                        $('#coupon-style').find('input').eq(0).attr('checked','checked').prop('checked','checked');
                        $('#coupon-style').find('input').eq(1).removeAttr('checked');
                    }else{
                        $('#coupon-style').find('input').eq(1).attr('checked','checked').prop('checked','checked');
                        $('#coupon-style').find('input').eq(0).removeAttr('checked');
                    }
                    break;

                case '10':

                    show_msg();
                    break;
                // case '11':
                //     footer_split();
                //     break;
                case '12':

                    $('#news_title').val(block[divid].new_title);
                    $('#news_title').parent().parent().find('.now-words').html(block[divid].new_title.length);
                    //.attr('checked','checked')
                    var input_li= $("#goods-num-one").find("input");
                    input_li.removeAttr("checked");
                    for(var i=0; i<input_li.length;i++){
                      if ($(input_li[i]).val()==block[divid].new_num) {
                        $(input_li[i]).prop('checked','checked');
                      };
                    }

                    $.post("/index.php/Admin/Block/get_news_list",{'num':block[divid].new_num,'news':block[divid]['ids']},function(res){
                        $('.tpd-edits-hidden.tpd-editing').find('.newlist-item ul').html(res.result);
                    },'JSON');
                    break;
                case '13':
                    //set_now_words_length()
                    show_edit13(block[divid])
                    break;
                case '14':
                    edit_show_video(block[divid].video_url,block[divid].video_img)
                    break;
                case '15':
                    //tpd-wstyle-list wstyle-ac tpd-cdiy-list
                    //$(".tpd-wstyle-wrap.tpd-wstyle-list").show();
                    $(".tpd-wstyle-wrap.tpd-graphicList-link.wstyle-ac").find('ul').removeClass().attr('class','tpd-wstyle-list wstyle-ac tpd-cdiy-list')
                    console.log('show 15')
                    $('.tpd-graphicList-warps .tpd-graphicList-li').eq(block[divid].article_type).click();
                    break;
                case '16':
                    show_msg_16();
                    break;
                case '17':
                    show_text();
                    break;
                case '18':
                    show_multipleStores();
                    break;
            }
        }
        //创建拖拽代替模块
        $('body').append('<div class="drag-module2 hide" id="drag-module2"></div>');

        $(this).after(dragM);
        $('#guide-box').addClass('hide');
        objH.dragM2=$('#drag-module2');
        var _this=this; //保存选择元素
        var yE=0;
        $(document).mousemove(function (e){
            $('.tpd-diy-js.'+dataEidtid[editid]).addClass('diy-ac');
            $('#guide-box').removeClass('hide');
            $(_this).addClass('hide tpd-editing');  //拖拽移动的时候 保持触发的原始转态并隐藏
            yE=e.pageY-$(document).scrollTop()-30;
            //防止移动出边界
            if(yE<=objH.y0-$(document).scrollTop()) yE=objH.y0-$(document).scrollTop();
            if(yE+60>=objH.y1-$(document).scrollTop()) yE=objH.y1-$(document).scrollTop()-60;
            objH.dragM2.removeClass('hide').css({'top':yE});
            isCollision(objH.view,e.pageY,e.pageY+60);
            //鼠标驱动滚动条判断
            judgePos(e.pageY,objH.viewScroll,objH.view);
        });
        $(document).mouseup(function (e){
            $(document).unbind('mousemove').unbind('mouseup');
            $('#guide-box').after($(_this).removeClass('hide')).remove();
            objH.dragM2.remove();
        });
    
        $(".tpdm-head-wrap.tpdm-head-edit").removeClass('tpdm-head-edit').find('.tpd-edit-module').hide();
//      $(".tpdm-head-wrap.tpdm-head-edit").find('.tpd-edit-module').hide();
        return false;
    })
  
}
dragModlue();
/*调整下边距*/
$('.tpd-diy-bb>input').blur(function () {
    var val=parseInt($(this).val());
    if(isNaN(val)){
        val=10;
    }else if(val<0){
        val=0;
    }
    $(this).val(val);
    block[divid].spacing=val; //修改板块下边距时存入block数组
    $('.tpd-edits-hidden.tpd-editing').css('margin-bottom',val);
})

$(function () {
    $('body').on('click','.pop-swith',function () { /*弹窗里面的开关控制*/
        $(this).toggleClass('pop-swith-on');
        if(choice_type=='7'){
            var num = parseInt($("#pop-coupon").attr('data-num'))

            if($(this).hasClass('pop-swith-on')){
                if((block[divid].coupon_style == '0' && num<3) || ((block[divid].coupon_style == '1' && num<2))){
                    num=num+1; //记录选择了多少个
                    var money=$(this).parents('li').attr('money');
                    var coupon_id=$(this).parents('li').attr('coupon_id');
                    var condition=$(this).parents('li').attr('term');
                    var b={'money':money,'id':coupon_id,'condition':condition};
                    coupon_tmp.push(b);  //添加时block数组加入此优惠券数据
                }else{
                    layer.msg('已超过该优惠券样式下所允许的最大数量', {icon: 2, time: 1000});
                    $(this).removeClass('pop-swith-on');
                }
            }else{
                num=num-1;
            }
            $("#pop-coupon").attr('data-num',num);
        }
    });
})
/*限制输入框输入的长度-s*/
$('body').on('keyup','.maxword-input-js',function () {
    $(this).next().find('.now-words').text($(this).val().length);
});
$('body').on('blur','.maxword-input-js',function () {
    var maxlength=$(this).attr('data-maxlength');
    if($(this).val().length>maxlength){
        var val=$(this).val().substring(0,maxlength);
        $(this).val(val);
        $(this).next().find('.now-words').text(maxlength);
    }
});
/*限制输入文本框的长度-s*/
$('body').on('keyup','.tpd-txtnav-textarea textarea',function () {
    $(this).next().find('.now-wordOne').text($(this).val().length);
});
$('body').on('blur','.tpd-txtnav-textarea textarea',function () {
    var maxlength=$(this).attr('data-maxlength');
    if($(this).val().length>maxlength){
        var val=$(this).val().substring(0,maxlength);
        $(this).val(val);
        $(this).next().find('.now-wordOne').text(maxlength);
    }
});
/*限制输入文本框的长度-e*/

//快捷入口编辑状态时数据还原
function li_split(){
    var li_str='';
//  console.log(block[divid].nav);
    for(var i=0;i<block[divid].nav.length;i++){
        li_str+='<li><div class="tpd-nav-ico"><img src="'+block[divid].nav[i].pic+'" alt="" /></div>';
        li_str+='<div class="tpd-nav-cont"><div class="tpd-nav-name tpd-cdiy-font28">';
        li_str+='导航名称 : <input onblur="tit_name(this);" class="maxword-input-js" data-classid="5" data-maxlength="4" type="text" value="'+block[divid].nav[i].title_name+'" /><span><i class="now-words">'+block[divid].nav[i].title_name.length+'</i>/4</span></div>';
        li_str+='<div class="tpd-cstyle-select tpd-cdiy-font28">';
        li_str+='<label>链接目标 :</label>';
        li_str+='<div class="tpd-select-wrap"><span>分类</span>';
        li_str+='<ul class="tpd-select-list"><li url-item="0">首页</li><li url-item="1">分类</li><li url-item="2">收藏</li><li url-item="3">购物车</li><li url-item="4">个人中心</li><li>创建链接</li></ul>';
        li_str+='</div><div class="tpd-addlink-target"><i></i>添加链接目标</div></div></div>';
        li_str+='<div class="tpd-cdiy-identity"><img src="__PUBLIC__/static/images/ico-diy-drag.png" alt="" /></div>'
        li_str+='<a class="tpd-del-nav" href="javascript:;"></a></li>';
    }
    $('#tpd-navs-list').html(li_str); 
    //tpd-foots-list 
    selectNav(); 
}

/*
<li>
    <div class="tpd-foot_tbxz">
        <div class="tpd-nav-ico tpd-foots-ico"><img src="/template/mobile/rainbow/static/images/home1.png" alt="" /></div>
        <div class="tpd-nav-ico tpd-foots-ico"><img src="/template/mobile/rainbow/static/images/home2.png" alt="" /></div>
    </div>
    <div class="tpd-nav-cont">
        <div class="tpd-nav-name tpd-cdiy-font28">导航名称 : <input onblur="tit_name2(this);" class="maxword-input-js" data-classid="5" data-maxlength="4" type="text" value="首页" /><span><i class="now-words">2</i>/4</span></div>
        <div class="tpd-cstyle-select tpd-cdiy-font28">
            <label>链接目标 :</label>
            <div class="tpd-addlink-target" style="display:block;"><i></i>添加链接目标</div>
        </div>
    </div>
    <div class="tpd-cdiy-identity"><img src="images/ico-diy-drag.png" alt="" /></div>
    <a class="tpd-del-nav" href="javascript:;"></a>
</li>
 */


//底部菜单编辑状态时数据还原
function footer_split(){
	
    var li_str='';
    for(var i=0;i<block[divid].nav.length;i++){
    	console.log(block[divid].nav[i].pic1);
        li_str+='<li><div class="tpd-foot_tbxz">';
        li_str+='<div class="tpd-nav-ico tpd-foots-ico"><img src="'+block[divid].nav[i].pic1+'" alt="" /></div>';
        li_str+='<div class="tpd-nav-ico tpd-foots-ico"><img src="'+block[divid].nav[i].pic2+'" alt="" /></div>';
        li_str+='</div>';
        li_str+='<div class="tpd-nav-cont">';
        li_str+='<div class="tpd-nav-name tpd-cdiy-font28">导航名称 : ';
        li_str+='<input onblur="tit_name2(this);" class="maxword-input-js" data-classid="5" data-maxlength="4" type="text" value="'+block[divid].nav[i].title_name+'" />';
        li_str+='<span><i class="now-words">2</i>/4</span></div>';
        li_str+='<div class="tpd-cstyle-select tpd-cdiy-font28">';
        li_str+='<label>链接目标 :</label>';
        if(block[divid].nav[i].url){
            li_str+='<div class="tpd-addlink-target" style="display:block;"><i></i>'+block[divid].nav[i].url+'</div>';
        }else{
            li_str+='<div class="tpd-addlink-target" style="display:block;"><i></i>添加链接目标</div>';
        }
        li_str+='</div></div>';
        li_str+='<div class="tpd-cdiy-identity"><img src="images/ico-diy-drag.png" alt="" /></div>';
        li_str+='<a class="tpd-del-nav" href="javascript:;"></a></li>';
    }
    $('#tpd-foots-list').html(li_str); 
}

function roll_split(){
    var str='';
    for(var i=0;i<block[divid].nav.length;i++){
        str+='<li><div class="tpd-wstyle-uploadimg">';
        if(block[divid].nav[i].pic){
            str+='<img style="width:210px;height:130px" src="'+block[divid].nav[i].pic+'">';
        }else{
            str+='<span></span><label>建议宽度372px，高度216px<br />如尺寸不达标，图片将自适应显示</label>';
        }
        str+='</div>';
        str+='<div class="tpd-wstyle-addlink"><span>链接设置</span>';
        if(block[divid].nav[i].url){
            str+='<div class="tpd-addlink-target"><i></i>'+block[divid].nav[i].url+'</div>';
        }else{
            str+='<div class="tpd-addlink-target"><i></i>添加链接目标</div>';
        }
        str+='<label>请先上传图片</label></div><div class="tpd-cdiy-identity"></div>';
        str+='<a class="tpd-del-nav" href="javascript:;"></a></li>';
    }
    $('#roll').html(str);
}

//商品列表弹窗 商品数据
function goods_list_edit(){
    var str='';
    for(var i=0;i<block[divid].nav.length;i++){
        str+='<li><div class="f-l"><div class="tpd-goods-name tpd-cdiy-font28">列表名称 : ';
        str+='<input onblur="tab_title(this);" class="maxword-input-js" data-classid="10" data-maxlength="8" type="text" value="'+block[divid].nav[i].tab_title+'" />';
        str+='<span><i class="now-words">4</i>/8</span></div>';
        str+='<dl class="tpd-cstyle-dl clearfix"><dt>选取商品 : </dt>';
        str+='<dd><label><input class="screen" type="radio" name="goods-style"'+i+' />按条件选取</label></dd>';
        str+='<dd><label><input type="radio" class="hand1" name="goods-style"'+i+' />手动添加</label></dd>';
        str+='</dl></div>';
        str+='<div class="tpd-cdiy-identity"><img src="__PUBLIC__/static/images/ico-diy-drag.png" alt="" /></div>';
        str+='<a class="tpd-del-nav" href="javascript:;"></a></li>';
    }
    $('#tab_list').html(str);
}

//获取拼团商品数据
function get_activity_0(){
    $.post('/index.php/Mobile/Team/AjaxTeamList',{'p':1},function(data){
        if (data.status == 1) {
            var html = '';
            var goods_price,link_url;
            if (data.result.length > 0) {
                for (var i = 0; i < data.result.length; i++) {
                    link_url = "/index.php?m=Mobile&c=Team&a=info&team_id=" + data.result[i].team_id + "&goods_id="+data.result[i].goods_id;
                    html += '<li><a class="tpdm-goods-pic" href="javascript:;"><img src="'+data.result[i].share_img+'" alt="" /></a>';
                    html += '<a href="javascript:;" class="tpdm-goods-name">'+data.result[i].goods_name+'</a>';
                    html += '<div class="tpdm-goods-des">';
                    html += '<div class="tpdm-goods-price">￥'+data.result[i].team_price+'</div>';
                    html += '<div class="tpdm-sold-num">单买:￥'+data.result[i].goods.shop_price+'</div>';
                    html += '</div>';
                    html += '<div class="tpdm-goods-mes">';
                    html += '<a href="'+link_url+'">去拼单 ></a><span>'+data.result[i].needer+'人团</span>';
                    html += '<div class="tpdm-acbuyer"></div>';
                    html += '</div></li>';
                }  
                $('.nxkhh').html(html);
            } else {  
                html='<div id="notmore"  style="font-size:.32rem;text-align: center;color:#888;padding:.25rem .24rem .4rem; clear:both;"> <a style="font-size:.50rem;">没有更多喽</a> </div>';
                $('.tpd-edits-hidden.tpd-editing .tpdm-mes-logo').find('ul').html(html);
            }
        }else{

        }
    },'JSON')
}

function set_now_words_length(obj){
    var v = $(obj).val();
    $(obj).parent().parent().find('.now-words').html(v.length);
}

/*************************************************弹窗部分***********************************************************/
$(function(){
    $("body").on('click','.tpd-addlink-target',function(){
        link=$(this);
        $('#pop-link').slideToggle('slow');
        $(".lists-pop-bg").show();
    })
    $("body").on('click','.screen',function(){
        tab_item=$(this).parents('li').index();
        $('#pop-goods').slideToggle('slow');
        $(".lists-pop-bg").show();
        if(typeof (block[divid].sql_where) && block[divid].sql_where){
            console.log('sql_where 1',block[divid].sql_where)
            set_pop_goods(block[divid].sql_where)

        }else{
            console.log('sql_where 2', block[divid].nav[tab_item].sql_where)
            set_pop_goods(block[divid].nav[tab_item].sql_where)

        }

    })
    $("body").on('click','.hand1',function(){ 
        tab_item=$(this).parents('li').index();
        $('#pop-link2').slideToggle('slow');
        $(".lists-pop-bg").show();
        // goods   "40,41,"
    })
    $("body").on('click','.hand2',function(){ 
        if(choice_type==10){
            tab_item=$(this).parents('li').index();
        }
        if(choice_type==15){
            news_tag=$(this);
        }
        $('#pop-link3').slideToggle('slow');
        $(".lists-pop-bg").show();
    })
    $("body").on('click','#add_coupon',function(){ 
        $('#pop-coupon').slideToggle('slow');
        $('#pop-link').hide();
        $(".lists-pop-bg").show();
    })
})







