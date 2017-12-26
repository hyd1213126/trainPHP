var siteHead = "";
//url-弹出层内容的路径
//showCallback-打开弹出层时执行的函数，没有可以写null
//closeCallback_s-关闭弹出层时执行的函数，没有可以写null
function showlayer(Url, showCallback, closeCallback_s) {
    var DefaultItems = new Array();         //默认样式数组 

    DefaultItems = {
        Width: "85%",               					    //弹出层页面宽度
        Height: "85%",                   				    //弹出层页面高度                                                               
        imgurl: "/" + siteHead + "iframeLayer/img/loading_street.gif",    //Loading图片
        Layer_BackGround: "iframeLayer_bg_div",             //遮罩层DIV ID与样式                               
        Layer_content: "iframeLayer_content_div",           //弹出层容器DIV ID与样式
        ShowCloseBtn: true,                                 //是否显示关闭按钮 True为显示
        CloseLayerBack: closeCallback_s,               	    //关闭弹出层回调函数 （括号都不用写）                             
        LoadingAfterBack: showCallback,                    	//加载完成后回调函数	  
        CloseBtn: "/" + siteHead + "iframeLayer/img/close.png",            //自定义关闭按钮图片	
        AutoHeight: false                                    //自动高度，为ture会以内容高度为准,否则以自定义高度为准


    }

    LayerShow(Url, DefaultItems);			//主调用方法  Url为地址   DefaultItems为样式数组
    /*


    var ifrmImgBgs = new Array();
    var ifrmImgBgs = {
        isImgLayer: false,       
        isShowScroll: false,     
        ifrmImgWidth: "85%",    
        ifrmImgHeight: "85%",   
        countDivId: "countDivId"         
    }
    parent.InitLayer(url, ifrmImgBgs, showCallback, closeCallback_s);
    */
}