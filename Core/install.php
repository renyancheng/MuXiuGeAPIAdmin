<?php
require '../Include/Common.php';

/* 获取请求的安装步骤 */
$step = intval($_POST['step']);

/* 开始一个SESSION会话 */
session_start();

/* 获取SESSION中储存的安装步骤 */
$install_step = $_SESSION['install_step'];

if(file_exists('./Database/connect.php')){
	require './Database/connect.php';
}

/* 判断步骤 */
switch($step){
	case '1':
    	$host = $_POST["host"];
        $port = $_POST["port"];
        $dbname = $_POST["dbname"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        if($host && $dbname && $username){
        	$db = @new mysqli($host, $username, $password, $dbname);
        	if($db->connect_error){
        		alert("数据库连接失败：".$db->connect_error, '../?action=install&step=1');
        		jsonError(-1,"数据库连接失败：".$db->connect_error);
            }else{
            	$data='<?php
/*数据库配置*/
$host = "'.$host.'";
$username = "'.$username.'";
$password = "'.$password.'";
$dbname = "'.$dbname.'";
// 创建连接
@$db = new mysqli($host, $username, $password, $dbname);
// 检测连接
if ($db->connect_error) {
	die("连接失败: ".$db->connect_error);
}else{
	$db->query("set names utf8"); 
}
?>';
				unlink('./Database/connect.php');
            	file_put_contents('./Database/connect.php',$data);
            	$init_sql = array(
            		'SET FOREIGN_KEY_CHECKS=0;',
					'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";',
					'SET AUTOCOMMIT = 0;',
					'START TRANSACTION;',
					'SET time_zone = "+00:00";'
            	);
            	$create_sql = array(
            		"CREATE TABLE `mxgapi_access` ( `id` int(11) NOT NULL,`host` varchar(255) NOT NULL,`user_agent` varchar(255) NOT NULL,`protocol` varchar(255) NOT NULL,`method` varchar(255) NOT NULL,`ip` varchar(25) NOT NULL,`time` int(20) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;", 
            		"CREATE TABLE `mxgapi_api` (`id` int(11) NOT NULL,`name` varchar(200) NOT NULL,`enname` varchar(200) NOT NULL,`desc` text NOT NULL,`url` varchar(200) NOT NULL,`format` varchar(200) NOT NULL,`method` varchar(200) NOT NULL,`example_url` varchar(200) NOT NULL,`request_parameter` text NOT NULL,`return_parameter` text NOT NULL,`error_code` text NOT NULL,`PHP_example` text NOT NULL,`return` text NOT NULL,`time` int(20) NOT NULL,`access` int(20) NOT NULL DEFAULT '0',`status` int(10) NOT NULL DEFAULT '1') ENGINE=MyISAM DEFAULT CHARSET=utf8;",
            		"CREATE TABLE `mxgapi_config` (`title` varchar(255) NOT NULL,`subtitle` varchar(255) NOT NULL,`description` varchar(255) NOT NULL,`keywords` varchar(255) NOT NULL,`favicon` varchar(255) NOT NULL,`username` varchar(255) NOT NULL DEFAULT 'admin',`password` varchar(255) NOT NULL DEFAULT '123456',`email` varchar(20) NOT NULL,`qq` varchar(12) NOT NULL,`url` varchar(255) NOT NULL,`icp` varchar(255) NOT NULL,`copyright` varchar(255) NOT NULL,`theme` varchar(255) NOT NULL DEFAULT 'blue',`accent` varchar(255) NOT NULL DEFAULT 'blue',`qqqrcode` varchar(100) NOT NULL,`vxqrcode` varchar(100) NOT NULL,`aliqrcode` varchar(100) NOT NULL,`smtp_host` varchar(50) NOT NULL,`smtp_username` varchar(50) NOT NULL,`smtp_password` varchar(50) NOT NULL,`smtp_port` int(10) NOT NULL,`smtp_secure` varchar(50) NOT NULL,`smtp_reply_to` varchar(50) NOT NULL,`post_id` int(100) NOT NULL,`set_time` int(20) NOT NULL,`close_site` varchar(1) NOT NULL DEFAULT '0',`cc_protect` varchar(1) NOT NULL DEFAULT '0',`fire_wall` varchar(1) NOT NULL DEFAULT '0',`end_script` varchar(255) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
            		"CREATE TABLE `mxgapi_feedback` (`id` int(11) NOT NULL,`api_id` int(100) NOT NULL,`api_name` varchar(100) NOT NULL,`title` varchar(50) NOT NULL,`content` text NOT NULL,`email` varchar(50) NOT NULL,`ip` varchar(20) NOT NULL,`time` int(20) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
            		"CREATE TABLE `mxgapi_friendlinks` (`id` int(11) NOT NULL,`name` varchar(255) NOT NULL,`desc` varchar(255) NOT NULL,`url` varchar(255) NOT NULL,`picurl` varchar(255) NOT NULL,`time` text NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
            		"CREATE TABLE `mxgapi_login_log` (`id` int(11) NOT NULL,`ip` varchar(20) NOT NULL,`address` varchar(50) NOT NULL,`time` int(20) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
					"CREATE TABLE `mxgapi_post` (`id` int(11) NOT NULL,`title` varchar(30) NOT NULL,`content` text NOT NULL,`icon` varchar(20) NOT NULL,`time` int(20) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
					"CREATE TABLE `mxgapi_spider` (`id` int(11) NOT NULL,`agent` varchar(100) NOT NULL,`ip` varchar(20) NOT NULL,`time` int(20) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
            	);
            	$insert_sql = array(
            		"INSERT INTO `mxgapi_config` (`title`, `subtitle`, `description`, `keywords`, `favicon`, `username`, `password`, `email`, `qq`, `url`, `icp`, `copyright`, `theme`, `accent`, `qqqrcode`, `vxqrcode`, `aliqrcode`, `smtp_host`, `smtp_username`, `smtp_password`, `smtp_port`, `smtp_secure`, `smtp_reply_to`, `post_id`, `set_time`, `close_site`, `cc_protect`, `fire_wall`, `end_script`) VALUES('默认标题', 'API接口列表', '默认简介', 'API,接口,免费调用', 'favicon.ico', 'admin', '123456', 'example@example.com', '10001', 'http://".$_SERVER['HTTP_HOST']."/', '', '©Copyright 沐朽阁API', 'blue', 'blue', 'https://i.loli.net/2021/01/22/s5NLe3gzRUBwK9a.png', 'https://i.loli.net/2021/01/22/o7miaMcP45QqdCk.png', 'https://i.loli.net/2021/01/22/GeaxMpmAtRfYUz2.jpg', 'smtp.exmaple.com', 'exmaple@exmaple.com', '', '', '', '', '-1', '".time()."', '0', '0', '0', '');"
            	);
            	$alter_sql = array(
            		"ALTER TABLE `mxgapi_access` ADD PRIMARY KEY (`id`) USING BTREE;",
            		"ALTER TABLE `mxgapi_api` ADD PRIMARY KEY (`id`);",
            		"ALTER TABLE `mxgapi_feedback` ADD PRIMARY KEY (`id`);",
					"ALTER TABLE `mxgapi_friendlinks` ADD PRIMARY KEY (`id`);",
					"ALTER TABLE `mxgapi_login_log` ADD PRIMARY KEY (`id`);",
					"ALTER TABLE `mxgapi_post` ADD PRIMARY KEY (`id`);",
					"ALTER TABLE `mxgapi_spider` ADD PRIMARY KEY (`id`);"
            	);
            	$alter2_sql = array(
            		'ALTER TABLE `mxgapi_access` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
            		'ALTER TABLE `mxgapi_api` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
            		'ALTER TABLE `mxgapi_feedback` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
					'ALTER TABLE `mxgapi_friendlinks` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
					'ALTER TABLE `mxgapi_login_log` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
					'ALTER TABLE `mxgapi_post` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;',
					'ALTER TABLE `mxgapi_spider` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;'
            	);
            	$init_result = loop_query($db, $init_sql);
            	$create_result = loop_query($db, $create_sql);
            	$insert_result = loop_query($db, $insert_sql);
            	$alter_result = loop_query($db, $alter_sql);
				$alter2_result = loop_query($db, $alter2_sql);
            	if($init_result && $create_result && $insert_result && $alter_result && $alter2_result){
            		$db->query('SET FOREIGN_KEY_CHECKS=1;');
            		$db->query('COMMIT;');
            		$_SESSION['install_step'] = 2;
            		jump('../?action=install&step=2');
            		jsonError(0,'数据表创建成功');
            	}else{
					unlink('./Database/connect.php');
					$db->query('DROP TABLE `mxgapi_access`, `mxgapi_api`, `mxgapi_config`, `mxgapi_feedback`, `mxgapi_friendlinks`, `mxgapi_login_log`, `mxgapi_post`, `mxgapi_spider`;');
            		alert('数据库创建失败', '../?action=install&step=1');
            		jsonError(-1,"数据库创建失败");
            	}
            }
        }else{
        	alert('请输入完整', '../?action=install&step=1');
        	jsonError(-1,'请输入完整');
        }
        break;
  	case '2':
  		$title = $_POST['title'];
  		$username = $_POST['username'];
  		$password = $_POST['password'];
  		$email = $_POST['email'];
  		$domain = $_POST['domain'];
  		if($title && $username && $password && $email && $domain){
  			$update_sql = "UPDATE `mxgapi_config` SET `title`='{$title}',`username`='{$username}',`password`='{$password}',`email`='{$email}',`url`='http://{$domain}/';";
  			$update_result = $db->query($update_sql);
  			if($update_result){
            	$_SESSION['install_step'] = 3;
            	jump('../?action=install&step=3');
           		jsonError(0,'保存成功');
           	}else{
           		alert('保存失败', '../?action=install&step=2');
           		jsonError(-1,"保存失败");
           	}
  		}else{
  			alert('请输入完整', '../?action=install&step=2');
  			jsonError(-1,'请输入完整');
  		}
  		break;
  	case '3':
  		$host = $_POST['host'];
  		$username = $_POST['username'];
  		$password = $_POST['password'];
  		$port = $_POST['port'];
  		$secure = $_POST['secure'];
  		if($host && $username && $password && $port){
  			$update_sql = "UPDATE `mxgapi_config` SET `smtp_host`='{$host}',`smtp_username`='{$username}',`smtp_password`='{$password}',`smtp_port`='{$port}',`smtp_secure`='{$secure}';";
  			$update_result = $db->query($update_sql);
  			if($update_result){
            	$_SESSION['login'] = 'admin';
            	$_SESSION['install_step'] = 4;
            	jump('../?action=install&step=4');
           		jsonError(0,'保存成功');
           	}else{
           		alert('保存失败', '../?action=install&step=3');
           		jsonError(-1,"保存失败");
           	}
  		}else{
  			alert('请输入完整', '../?action=install&step=3');
  			jsonError(-1,'请输入完整');
  		}
  		break;
}

function loop_query($db, $array){
	foreach($array as $val){
		$result = $db->query($val);
	}
	if($result){
		return true;
	}else{
		return false;
	}
}
