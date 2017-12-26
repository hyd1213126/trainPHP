var DefaultItems = new Array();         //默认样式数组
var NewStyleItems = new Array();        //合并后样式
var topHeight = 0;
var iframeWidth = "";//层的总宽度
var ifrmaeHeight = "";//层的总高度
var width = "800";
var height = "600";

function LayerShow(Url, StyleItems) {
    //默认宽度和高度
    //默认样式       
    DefaultItems = {
        Width: "800",                                                        //弹出层页面宽度
        Height: "600",                                                      //弹出层页面高度
        imgurl: "/res/iframeLayer/img/loading_street.gif",                     //Loading图片
        Layer_BackGround: "",                                                // 遮罩层DIV ID与样式
        Layer_content: "",                                                   //  弹出层容器DIV ID与样式
        ShowCloseBtn: true,                                                  //  是否显示关闭按钮 
        CloseLayerBack: function () { },                                     // 关闭弹出层回调函数
        CloseBtn: "/res/iframeLayer/img/close.png",                             //自定义关闭按钮图片
        LoadingAfterBack: function () { },                                   //加载完成后回调函数
        AutoHeight: false                                                  //高度自动，宽度自定义，为ture会以内容高度为准,否则以自定义高度为准,   
        //AutoObjContent: ""                                                    //配合自动高度一同使用，会增大或者缩小的内容的Class或ID如".obj"
    }
    //合并数据
    NewStyleItems = $.extend(true, DefaultItems, StyleItems);

    //判断是否显示关闭按钮
    topHeight = 0;
    if (NewStyleItems.ShowCloseBtn) {
        $(".CloseLayerBack").attr("src", NewStyleItems.CloseBtn);
        $(".Layer_top").show();
        topHeight = parseInt($(".Layer_top").css("height").replace("px", ""));

    }
    else
        $(".Layer_top").hide();
    //计算百分号
    if (NewStyleItems.Width.indexOf("%") > -1) {
        width = iframeWidth = $(window).width() * (NewStyleItems.Width.replace("%", "") * 0.01);

    }
    else {
        height = iframeWidth = NewStyleItems.Width;
    }
    if (NewStyleItems.Height.indexOf("%") > -1) {
        ifrmaeHeight = $(window).height() * (NewStyleItems.Height.replace("%", "") * 0.01);
    }
    else {
        ifrmaeHeight = NewStyleItems.Height;
    }
    width = parseInt(width) + parseInt($(".Layer_content").css("padding-left")) * 2;

    //获取宽高用在别的方法计算

    //回调函数是否为 NULL

    if (NewStyleItems.CloseLayerBack == null)
        NewStyleItems.CloseLayerBack = function () { };
    if (NewStyleItems.LoadingAfterBack == null)
        NewStyleItems.LoadingAfterBack = function () { };

    //如果遮罩层ID不等于空就赋值
    if (NewStyleItems.Layer_BackGround != "")
        $(".Layer_BackGround").attr("id", NewStyleItems.Layer_BackGround);

    //如果图片DIV ID不等于空就赋值
    if (NewStyleItems.Layer_content != "") {
        $(".Layer_content").attr("id", NewStyleItems.Layer_content);

    }
    //Loading图片
    var obj = $(".Layer_pic");
    //显示弹出层
    imgLoad(NewStyleItems.imgurl, obj, Layer_iframeShow, true);
    // $(".Layer_pic").attr("src", NewStyleItems.imgurl);   
    Layer_iframeShow();



    //网页加载完成后隐藏图片显示页面
    $(".iframe_iframe").load(function () {
        //alert("dd");
        $(this).css("width", "100%");
        Layer_changePicWindow();
        //执行加载完成回调函数
        NewStyleItems.LoadingAfterBack();
    });
    $(".iframe_iframe").attr("src", Url);

    //关闭按钮图片回调函数        因为有时候需要点击图片关闭弹出层。所以他的回调函数直接在关闭方法里
    $(".CloseLayerBack").unbind("click");
    $(".CloseLayerBack").bind("click", NewStyleItems.CloseLayerBack);
    $(".CloseLayerBack").click(function () {
        $(".Layer_BackGround").fadeOut("100");
        //如不先none，关闭弹出层的时候会变大再缩小；
        $(".Layer_content").css("display", "none");
        $(".iframe_iframe").removeAttr("src");
    })

    //检测窗口发生变化
    $(window).resize(function () {
        Layer_changePicWindow();
    })
}

