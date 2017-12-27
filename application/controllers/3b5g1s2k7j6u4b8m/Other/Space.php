<?php
/*
@刘超
@2016-06-16
@删除垃圾文件
 */
class Space extends CI_Controller {

	public $data;

	public function __construct() {
		parent::__construct();

		// 验证登录
		$this->load->model(array("adminUsers_model", "Init_model"));
		$this->data["adminUsers"] = $this->adminUsers_model->checkLogin();

		// act
		$this->data["act"] = strtolower($this->functions_lib->Convert(@$_POST["act"], "string"));

	}

	public function index() {
		$_data = $this->data;
		$_data['Iinfo'] = $this->Init_model->clean(); //上次清空时间
		$this->load->view($this->config_lib->admin_dir . "/other/space.html", $_data);
	}
}

?>