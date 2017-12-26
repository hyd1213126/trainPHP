<?php
class AdminUsers_model extends CI_Model {
	// public $Auid;
	// public $Auser;
	// public $Passwd;
	// public $Alive;
	// public $Aulid;

	public function __construct() {
		parent::__construct();
    }

    // 查询
    public function select($params=array(),$pages=array()){
        $params_standerd=array(
            "Auid",//编号
            "Auser",//用户名
            "Passwd",//加密后字符串，和Auser一起使用，可为空
            "Alive",//0-全部 1-有效 2-屏蔽
            "Aulid",//多个用逗号分隔，可为空
        );
        $pages_standard = $this->config_lib->pages_standard;

        $params=elements($params_standerd,$params,"");
        $pages = elements($pages_standard, $pages, "");
        
        $this->db->select("a.*,b.Ltitle,b.Area");
        $this->db->from("ZhuoFu2016_adminUsers as a");

        // 根据管理员编号查询
        if($params["Auid"]!=""){
            $this->db->where_in("Auid",$params["Auid"]);
        }

        // 根据用户名和密码查询
        if($params["Auser"]!=""&&$params["Passwd"]!=""){
            $this->db->where_in("Auser",$params["Auser"]);
            $this->db->where_in("Passwd",$params["Passwd"]);
        }

        // 根据是否屏蔽查询 bit型数据查询需要先转换成int，同时在查询时，需要同数据库相同，使用string字符串
        $params["Alive"]=$this->function_lib->Convert($params["Alive"]);
        if($params["Alive"]==1){
            $this->db->where("a.Alive","1");
        }
        else if($params["Alive"]==2){
            $this->db->where("a.Alive","0");
        }

        // 根据管理员权限查询
        if($params["Aulid"]!=""&&$params["Auild"]!="0"){
            $this->db->where_in("a.Aulid",$params["Aulid"]);
        }

        //查询扩展
        // join(表名，条件，关联类型)
        $this->db->join('ZhuoFu2016_adminusersLevel as b','b.Aulid=a.Aulid','left');

        $this->functions_lib->Organize_limit($pages);

        $query = $this->db->get();
		return $query->result();
    }

    /* 可改进（字段可在数据库添加时给默认值，比如，Alive可给默认值1） */
    public function add($params=array()){
        $params_standerd=array(
            "Auid",//主键编号
            "Auser",//用户名
            "Passwd",//密码
            "Alive",//屏蔽状态
            "Aulid",//所属级别
        );

        $params=elements($params_standerd,$params,"");

        $params["Alive"]=1;

        // 添加
        $this->db->insert("ZhuoFu2016_adminUsers",$params);
        return $this->db->query("select last_insert_id()")->result();
    }

    //修改
	public function modify($params = array()) {
		$params_standard = array(
			"Auid", "Auser", "Passwd", "Aulid",
		);

		$params = elements($params_standard, $params, "");

		$data = array(
			'Auser' => $params["Auser"],
			'Passwd' => $params["Passwd"],
			'Aulid' => $params["Aulid"],
		);
		$this->db->where('Auid', $params["Auid"]);
		$this->db->update('zhuofu2016_adminusers', $data);
    }
    
    //屏蔽状态修改
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
    
    //删除
	public function del($Auid) {
		$this->db->where_in("Auid", $Auid);
		$this->db->delete("zhuofu2016_adminusers");
    }
    
    // 保存cookie
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