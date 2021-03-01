<?php
/* 连接数据库 */
require '../Core/Database/connect.php';

/* 引入公共函数文件 */
require '../Include/Common.php';

/* 设置时间地区 */
date_default_timezone_set("PRC");

/* 设置PHP报错等级 */
error_reporting(E_ERROR);

/* 开始一个SESSION回话 */
session_start();