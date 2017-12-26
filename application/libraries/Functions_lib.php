<?php
/* 
    @ 胡浴东
    @ 20171226
*/
class Functions_lib {

	/*
		@ 高京
		@ 2016-06-07
		@ 判断字符串长度是否在预期长度内（表单验证用），一个汉字=两个字节。返回TRUE/FALSE
		@ str：字符串
		@ params=array(
			bytes_count：预期字节长度。默认：0
		)
	*/
	public function check_length($str, $params = array(0)) {
		$params["bytes_count"] = $this->Convert($params["bytes_count"], "int");
		if (mb_strwidth($str) > $params["bytes_count"]) {
			return false;
		} else {
			return true;
		}
	}

	/*
		 @ 高京
		 @ 2016-06-02
		 @ 根据文件路径或文件名，获得文件扩展名。返回扩展名字符串
		 @ FilePath：文件路径或文件名
	*/
	public function GetExtension($FilePath) {
		$exts = explode(".", $FilePath);
		$ext = "";
		@$ext = $exts[count($exts) - 1];
		return $ext;
	}

	/*
		 @ 高京
		 @ 2016-06-02
		 @ 对字符串进行数字型判断（支持逗号分隔的多值字符串），返回过滤后的字符串（非数字值被过滤）
		 @ str：逗号分隔的主键字符串
	*/
	public function filterNoNum($str) {
		$arr = explode(",", $str);
		$i = 0;
		$len = count($arr);
		$return = "";
		for (; $i < $len; $i++) {

			if (is_numeric($arr[$i])) {
				if ($i > 0) {
					$return .= ",";
				}
				$return .= $arr[$i];
			}

		}
		return $return;
	}

	/*
		 @ 高京
		 @ 2016-06-02
		 @ 自动获得地址栏参数集，并拼接返回为地址栏字符串：a=1&b=2&c=3
		 @ Filter_Para：过滤掉的参数名（键），多个用|分隔，区分大小写。默认""
	*/
	public function transParameters($Filter_Para = "") {
		$query = $_GET;
		$filter = explode("|", $Filter_Para);
		$return = "";
		foreach ($query as $key => $value) {
			if (!in_array($key, $filter)) {
				if ($return != "") {
					$return .= "&";
				}

				if (is_array($value)) {
					$value = join(',', $value);
				}

				$return .= $key . "=" . $value;
			}
		}
		return $return;
	}

	/*
		@ 高京
		@ 2016-06-02
		@ 遮盖姓名。返回新字符串：高*、马*君、G*****g
		@ str：原字符串
		@ mask：遮盖字符。默认"*"
	*/
	public function NameMask($str, $mask = "*") {
		$i = 0;
		$c = mb_strlen($str);

		if ($c < 2) {
			return $str;
		}

		$_str = mb_substr($str, 0, 1);
		if ($c == 2) {
			return $_str . $mask;
		}

		for (; $i < $c - 2; $i++) {
			$_str .= $mask;
		}
		return $_str . mb_substr($str, $c - 1);
	}

	/*
		@ 高京
		@ 2016-06-02
		@ 遮盖手机号。返回新字符串：155****5957
		@ str：手机字符串，不支持汉字
		@ length：遮盖长度。默认：4
		@ mask：遮盖字符。默认"*"
	*/
	public function MobileMask($str, $length = 4, $mask = "*") {
		$i = 0;
		$c = strlen($str);

		if ($c < $length + 2) {
			return $str;
		}

		$start = ($c - $length) / 2; // 遮罩起始字符位置

		$_str = substr($str, 0, $start);

		for (; $i < $length; $i++) {
			$_str .= $mask;
		}

		return $_str . mb_substr($str, $start + $length);
	}

