<?php
/*
@刘超
@2016-06-20
@管理员级别
 */
class AdminUsersLevel extends CI_Controller {
	public $data;
	public function __construct() {
		parent::__construct();

		$this->load->model(array("AdminUsers_model", "AdminUsersLevel_model"));

		//验证登录
		$this->data['adminUsers'] = $this->AdminUsers_model->checkLogin();
		//act
		$this->data["act"] = strtolower($this->functions_lib->Convert(@$_POST["act"], "string"));
		//Aulid
		$this->data["Aulid"] = strtolower($this->functions_lib->Convert(@$_GET["id"], "string"));
	}

	public function _remap($method, $params = array()) {
		switch ($method) {
		case 'List':
		default:
			$this->_list();
			break;
		case 'Modify':
			$this->_modify();
			break;
		case 'Add':
			$this->_add();
			break;
		case 'Alive':
			$this->_alive();
			break;
		}
	}

	private function _List() {
		$_data = $this->data;

		// 获得地址栏查询参数
		$_getParams_standard = array(
			"p", "searching",
		);

		$_data["searchParams"] = elements($_getParams_standard, $this->input->get(null, true), "");

		// 获得分页
		$_data["pageParams"] = array(
			"ps" => 0,
			"Page" => $_data["searchParams"]["p"],
		);
		//处理表单提交
		if ($_POST) {
			if ($_data["act"] == "del") {
				$checkid = @$_POST["checked"];
				if ($checkid == null) {
					$checkid = "0";
				}
				$this->AdminUsersLevel_model->del($checkid);
				$_data["act"]="success";
				// redirect("http://" . $_SERVER["HTTP_HOST"] . $this->config_lib->admin_dir . "/adminUsersLevel/List.html");
			}
		}

		$_list = $this->AdminUsersLevel_model->select($_data["searchParams"], $_data["pageParams"]);

		$_data["adminUsersLevel"] = $_list["list"];
		$rc = $_list["rc"];

		// 分页字符串
		$opt = array(
			"base_url" => "?",
			"total_rows" => $rc,
			"per_page" => $_data["pageParams"]["ps"],
			"num_links" => 5,
		);
		$opt = $this->functions_lib->admin_oranize_page_str($opt);
		$_data["page_str"] = $this->pagination->initialize($opt)->create_links();

		$body = $this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/List.html", $_data, true);

		// 弹出层插件
		$iframeLayer = $this->load->view($this->config_lib->admin_dir . "/comm/iframeLayer.html", null, true);
		$body = str_replace("{\$iframeLayer}", $iframeLayer, $body);

		echo $body;

		// $this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/List.html", $_data);
	}

	private function _modify() {
		$_data = $this->data;
		// $this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/Modify.html", $_data);
		if ($_data["act"] == "modify") {
			// 验证表单数据方法
			//级别编号
			$this->form_validation->set_rules("aulid", "",
				array(
					"trim",
					"required",
				),
				array(
					"required" => "请键入级别编号",
				)
			);

			if (@$_POST["aulid"] <= 0 || @$_POST["aulid"] > 100) {
				$this->form_validation->set_rules("aulid2", "",
					array(
						"required",
					),
					array(
						"required" => "级别编号请键入100以内的正整数",
					)
				);
			}

			$Aulid1 = $this->db->query("select Aulid from zhuofu2016_adminusersLevel where Aulid='" . @$_POST['aulid'] . "'")->result();
			if (count($Aulid1) != 0 && $_data["Aulid"] != @$_POST['aulid']) {
				$this->form_validation->set_rules("Aulid1", "",
					array(
						"required",
					),
					array(
						"required" => "此级别编号已存在",
					)
				);
			}

			// 级别名称
			$this->form_validation->set_rules("ltitle", "",
				array(
					"trim",
					"required",
					array("_length", array($this->functions_lib, "check_length"), array("bytes_count" => "20")),
				),
				array(
					"required" => "请键入级别名称",
					"_length" => "级别名称不能超过10个汉字（20个字符）",
				)
			);

			// 后台管理范围
			$area = @$_POST['area'];
			if ($area == null) {
				$this->form_validation->set_rules("area", "",
					array(
						"required",
					),
					array(
						"required" => "请选择后台管理范围",
					)
				);
			}

			if ($this->form_validation->run()) {
				// 接收表单
				$aulid = $this->input->post('aulid', true);
				$ltitle = $this->input->post('ltitle', true);
				$area1 = $this->input->post('area', true);
				$area = "";
				if ($area1 == null) {
					$area = -1;
				}
				for ($i = 0; $i < count($this->config_lib->admin_area); $i++) {
					if ($i > 0) {
						$area .= ",";
					}

					if (in_array($i, $area1)) {
						$area .= "1";
					} else {
						$area .= "0";
					}

				}
				$params = array(
					"Aulid" => $aulid,
					"Ltitle" => $ltitle,
					"Area" => $area,
				);
				// 修改记录
				$Aulid = $this->AdminUsersLevel_model->modify($_data["Aulid"], $params);

				// 完成修改
				$_data["act"] = "success";
			}
		}
		if ($_data["act"] == "success") {
			$this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/Modify.html", $_data);
		} else {
			$_data["searchParams"] = array('Aulid' => $_data["Aulid"]);
			$_list = $this->AdminUsersLevel_model->select($_data["searchParams"]);
			$_data["adminUsersLevel"] = $_list["list"];
			$_data["Area"] = $_data["adminUsersLevel"][0]->Area;
			$Area_temp = "";
			for ($i = 0; $i < count($this->config_lib->admin_area); $i++) {
				if ($Area_temp != "") {
					$Area_temp .= ",";
				}
				if (explode(',', $_data["Area"])[$i] == "1") {
					$Area_temp .= "true";
				} else {
					$Area_temp .= "false";
				}
			}
			$_data["Area"] = explode(',', $Area_temp);
			$this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/Modify.html", $_data);
		}
	}

