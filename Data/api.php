<?php
require 'init.php';
$type = $_REQUEST['type'];
/* 用switch判断类型 */
switch($type){

	/* 获取全部API数据 */
	case 'getAllApi' :
		$sql = 'SELECT * FROM `mxgapi_api` order by 1 desc';
		$result = $db->query($sql);
		if($result){
			$result = $result->fetch_all(MYSQLI_ASSOC);
			if(!$result){
				jsonError(-1, '暂无接口');
			}
			foreach($result as $v){
				$arr[] = array(
					'id' => $v['id'],
					'name' => $v['name'],
					'enname' => $v['enname'],
					'desc' => $v['desc'],
					'time' => date('Y-m-d h:i:s', $v['time']),
					'access' => $v['access'],
					'status' => $v['status']
				);
			}
			json(0, '获取成功', $arr);
		}else{
			jsonError(-1, '获取数据失败');
		}
		break;
		
	/* 获取单一API数据 */
	case 'getOneApi' :
		$id = intval($_REQUEST['id']);
		if(!$id){
			jsonError(-1, '缺少参数');
		}
		$sql = 'SELECT * FROM `mxgapi_api` WHERE `id`='.$id;
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			if(!$data){
				jsonError(-1, '暂无接口');
			}
			json(0, '获取成功', $data);
		}else{
			jsonError(-1, '获取数据失败');
		}
		break;
		
	/* 搜索API，返回数据 */
	case 'searchApi' :
		$s = addslashes(sprintf("%s", $_REQUEST['s']));
		if(!$s){
			jsonError(-1, '输入搜索内容');
		}
		$sql = "SELECT * FROM `mxgapi_api` WHERE `status`='1' && `name` LIKE '%" . $s . "%' order by 1 desc";
		$result = $db->query($sql);
		if($result){
			$result = $result->fetch_all(MYSQLI_ASSOC);
			if(!$result){
				jsonError(-1, '没有搜到你想要的接口');
			}
			foreach($result as $v){
				$arr[] = array(
					'id' => $v['id'],
					'name' => $v['name'],
					'enname' => $v['enname'],
					'desc' => $v['desc'],
					'access' => $v['access'],
					'status' => $v['status']
				);
			}
			json(0, '获取成功', $arr);
		}else{
			jsonError(-1, '获取数据失败');
		}
		break;
		
	/* 获取全部友情链接数据 */
	case 'getAllLink' :
		$mod = $_GET['mod'];
		$sql = 'SELECT * FROM `mxgapi_friendlinks`';
		$result = $db->query($sql);
		if($result){
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			if(!$arr){
				jsonError(-1, '暂无友情链接');
			}
			if($mod == 'rand'){
				shuffle($arr);
			}
			json(0, '获取成功', $arr);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
		
	/* 获取单一友链数据 */
	case 'getOneLink' :
		$id = intval($_REQUEST['id']);
		if(!$id){
			jsonError(-1, '缺少参数');
		}
		$sql = 'SELECT * FROM `mxgapi_friendlinks` WHERE `id`='.$id;
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			if(!$data){
				jsonError(-1, '暂无友链');
			}
			json(0, '获取成功', $data);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
		
	/* 获取全部公告数据 */
	case 'getAllPost' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$sql = 'SELECT * FROM `mxgapi_post` order by 1 desc';
		$result = $db->query($sql);
		if($result){
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			if(!$arr){
				jsonError(-1, '暂无公告');
			}
			json(0, '获取成功', $arr);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
		
	/* 获取单一公告数据 */
	case 'getOnePost' :
		$id = intval($_REQUEST['id']);
		if(!$id){
			jsonError(-1, '缺少参数');
		}
		$sql = 'SELECT * FROM `mxgapi_post` WHERE `id`='.$id;
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			if(!$data){
				jsonError(-1, '暂无公告');
			}
			json(0, '获取成功', $data);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
		
	/* 获取全部接口反馈数据 */
	case 'getAllFeedback' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$sql = 'SELECT * FROM `mxgapi_feedback` order by 1 desc';
		$result = $db->query($sql);
		if($result){
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			if(!$arr){
				jsonError(-1, '暂无反馈信息');
			}
			json(0, '获取成功', $arr);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
		
	/* 获取单一反馈数据 */
	case 'getOneFeedback' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$id = intval($_REQUEST['id']);
		if(!$id){
			jsonError(-1, '缺少参数');
		}
		$sql = 'SELECT * FROM `mxgapi_feedback` WHERE `id`='.$id;
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			if(!$data){
				jsonError(-1, '暂无该反馈信息');
			}
			json(0, '获取成功', $data);
		}else{
			jsonError(-1, '获取数据失败');
		}
		break;
		
	/* 获取后台首页信息（需要登录） */
	case 'getAdminInfo' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		
		$sql = array(
			'api' => 'SELECT count(1) FROM `mxgapi_api`',
			'access' => 'SELECT count(1) FROM `mxgapi_access`',
			'spider' => 'SELECT count(1) FROM `mxgapi_spider`',
			'link' => 'SELECT count(1) FROM `mxgapi_friendlinks`',
			'post' => 'SELECT count(1) FROM `mxgapi_post`',
			'feedback' => 'SELECT count(1) FROM `mxgapi_feedback`',
		);

		$timestamp = array(
			strtotime('today')-4*86400,	
			strtotime('today')-3*86400,
			strtotime('today')-2*86400,
			strtotime('today')-86400,
			strtotime('today'),
			strtotime('today')+86400
		);
		// 统计访问
		for ($i=0;$i<5;$i++) {
			$access_sql = "SELECT count(1) FROM `mxgapi_access` WHERE `time` between '{$timestamp[$i]}' and '{$timestamp[($i+1)]}';";
			$access_result = $db->query($access_sql);
			$access_data[] = ($access_result->fetch_array())[0];
		}
		for ($i=0;$i<5; $i++) { 
			$access_time[] = date('d', $timestamp[$i]);
		}
		$access = array(
			'access_data' => $access_data,
			'access_time' => $access_time
		);

		// 统计蜘蛛
		for ($i=0;$i<5;$i++) {
			$spider_sql = "SELECT count(1) FROM `mxgapi_spider` WHERE `time` between '{$timestamp[$i]}' and '{$timestamp[($i+1)]}';";
			$spider_result = $db->query($spider_sql);
			$spider_data[] = ($spider_result->fetch_array())[0];
		}
		for ($i=0;$i<5; $i++) { 
			$spider_time[] = date('d', $timestamp[$i]);
		}
		$spider = array(
			'spider_data' => $spider_data,
			'spider_time' => $spider_time
		);

		foreach($sql as $key => $val){
			$result = $db->query($val);
			// 遍历创建data数组(仅包含api，access，spider，link，post，feedback数量)
			$data[$key] = ($result->fetch_array())[0];
		}
		json(0, '获取成功！', array_merge($data, $access, $spider));
		break;
		
	/* 获取网站配置信息 */
	case 'getWebSetting' :
		$sql = 'SELECT title,subtitle,description,keywords,favicon,url,icp,copyright,theme,accent,post_id,set_time,close_site,cc_protect,fire_wall,end_script FROM `mxgapi_config`';
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			$post_id = $data['post_id'];
			$post['post'] = $db->query("SELECT * FROM `mxgapi_post` WHERE `id`='{$post_id}';")->fetch_assoc();
			json(0, '获取成功！', array_merge($data,$post));
		}else{
			jsonError(-1, '获取数据失败！');
		}
		break;
	
	/* 获取邮件配置信息 */
	case 'getSmtpConfig' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$sql = 'SELECT smtp_host,smtp_username,smtp_password,smtp_port,smtp_secure FROM `mxgapi_config`';
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			json(0, '获取成功！', $data);
		}else{
			jsonError(-1, '获取数据失败！');
		}
		break;
		
	/* 获取后台用户信息 */
	case 'getUserInfo' : 
		$sql = 'SELECT username,email,qq,qqqrcode,vxqrcode,aliqrcode FROM `mxgapi_config`';
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_assoc();
			$qqhead = [
				'qqhead' => 'https://q2.qlogo.cn/headimg_dl?dst_uin=' . $data['qq'] . '&spec=640',
				'href' => 'mqqapi://card/show_pslcard?src_type=internal&source=sharecard&version=1&uin=' . $data['qq']
			];
			json(0, '获取成功！', array_merge($data,$qqhead));
		}else{
			jsonError(-1, '获取数据失败！');
		}
		break;
		
	/* 获取访问信息 */
	case 'getAccessInfo':
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$num = intval($_REQUEST['num'] ?? '25');
		$result = $db->query("SELECT * FROM `mxgapi_access` order by 1 desc limit ".$num)->fetch_all(MYSQLI_ASSOC);
		if(!$result){
			jsonError(-1, '数据获取失败！');
		} 
		foreach($result as $val){
			$data[] = [
				'id' => $val['id'],
				'ip' => $val['ip'],
				'host' => $val['host'],
				'protocol' => $val['protocol'],
				'method' => $val['method'],
				'user_agent' => $val['user_agent'],
				'time' => date('Y-m-d h:i:s', $val['time'])
			];
		}
		json(0, '获取成功', $data);
		break;
	
	/* 获取IP地址具体位置 */
	case 'getIpAddress':
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$ip = $_REQUEST['ip'];
		if(!$ip){
			jsonError(-1, '参数错误');
		}else{
			$data = curl('https://api.oioweb.cn/api/ipaddress.php?host='.$ip, 'GET', 0, 0);
			$data = json_decode($data, true);
			if($data['disp'] != ''){
				json(0, '获取成功', $data['disp']);
			}else{
				jsonError(-1, '获取失败');
			}
		}
		break;
		
	/* 退出登录 */
	case 'exitLogin':
		session_start();
		if($_SESSION['login'] == 'admin'){
			unset($_SESSION['login']);
			jsonError(0, '退出登录成功');
		}else{
			jsonError(-1, '用户未登录');
		}
		break;
		
	/* 发送测试邮件 */
	case 'sendTestEmail':
		if($_REQUEST['to']){
			die(sendMail($_REQUEST['to'], '一封测试邮件', '你收到了这封邮件，表示你的邮件服务器已设置成功。'));
		}else{
			jsonError(-1, '缺少参数！');
		}
		break;
		
	/* 接口调用排行榜 */
	case 'getApiAccessList':
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$sql = 'SELECT name,access FROM `mxgapi_api` order by access desc limit 5';
		$result = $db->query($sql);
		if($result){
			$data = $result->fetch_all(MYSQLI_ASSOC);
			json(0, '获取成功！', $data);
		}else{
			jsonError(-1, '获取数据失败！');
		}
		break;
	
	/* 获取全部登录日志数据 */
	case 'getAllLoginLog' :
		if(!isAdmin()){
			jsonError(-1, '未登录到后台');
		}
		$sql = 'SELECT * FROM `mxgapi_login_log` order by 1 desc';
		$result = $db->query($sql);
		if($result){
			$arr = $result->fetch_all(MYSQLI_ASSOC);
			if(!$result){
				jsonError(-1, '暂无登录信息');
			}
			foreach($arr as $val){
				$data[] = [
					'id' => $val['id'],
					'ip' => $val['ip'],
					'address' => $val['address'],
					'time' => date('Y-m-d h:i:s', $val['time'])
				];
			}
			json(0, '获取成功', $data);
		}else{
			jsonError(-1, '获取失败');
		}
		break;
}
 