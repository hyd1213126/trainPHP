<?php
class Init_model extends CI_Model {

    public function __construct() {
		parent::__construct();
    }
    
    public function select($params=array(),$pages=array()){
        
        $params_standard=array(
            "Iid",//多个用逗号分隔，可为空
        );

        $pages_standard = $this->config_lib->pages_standard;
        
        $params = elements($params_standard, $params, "");// 对比数组，如果有该索引下没有值，则设置为""
        $pages=elemrnts($pages_standard,$pages,"");

        $this->db->from("ZhuoFu2016_Init");//执行数据库

        if ($params["Iid"] != "") {
			$this->db->where_in("Iid", $params["Iid"]);
		}
		$this->db->where("Type !=0");

		$this->functions_lib->Organize_limit($pages);

		$query = $this->db->get();
		return $query->result();
        
    }

    //修改
	public function modify($params = array()) {
		$this->db->where('Iid', $params["Iid"]);
		$this->db->update('ZhuoFu2016_Init', array('Iinfo' => $params["v"]));
	}

	//查询上次清空时间
	public function clean() {
		$this->db->where("Iid=", 100);
		$this->db->select("Iinfo");
		$query = $this->db->get("ZhuoFu2016_Init");
		return "上次清空时间：" . $query->row()->Iinfo;
	}
}