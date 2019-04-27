
<div class="ma-list">
    <empty name="list">
        <div class="no-data">
            <i class="fa fa-exclamation-circle"></i>没有相关素材~
        </div>
    </empty>
    <volist name="list" id="item">
        <div class="ma-card" style="cursor: pointer;" data-mid="{$item.id}">
            <if condition="count($item.wx_news) === 1">
                <!--单图文-->
                <div class="title ellipsis-1">{$item.wx_news.0.title}</div>
                <div class="time">{$item.update_time}</div>
                <div class="card-item no-line">
                    <div class="cover">
                        <img src="{$item.wx_news.0.thumb_url}"/>
                    </div>
                </div>
                <div class="desc ellipsis-2">{$item.wx_news.0.digest?:$item.wx_news.0.content_digest}</div>
                <else/>
                <!--多图文-->
                <div class="time">{$item.update_time}</div>
                <volist name="item.wx_news" id="news" key="i">
                    <div class="card-item">
                        <if condition="$i==1">
                            <div class="cover cover-sm">
                                <img src="{$news.thumb_url}"/>
                                <div class="title-in ellipsis-1">{$news.title}</div>
                            </div>
                            <else/>
                            <div class="post">
                                <div class="post-title ellipsis-2">{$news.title}</div>
                                <div class="post-cover">
                                    <img src="{$news.thumb_url}"/>
                                </div>
                            </div>
                        </if>
                    </div>
                </volist>
            </if>
            <div class="ma-card-mask hidden" data-mid="{$item.id}">
                <div class="fa fa-check-circle ma-card-check"></div>
            </div>
        </div>
    </volist>
</div>
{$page->show()}

<script>
    $('.ma-card').on('click', function () {
        $('.ma-card-mask').addClass('hidden');
        $(this).children('.ma-card-mask').removeClass('hidden');
    });
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajaxNews(page);
    });
//    $(document).ready(function(){
//        $('#count').empty().html("{$page->totalRows}");
//    });
</script>


