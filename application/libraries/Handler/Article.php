<?php

/**
 * 刘超
 * 2016-11-28
 * Article接口.
 */
class Article
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('Article_model');
        $this->CI->load->library(array('Functions_lib', 'Handler_lib'));
    }
    //重载方法   为了避免当调用的方法不存在时产生错误，可以使用 __call() 方法来避免（主要是接口用）
    public function __call($function_name, array $arguments)
    {
        $retjson['error'] = '47004';
        $retjson['errmsg'] = '没有对应方法';

        return $retjson;
    }
    public function select($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库查询
        $_list = $this->CI->Article_model->select($para['params'], $para['pages']);
        $result['list'] = $_list['list'];
        $rc = $_list['rc'];

        // 分页
        $opt = array(
            'base_url' => '?',
            'total_rows' => $rc,
            'per_page' => $para['pages']['ps'],
            'num_links' => 10,
        );
        $opt = $this->CI->functions_lib->admin_oranize_page_str($opt);
        $this->CI->load->library('pagination');
        $result['page'] = $this->CI->pagination->initialize($opt)->create_links();

        return $result;
    }

    public function add($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库添加
        $result['result']['Aid'] = $this->CI->Article_model->add($para['params'])[0]->Aid;

        return $result;
    }

    public function modify($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库修改
        $this->CI->Article_model->modify($para['params']);

        return $result;
    }

    public function alive($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库修改
        $this->CI->Article_model->alive($para['params']['Aid']);

        return $result;
    }

    public function del($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库删除
        $this->CI->Article_model->del($para['params']);

        return $result;
    }

    public function layer($para)
    {
        //验证签名
        $sign = $this->CI->handler_lib->ValidSign($para['sign_valid']['signature'], $para['sign_valid']['non_str'], $para['sign_valid']['stamp'], $para['params']);
        $result['error'] = $sign;
        if ($sign != 'SUCCESS') {
            return $result;
        }
        //去数据库删除
        $this->CI->Article_model->layer($para['params']);

        return $result;
    }
}
