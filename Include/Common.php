<?php
/* 
 * 应用公共文件
 */


/*
 * 抛出JSON格式错误码
 * @param int $code 状态码
 * @param string $msg 状态信息
 */
function jsonError($code, $msg)
{
	die(json_encode(array(
		'code' => $code,
		'msg' => $msg
	), 320 | JSON_PRETTY_PRINT));
}

/*
 * 数组转JSON
 * @param int $code 状态码
 * @param string $msg 状态信息
 * @param string/array $data 数据
 */
function json($code, $msg, $data)
{
	die(json_encode(array(
		'code' => $code,
		'msg' => $msg,
		'data' => $data
	), 320 | JSON_PRETTY_PRINT));
}

/*
 * 判断用户是否登录到后台
 *	@return bool 是否登录
 */
function isAdmin()
{
	session_start();
	$apikey = @include __DIR__ . '/../Core/Config/apikey.php';
	if ($_SESSION['login'] == 'admin') {
		return true;
	} else if($_GET['apikey'] != '' && $_GET['apikey'] == $apikey) {
		return true;
	} else {
		return false;
	}
}

/*
 * 清除登录session（退出登录）
 *	@return bool 清除是否成功
 */
function clearAdmin()
{
	session_start();
	if ($_SESSION['login'] == 'admin') {
		unset($_SESSION['login']);
		return true;
	} else {
		return false;
	}
}

/*
 * 跳转并带有提示信息
 * @param string $msg 提示信息
 * @param string $url 跳转链接
 */
function alert($msg, $url)
{
	$alert = '<script>';
	$alert .= 'alert("' . $msg . '");';
	$alert .= 'window.location.href="' . $url . '";';
	$alert .= '</script>';
	die($alert);
}

/*
 * 跳转到某一链接
 * @param string  $url 链接
 */
function jump($url)
{
	header('Location: ' . $url);
}

/*
 * 添加访问记录
 * @return bool 添加是否成功
 */
