<?php

/* 
    20171226
    胡浴东
*/
class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		session_start();
	}

	public function _remap($method, $params = array()) {
		switch ($method) {
		case "Login":
		default:
			$this->_Login();
			break;
		case "captcha":
			$this->_create_captcha();
			break;
		case "valid":
			$this->_Valid();
			break;
		case "quit":
			$this->_Quit();
			break;
		case "reLogin":
			$this->_reLogin();
			break;
		}
	}

	// 登录页
	private function _Login() {

		$this->load->model("Init_model");

		$params = array();
		$data["Init"] = $this->Init_model->select($params);

		$this->load->view($this->config_lib->admin_dir . "/Login.html", $data);
	}

	// 生成验证码图片
	private function _create_captcha() {
		$vals = array(
			// 'word' => 'Random word',
			'img_path' => './upload_file/captcha/',
			'img_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/upload_file/captcha/',
			'font_path' => './res/fonts/bmxy.ttf',
			'img_width' => '100',
			'img_height' => 27,
			'expiration' => 1800,
			'word_length' => 4,
			'font_size' => 16,
			'img_id' => 'validimg',
			'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

			// White background and border, black text and red grid
			// 'colors' => array(
			// 	'background' => array(255, 255, 255),
			// 	'border' => array(255, 255, 255),
			// 	'text' => array(0, 0, 0),
			// 	'grid' => array(255, 40, 40),
			// ),
		);

		$this->load->helpers("captcha");

		$cap = create_captcha($vals);

		$_SESSION["adminUsers_captcha"] = $cap["word"];

		echo $cap['image'];
	}

	// 验证登录
	private function _Valid() {
		$auser = $this->functions_lib->Convert($this->input->post("auser"), "string");
		$passwd = $this->input->post("passwd");
		$passwd = hash("md5", $this->functions_lib->Convert($passwd, "string"));
		$passwd = $this->functions_lib->encrypt_md5($passwd, str_replace("/", "", $this->config_lib->admin_dir));
		$captcha_value = $this->functions_lib->Convert($this->input->post("captcha_value"), "string");
		$captcha_code = $this->functions_lib->Convert($_SESSION["adminUsers_captcha"], "string");

		unset($_SESSION["adminUsers_captcha"]);

		if (strtolower($captcha_value) != strtolower($captcha_code) or $captcha_code == "") {
			echo "验证码有误";
			return;
		}

		if ($auser == "" or $passwd == "") {
			echo "账号或密码错误";
			return;
		}

		// 查询
		$this->load->model("AdminUsers_model");
		$params = array(
			"Auser" => $auser,
			"Passwd" => $passwd,
			"Alive" => 1,
		);
		$data = $this->AdminUsers_model->select($params);
		if (count($data) == 0) {
			echo "账号或密码错误";
			return;
		} else {
			$m = $data[0];
			$this->AdminUsers_model->saveCookie($m->Auid, $m->Auser, $m->Passwd);
			echo "ok";
			return;
		}
	}

	// 退出
	private function _Quit() {
		delete_cookie("id", "", $this->config_lib->admin_dir);
		delete_cookie("Auser", "", $this->config_lib->admin_dir);
		delete_cookie("Passwd", "", $this->config_lib->admin_dir);

		redirect("http://" . $_SERVER["HTTP_HOST"] . $this->config_lib->admin_dir);
	}

	// 重新登录（为用js实现父级框架跳转）
	private function _reLogin() {
		$this->load->view($this->config_lib->admin_dir . "/reLogin.html");
	}

}
