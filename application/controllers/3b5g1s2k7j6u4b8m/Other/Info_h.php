<?php
/*
 * 胡浴东
 * 2016-11-15
 * Imfo.Controller
 */
class Info_h extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        //验证登陆
        $this->load->model(array('adminUsers_model', 'info_h_model'));
        $this->data['adminUsers'] = $this->adminUsers_model->checkLogin();

        //act
        $this->data['act'] = strtolower($this->functions_lib->Convert(@$_POST['act'], 'string'));
        //Iid
        $this->data['Iid'] = strtolower($this->functions_lib->Convert(@$_GET['iid'], 'string'));

        $this->data['i'] = 0;
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
        case 'Modify':
        default:
            $this->_Modify();
            break;
        }
    }

    private function _Modify()
    {
        $_data = $this->data;

        //处理表单提交
        if ($_data['act'] == 'modify') {

            //验证通过
            // 接收表单
            $Iinfo = $this->input->post('Iinfo', true);

            $params = array(
                'Iid' => $_data['Iid'],
                'Iinfo' => $Iinfo,
                );
                // 修改记录
            $this->info_h_model->modify($params);
                // 完成修改
                $_data['act'] = 'success';
        }
        //赋初值
        $params = array('Iid' => $_data['Iid']);
        $_data['Info'] = $this->info_h_model->select($params)['list'];

        if ($_data['act'] == 'success') {
            $this->load->view($this->config_lib->admin_dir.'/other/info_h.html', $_data);
        } else {
            $body = $this->load->view($this->config_lib->admin_dir.'/other/info_h.html', $_data, true);//true表示将页面作为字符串返回
            $iframeLayer = $this->load->view($this->config_lib->admin_dir.'/comm/iframeLayer.html', '', true);
            $body = str_replace('{$iframeLayer}', $iframeLayer, $body);//用$iframeLayer替换$body里的{$iframeLayer}
            echo $body;
        }
        // $this->load->view($this->config_lib->admin_dir.'/other/info_h.html');
    }
}