function addAccess()
{
	require __CORE_DIR__ . '/Database/connect.php';

	$host = $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . '?' . $_SERVER['QUERY_STRING'];
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	$protocol = $_SERVER["SERVER_PROTOCOL"];
	$method = $_SERVER["REQUEST_METHOD"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$time = $_SERVER["REQUEST_TIME"];
	$result = $db->query("INSERT INTO `mxgapi_access`(`id`, `host`, `user_agent`, `protocol`, `method`, `ip`, `time`) VALUES (NULL,'{$host}','{$user_agent}','{$protocol}','{$method}','{$ip}','{$time}');");
	if ($result) {
		return true;
	} else {
		return false;
	}
}

/*
 * 检查字符串是否为邮箱
 * @param string $email
 * @return bool
 */
function checkEmail($email)
{
	$result = trim($email);
	if (filter_var($result, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

/*
 * 发送邮件方法
 * @param $to：接收者 $title：标题 $content：邮件内容
 * @return bool true:发送成功 false:发送失败
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $title, $content)
{
	require '../Core/Database/connect.php';
	require '../Include/PHPMailer/Exception.php';
	require '../Include/PHPMailer/PHPMailer.php';
	require '../Include/PHPMailer/SMTP.php';
	$sql = "SELECT title,smtp_host,smtp_username,smtp_password,smtp_port,smtp_secure FROM `mxgapi_config`;";
	$result = $db->query($sql);
	if ($result = $result->fetch_assoc()) {
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = $result['smtp_host'];
		$mail->Username = $result['smtp_username'];
		$mail->Password = $result['smtp_password'];
		$mail->SMTPSecure = $result['smtp_secure'];
		$mail->Port = $result['smtp_port'];
		$mail->setFrom($result['smtp_username'], $result['title']);
		$mail->addAddress($to);
		$mail->WordWrap = 50;
		$mail->IsHTML(true);
		$mail->CharSet = "utf-8";
		$mail->Subject = $title;
		$mail->Body = '
        	<div style="background-color:#448AFF;color:#448AFF;padding:15px;">
        		<p style="font-weight:bold;color:#fff;font-size:20px;text-align:center;">' . addslashes($result['title']) . '</p>
        	</div>
        	<div style="background-color:#fff;padding:10px;border:2px solid #448AFF;">
        		<p style="color:#000;font-size:15px;">' . $content . '</p>
        		<p style="color:#000;font-size:15px;text-align:center;">' . date('Y-m-d') . '&nbsp;' . date('h:i:s') . '</p>
        	</div>
        ';
		if (!$mail->send()) {
			$code = -1;
			$msg = '发送失败: ' . $mail->ErrorInfo;
		} else {
			$code = 0;
			$msg = '发送成功';
		}
	} else {
		$code = -1;
		$msg = '获取邮件配置信息时发生错误';
	}
	$output = json_encode(array(
		'code' => $code,
		'msg' => $msg
	), 320);
	return $output;
}

/*
 * 检测域名是否授权
 * @param $domain string 域名
 * @return bool true授权，false未授权
 */
function domainAuth($domain)
{
    return true;
}

/* 
 * 添加接口统计函数
 * @param int $id 接口id
 * @return bool 添加成功为true，失败则为false
 */
function addApiAccess($id)
{
	require __CORE_DIR__ . '/Database/connect.php';
	if (intval($id)) {
		$get_access = $db->query("SELECT access FROM `mxgapi_api` WHERE `id` = '{$id}';");
		if ($get_access) {
			$get_access = $get_access->fetch_assoc();
			$update_access = $get_access['access'] + 1;
			$update_result = $db->query("UPDATE `mxgapi_api` SET `access` = '{$update_access}' WHERE `id` = '{$id}';");
			if ($update_result) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/*
 * 获取用户的真实ip
 * @return string 用户IP
 */
function getUserIp()
{
	$ip = false;
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) {
			array_unshift($ips, $ip);
			$ip = FALSE;
		}
		for ($i = 0; $i < count($ips); $i++) {
			if (!mb_eregi("^(10│172.16│192.168).", $ips[$i])) {
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/*
 * 通过ua判断是否为搜索引擎蜘蛛
 * @name is_spider()
 * @return bool
 */
function is_spider()
{
	require __CORE_DIR__ . '/Database/connect.php';
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (!empty($agent)) {
		$spiderSite = array(
			"TencentTraveler",
			"Baiduspider+",
			"BaiduGame",
			"Googlebot",
			"msnbot",
			"Sosospider+",
			"Sogou web spider",
			"ia_archiver",
			"Yahoo! Slurp",
			"YoudaoBot",
			"Yahoo Slurp",
			"MSNBot",
			"Java (Often spam bot)",
			"BaiDuSpider",
			"Voila",
			"Yandex bot",
			"BSpider",
			"twiceler",
			"Sogou Spider",
			"Speedy Spider",
			"Google AdSense",
			"Heritrix",
			"Python-urllib",
			"Alexa (IA Archiver)",
			"Ask",
			"Exabot",
			"Custo",
			"OutfoxBot/YodaoBot",
			"yacy",
			"SurveyBot",
			"legs",
			"lwp-trivial",
			"Nutch",
			"StackRambler",
			"The web archive (IA Archiver)",
			"Perl tool",
			"MJ12bot",
			"Netcraft",
			"MSIECrawler",
			"WGet tools",
			"larbin",
			"Fish search",
		);
		foreach ($spiderSite as $val) {
			$str = strtolower($val);
			if (strpos($agent, $str) !== false) {
				$sql = "INSERT INTO `mxgapi_spider` (`id`, `agent`, `ip`, `time`) VALUES (NULL, '{$str}', '" . getUserIp() . "', '" . time() . "');";
				$result = $db->query($sql);
				if ($result) {
					return true;
				}
				return false;
			}
		}
		return false;
	} else {
		return false;
	}
}

/*
 * 封装Curl请求
 * @param string $url 地址
 * @param string $method 方法
 * @param array $headers 头
 * @param array $params 参数
 */
function curl($url, $method, $headers, $params)
{
	if (is_array($params)) {
		$requestString = http_build_query($params);
	} else {
		$requestString = $params ?: '';
	}
	if (empty($headers)) {
		$headers = array('Content-type: text/json');
	} elseif (!is_array($headers)) {
		parse_str($headers, $headers);
	}
	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	// setting the POST FIELD to curl
	switch ($method) {
		case "GET":
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			break;
		case "POST":
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
			break;
		case "DELETE":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
			break;
	}
	// getting response from server
	$response = curl_exec($ch);

	//close the connection
	curl_close($ch);

	//return the response
	if (stristr($response, 'HTTP 404') || $response == '') {
		return array('Error' => '请求错误');
	}
	return $response;
}
