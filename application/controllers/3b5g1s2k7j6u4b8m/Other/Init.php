<?php

/*
@刘超
@2016-06-14
@网站默认设置
 */
class Init extends CI_Controller {

	public function __construct() {
		parent::__construct();

		// 验证登录
		$this->load->model(array("adminUsers_model", "Init_model"));
		$this->data["adminUsers"] = $this->adminUsers_model->checkLogin();

		// act
		$this->data["act"] = strtolower($this->functions_lib->Convert(@$_POST["act"], "string"));

	}

	public function _remap($method, $params = array()) {
		switch ($method) {
		case "List":
		default:
			$this->_List();
			break; /*
		case "Modify":
			$this->_Modify();*/

		}
	}

	public $data;

	private function _List() {
		$_data = $this->data;

		// 处理表单提交
		if ($_data["act"] == "modify") {
			$_data["init"] = $this->Init_model->select();
			$count = count($_data["init"]);
			for ($i = 0; $i < $count; $i++) {
				if ($_data["init"][$i]->Type == 2) {
					$v = trim($this->functions_lib->Convert(@$_POST["i" . $_data["init"][$i]->Iid . "_2"], "string")); //可视化内容
				} else {
					$v = trim($this->functions_lib->Convert(@$_POST["i" . $_data["init"][$i]->Iid . "_0"], "string")); //input/textarea内容
				}
				$this->Init_model->modify(
					array(
						"Iid" => $_data["init"][$i]->Iid,
						"v" => $v,
					)
				);
			}
			$_data["act"] = "success";
		}

		$_data["init"] = $this->Init_model->select();

		$body = $this->load->view($this->config_lib->admin_dir . "/other/other.html", $_data, true);
		echo $body;

	}

	private function _Modify() {
		$_data = $this->data;

		$body = $this->load->view($this->config_lib->admin_dir . "/other/other.html", $_data, true);
		echo $body;

	}
}
?>