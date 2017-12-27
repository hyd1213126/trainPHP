<!--
	@ 高京
	@ 2016-06-01
	@ 公共方法文档控制器
 -->
<?php
class Functions extends CI_Controller {

	// 构造函数
	public function __construct() {
		parent::__construct();

		$this->load->helper("text");
		$this->load->library("Functions_lib");
	}

	public function _remap($method, $params = array()) {
		switch ($method) {
			case "index":
			default:
				$this->_index();
				break;
		}
	}

	// 默认列表
	private function _index() {
		// echo "<br />" . $this->functions_lib->StrLength("1234567890中");
		// echo "<br />" . $this->functions_lib->GetExtension("abc.jpg");
		// echo "<br />" . $this->functions_lib->filterNoNum("1,2,a,3,4");
		// echo "<br />" . $this->functions_lib->transParameters("b|c|D");
		// echo "<br />" . $this->functions_lib->NameMask("高京");
		// echo "<br />" . $this->functions_lib->MobileMask("15501235957", "", 4);
		// echo "<br />" . $this->functions_lib->createFileName("/application/views/", ".doc", 8, "_");
		// echo "<br />" . $this->functions_lib->Convert("sdfsdfds", "double");
		// echo "<br />" . $this->functions_lib->encrypt_md5(str_repeat("0", 16), str_repeat("a", 16));
		// echo "<br />" . hash("sha1", "abcde我爱北京天安门");
		$this->load->view("functions/list.html");
	}
}
?>