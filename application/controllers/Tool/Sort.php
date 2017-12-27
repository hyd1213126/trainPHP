<?php

/**
 * 刘超
 * 2016-12-01
 * 接口排序小工具.
 */
class Sort extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        $this->data['act'] = $this->functions_lib->Convert(@$_POST['act'], 'string');
    }
    public function _remap()
    {
        $this->_sort();
    }

    private function _sort()
    {
        $_data = $this->data;
        if ($_POST) {
            if ($_data['act'] == 'Sort') {
                $_data['Sort_in'] = @$_POST['Sort_in'];
                //替换textarea的换行符，并分割成数组
                $arr = explode('|',  str_replace("\n", '|', $_data['Sort_in']));
                //替换数组中的相关字符
                $arr = str_replace(':', '', str_replace('“', '', str_replace('"', '', str_replace('，', '', str_replace(',', '', str_replace("'", '', $arr))))));
                foreach ($arr as $key => $value) {
                    $params[$key] = trim($value);//去掉空格
                }
                // 排序
                sort($params);
                //输出排序后的格式
                $count = count($params);
                $_data['Sort_out'] = '';
                for ($i = 0; $i < $count; ++$i) {
                    if ($i > 0) {
                        $_data['Sort_out'] .= ",\n";
                    }
                    $_data['Sort_out'] .= '"'.$params[$i].'"'.':""';
                }
            }
        }
        $this->load->view('/Tool/Sort.html', $_data);
    }
}
