<?php
// 获取所有友链及其信息
$result=@$db->query("SELECT * FROM `mxgapi_friendlinks`;")->fetch_all(MYSQLI_ASSOC);

// 载入友链页面
require_once __TEMPLATE_DIR__.'/Home/friendlinks.html';