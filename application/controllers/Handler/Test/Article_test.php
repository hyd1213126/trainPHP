<?php
/*
@刘超
@2016-06-17
@Article接口
 */
class Article_test extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Article_model'));
        $this->load->library(array('Handler_lib', 'Handler/Article'));
        //是否是提交事件
        $this->data['submiting'] = $this->functions_lib->Convert(@$_POST['submiting'], 'int');
        //验证签名用的随机数
        $this->data['non_str'] = $this->functions_lib->Convert(@$_POST['non_str'], 'string');
        //验证签名用的时间戳
        $this->data['stamp'] = $this->functions_lib->Convert(@$_POST['stamp'], 'string');
        //验证签名用的来源
        $this->data['source'] = $this->functions_lib->Convert(@$_POST['source'], 'string');
        //加密签名
        $this->data['signature'] = $this->functions_lib->Convert(@$_POST['signature'], 'string');
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
        case 'List':
        default:
            $this->_List();
            break;
        case 'add':
            $this->_Add();
            break;
        case 'modify':
            $this->_Modify();
            break;
        case 'alive':
            $this->_Alive();
            break;
        case 'del':
            $this->_Del();
            break;
        case 'layer':
            $this->_Layer();
            break;
        }
    }

    private function _List()
    {
        $_data = $this->data;
        $_data['act'] = 'select';
        //查询参数标准数组
        $_data['column'] = array('Order', 'Aid', 'Keywords', 'Alive', 'd1', 'd2', 'Kind', 'Trade_id', 'Tags_id', 'New', 'Status', 'Recommend');
        //分页参数标准数组
        $_data['pagecolumn'] = array('ps', 'Page');
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的查询数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');
            $_data['pageParams'] = elements($_data['pagecolumn'], $this->input->post(null, true), '0');
            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }
            //组织一个数组
            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params'], 'pages' => $_data['pageParams']);
            $_data['jsonList'] = json_encode($this->article->select($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }

    private function _Add()
    {
        $_data = $this->data;
        $_data['act'] = 'add';
        //添加参数标准数组
        $_data['column'] = array(
            'Atitle',
            'Url',
            'Kind',
            'Ainfo',
            'Pic1',
            'Pic2',
            'Summary',
            'Atime',
            'ieTitle',
            'seoKeywords',
            'seoDescription',
            'SubTitle',
            'Trade_id',
            'Area_id',
            'Tags_id',
            'Source',
            'Person',
            'Status',
            'Recommend',
        );
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的添加数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');

            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }
            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params']);
            $_data['jsonList'] = json_encode($this->article->add($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }

    private function _Modify()
    {
        $_data = $this->data;
        $_data['act'] = 'modify';
        //修改参数标准数组
        $_data['column'] = array(
            'Aid',
            'Atitle',
            'Url',
            'Ainfo',
            'Pic1',
            'Pic2',
            'Summary',
            'Atime',
            'ieTitle',
            'seoKeywords',
            'seoDescription',
            'SubTitle',
            'Trade_id',
            'Area_id',
            'Tags_id',
            'Source',
            'Person',
        );
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的修改数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');

            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }
            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params']);
            $_data['jsonList'] = json_encode($this->article->modify($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }

    private function _Alive()
    {
        $_data = $this->data;
        $_data['act'] = 'alive';

        //修改屏蔽状态参数标准数组
        $_data['column'] = array(
            'Aid',
        );
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的修改数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');

            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }
            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params']);
            $_data['jsonList'] = json_encode($this->article->alive($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }

    private function _Del()
    {
        $_data = $this->data;
        $_data['act'] = 'del';

        //删除参数标准数组
        $_data['column'] = array(
            'Aid',
        );
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的修改数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');

            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }
            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params']);
            $_data['jsonList'] = json_encode($this->article->del($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }

    private function _Layer()
    {
        $_data = $this->data;
        $_data['act'] = 'layer';

        //参数标准数组
        $_data['column'] = array(
            'Aid',
            'Layer',
        );
        //处理数据提交
        if ($_data['submiting'] == 1) {
            //接收提交的修改数据
            //这里过滤一下参数是为了签名用 签名需要的参数 不包括submiting什么的。。。
            $_data['Params'] = elements($_data['column'], $this->input->post(null, true), '');

            //关于签名验证
            if ($_data['signature'] == '' || $_data['non_str'] == '' || $_data['stamp'] == '') {
                if ($_data['source'] == '') {
                    $_data['source'] = 'default';
                }
                $arr = $this->handler_lib->BuildSigned($_data['Params']);
                $_data['non_str'] = $arr['non_str'];
                $_data['stamp'] = $arr['stamp'];
                $_data['signature'] = $arr['signature'];
            }

            $para = array('sign_valid' => array('signature' => $_data['signature'], 'non_str' => $_data['non_str'], 'stamp' => $_data['stamp']), 'params' => $_data['Params']);
            $_data['jsonList'] = json_encode($this->article->layer($para));
        }

        $this->load->view('/Test_Handler/Article.html', $_data);
    }
}
