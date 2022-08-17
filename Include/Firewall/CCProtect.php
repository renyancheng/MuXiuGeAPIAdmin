<?php
// 开始一个SESSION会话
session_start();
// 获取当前时间戳
$timestamp = time();

// debug
// $_SESSION['cc_locktime'] = 0;

$cc_nowtime = $timestamp;
if (isset($_SESSION['cc_lasttime'])) {
	$cc_lasttime = $_SESSION['cc_lasttime'];
	$cc_times = $_SESSION['cc_times'] + 1;
	$_SESSION['cc_times'] = $cc_times;
} else {
	$cc_lasttime = $cc_nowtime;
	$cc_times = 1;
	$_SESSION['cc_times'] = $cc_times;
	$_SESSION['cc_lasttime'] = $cc_lasttime;
}

// 判断是否处于封禁时间
if ($_SESSION['cc_locktime'] >= $timestamp) {
	header('HTTP/1.0 444');
	exit;
}

// 判断时间间隔
if (($cc_nowtime - $cc_lasttime) < 30) {
	// 判断访问次数
	if ($cc_times >= 10) {
		// 达到访问限制，进行封锁，‘+‘后面为封禁时间，单位是秒
		$_SESSION['cc_locktime'] = $timestamp + 60;
		header('HTTP/1.0 444');
		exit;
	}
} else {
	$cc_times = 0;
	$_SESSION['cc_lasttime'] = $cc_nowtime;
	$_SESSION['cc_times'] = $cc_times;
}
