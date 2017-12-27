	<?php
/**
@管理员级别
 */
class AdminUsersLevel_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // select
    public function select($params = array(), $pages = array())
    {
        $params_standard = array(
            'Aulid', // 多个用逗号分隔，可为空
            'Alive', // 0-全部 1-有效 2-屏蔽
        );
        $pages_standard = $this->config_lib->pages_standard;

        $params = elements($params_standard, $params, '');
        $pages = elements($pages_standard, $pages, '');

        $this->db->start_cache(); //开始缓存

        $this->db->select('t1.*,ifnull(t2.c,0) as c');
        $this->db->from('ZhuoFu2016_adminuserslevel as t1');

        if ($params['Aulid'] != '') {
            $this->db->where_in('t1.Aulid', $params['Aulid']);
        }
        $params['Alive'] = $this->functions_lib->Convert($params['Alive'], 'int');
        if ($params['Alive'] == 1) {
            $this->db->where('t1.Alive', '1');
        } elseif ($params['Alive'] == 2) {
            $this->db->where('t1.Alive', '0');
        }
        //扩展对应管理员的数量
        $this->db->join('(select Aulid,count(Auid) as c from zhuofu2016_adminusers group by Aulid) as t2', 't2.Aulid=t1.Aulid', 'left');

        $this->db->stop_cache(); //上面有开始缓存
        $data['rc'] = $this->db->count_all_results(); //需要缓存

        $this->functions_lib->Organize_limit($pages);
        $query = $this->db->get();

        $data['list'] = $query->result();
        $this->db->flush_cache();

        return $data;
    }

    //add
    public function add($params = array())
    {
        $params_standard = array(
            'Aulid', 'Ltitle', 'Area', 'Alive',
        );

        $params = elements($params_standard, $params, '');
        // 补齐其他字段
        $params['Alive'] = 1;
        // 添加
        $this->db->insert('zhuofu2016_adminuserslevel', $params);

        return $this->db->query('select last_insert_id()')->result();
    }

    //modify
    public function modify($Aulid, $params = array())
    {
        // var_dump($params);
        $params_standard = array(
            'Aulid', 'Ltitle', 'Area',
        );

        $params = elements($params_standard, $params, '');
        // 修改
        $data = array(
            'Aulid' => $params['Aulid'],
            'Ltitle' => $params['Ltitle'],
            'Area' => $params['Area'],
        );
        $this->db->where('Aulid', $Aulid);
        $this->db->update('zhuofu2016_adminuserslevel', $data);
    }

    //alive
    public function alive($Aulid)
    {
        $sql = 'select Alive from zhuofu2016_adminuserslevel where Aulid='.$Aulid;
        $query = $this->db->query($sql);
        $Alive = $query->row()->Alive;
        if ($Alive == '1') {
            $Alive = 0;
        } else {
            $Alive = 1;
        }
        $data = array(
            'Alive' => $Alive,
        );
        $this->db->where('Aulid', $Aulid);
        $this->db->update('zhuofu2016_adminuserslevel', $data);
    }

    //del
    public function del($Aulid)
    {
        $this->db->where_in('Aulid', $Aulid);
        $this->db->delete('zhuofu2016_adminuserslevel');
    }
}

?>
