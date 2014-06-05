<?php
ini_set('include_path', ".;E:/PHPworkspace/MyGame/");	//本地测试地址
require_once '/lib/class/comm/class.file.php';
// $begin  = 294500;
// for ($i = 294500 ; $i < 295001 ;$i++){
// 	if(true || $i == 8281411){
$begin  = 15;
for ($i = 15 ; $i < 1000001 ;$i++){
	if($i%1000 == 0){
		$end = $i;
		echo $begin.'_'.$end.'_';
		
		$info = File::getFileLines('D:/Data/xiaomi_com.txt', $begin, $end);
		$begin = $i+1;
		$sql = "insert into tbl_miui_info (id,user_name,user_pass,email,ip) values ('";
		$sql.= implode("'),('",$info);
		$sql.= "');";
// 		die($sql);
// 		if($i == 42397){
// 			die(iconv('UTF-8','GBK',$sql));
// 		}
		$con = mysql_connect('localhost','root','') or die('could not connect');
		@mysql_select_db('test');
		@mysql_query("set names 'utf8'");
		$result = mysql_query($sql);
		mysql_close($con);
		
		echo $result."\n";
		if(!$result){
			die(iconv('UTF-8','GBK',$sql));
		}
	}
}
?>