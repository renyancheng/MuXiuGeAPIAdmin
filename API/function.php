<?php
/*
 * 添加访问记录
 * @return bool 添加是否成功
 */
function addAccess()
{
	require __DIR__ . '/../Core/Database/connect.php';

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
 * 添加接口统计函数
 * @param int $id 接口id
 * @return bool 添加成功为true，失败则为false
 */
function addApiAccess($id)
{
	require __DIR__ . '/../Core/Database/connect.php';
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

//Curl请求，参数：地址，方法，头，参数
function curl($url, $method, $headers, $params){
    if (is_array($params)) {
        $requestString = http_build_query($params);
    } else {
        $requestString = $params ? : '';
    }
    if (empty($headers)) {
        $headers = array('Content-type: text/json'); 
    } elseif (!is_array($headers)) {
        parse_str($headers,$headers);
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
    switch ($method){  
        case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;  
        case "POST": curl_setopt($ch, CURLOPT_POST, 1);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
        case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
        case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
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