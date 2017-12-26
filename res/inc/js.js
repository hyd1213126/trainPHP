
$(function() {
    //==页面中mytable行变色==
    $("#mytable").find("tr").each(function(index, element) {
        if (index % 2 == 1)
            $(this).find("td").each(function() {
                $(this).css("backgroundColor", "#f9f9f9").css("color", "#666");
            })
        else
            $(this).find("td").each(function() {
                $(this).css("backgroundColor", "#fff").css("color", "#666");
            })
    });

    //==搜索框展开收起==
    $("#close").click(function() {
        $("#closetable").toggle();
        if ($("#closeimg").attr("src") == "/res/admin/images/hou/trclosedown.png") {
            $("#closeimg").attr("src", "/res/admin/images/hou/trcloseup.png");
        } else {
            $("#closeimg").attr("src", "/res/admin/images/hou/trclosedown.png");
        }
    });

    //==列表页左侧input全选/取消全选
    $("input#checkall").click(function() {
        var checked = $("input.checked");
        var isChecked = $(this).get(0).checked;
        var i = 0;
        var len = checked.length;
        var obj_temp;
        for (; i < len; i++) {
            obj_temp = checked[i];
            obj_temp.checked = isChecked ? 1 : 0;
        }
    })
});

// ==删除==
function del(form) {
    var obj = $("input.checked:checked");
    if (obj.length == 0) {
        // var _obj = $(".weui_dialog_alert");
        // _obj.find(".weui_dialog_title").text("请选择要删除的记录");
        // _obj.find(".weui_btn_dialog.primary").unbind("click").click(function() {
        //     _obj.fadeOut(200);
        // });
        // _obj.fadeIn(500);
        if($(".weui_dialog_alert").length==0){
            $("body.bcolor1").append('<div class="weui_dialog_alert"><div class="weui_mask"></div><div class="weui_dialog"><div class="weui_dialog_hd"><strong class="weui_dialog_title">请选择要删除的记录</strong></div><div class="weui_dialog_bd"></div><div class="weui_dialog_ft"><a href="#" class="weui_btn_dialog primary">确定</a></div></div></div>');
        }
        $(".weui_dialog_alert").fadeIn(500);
        $(".weui_btn_dialog.primary").unbind("click").click(function() {
            $(".weui_dialog_alert").fadeOut(200);
        });
        }
        else {
            // var _obj = $(".weui_dialog_confirm");
            // _obj.find(".weui_dialog_title").text("删除确认");
            // _obj.find(".weui_dialog_bd").text("此操作不可撤销，是否继续？");
            // _obj.find(".weui_btn_dialog.default").unbind("click").click(function() {
            //     _obj.fadeOut(200);
            // });
            // _obj.find(".weui_btn_dialog.primary").unbind("click").click(function() {
            //     form.submit();
            // });
            // _obj.fadeIn(500);
             if($(".weui_dialog_confirm").length==0){
                $("body.bcolor1").append('<div class="weui_dialog_confirm"><div class="weui_mask"></div><div class="weui_dialog"><div class="weui_dialog_hd"><strong class="weui_dialog_title">删除确认</strong></div><div class="weui_dialog_bd">此操作不可撤销，是否继续？</div><div class="weui_dialog_ft"><a href="#" class="weui_btn_dialog default">取消</a><a href="#" class="weui_btn_dialog primary">确定</a></div></div></div>');
            }
            $(".weui_btn_dialog.default").unbind("click").click(function() {
                $(".weui_dialog_confirm").fadeOut(200);
            });
            $(".weui_btn_dialog.primary").unbind("click").click(function() {
                form.submit();
            });
            $(".weui_dialog_confirm").fadeIn(500);
        }
    }

    //==新后台自动调整Iframe高度==
    function changeIframe() {
        var iframe = parent.document.getElementById("info");
        var h = $("#info_panel").height();
        var wh = $(parent).height() - 205;
        if (h < wh)
            h = wh;
        $(iframe).height(h);
        //iframe.height=h;
    }

    //==询问是否删除==
    function askdel() {
        asked = confirm("确定要删除吗？");
        if (asked == 0)
            return true;
        else
            return false;
    }

    //==全选/清空==
    function selectall(cobj, flag) {
        flag1 = 0
        if (flag == 0) {
            for (i = 0; i < cobj.length; i++) {
                flag1 = 1
                cobj[i].checked = true
            }
            if (flag1 == 0)
                cobj.checked = true
        } else {
            for (i = 0; i < cobj.length; i++) {
                flag1 = 1
                cobj[i].checked = false
            }
            if (flag1 == 0)
                cobj.checked = false
        }
    }

    //弹出层为大窗口时，弹出层的顶部和底部高度的样式
    $(function() {
        if ($("#small_window").val() == "false") {
            var id = window.parent.getIframeId(document.body);
            if (id == "iframe_iframe") {
                $("#info_panel_table").css("width", "100%");
                $("#info_panel").css("padding", "0px");
                $("#stay_bottom").css("height", "auto");
            }
        }
    });

    //在本页面弹框（区别于在父级框架页弹框）
    function ShowLayer(url, width, height, CloseLayerBack, LoadingAfterBack) {
        if (width == null || width == "undefined") {
            if ($("#small_window").val() == "false")
                width = $(window).width() - 54;
            else
                width = "80%";
        }
        if (height == null || height == "undefined") {
            if ($("#small_window").val() == "false")
                height = $(window).height() - 37;
            else
                height = "80%";
        }

        var Layer_content = "";
        if ($("#small_window").val() == "false")
            Layer_content = "tanchu_top";
        var StyleItems = {
            Width: width.toString(), //弹出层页面宽度
            Height: height.toString(), //弹出层页面高度
            imgurl: "/res/iframeLayer/img/loading_street.gif", //Loading图片
            Layer_content: Layer_content, //  弹出层容器DIV ID与样式
            ShowCloseBtn: true, //  是否显示关闭按钮
            CloseLayerBack: function() {
                CloseLayerBack
            }, // 关闭弹出层回调函数
            CloseBtn: "/res/iframeLayer/img/close.png", //自定义关闭按钮图片
            LoadingAfterBack: function() {
                LoadingAfterBack
            }, //加载完成后回调函数
            AutoHeight: false, //高度自动，宽度自定义，为ture会以内容高度为准,否则以自定义高度为准,
            AutoObjContent: "" //配合自动高度一同使用，会增大或者缩小的内容的Class或ID如".obj"
        }
        LayerShow(url, StyleItems)
    }
