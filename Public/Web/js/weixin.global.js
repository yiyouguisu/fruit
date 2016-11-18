var wxglobal = wxglobal || {};
try {
    wxglobal.TurnUrl = function (myUrl) {
        window.location.href = myUrl;
    }
    //var openBoxIndex;
    //wxglobal.OpenBox = function (title, myUrl, open_W, open_H) {
    //    openBoxIndex = window.top.layer.open({ type: 1, content: '<iframe src="' + myUrl + '" frameborder="0" scrolling="auto" style="width:' + open_W + 'px; height:' + open_H + 'px;"></iframe>', style: 'background-color:#ffffff; color:#000000; border:none;' });
    //};
    //wxglobal.GuanBox = function () {
    //    window.top.layer.close(openBoxIndex);
    //};

    var openBoxIndex;
    wxglobal.OpenBox = function (myUrl, open_W, open_H) {
        wxglobal.Loading('加载中...');
        open_W = typeof (open_W) == 'undefined' ? $(window).width() : open_W;
        open_H = typeof (open_H) == 'undefined' ? $(window).height() : open_H;
        var openBoxIframe = '<div id="windowBox" class="windowBox">';
        openBoxIframe += '      <div class="backDiv"></div>';
        openBoxIframe += '      <div class="ifrmDiv" style="height:' + open_H + 'px;">' + '<iframe src="' + myUrl + '" frameborder="0" scrolling="auto"></iframe>' + '</div>';
        openBoxIframe += '   </div>';
        $(document.body).append(openBoxIframe);
        $("#windowBox .ifrmDiv").css("top", $(window).height() + "px");
        $("#windowBox").find("iframe:eq(0)").bind("load", function () {
            window.setTimeout(function () {
                wxglobal.GbiLoad();
                $("#windowBox").show();
                $("#windowBox .ifrmDiv").animate({ top: ($(window).height() - open_H) + "px" }, 500, function () { });
                $("#windowBox .backDiv").bind("click", function () {
                    $("#windowBox").remove();
                });
            }, 200);
        });
    };
    wxglobal.GuanBox = function () {
        $("#windowBox .ifrmDiv").animate({ top: $(window).height() + "px" }, 500, function () { $("#windowBox").remove(); });
    };

    wxglobal.ShowMsg = function (mInfo, mType) {
        switch (mType) {
            case "right": mType = 6; break;
            case "error": mType = 5; break;
            case "abort": mType = 4; break;
            default:
                mType = 5; 
                break;
        }
        window.top.layer.alert(mInfo, { icon: 6 });
    };
    wxglobal.Confirm = function (mInfo, yCall, nCall, yTitle, nTitle) {
        yTitle = typeof (yTitle) == 'undefined' ? '确认' : yTitle;
        nTitle = typeof (nTitle) == 'undefined' ? '取消' : nTitle;
        window.top.layer.open({
            title: false, content: mInfo, btn: [yTitle, nTitle], shadeClose: false,
            yes: function (index) {
                yCall();
                window.top.layer.close(index);
            },
            no: function (index) {
                if (typeof (nCall) != 'undefined' && nCall != null) { nCall(); }
                window.top.layer.close(index);
            }
        });
    };
    var loadingIndex = 0;
    wxglobal.Loading = function (mtips) {
        window.top.loadingIndex = window.top.layer.msg(mtips, { icon: 16, shade: [0.3, '#000000'], shadeClose: false, time: 0 });
    }
    wxglobal.GbiLoad = function () {
        window.top.layer.close(window.top.loadingIndex);
    }
    wxglobal.AjaxUrl = function (myUrl, mData, dCall) {
        wxglobal.Loading('正在提交请求中，请稍后...');
        $.ajax({
            type: 'post', url: myUrl, data: mData, dataType: 'json', success: function (data) {
                window.setTimeout(function () {
                    wxglobal.GbiLoad();
                    dCall(data);
                }, 600);
            }
        });
    };
    wxglobal.SetDate = function (htmId) {
        laydate({ elem: '#' + htmId, format: 'YYYY-MM-DD', event: 'focus' });
    }
    wxglobal.SetSize = function (dcall) {
        $(window).resize(function () { dcall(); });
        $(function () {
            dcall();
        });
    }
    wxglobal.Dselect = function (mjson) {
        var myDiv = '';
        myDiv += '<div id="selectBox" class="selectBox">';
        myDiv += '   <div><span class="bt">' + mjson.Title + '</span><span class="gb">关闭</span></div>';
        myDiv += '   <ul>';
        myDiv += '      <li class="' + (mjson.Value == '' ? 'hover' : 'nomal') + '" value="">请选择</li>';
        $(mjson.Items).each(function (index, model) {
            myDiv += '      <li class="' + (model.Value == '' ? 'hover' : 'nomal') + '" value="' + model.Value + '">' + model.Title + '</li>';
        });
        myDiv += '   </ul>';
        myDiv += '</div>';
        $("#selectBox").remove(); $(myDiv).appendTo(document.body);
        $("#selectBox").animate({ bottom: "0px" }, 500);
        $("#selectBox").find("span[class='gb']").bind("click", function () {
            $("#selectBox").remove();
        });
    }
    Request = {
        QueryString: function (item) {
            var svalue = location.search.match(new RegExp("[\?\&]" + item + "=([^\&]*)(\&?)", "i"));
            return svalue ? svalue[1] : svalue;
        }
    }
}
catch (e) { }