<?php
/*
@ 高京
@ 2016-06-03
@ Model.adminUsers
 */
class AdminUsers_model extends CI_Model {
	// public $Auid;
	// public $Auser;
	// public $Passwd;
	// public $Alive;
	// public $Aulid;

	public function __construct() {
		parent::__construct();
	}

	// select
	public function select($params = array(), $pages = array()) {
		$params_standard = array(
			"Auid", // 多个用逗号分隔，可为空
			"Auser", // 和Passwd一起使用，可为空
			"Passwd", // 加密后字符串，和Auser一起使用，可为空
			"Alive", // 0-全部 1-有效 2-屏蔽
			"Aulid", // 多个用逗号分隔，可为空
		);
		$pages_standard = $this->config_lib->pages_standard;

		$params = elements($params_standard, $params, "");
		$pages = elements($pages_standard, $pages, "");
		$this->db->select("a.*,b.Ltitle,b.Area");
		$this->db->from("ZhuoFu2016_adminUsers as a");

		if ($params["Auid"] != "") {
			$this->db->where_in("Auid", $params["Auid"]);
		}
		if ($params["Auser"] != "" && $params["Passwd"] != "") {
			$this->db->where("Auser", $params["Auser"]);
			$this->db->where("Passwd", $params["Passwd"]);
		}

		$params["Alive"] = $this->functions_lib->Convert($params["Alive"], "int");
		if ($params["Alive"] == 1) {
			$this->db->where("a.Alive", "1");
		} else if ($params["Alive"] == 2) {
			$this->db->where("a.Alive", "0");
		}
		//根据级别查询
		if ($params["Aulid"] != "" && $params["Aulid"] != "0") {
			$this->db->where("a.Aulid", $params["Aulid"]);
		}
		//查询结束
		//扩展Ltitle
		$this->db->join('ZhuoFu2016_adminusersLevel as b', 'b.Aulid = a.Aulid', 'left');

		$this->functions_lib->Organize_limit($pages);

		$query = $this->db->get();
		return $query->result();
	}

	// select_list
	public function select_list($params = array(), $pages = array()) {
		$params_standard = array(
			"Auid", // 多个用逗号分隔，可为空
			"Auser", // 和Passwd一起使用，可为空
			"Passwd", // 加密后字符串，和Auser一起使用，可为空
			"Alive", // 0-全部 1-有效 2-屏蔽
			"Aulid", // 多个用逗号分隔，可为空
		);
		$pages_standard = $this->config_lib->pages_standard;

		$params = elements($params_standard, $params, "");
		$pages = elements($pages_standard, $pages, "");

		$this->db->start_cache();
		$this->db->select('a.*,b.Ltitle,b.Area');
		$this->db->from("ZhuoFu2016_adminUsers as a");

		if ($params["Auid"] != "") {
			$this->db->where_in("Auid", $params["Auid"]);
		}
		if ($params["Auser"] != "" && $params["Passwd"] != "") {
			$this->db->where("Auser", $params["Auser"]);
			$this->db->where("Passwd", $params["Passwd"]);
		}

		$params["Alive"] = $this->functions_lib->Convert($params["Alive"], "int");
		if ($params["Alive"] == 1) {
			$this->db->where("a.Alive", "1");
		} else if ($params["Alive"] == 2) {
			$this->db->where("a.Alive", "0");
		}
		//根据级别查询
		if ($params["Aulid"] != "" && $params["Aulid"] != "0") {
			$this->db->where("a.Aulid", $params["Aulid"]);
		}
		//查询结束

		//扩展Ltitle
		$this->db->join('ZhuoFu2016_adminusersLevel as b', 'b.Aulid = a.Aulid', 'left');

		$this->db->stop_cache(); //上面有开始缓存
		$data["rc"] = $this->db->count_all_results(); //需要缓存

		$this->functions_lib->Organize_limit($pages);
		$query = $this->db->get();

		$data["list"] = $query->result();
		$this->db->flush_cache();
		return $data;
	}

	//add
	public function add($params = array()) {

		$params_standard = array(
			"Auid", "Auser", "Passwd", "Alive", "Aulid",
		);

		$params = elements($params_standard, $params, "");
		// 补齐其他字段
		$params["Alive"] = 1;
		// 添加
		$this->db->insert("zhuofu2016_adminusers", $params);
		return $this->db->query("select last_insert_id()")->result();
	}

	//modify
	public function modify($params = array()) {
		$params_standard = array(
			"Auid", "Auser", "Passwd", "Aulid",
		);

		$params = elements($params_standard, $params, "");
		// 修改
		$data = array(
			'Auser' => $params["Auser"],
			'Passwd' => $params["Passwd"],
			'Aulid' => $params["Aulid"],
		);
		// $where = "Auid=" . $params["Auid"];
		$this->db->where('Auid', $params["Auid"]);
		$this->db->update('zhuofu2016_adminusers', $data);
	}

	//Alive
	public function alive($Auid) {
		$sql = "select Alive from zhuofu2016_adminusers where Auid=" . $Auid;
		$query = $this->db->query($sql);
		$Alive = $query->row()->Alive;
		if ($Alive == "1") {
			$Alive = 0;
		} else {
			$Alive = 1;
		}
		$data = array(
			'Alive' => $Alive,
		);
		$this->db->where('Auid', $Auid);
		$this->db->update('zhuofu2016_adminusers', $data);
	}

	//del
	public function del($Auid) {
		$this->db->where_in("Auid", $Auid);
		$this->db->delete("zhuofu2016_adminusers");
	}

	public function saveCookie($id = "", $Auser = "", $Passwd = "") {
		setcookie("id", $id, 0, $this->config_lib->admin_dir);
		setcookie("Auser", $Auser, 0, $this->config_lib->admin_dir);
		setcookie("Passwd", $Passwd, 0, $this->config_lib->admin_dir);
	}

	/*
		验证登录
		失败则跳至登录页
		成功则返回array
	*/
	public function checkLogin() {
		$id = get_cookie("id", true);
		$Auser = get_cookie("Auser", true);
		$Passwd = get_cookie("Passwd", true);

		if ($id == "" or $Auser == "" or $Passwd == "") {
			$this->_redirectLogin();
		}

		$params = array(
			"id" => $id,
			"Auser" => $Auser,
			"Passwd" => $Passwd,
		);

		$data = $this->select($params);

		if (count($data) == 0) {
			$this->_redirectLogin();
		} else {
			return $data;
		}

	}

	// 跳转至登录页方法
	private function _redirectLogin() {
		redirect("http://" . $_SERVER["HTTP_HOST"] . $this->config_lib->admin_dir . "/Login/reLogin/");
	}
}
?>
