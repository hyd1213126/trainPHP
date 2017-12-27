<?php
/**
 *刘超
 *2016-11-23
 * 接口签名验证
 */
class Handler_lib
{
    /**
     * 刘超
     * 2016-11-24
     * 验证签名.
     *
     * @method ValidSign
     *
     * @param [string] $signature [签名]
     * @param [string] $non_str   [随机数]
     * @param [string] $stamp     [时间戳]
     * @param [array]  $params    [参数数组]
     */
    public function ValidSign($signature, $non_str, $stamp, $params)
    {
        //参数验证
        //签名为空
        if ($signature == '' || $signature == null) {
            return '20001';
        }
        //随机数为空
        elseif ($non_str == '' || $non_str == null) {
            return '20002';
        }
        //时间戳为空
        elseif ($stamp == '' || $stamp == null) {
            return '20003';
        }
        //随机数长度32位验证
        elseif (strlen($non_str) != 32) {
            return '20004';
        }
        //重新获取签名字符串
        $new_signature = $this->_getSign_str($non_str, $stamp, $params);

        //新生成的签名和签名对比
        if (strtoupper($new_signature) == strtoupper($signature)) {
            return 'SUCCESS';
        } else {
            return '30001';//签名验证失败
        }
    }

    /**
     * 刘超
     * 2016-11-24
     * 生成签名.
     *
     * @method BuildSigned
     *
     * @param [array] $params [参数]
     */
    public function BuildSigned($params, $non_str = '', $stamp = '')
    {
        $CI = &get_instance(); // 初始化CI对象
        if ($non_str == '') {
            //生成一个随机数
            $CI->load->helper('string');
            $non_str = random_string('alnum', 32);//含有大小写字母的及数字的32位随机数
        }
        if ($stamp == '') {
            //生成一个时间戳
            $CI->load->helper('date');
            $stamp = human_to_unix(unix_to_human(time()));
        }
        //获取签名字符串
        $signature = $this->_getSign_str($non_str, $stamp, $params);

        return array('non_str' => $non_str, 'stamp' => $stamp, 'signature' => $signature);
    }

    /**
     *  刘超
     * 2016-11-24
     * 获取签名字符串.
     *
     * @method getSign_str
     *
     * @param [string] $non_str [随机数]
     * @param [string] $stamp   [时间戳]
     * @param [array]  $params  [查询参数数组]
     *
     * @return [string] [签名]
     */
    private function _getSign_str($non_str, $stamp, $params)
    {
        //生成签名时的密钥，所在signature_secret.sec文件位置
        $signature_secret_path_local = 'd:\\abc.txt';//本地测试使用路径
        $signature_secret_path_virtual = ''; //虚拟主机使用相对路径
        $signature_secret_path = 'd:\\signature_secret.sec';//服务器使用物理路径

        //判断密钥文件是否存在
        if (file_exists($signature_secret_path_local)) {
            $KeyAddress = $signature_secret_path_local;//本地测试
        } elseif (file_exists($signature_secret_path_virtual)) {
            $KeyAddress = $signature_secret_path_virtual;//虚拟主机
        } elseif (file_exists($signature_secret_path)) {
            $KeyAddress = $signature_secret_path;//服务器
        } else {
            return '10001';//密钥文件不存在
        }
        //获得密钥
        $keySecret = file_get_contents($KeyAddress);
        //密钥和随机数混插 获得新的密钥
        $new_keySecret = '';
        if (strlen($keySecret) == 32) {
            for ($i = 0; $i < 32; ++$i) {
                $new_keySecret .= substr($non_str, $i, 1);
                $new_keySecret .= substr($keySecret, $i, 1);
            }
        } else {
            return '10002';//密钥长度不正确
        }
        //对加密数组进行字典排序
        foreach ($params as $key => $value) {
            $arr[$key] = $key;
        }
        sort($arr); //字典排序的作用就是防止因为参数顺序不一致而导致下面拼接加密不同

        //将Key和Value拼接
        $str = '';
        foreach ($arr as $k => $v) {
            $str = $str.$arr[$k].'='.$params[$v];
        }
        //结尾拼接随机数，时间戳，密钥 通过sha1加密并转化为大写获得签名(这里要跟node的拼接相同)
        $restr = $str.'non_str='.$non_str.'stamp='.$stamp.'keySecret='.$new_keySecret;
        $signature = strtoupper(sha1($restr));

        return $signature;
    }
}
