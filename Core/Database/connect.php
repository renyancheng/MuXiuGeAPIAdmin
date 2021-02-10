<?php
/*数据库配置*/
$host = "localhost";
$username = "root";
$password = "root";
$dbname = "APItest";
// 创建连接
@$db = new mysqli($host, $username, $password, $dbname);
// 检测连接
if ($db->connect_error) {
	die("连接失败: ".$db->connect_error);
}else{
	$db->query("set names utf8"); 
}
?>