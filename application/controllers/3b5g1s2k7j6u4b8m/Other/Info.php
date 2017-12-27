<?php

class Info extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();

        // 验证登录
        $this->load->model(array('adminUsers_model', 'Info_model'));
        $this->data['adminUsers'] = $this->adminUsers_model->checkLogin();

        $this->data['Iid'] = $this->functions_lib->Convert($_GET['iid'], 'int');
        $this->data['act'] = strtolower($this->functions_lib->Convert(@$_POST['act'], 'string'));
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
        case 'List':
        default:
            $this->_List();
            break;

        }
    }

    private function _List()
    {
        $_data = $this->data;

        // 处理表单提交
        if ($_data['act'] == 'modify') {
            $_data['searchParams']['Iid'] = $_data['Iid'];
            $_data['info'] = $this->Info_model->select($_data['searchParams']);
            $count = count($_data['info']);
            for ($i = 0; $i < $count; ++$i) {
                $v = trim($this->functions_lib->Convert(@$_POST['Iinfo'], 'string')); //可视化内容

                $this->Info_model->modify(
                    array(
                        'Iid' => $_data['info'][$i]->Iid,
                        'v' => $v,
                    )
                );
            }
            $_data['act'] = 'success';
        }
        $_data['searchParams']['Iid'] = $_data['Iid'];
        $_data['info'] = $this->Info_model->select($_data['searchParams']);

        $this->load->view($this->config_lib->admin_dir.'/other/info.html', $_data);
    }
}
