<?php
require "./function.php"; // 引入函数文件
addApiAccess(1); // 调用统计函数

echo curl("http://ai.kenaisq.top/API/yiyan.php", "GET", 0, 0);