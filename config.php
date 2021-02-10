<?php
// 载入目录配置文件
require_once __DIR__.'/config.inc.php';
// 载入公共函数文件
require_once __INCLUDE_DIR__.'/Common.php';

if(file_exists(__CORE_DIR__.'/install.lock')){
	if(file_exists(__CORE_DIR__.'/Database/connect.php')){
		// 连接数据库
		require_once __CORE_DIR__.'/Database/connect.php';
		
		$server = include __CORE_DIR__.'/Config/server.php';
		
		$websetting = include __CORE_DIR__.'/Config/webSet.php';
		// $set_time = $data['set_time'];
		
		//网站配置信息
		$config = array_merge($server, $websetting);
	}
}