<?php
/*
@刘超
@2016-06-17
@管理员
 */
class AdminUsers extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();

        //验证登录
        $this->load->model(array('adminUsers_model', 'AdminUsersLevel_model'));
        $this->data['adminUsers'] = $this->adminUsers_model->checkLogin();
        //act
        $this->data['act'] = strtolower($this->functions_lib->Convert(@$_POST['act'], 'string'));
        //Auid
        $this->data['Auid'] = strtolower($this->functions_lib->Convert(@$_GET['id'], 'string'));
        //获取级别
        $params = array('Alive' => 1); //查有效的等级
        $_list = $this->AdminUsersLevel_model->select($params);
        $this->data['adminUsersLevel'] = $_list['list'];
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
        case 'List':
        default:
            $this->_List();
            break;
        case 'Add':
            $this->_Add();
            break;
        case 'Modify':
            $this->_Modify(); //Auid
            break;
        case 'Alive':
            $this->_Alive(); //Auid
        }
    }

    private function _List()
    {
        $_data = $this->data;

        // 获得地址栏查询参数
        $_getParams_standard = array(
            'p', 'searching', 'Aulid',
        );

        $_data['searchParams'] = elements($_getParams_standard, $this->input->get(null, true), '');

        // 获得分页
        $_data['pageParams'] = array(
            'ps' => 0,
            'Page' => $_data['searchParams']['p'],
        );
        //处理表单提交
        if ($_POST) {
            if ($_data['act'] == 'del') {
                $checkid = @$_POST['checked'];
                if ($checkid == null) {
                    $checkid = '0';
                }
                $this->adminUsers_model->del($checkid);
                $_data['act'] = 'success';
                // redirect("http://" . $_SERVER["HTTP_HOST"] . $this->config_lib->admin_dir . "/adminUsers/List.html");
                // $this->load->view($this->config_lib->admin_dir . "/adminUsers/List.html", $_data);
            }
        }

        $_list = $this->adminUsers_model->select_list($_data['searchParams'], $_data['pageParams']);
        $_data['adminUsers'] = $_list['list'];
        $rc = $_list['rc'];

        // 分页字符串
        $opt = array(
            'base_url' => '?',
            'total_rows' => $rc,
            'per_page' => $_data['pageParams']['ps'],
            'num_links' => 5,
        );
        $opt = $this->functions_lib->admin_oranize_page_str($opt);
        $_data['page_str'] = $this->pagination->initialize($opt)->create_links();

        $body = $this->load->view($this->config_lib->admin_dir.'/adminUsers/List.html', $_data, true);

        // 弹出层插件
        $iframeLayer = $this->load->view($this->config_lib->admin_dir.'/comm/iframeLayer.html', null, true);
        $body = str_replace('{$iframeLayer}', $iframeLayer, $body);

        echo $body;
    }

    private function _Add()
    {
        $_data = $this->data;
        // 处理表单提交
        if ($_data['act'] == 'add') {

            // 验证表单数据方法
            //用户名
            $this->form_validation->set_rules('auser', '',
                array(
                    'trim',
                    'required',
                    array('_length', array($this->functions_lib, 'check_length'), array('bytes_count' => '20')),
                ),
                array(
                    'required' => '请键入用户名',
                    '_length' => '用户名不能超过10个汉字（20个字符）',
                )
            );

            $Auid1 = $this->db->query("select Auid from zhuofu2016_adminusers where Auser='".@$_POST['auser']."'")->result();
            if (count($Auid1) != 0) {
                $this->form_validation->set_rules('Auid1', '',
                    array(
                        'required',
                    ),
                    array(
                        'required' => '此用户名已存在',
                    )
                );
            }

            // 密码
            $this->form_validation->set_rules('passwd', '',
                array(
                    'trim',
                    'required',
                    // "matches[$repwd]",
                ),
                array(
                    'required' => '请键入密码',
                    // "matches[$repwd]" => "两次输入密码不一致",
                )
            );

            //重复密码
            $this->form_validation->set_rules('rePasswd', '',
                array(
                    'trim',
                    'required',
                    // "matches[$pwd]",//不会用。。。。
                ),
                array(
                    'required' => '请键入重复密码',
                    // "matches[$pwd]" => "两次输入密码不一致",
                )
            );

            //验证密码一致
            if (@$_POST['passwd'] != @$_POST['rePasswd']) {
                $this->form_validation->set_rules('pwd', '',
                    array(
                        'required',
                    ),
                    array(
                        'required' => '两次输入密码不一致',
                    )
                );
            }
            // 所属级别
            $aulid = @$_POST['aulid'];
            if ($aulid == 0) {
                $this->form_validation->set_rules('Aulid', '',
                    array(
                        'required',
                    ),
                    array(
                        'required' => '请选择所属级别',
                    )
                );
            }

            if ($this->form_validation->run()) {
                // 接收表单
                $auser = $this->input->post('auser', true);
                $passwd = $this->input->post('passwd', true);
                $aulid = $this->input->post('aulid', true);
                $passwd = hash('md5', $this->functions_lib->Convert($passwd, 'string'));
                $passwd = $this->functions_lib->encrypt_md5($passwd, str_replace('/', '', $this->config_lib->admin_dir));
                $params = array(
                    'Auser' => $auser,
                    'Passwd' => $passwd,
                    'Aulid' => $aulid,
                );
                // 添加记录
                $this->load->model('AdminUsers_model');
                $Auid = $this->AdminUsers_model->add($params);

                // 完成添加
                $_data['act'] = 'success';
            }
        }

        if ($_data['act'] == 'success') {
            $this->load->view($this->config_lib->admin_dir.'/adminUsers/Add.html', $_data);
        } else {
            $this->load->view($this->config_lib->admin_dir.'/adminUsers/Add.html', $_data);
        }
    }

    private function _Modify()
    {
        $_data = $this->data;
        if ($_data['act'] == 'modify') {
            // 验证表单数据方法
            //用户名
            $this->form_validation->set_rules('auser', '',
                array(
                    'trim',
                    'required',
                    array('_length', array($this->functions_lib, 'check_length'), array('bytes_count' => '20')),
                ),
                array(
                    'required' => '请键入用户名',
                    '_length' => '用户名不能超过10个汉字（20个字符）',
                )
            );

            // 密码
            $this->form_validation->set_rules('passwd', '',
                array(
                    'trim',
                    'required',
                    // "matches[$repwd]",
                ),
                array(
                    'required' => '请键入密码',
                    // "matches[$repwd]" => "两次输入密码不一致",
                )
            );

            //重复密码
            $this->form_validation->set_rules('rePasswd', '',
                array(
                    'trim',
                    'required',
                    // "matches[$pwd]",//不会用。。。。
                ),
                array(
                    'required' => '请键入重复密码',
                    // "matches[$pwd]" => "两次输入密码不一致",
                )
            );

            //验证密码一致
            if (@$_POST['passwd'] != @$_POST['rePasswd']) {
                $this->form_validation->set_rules('pwd', '',
                    array(
                        'required',
                    ),
                    array(
                        'required' => '两次输入密码不一致',
                    )
                );
            }
            // 所属级别
            $aulid = @$_POST['aulid'];
            if ($aulid == 0) {
                $this->form_validation->set_rules('Aulid', '',
                    array(
                        'required',
                    ),
                    array(
                        'required' => '请选择所属级别',
                    )
                );
            }

            if ($this->form_validation->run()) {
                // 接收表单
                $auser = $this->input->post('auser', true);
                $passwd = $this->input->post('passwd', true);
                $aulid = $this->input->post('aulid', true);
                $passwd = hash('md5', $this->functions_lib->Convert($passwd, 'string'));
                $passwd = $this->functions_lib->encrypt_md5($passwd, str_replace('/', '', $this->config_lib->admin_dir));
                $params = array(
                    'Auid' => $_data['Auid'],
                    'Auser' => $auser,
                    'Passwd' => $passwd,
                    'Aulid' => $aulid,
                );
                // 修改记录
                $this->load->model('AdminUsers_model');
                $Auid = $this->AdminUsers_model->modify($params);

                // 完成修改
                $_data['act'] = 'success';
            }
        }
        if ($_data['act'] == 'success') {
            $this->load->view($this->config_lib->admin_dir.'/adminUsers/Modify.html', $_data);
        } else {
            $_data['searchParams'] = array('Auid' => $_data['Auid']);
            $_data['adminUsers'] = $this->adminUsers_model->select($_data['searchParams']);
            $this->load->view($this->config_lib->admin_dir.'/adminUsers/Modify.html', $_data);
        }
    }

    private function _Alive()
    {
        $_data = $this->data;
        $this->adminUsers_model->alive($_data['Auid']);
        $this->_list();
    }
}
