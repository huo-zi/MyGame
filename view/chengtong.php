<?php
define('SCRIPT_ROOT',dirname(__FILE__).'/');
echo '<style type="text/css">.centerDiv{width:800px; margin:0 auto;padding:5px 5px;border:1px solid;}</style>';
echo '<div class="centerDiv">';
if($_REQUEST['detail']){
	$userId = trim($_REQUEST['userId']) ? trim($_REQUEST['userId']) : '1788997';
	$folderId = trim($_REQUEST['detail']);
	$info = @file_get_contents('http://www.400gb.com/u/'.$userId.'/'.$folderId);
	$info = substr($info, stripos($info,'"sAjaxSource": "')+16, 140);
	$list = @file_get_contents("http://www.400gb.com{$info}&iColumns=4&sColumns=&iDisplayStart=0&iDisplayLength=100&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&sSearch=&bRegex=false&sSearch_0=&bRegex_0=false&bSearchable_0=true&sSearch_1=&bRegex_1=false&bSearchable_1=true&sSearch_2=&bRegex_2=false&bSearchable_2=true&sSearch_3=&bRegex_3=false&bSearchable_3=true&iSortCol_0=3&sSortDir_0=desc&iSortingCols=1&bSortable_0=false&bSortable_1=true&bSortable_2=true&bSortable_3=true&_=1402296435772");
// 	print_r($list);exit;
	$list = str_replace(array('<label><input type=\'checkbox\' name=\"file_ids[]\" id=\"file_ids\" value=\"','\" \/><span class=\"lbl\"><\/span><\/label>','\t\t\t\t\t<i class=\"fileicon fileicon-','<\/i> <a target=\"_blank\" href=\"','<\/a>\t\t\t\t'),'',$list);
	$list = str_replace(array('\">'),'|', $list);
	$list = json_decode($list,true);
	$list = $list['aaData'];
// 	var_dump($list);exit;
	foreach ($list as $k=>$v){
		$info = explode('|',$v[1]);
		echo "<li>{$info[0]}：<a target=\"_blank\" href=\"?file={$v[0]}\" title=\"{$info[2]}\">{$info[2]}</a>——{$v[2]}</li>";
	}
}else if($_REQUEST['file']){
	$file = $_REQUEST['file'];
	$info = @file_get_contents('http://www.400gb.com/file/'.$file);
// 	print_r($info);exit;
	$imgs = 'http://www.400gb.com'.substr($info, stripos($info,'document.write("<img id=\'vfcode\' src=\'')+38, 32+strlen($_REQUEST['file'])).randomFloat(0,1);
	$hash = substr($info, stripos($info,'<input type="hidden" id="hash_key" name="hash_key" value="'), 94);
	showAuthcode($imgs);
// 	echo '<form name="user_form" action="http://www.400gb.com/guest_loginV2.php" method="post">'."\n";
	echo '<form name="user_form" action="?submit=true" method="post">'."\n";
	echo '<script type="text/javascript">'."\n";
	echo "document.write(\"<img id='vfcode' src='{$imgs}'/>\");\n";
	echo '</script>'."\n";
	echo '<input type="hidden" id="file_id" name="file_id" value="'.$file.'"/>'."\n";
	echo $hash."\n";
	echo '<br/><input type="text" name="randcode" maxlength=4 />'."\n";
	echo '<input type="submit"/>'."\n";
	echo '</form>'."\n";
}else if($_REQUEST['submit']){
	$strLinkHost = "http://www.400gb.com/guest_loginV2.php";
	$data_string = http_build_query($_POST);
	
	$_SERVER['HTTP_HOST'] = 'http://www.400gb.com/guest_loginV2.php';
	$_SERVER['HTTP_HOST'] = 'www.400gb.com';
	$_SERVER['SCRIPT_URL'] = '/guest_loginV2.php';
	$_SERVER['HTTP_ORIGIN'] = 'http://www.400gb.com';
	$_SERVER['HTTP_REFERER'] = 'http://www.400gb.com/guest_loginV2.php';
	$_SERVER['SERVER_NAME'] = 'www.400gb.com';
	$_SERVER['PHP_SELF'] = 'www.400gb.com';
	$_SERVER['REQUEST_URI'] = 'http://www.400gb.com/guest_loginV2.php';
	$_SERVER['SCRIPT_NAME'] = '/guest_loginV2.php';
	$_SERVER['PHP_SELF'] = '/guest_loginV2.php';
// 	echo http_build_query($_POST);exit;
	$objCurl = curl_init($strLinkHost);
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($objCurl, CURLOPT_REFERER, "http://www.400gb.com");
	curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:111.222.333.4', 'CLIENT-IP:111.222.333.4'));
	curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($objCurl, CURLOPT_COOKIEJAR, SCRIPT_ROOT.'cookie.tmp');
	curl_setopt($objCurl, CURLOPT_POST, 1);
	curl_setopt($objCurl, CURLOPT_POSTFIELDS, $data_string);
	$strResult = curl_exec($objCurl);
	curl_close($objCurl);
	echo ($strResult);exit;
	$arrResult = json_decode($strResult,true);
// 			print_r($strResult);exit;
}else if($_REQUEST['img']){
	$img = urldecode($_REQUEST['img']);
	showAuthcode('http://www.400gb.com'.$img);
}else{
	$userId = trim($_REQUEST['userId']) ? trim($_REQUEST['userId']) : '1788997';
	$info = @file_get_contents('http://www.400gb.com/u/'.$userId);
	$info = substr($info, stripos($info,'"sAjaxSource": "')+16, 134);
	$list = @file_get_contents("http://www.400gb.com{$info}&iColumns=4&sColumns=&iDisplayStart=0&iDisplayLength=100&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&sSearch=&bRegex=false&sSearch_0=&bRegex_0=false&bSearchable_0=true&sSearch_1=&bRegex_1=false&bSearchable_1=true&sSearch_2=&bRegex_2=false&bSearchable_2=true&sSearch_3=&bRegex_3=false&bSearchable_3=true&iSortCol_0=3&sSortDir_0=desc&iSortingCols=1&bSortable_0=false&bSortable_1=true&bSortable_2=true&bSortable_3=true&_=1402296435772");
	//print_r($list);exit;
	$list = str_replace(array('<label><input type=\'checkbox\' name=\"folder_ids[]\" id=\"folder_ids\" value=\"','\" disabled \/><span class=\"lbl\"><\/span><\/label>','\t\t\t\t\t\t<i class=\"fileicon ','<\/i> <a href=\"','<\/a> \t\t\t\t<span class=\"folder_desc','<\/span>'),'',$list);
	$list = str_replace(array('\">'),'|', $list);
	$list = json_decode($list,true);
	$list = $list['aaData'];
// 	var_dump($list);exit;
	foreach ($list as $k=>$v){
		$info = explode('|',$v[1]);
		echo "<li><a href=\"?detail={$v[0]}\" title=\"{$info[3]}\">{$info[2]}</a></li>";
	}
}
echo '</div>';exit;
function showAuthcode($authcode_url){
	$cookieFile = SCRIPT_ROOT.'cookie.tmp';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $authcode_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile); // 把返回来的cookie信息保存在文件中
	$content = curl_exec($ch);
	curl_close($ch);
}
function randomFloat($min = 0, $max = 1) {
	return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}
?>