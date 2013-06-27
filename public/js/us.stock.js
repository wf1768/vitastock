//系统js库。

//制作的通用alert
openalert = function(text) {
    var html = $(
        '<div id="dialog-message" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
            '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                '<h3 id="myModalLabel">系统提示</h3>' +
            '</div>' +
            '<div class="modal-body">' +
                '<p id="continfo">' + text + '</p>' +
            '</div>' +
            '<div class="modal-footer">' +
                '<button class="btn" data-dismiss="modal" id="closeidnow" aria-hidden="true">关闭</button>' +
            '</div>' +
        '</div>');
    return html.modal()
}

openloading = function(text) {
    if ( $("#dialog-loading").length > 0 ) {
        $("#loadingtext").html(text);
        return $("#dialog-loading").modal({
//            backdrop:false,
//            keyboard:false,
//            show:true
        });
    }
    var html = $(
        '<div class="modal hide fade" id="dialog-loading" >' +
            '  <div class="modal-body">' +
            '   <img alt="" src="../img/loading.gif" height="20" width="20" > <span id="loadingtext">' + text + '</span>'+
            '  </div>' +
            '</div>');
    return $(html).modal({
//        backdrop:false,
//        keyboard:false,
//        show:true
    });
}

closeloading = function() {
    return $('#dialog-loading').modal('hide');
}


openloading2 = function() {
    var html = $(
        '<div id="dialog-loading" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +

            '<div class="modal-body">' +
            '<img src="../img/loading.gif"><p id="continfo">正在操作，请等待......</p>' +
            '</div>');
    return html.modal()
}

/**
 * input 只能输入数字
 * @param e
 */
function isnumber(e) {
    var k = window.event ? e.keyCode : e.which;
    if (((k >= 48) && (k <= 57)) || k == 8 || k == 0 ) {
    } else {
        if (window.event) {
            window.event.returnValue = false;
        }
        else {
            e.preventDefault(); //for firefox
        }
    }
}

/**
 * input 只能输入数字+小数点
 * @param e
 */
function isfloat(e) {
    var k = window.event ? e.keyCode : e.which;
    if (((k >= 48) && (k <= 57)) || k == 8 || k == 0 || k == 46) {
    } else {
        if (window.event) {
            window.event.returnValue = false;
        }
        else {
            e.preventDefault(); //for firefox
        }
    }
}