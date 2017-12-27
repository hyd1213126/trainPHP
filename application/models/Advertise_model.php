<?php

/**
 *刘超
 *固定广告位
 * 2016-11-11.
 */
class Advertise_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //select by 刘超
    public function select($params = array())
    {
        $params_standard = array(
          'Aid', // 多个用逗号分隔，可为空
        );
        $params = elements($params_standard, $params, '');

        $this->db->start_cache(); //开始缓存

        $this->db->select('*');
        $this->db->from('ZhuoFu2016_advertise');

        if ($params['Aid'] != '') {
            $this->db->where_in('Aid', $params['Aid']);
        }

        $this->db->stop_cache(); //上面有开始缓存
        $data['rc'] = $this->db->count_all_results(); //需要缓存

        $query = $this->db->get();

        $data['list'] = $query->result();
        $this->db->flush_cache();

        return $data;
    }
    //modify by 刘超
    public function modify($params = array())
    {
        $params_standard = array(
          'Aid',
          'Pic1',
          'Url',
        );
        $params = elements($params_standard, $params, '');
        // 修改
        $Aid = $params['Aid'];
        $data = array(
            'Pic1' => $params['Pic1'],
            'Url' => $params['Url'],
        );
        $this->db->where('Aid', $Aid);
        $this->db->update('zhuofu2016_advertise', $data);
    }
}
