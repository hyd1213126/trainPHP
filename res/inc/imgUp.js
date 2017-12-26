
var pics_view = false; //是否有图片库浏览功能
//页面加载，图片处理
$(function() {
    var imgFileObjs = $(".imgFileDiv");
    for (var i = 0; i < imgFileObjs.length; i++) {
        var imgSrc = $("#imageFile" + i).val();
        var imgfilesrc = imgSrc;
        // imgSrc = imgSrc.substring(imgSrc.lastIndexOf("/") + 1);
        if (imgSrc != "")
            YesImgStyle(i, imgfilesrc);
        else
            NoImgStyle(i);
    }
});
//询问是否删除
function delteImgClick(delteImgObj) {
    var imgI = $(delteImgObj).attr("id").substring($(delteImgObj).attr("id").length - 1);
    // alerts(4, "请确认删除图片", "<input type=\"button\" value=\"确认\"  class=\"Popup_link\" onclick=\"parent.delteImg('" + imgI + "');return false;\"/>&nbsp;&nbsp;<input type=\"button\" value=\"取消\"  class=\"Popup_link\" onclick=\"return false;\"/>", "", 0, 1);
    var _obj = $(".weui_dialog_confirm");
    _obj.find(".weui_dialog_title").text("请确认删除图片");
    _obj.find(".weui_dialog_bd").text("此操作不可撤销，是否继续？");
    _obj.find(".weui_btn_dialog.default").unbind("click").click(function() {
        _obj.fadeOut(200);
    });
    _obj.find(".weui_btn_dialog.primary").unbind("click").click(function() {
        delteImg(imgI);
    });
    _obj.fadeIn(500);
}
//删除图片方法
function delteImg(imgI) {
    var imgfilesrc = $("#imgFile" + imgI).attr("src");
    var Name = imgfilesrc.substring(imgfilesrc.lastIndexOf("/") + 1);
    imgfilesrc = imgfilesrc.substring(0, imgfilesrc.lastIndexOf("/"));
    isAT = imgfilesrc.substring(imgfilesrc.lastIndexOf("/") + 1);
    if (isAT == "temp") {
        $.get("/imgUp/Del", {
            isAT: isAT,
            Name: Name,
            Random: new Date().getTime()
        }, function() {
            NoImgStyle(imgI);
        });
    } else {
        NoImgStyle(imgI);
    }
}
//没有图片时或删除图片后的方法
function NoImgStyle(imgI) {
    var htmls = "<img id=\"imgFile" + imgI + "\" src=\"/res/admin/images/hou/nopic.png\" onclick=\"delteAddImg(this);\" />";
    $("#imgFileDiv" + imgI).html(htmls)
    $("#imgFileTd" + imgI).css("border", 0);
    $("#imgFileClose" + imgI).css("display", "none");
    $("#imageFile" + imgI).val("");
    $(".weui_dialog_confirm").css("display", "none");

}
//有图片时或者添加图片后的方法
function YesImgStyle(imgI, src) {
    var htmls = "<a href=\"" + src + "\" target=\"_blank\"><img id=\"imgFile" + imgI + "\" src=\"" + src + "\"/></a>";
    $("#imgFileDiv" + imgI).html(htmls);
    $("#imgFileTd" + imgI).css("border", "1px solid #888888");
    $("#imgFileClose" + imgI).css("display", "block");
    $("#imageFile" + imgI).val(src);

}
//弹出层浏览图片上传
function delteAddImg(imgFileObj) {
    var DefaultItems = new Array(); //默认样式数组
    if (pics_view) {
        width = "85%";
        height = "85%"
    } else {
        width = "455";
        height = "285"
    }
    DefaultItems = {
        Width: width, //弹出层页面宽度
        Height: height, //弹出层页面高度
        //imgurl: "../iframeLayer/img/loading_street.gif",                  //Loading图片
        Layer_BackGround: "bgPanelDivId", // 遮罩层DIV ID与样式
        Layer_content: "countDivId", //  弹出层容器DIV ID与样式
        ShowCloseBtn: false //  是否显示关闭按钮 True为显示
            //CloseLayerBack: 如:方法 aa ,                     // 关闭弹出层回调函数 （括号都不用写）
            //LoadingAfterBack: 同上                              //加载完成后回调函数
    }

    //var ifrmImgBg = new Array();
    //ifrmImgBg = {
    //    bgOpacity: "30",
    //    isFixedSize: true,
    //    isCloseBtn: false,
    //    isImgLayer: false,
    //    ifrmImgWidth: "455",
    //    ifrmImgHeight: "260",
    //    bgPanelDivId: "bgPanelDivId",
    //    visibleRegionDivId: "visibleRegionDivId",
    //    countDivId: "countDivId"
    //}
    var imgI = $(imgFileObj).attr("id").substring($(imgFileObj).attr("id").length - 1);
    LayerShow("/3b5g1s2k7j6u4b8m/Welcome/imgUp?color=1&img_i=" + imgI, DefaultItems);
    //InitLayer("../imgUp.aspx?color=1&kind=" + imgI, ifrmImgBg, null, null);
}
