SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `mxgapi_access` (
  `id` int(11) NOT NULL,
  `host` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `protocol` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `address` varchar(255) NOT NULL,
  `time` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `mxgapi_api` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `enname` varchar(200) NOT NULL,
  `desc` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `format` varchar(200) NOT NULL,
  `method` varchar(200) NOT NULL,
  `example_url` varchar(200) NOT NULL,
  `request_parameter` text NOT NULL,
  `return_parameter` text NOT NULL,
  `error_code` varchar(255) NOT NULL,
  `PHP_example` text NOT NULL,
  `return` text NOT NULL,
  `time` int(20) NOT NULL,
  `access` int(20) NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_api` (`id`, `name`, `enname`, `desc`, `url`, `format`, `method`, `example_url`, `request_parameter`, `return_parameter`, `error_code`, `PHP_example`, `return`, `time`, `access`, `status`) VALUES
(1, '随机一言', 'yiyan', '随机一言', 'yiyan.php', 'JSON/TEXT', 'GET', 'yiyan.php', '{\"data\":[{\"name\":\"无\",\"type\":\"string\",\"required\":\"否\",\"info\":\"无需请求参数\"}]}', '{\"data\":[{\"name\":\"code\",\"type\":\"int\",\"msg\":\"状态码\"},{\"name\":\"text\",\"type\":\"string\",\"msg\":\"内容\"}]}', '{\"data\":[{\"code\":\"1\",\"msg\":\"成功\"},{\"code\":\"0\",\"msg\":\"失败\"}]}', '<?php\necho file_get_contents(\"yiyan.php\");\n?>', '{ \"code\": 1, \"text\": \"就算是自私⋯⋯我也希望那些人能够永远都有笑容⋯⋯\" }', 2147483647, 254, 1);

CREATE TABLE `mxgapi_config` (
  `title` varchar(255) NOT NULL COMMENT '标题',
  `subtitle` varchar(255) NOT NULL COMMENT '副标题',
  `description` varchar(255) NOT NULL COMMENT '站点简介',
  `keywords` varchar(255) NOT NULL COMMENT '站点关键词',
  `favicon` varchar(255) NOT NULL COMMENT '站点头像',
  `username` varchar(255) NOT NULL DEFAULT 'admin' COMMENT '后台用户名',
  `password` varchar(255) NOT NULL DEFAULT '123456' COMMENT '后台密码',
  `email` varchar(20) NOT NULL DEFAULT '2441260435@qq.com' COMMENT '邮箱',
  `qq` varchar(12) NOT NULL DEFAULT '2441260435' COMMENT 'QQ',
  `url` varchar(255) NOT NULL COMMENT '站点地址',
  `icp` varchar(255) NOT NULL COMMENT '备案信息',
  `copyright` varchar(255) NOT NULL COMMENT '版权信息',
  `theme` varchar(255) NOT NULL COMMENT '主题色',
  `accent` varchar(255) NOT NULL COMMENT '强调色',
  `qqqrcode` varchar(100) NOT NULL DEFAULT 'https://i.loli.net/2021/01/22/s5NLe3gzRUBwK9a.png' COMMENT 'QQ支付二维码',
  `vxqrcode` varchar(100) NOT NULL DEFAULT 'https://i.loli.net/2021/01/22/o7miaMcP45QqdCk.png' COMMENT '微信支付二维码',
  `aliqrcode` varchar(100) NOT NULL DEFAULT 'https://i.loli.net/2021/01/22/GeaxMpmAtRfYUz2.jpg' COMMENT '支付宝二维码',
  `smtp_host` varchar(50) NOT NULL,
  `smtp_username` varchar(50) NOT NULL,
  `smtp_password` varchar(50) NOT NULL,
  `smtp_port` int(10) NOT NULL,
  `smtp_secure` varchar(50) NOT NULL,
  `smtp_reply_to` varchar(50) NOT NULL,
  `post_id` int(100) NOT NULL,
  `set_time` int(20) NOT NULL,
  `close_site` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_config` (`title`, `subtitle`, `description`, `keywords`, `favicon`, `username`, `password`, `email`, `qq`, `url`, `icp`, `copyright`, `theme`, `accent`, `qqqrcode`, `vxqrcode`, `aliqrcode`, `smtp_host`, `smtp_username`, `smtp_password`, `smtp_port`, `smtp_secure`, `smtp_reply_to`, `post_id`, `set_time`, `close_site`) VALUES
('沐朽阁API', 'API接口列表', '沐朽阁API，免费接口调用平台', '嘤,嘤,嘤', 'favicon.ico', '沐风', 'a1242196674', '2441260435@qq.com', '2441260435', 'http://127.0.0.1:8000/', '京ICP 备你妈的案', '©沐朽阁API', 'blue', 'blue', 'https://i.loli.net/2021/01/22/s5NLe3gzRUBwK9a.png', 'https://i.loli.net/2021/01/22/o7miaMcP45QqdCk.png', 'https://i.loli.net/2021/01/22/GeaxMpmAtRfYUz2.jpg', 'smtp.qq.com', '2441260435@qq.com', 'pzucvrydsbuaebga', 587, 'tls', '', 1, 0, '0');

CREATE TABLE `mxgapi_feedback` (
  `id` int(11) NOT NULL,
  `api_id` int(100) NOT NULL,
  `api_name` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_feedback` (`id`, `api_id`, `api_name`, `title`, `content`, `email`, `ip`, `time`) VALUES
(8, 15, '测试', '测试', '测试', '2441260435@qq.com', '127.0.0.1', 1611755510);

CREATE TABLE `mxgapi_friendlinks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  `time` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_friendlinks` (`id`, `name`, `desc`, `url`, `picurl`, `time`) VALUES
(1, '沐朽阁', '沐朽阁官方博客～', 'https://muxiuge.cn', 'https://q.qlogo.cn/headimg_dl?dst_uin=2441260435&spec=640', '16434343434');

CREATE TABLE `mxgapi_login_log` (
  `id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `time` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_login_log` (`id`, `ip`, `address`, `time`) VALUES
(1, '127.0.0.1', '中国河北省张家口市', 1704646464);

CREATE TABLE `mxgapi_post` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `icon` varchar(20) NOT NULL,
  `time` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `mxgapi_post` (`id`, `title`, `content`, `icon`, `time`) VALUES
(1, '关于接口反馈', '接口反馈功能已正式上线！\n\n希望大家遇到问题是能够及时反馈，本站会以邮件的方式告知你，谢谢大家', 'success', 1704646464);


ALTER TABLE `mxgapi_access`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `mxgapi_api`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mxgapi_feedback`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mxgapi_friendlinks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mxgapi_login_log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mxgapi_post`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `mxgapi_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1112;

ALTER TABLE `mxgapi_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `mxgapi_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `mxgapi_friendlinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `mxgapi_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `mxgapi_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;