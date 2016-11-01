function ajaxPost(url,postData,callBack){
    var ajax = $.ajax({
        type: "POST",
        url: url,
        data:postData,
        timeout:15000,
        success: callBack,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error' || errorThrown == 'Internal Server Error'){
                window.alertError('服务器异常');
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                ajax.abort();
                window.alertError('请求超时，请稍后重试');
            }
        }
    });

}
/**
 * 带loading提示
 */
function ajaxPost_loading(url,postData,callBack){
    ityzl_SHOW_LOAD_LAYER();
    var ajax = $.ajax({
        type: "POST",
        url: url,
        data:postData,
        timeout:15000,
        success: callBack,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error' || errorThrown == 'Internal Server Error'){
                window.alertError('服务器异常');
                ityzl_SHOW_ERROR_LAYER();
            }
        },
        complete : function(XMLHttpRequest,status){
            if(status=='timeout'){
                ajax.abort();
                ityzl_SHOW_ERROR_LAYER();
                window.alertError('请求超时，请稍后重试');
            }
        }
    });

}


function ityzl_SHOW_LOAD_LAYER(){
    return layer.msg('处理中...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '0px', time:100000}) ;
}
function ityzl_CLOSE_LOAD_LAYER(index){
    layer.close(index);
}
function ityzl_SHOW_TIP_LAYER(){
    layer.msg('恭喜您，加载完成！',{time: 1000,offset: '10px'});
}
function ityzl_SHOW_ERROR_LAYER(){
    layer.msg('请求失败！',{time: 1000,offset: '10px'});
}

/*弹出层*/
/*
 参数解释：
 title	标题
 url		请求的url
 id		需要操作的数据id
 w		弹出层宽度（缺省调默认值）
 h		弹出层高度（缺省调默认值）
 */
function layer_show(title,url,w,h){
    if (title == null || title == '') {
        title=false;
    };
    if (url == null || url == '') {
        url="404.html";
    };
    if (w == null || w == '') {
        w=800;
    };
    if (h == null || h == '') {
        h=($(window).height() - 50);
    };
    layer.open({
        type: 2,
        area: [w+'px', h +'px'],
        fix: false, //不固定
        maxmin: true,
        shade:0.4,
        title: title,
        content: url
    });
}
/*关闭弹出框口*/
function layer_close(){
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}

function layerShow(title,url,w,h){
    layer_show(title,url,w,h);
}



(function(){

    window.alertSuccess = {};
    window.alertError = {};
    window.appendStr = {};
    function success(str){
        $('.data-alert').animate({top:"0px",display:'block'},1);
        $('.date-success-alert').html(str).show().animate({top:"50px"},500);
        var timer = setTimeout(function(){
            $('.date-success-alert').animate({top:"-100px",display:'none'},800);
            $('.data-alert').animate({top:"-100px",display:'none'},1000);
            clearTimeout(timer);
        },3000);

    }

    function error(str){
        $('.data-alert').animate({top:"0px",display:'block'},1);
        $('.date-error-alert').html(str).show().animate({top:"50px"},500);
        var timer = setTimeout(function(){
            $('.date-error-alert').animate({top:"-100px",display:'none'},1000);
            $('.data-alert').animate({top:"-100px",display:'none'},1000);
            clearTimeout(timer);
        },3000);
    }

    function appendStr(){
        str = '<div class="" style="position: fixed;top:0px;left:0px;padding:0px;width:100%;height:0px;z-index: 999999;">' +
            '<div class="data-alert" style="margin: 0 auto;width: 50%;min-width: 200px;padding: 0px ;position: relative;">' +
            '<div class="date-success-alert" style="padding: 0px 50px;display:none;box-shadow: 5px 5px 5px rgb(221, 221, 221);font-size: 16px;background: #fff;text-align: center;height: 38px;line-height: 38px;position: relative;top: -100px;border-radius: 5px; color: #fff;background: rgba(0,192,254,0.8);">操作成功' +
            '</div>' +
            '<div class="date-error-alert" style="padding: 0px 50px;display:none;background:box-shadow: 5px 5px 5px rgb(221, 221, 221); font-size: 16px;#fff;text-align: center;height: 38px;line-height: 38px;position: relative;top: -150px;border-radius: 5px; color: yellow;background: rgba(255,0,0,0.8);">操作失败</div>' +
            '</div> </div>';
        $('body').append(str);
    }

    window.alertSuccess = success;
    window.alertError = error;
    window.appendStr = appendStr;

    window.appendStr();

})();