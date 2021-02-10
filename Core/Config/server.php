<?php
return array(
	'php_version' => PHP_VERSION,
	'php_uname' => PHP_OS,
	'server_software' => $_SERVER['SERVER_SOFTWARE'],
	'upload_max_filesize' => get_cfg_var("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许上传附件",
	'max_execution_time' => get_cfg_var("max_execution_time")."秒 ",
	'memory_limit' => get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无"
);