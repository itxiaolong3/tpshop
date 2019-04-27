/*
* 共用方法 layui
* */
layui.use('laytpl', function(){
    var laytpl = layui.laytpl;
});
function append_tpl(from,to,msg_data,call_back){
    layui.use('laytpl', function(){
        var laytpl = layui.laytpl;
        var html = $(from).html();
        var string =  laytpl(html).render(msg_data);
        $(to).append(string);
        if (typeof (call_back) == 'function'){
            call_back()
        }
    });
}
function get_from_tpl(from, data){
    var html = $(from).html();
    return layui.laytpl(html).render(data);
}
function html_tpl(from,to,msg_data,call_back){
    layui.use('laytpl', function(){
        var laytpl = layui.laytpl;
        var html = $(from).html();
        var string =  laytpl(html).render(msg_data);
        $(to).html(string);
        if (typeof (call_back) == 'function'){
            call_back()
        }
    });
}
function set_val(val,def){
    if(typeof (val) == 'undefined') return def;
    if(val) return val;
    return def;
}
/**
 * 通过js将时间戳转换成"yyyy--mm--dd"格式
 * @param obj
 * @returns {string}
 */
function formatDate(obj){
    var date =  new Date(obj*1000);
    var y = 1900+date.getYear();
    var m = "0"+(date.getMonth()+1);
    var d = "0"+date.getDate();
    return y+"-"+m.substring(m.length-2,m.length)+"-"+d.substring(d.length-2,d.length);
}
/**
 * 通过js将时间戳转换成"yyyy--mm--dd H:i:s"格式
 * https://blog.csdn.net/shan1774965666/article/details/55049819
 * @param inputTime
 * @returns {string}
 */
function formatDateTime(inputTime) {
    var date = new Date(inputTime*1000);
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    m = m < 10 ? ('0' + m) : m;
    var d = date.getDate();
    d = d < 10 ? ('0' + d) : d;
    var h = date.getHours();
    h = h < 10 ? ('0' + h) : h;
    var minute = date.getMinutes();
    var second = date.getSeconds();
    minute = minute < 10 ? ('0' + minute) : minute;
    second = second < 10 ? ('0' + second) : second;
    return y + '-' + m + '-' + d + ' ' + h + ':' + minute + ':' + second;
}
