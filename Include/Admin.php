<?php
/* 设置时间地区 */
date_default_timezone_set("PRC");

/* 通过GET参数获取页面 */
$page = $_GET['page'];

/* 判断是否登录 */
if(!isAdmin() && $page != 'login'){
	jump('?action=admin&page=login');
}

if($page == 'edit'){
	// 获取ID
	$id=$_REQUEST["id"];
	if(!$id){
		include __DIR__.'/404.php';
		exit;
	}
}

/* 连接字符串构成文件 */
$file = __TEMPLATE_DIR__.'/Admin/'.$page.'.html';

/* 若有该页面，直接包含 */
if(file_exists($file)){
	include $file;
}else{
	include __TEMPLATE_DIR__.'/Admin/index.html';
}