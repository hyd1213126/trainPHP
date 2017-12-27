<?php
/*
@ 高京
@ 2016-06-03
@ 主界面控制器
 */

class Welcome extends CI_Controller {
	public $img_i = -1;
	public function _remap($method, $params = array()) {
		$this->img_i = $this->input->get('img_i', true);
		switch ($method) {
			case "index":
			default:
				$this->_index();
				break;
			case "index2":
				$this->_index2();
				break;
			case "imgUp":
				$this->_imgUp();
				break;
			case "imgUp_do":
				$this->_imgUp_do();
				break;
		}
	}

	// 主框架
	private function _index() {

		$this->load->model("Init_model");
		$this->load->model("AdminUsers_model");

		$data["adminUsers"] = $this->AdminUsers_model->checkLogin();

		$params = array();
		$data["Init"] = $this->Init_model->select($params);

		// 主内容
		$body = $this->load->view($this->config_lib->admin_dir . "/index.html", $data, true);

		// top
		$top = $this->load->view($this->config_lib->admin_dir . "/comm/top.html", '', true);
		$body = str_replace("{\$top}", $top, $body);

		// menu
		$menu = $this->load->view($this->config_lib->admin_dir . "/comm/menu.html", "", true);
		$body = str_replace("{\$menu}", $menu, $body);

		// bottom
		$bottom = $this->load->view($this->config_lib->admin_dir . "/comm/bottom.html", "", true);
		$body = str_replace("{\$bottom}", $bottom, $body);

		// iframeLayer
		$iframeLayer = $this->load->view($this->config_lib->admin_dir . "/comm/iframeLayer.html", "", true);
		$body = str_replace("{\$iframeLayer}", $iframeLayer, $body);

		// var_dump($menu);

		echo ($body);
	}

	// 默认首页
	private function _index2() {
		$this->load->model("AdminUsers_model");

		$data["adminUsers"] = $this->AdminUsers_model->checkLogin();
		$this->load->view($this->config_lib->admin_dir . "/index2.html");
	}

	// 图片上传
	private function _imgUp($error = '', $filepath = '') {
		$data["img_i"] = $this->img_i;
		$data["error"] = $error;
		$data["filepath"] = $filepath;
		$this->load->view($this->config_lib->admin_dir . "/imgUp.html", $data);
	}

	// 图片上传执行
	private function _imgUp_do() {

		if (!file_exists('./upload_file')) {
			mkdir('./upload_file');
		}

		if (!file_exists('./upload_file/temp')) {
			mkdir('./upload_file/temp');
		}

		// $dir = "./upload_file/temp";
		// while (true) {
		// 	$filename = date("yyyyMMddHHmmss") . random_string("numeric", 4);
		// 	if (!file_exists($dir . "/" . $filename)) {
		// 		break;
		// 	}

		// }

		$config['upload_path'] = './upload_file/temp/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);

		$data = array(
			"error" => "",
			"filepath" => array(),
		);

		if (!$this->upload->do_upload('inputFile')) {
			$this->_imgUp($this->upload->display_errors());
		} else {
			$this->_imgUp("", "/upload_file/temp/" . $this->upload->data()["file_name"]);
		}
	}
}