	private function _add() {
		$_data = $this->data;
		// 处理表单提交
		if ($_data["act"] == "add") {

			// 验证表单数据方法
			//级别编号
			$this->form_validation->set_rules("aulid", "",
				array(
					"trim",
					"required",
				),
				array(
					"required" => "请键入级别编号",
				)
			);

			if (@$_POST["aulid"] <= 0 || @$_POST["aulid"] > 100) {
				$this->form_validation->set_rules("aulid2", "",
					array(
						"required",
					),
					array(
						"required" => "级别编号请键入100以内的正整数",
					)
				);
			}

			$Aulid1 = $this->db->query("select Aulid from zhuofu2016_adminusersLevel where Aulid='" . @$_POST['aulid'] . "'")->result();
			if (count($Aulid1) != 0) {
				$this->form_validation->set_rules("Aulid1", "",
					array(
						"required",
					),
					array(
						"required" => "此级别编号已存在",
					)
				);
			}

			// 级别名称
			$this->form_validation->set_rules("ltitle", "",
				array(
					"trim",
					"required",
					array("_length", array($this->functions_lib, "check_length"), array("bytes_count" => "20")),
				),
				array(
					"required" => "请键入级别名称",
					"_length" => "级别名称不能超过10个汉字（20个字符）",
				)
			);

			// 后台管理范围
			$area = @$_POST['area'];
			if ($area == null) {
				$this->form_validation->set_rules("area", "",
					array(
						"required",
					),
					array(
						"required" => "请选择后台管理范围",
					)
				);
			}

			if ($this->form_validation->run()) {
				// 接收表单
				$aulid = $this->input->post('aulid', true);
				$ltitle = $this->input->post('ltitle', true);
				$area1 = $this->input->post('area', true);
				$area = "";
				if ($area1 == null) {
					$area = -1;
				}
				for ($i = 0; $i < count($this->config_lib->admin_area); $i++) {
					if ($i > 0) {
						$area .= ",";
					}

					if (in_array($i, $area1)) {
						$area .= "1";
					} else {
						$area .= "0";
					}

				}
				$params = array(
					"Aulid" => $aulid,
					"Ltitle" => $ltitle,
					"Area" => $area,
				);
				// 添加记录
				$this->load->model("AdminUsersLevel_model");
				$Aulid = $this->AdminUsersLevel_model->add($params);

				// 完成添加
				$_data["act"] = "success";
			}
		}
		if ($_data["act"] == "success") {
			$this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/Add.html", $_data);
		} else {
			$_data["Aulid"] = $this->db->query("select max(Aulid)+1 aulid from zhuofu2016_adminusersLevel")->result()[0]->aulid;
			$this->load->view($this->config_lib->admin_dir . "/adminUsersLevel/Add.html", $_data);
		}
	}

	private function _alive() {
		$_data = $this->data;
		$this->AdminUsersLevel_model->alive($_data["Aulid"]);
		$this->_list();
	}

}
?>
