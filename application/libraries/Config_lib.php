<?php
/*
    20171226 胡浴东新建项目
*/
class Config_lib {

	// 后台路径
	public $admin_dir = "/3b5g1s2k7j6u4b8m";

	// 后台管理栏目
	public $admin_area = array("首页管理", "关于我们", "路演工场", "最新资讯", "其他", "系统设置");

	// 后台弹出层显示小窗口
	public $small_window = true;

	// 是否有图片库浏览功能，如果有则不连带删除记录中的图片，反之则连带删除记录中的图片
	public $pics_view = false;

	// 上传图片的显示域名
	public $ImageDomain = "/"; // 开发环境
	// public $ImageDomain = "http://www.topu.net"; // 正式环境

	// 标准分页数组
	public $pages_standard = array(
		"ps", // 每页记录数量。空为不分页
		"Page", // 页码，默认：0（0为第一页）
	);
}