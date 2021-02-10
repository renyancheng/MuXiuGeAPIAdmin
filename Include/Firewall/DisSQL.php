<?php
foreach ($_REQUEST as $key => $value) {
    $value = addslashes($value);
    $value = str_replace("%", "\%", $value); // 把' % '过滤掉    
    $value = nl2br($value); // 回车转换    
    $value = htmlspecialchars($value); // html标记转换
    $_REQUEST[$key] = $value;
}
foreach ($_GET as $key => $value) {
    $value = addslashes($value);
    $value = str_replace("%", "\%", $value); // 把' % '过滤掉    
    $value = nl2br($value); // 回车转换    
    $value = htmlspecialchars($value); // html标记转换
    $_GET[$key] = $value;
}
foreach ($_POST as $key => $value) {
    $value = addslashes($value);
    $value = str_replace("%", "\%", $value); // 把' % '过滤掉    
    $value = nl2br($value); // 回车转换    
    $value = htmlspecialchars($value); // html标记转换
    $_POST[$key] = $value;
}