//浏览器窗口尺寸方法
function Layer_changePicWindow() {
    if (NewStyleItems.Width.indexOf("%") > -1) {
        iframeWidth = $(window).width() * (NewStyleItems.Width.replace("%", "") * 0.01);
    }
    else {
        iframeWidth = NewStyleItems.Width;
    }
    if (NewStyleItems.Height.indexOf("%") > -1) {
        ifrmaeHeight = $(window).height() * (NewStyleItems.Height.replace("%", "") * 0.01);
    }
    else {
        ifrmaeHeight = NewStyleItems.Height;
    }
    //自动高度

    //设置iframe宽高
    if (ifrmaeHeight == 65) {
        $(".iframe_iframe").css("height", ifrmaeHeight - topHeight).css("padding", "0");
        $(".Layer_content").addClass("pad");
    } else {
        $(".iframe_iframe").css("height", ifrmaeHeight - topHeight);
        $(".Layer_content").removeClass("pad");
    }
    if (NewStyleItems.AutoHeight) {
        var AutoObjContent = parseInt($(".iframe_iframe").contents().find("body").height())
        //68是什么含义？
        $(".iframe_iframe").css("height", AutoObjContent + 15);
        ifrmaeHeight = parseInt(AutoObjContent);
    }
    //设置Layer_content宽高
    var Contentwidth = $(".Layer_content").css("width", iframeWidth);
    var Contentheight = $(".Layer_content").css("height", ifrmaeHeight);
    if (NewStyleItems.AutoHeight) {
        Contentwidth = $(".Layer_content").css("width", NewStyleItems.Width).width();   //获取弹出层页面宽高
        Contentheight = $(".Layer_content").css("height", "auto").height();
    }
    else {
        Contentwidth = $(".Layer_content").css("width", NewStyleItems.Width).width();   //获取弹出层页面宽高
        Contentheight = $(".Layer_content").css("height", NewStyleItems.Height).height() + parseInt($(".Layer_content").css("padding-bottom").replace("px", ""));

    }


    //判断是否显示关闭按钮
    var left = ($(window).width() - (Contentwidth + parseInt($(".Layer_content").css("padding-left")) * 2)) / 2; //查看图片居中位置参数     窗口高度减去图片高度/2等于居中
    var top = ($(window).height() - Contentheight) / 2;
    if (top <= 0) {
        top = $(window).scrollTop()
    }
    else {
    }

    //让图片容器居中
    $(".Layer_content").css({ "top": top, "left": left });

    if ($(window).width() < (Contentwidth + 10))
        $(".Layer_content").css({ "position": "absolute", "left": "0px" });
    if ($(window).height() < (Contentheight + 10)) {
        $(".Layer_content").css({ "top": top, "position": "absolute" });
    } else {
        $(".Layer_content").css({ "top": top, "position": "fixed" });
    }
}

//弹出层显示
function Layer_iframeShow() {
    $(".Layer_BackGround").fadeIn("100");
    $(".Layer_content").fadeIn("100");
    //laoding图片居中
    //$(".Layer_pic").css({ "position": "absolute", "top": (parseInt(ifrmaeHeight) - $(".Layer_pic").height()) / 2, "left": (parseInt(iframeWidth) - $(".Layer_pic").width()) / 2 });
    $(".Layer_pic").css({ "position": "absolute", "top": (parseInt(height) - $(".Layer_pic").height()) / 2, "left": (parseInt(width) - $(".Layer_pic").width()) / 2 });
    Layer_changePicWindow();
}
//弹出层关闭
function Layer_iframePicHide() {
    $(".CloseLayerBack").click();
}

/*
* 【关闭提示框】
* 参数：num <数字1：当前页面刷新或者跳转；数字2：当前页面的 iframe 刷新或者跳转；....后续等待扩展>
* 参数：url <刷新的页面路径，或者跳转到目标路径>
* 参数：id <当 num 值为 2 时，iframe 的 id 名>
*/
function closeIframeImgDiv(num, url, id) {
    num = parseInt(num);
    switch (num) {
        case 1:
            window.location.href = url;
            break;
        case 2:
            $("#" + id).attr("src", url);
            break;
    }
    Layer_iframePicHide();
}