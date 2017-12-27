<?php
/*
@刘超
@2016-06-17
@清理垃圾文件
 */
class Clean extends CI_Controller {

	public function __construct() {
		parent::__construct();
		//load文件辅助函数
		$this->load->helper('file');
		//验证登录
		$this->load->model(array("adminUsers_model", "Init_model"));
		$this->data["adminUsers"] = $this->adminUsers_model->checkLogin();
	}

	public function index() {
		//检查临时文件夹是否存在
		$Filepath = "./upload_file/temp/";
		if (!file_exists($Filepath)) {
			mkdir($Filepath);
		}
		//删除垃圾文件
		try
		{
			delete_files($Filepath, true); //true表示目录下的文件夹一并删除
			$data["act"] = "success";
		} catch (Exception $e) {
			$data["act"] = "error";
			echo $e;
		}
		//更新清空垃圾时间
		$this->Init_model->modify(
			array(
				"Iid" => 100,
				"v" => date("Y-m-d H:i:s"),
			)
		);
		$data['Iinfo'] = $this->Init_model->clean(); //上次清空时间
		$this->load->view($this->config_lib->admin_dir . "/other/Space.html", $data);
	}
}
?>
