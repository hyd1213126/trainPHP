<?php

/**
 * 刘超
 * 固定广告位
 * 2016-11-11.
 */
class Advertise extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();

        //验证登录
        $this->load->model(array('adminUsers_model', 'Advertise_model'));
        $this->data['adminUsers'] = $this->adminUsers_model->checkLogin();
        //act
        $this->data['act'] = strtolower($this->functions_lib->Convert(@$_POST['act'], 'string'));
        //Aid
        $this->data['Aid'] = strtolower($this->functions_lib->Convert(@$_GET['id'], 'string'));

        $this->data['i'] = 0;
        // 图片
        $this->data['pic'] = array();
        for ($i = 0; $i < 1; ++$i) {
            $this->data['pic'][$i] = '';
        }
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
            // 验证表单数据
            // Pic
            for ($i = 0;$i < count($_data['pic']);++$i) {
                $this->form_validation->set_rules('imageFile.$i', '',
                    array(
                        'trim',
                    )
                );
            }
            //Url
            $this->form_validation->set_rules('Url', '',
                array(
                    'trim',
                    array('_length', array($this->functions_lib, 'check_length'), array('bytes_count' => '100')),
                ),
                array(
                    '_length' => '链接不能超过100个字符',
                )
            );
            //验证通过
            if ($this->form_validation->run()) {
                // 接收表单
                for ($j = 0;$j < count($_data['pic']);++$j) {
                    $data['pic'][$j] = $this->input->post('imageFile'.$j, true);
                }
                $Url = $this->input->post('Url', true);
                // stripos不区分大小写
                if (stripos($Url, 'http://') === false && stripos($Url, 'https://') === false) {
                    //=== 表示全相等，值相等并且类型也相同  stripos可能返回Int(0)  int(0)==false  为true，所以这里用全相等
                    $Url = 'http://'.$Url;
                }

                // 处理图片
                $_dir = '/upload_file/Advertise/';
                if (!file_exists('.'.$_dir)) {
                    mkdir('.'.$_dir);
                }
                $i = 0;
                $len = count($data['pic']);
                for (; $i < $len; ++$i) {
                    $pic = $this->input->post('imageFile'.$i, true);
                    if ($pic == '') {
                        continue;
                    }
                    $data['pic'][$i] = str_replace('/temp/', '/Advertise/', $pic);
                    if (file_exists($pic[$i])) {
                        rename('.'.$pic, '.'.$data['pic'][$i]);//移动图片
                    }
                }

                $params = array(
                'Aid' => $_data['Aid'],
                'Pic1' => $data['pic'][0],
                'Url' => $Url,
                );
                // 修改记录
                $Aid = $this->Advertise_model->modify($params);
                // 完成修改
                $_data['act'] = 'success';
            }
        }
        //赋初值
        $params = array('Aid' => $_data['Aid']);
        $_data['Advertise'] = $this->Advertise_model->select($params)['list'];

        if ($_data['act'] == 'success') {
            $this->load->view($this->config_lib->admin_dir.'/Advertise/Advertise.html', $_data);
        } else {
            $body = $this->load->view($this->config_lib->admin_dir.'/Advertise/Advertise.html', $_data, true);//true表示将页面作为字符串返回
            $iframeLayer = $this->load->view($this->config_lib->admin_dir.'/comm/iframeLayer.html', '', true);
            $body = str_replace('{$iframeLayer}', $iframeLayer, $body);//用$iframeLayer替换$body里的{$iframeLayer}
            echo $body;
        }
    }
}
