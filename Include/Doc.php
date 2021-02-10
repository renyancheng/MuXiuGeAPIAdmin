<?php

// 获取ID
$id=intval($_REQUEST["id"]);

if(!$id){
	include __DIR__.'/404.php';
	exit;
}else{
	addApiAccess($id);
	// 根据ID查询接口是否是正常
	$result = $db->query("SELECT name,status FROM `mxgapi_api` WHERE `id`='{$id}';")->fetch_assoc();
	$status = $result['status'];
	if($status == '0'){
		include __DIR__.'/error.php';
		exit;
	}
}



// 载入文档页面
include __TEMPLATE_DIR__.'/Home/doc.html';