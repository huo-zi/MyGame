<?php
$ojbFile = fopen('D:/Data/www.csdn.net.sql', "r");
$i = 1;
if($ojbFile){
	$sql = "insert into tbl_csdn_info4 (user_name,user_pass,email) values ";
	$data = array();
	while(!feof($ojbFile)){
		$strLine = fgets($ojbFile);
		$strLine = addslashes($strLine);
		$arrLine = explode('#', $strLine);
		if(trim($arrLine[2]) == ''){
			$arrLine[2] = $arrLine[0];
		}
		fseek($ojbFile,0, SEEK_CUR);
		if($i <1){
			$i = $i+1;
			if($i%1000 == 0){
				echo $i."\n";
			}
			continue;
		}
		$data[] = "('".trim($arrLine[0])."','".trim($arrLine[1])."','".trim($arrLine[2])."')";
		//逐行读取XML文件，从第二行开始解析数据
		
		if($i%500 == 0 || $i == 6428632){
			$con = mysql_connect('localhost','root','') or die('could not connect');
			@mysql_select_db('test');
			@mysql_query("set names 'utf8'");
			$strSql = $sql.join(',', $data);
			$result = mysql_query($strSql);
			mysql_close($con);
			$data = array();
			if(!$result){
				die(iconv('UTF-8','GBK',$strSql));
			}else{
				echo 'RES:'.$result."I:{$i}\n";
			}
		}
		$i++;
	}
	die('clear');
}
?>