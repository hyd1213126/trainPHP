<?php

class Info_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function select($params = array(), $pages = array())
    {
        $params_standard = array(
          'Iid', //多个用逗号隔开，可为空
        );
        $pages_standard = $this->config_lib->pages_standard;
        $params = elements($params_standard, $params, '');
        $pages = elements($pages_standard, $pages, '');

        $this->db->from('ZhuoFu2016_Info');
        if ($params['Iid'] != '') {
            $this->db->where_in('Iid', $params['Iid']);
        }
        $this->db->where('Type!=0');

        $this->functions_lib->Organize_limit($pages);
        $query = $this->db->get();

        return $query->result();
    }
    //修改
    public function modify($params = array())
    {
        $this->db->where('Iid', $params['Iid']);
        $this->db->update('ZhuoFu2016_Info', array('Iinfo' => $params['v']));
    }
}