	/*
		@ 高京
		@ 2016-06-02
		@ 根据日期和随机数生成文件名，并确保文件不存在。返回文件名（无目录）：20160602_345123.jpg
		@ dir：存放文件的目录（用于验证新生成文件名是否已存在）。如：/application/images/
		@ ext：文件后缀。默认：".jpg"
		@ rnd_length：随机数长度。默认：6
		@ separator：日期和随机数之间的分隔符。默认：""
	*/
	public function createFileName($dir, $ext = ".jpg", $rnd_length = 6, $separator = "") {
		$CI = &get_instance(); // 初始化CI对象
		$CI->load->helper("string");

		// 获得随机数
		$rnd_str = random_string("numeric", $rnd_length);

		// 获得日期字符串
		$dt_str = date("Ymd");

		// 拼接为文件名
		$filename = $dt_str . $separator . $rnd_str . $ext;

		// 验证文件是否存在
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . $dir . $filename)) {
			$filename = createFileName($dir, $rnd_length, $separator);
		}

		return $filename;

	}

	/*
		@ 高京
		@ 2016-06-02
		@ 类型转换
		@ para：源数据
		@ type：目标类型。"int | string | bool | float | double"。默认："int"
	*/
	public function Convert($para = "", $type = "int") {
		$type = strtolower($type);
		$return;
		switch ($type) {
			case "int":
			case "float":
			case "double":
			default:
				$return = 0;
				break;
			case "string":
				$return = "";
				break;
			case "bool":
				$return = true;
				break;
		}
		eval("\$return=($type)\$para;");

		return $return;
	}

	/*
		@ 高京
		@ 2016-06-02
		@ 对MD5加密的字符串进行二次加密
		@ md5_str：MD5字符串（32位或16位）
		@ key_str：16位密钥
	*/
	public function encrypt_md5($md5_str, $key_str) {

		// 验证密钥长度
		if (strlen($key_str) != 16) {
			return "";
		}

		// 根据MD5长度分别做处理
		$return = "";
		$md5_str_len = strlen($md5_str);
		if ($md5_str_len == 32) {
			$return = substr($md5_str, 0, 8);
			$return .= $key_str;
			$return .= substr($md5_str, 24);
		} else if ($md5_str_len == 16) {
			$return = substr($md5_str, 0, 5);
			$return .= substr($key_str, 5, 6);
			$return .= substr($md5_str, 11);
		}

		return $return;

	}

	/*
		@ 高京
		@ 2016-06-03
		@ db->limit组建
		@ pages：分页参数数组
	*/
	public function Organize_limit($pages) {
		$CI = &get_instance();
		if ($pages["ps"] != "") {
			if ($pages["Page"] == "") {
				$pages["Page"] = "0";
			}

			$CI->db->limit($pages["ps"], $pages["Page"]);
		}
	}

	/*
		@ 高京
		@ 2016-06-17
		@ 组织分页参数-管理平台。返回组织过的array()
		@ $opt：空array或已有部分参数的分页参数集对象
	*/
	public function admin_oranize_page_str($opt = array()) {
		$opt["page_query_string"] = true;
		$opt["query_string_segment"] = "p";
		$opt["reuse_query_string"] = true;
		$opt["full_tag_open"] = "<div id='page_str_div'>";
		$opt["full_tag_close"] = "</div>";
		return $opt;
	}

	/*
		@ 高京
		@ 2016-06-16
		@ 将数组中指定项的数组拼接为逗号分隔或><分隔字符串，并验证每项均为数字类型
		@ arr: 数组
		@ params: 需要拼接的key
		@ kind: 1-逗号分隔（默认） 2-><分隔
		@ need_filter: true-需要进行数字型判断（默认） false-不需要
	*/
	public function joinArrayAndFilter($arr, $params = array(), $kind = 1, $need_filter = true) {
		$i = 0;
		$len = count($params);
		for (; $i < $len; $i++) {
			$_key = $params[$i];
			if (is_array($arr[$_key])) {
				$_temp = join(",", $arr[$_key]);
				if ($need_filter) {
					$_temp = $this->filterNoNum($_temp);
				}
				if ($kind == 2) {
					$_temp = "<" . str_replace(",", "><", $_temp) . ">";
				}
				$arr[$_key] = $_temp;
			}
		}
		return $arr;
	}
}