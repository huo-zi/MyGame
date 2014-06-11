<?php
//////////////
//生成生日MD5
///////////
$begin = 19591231;
$end = 20201231;
$con = mysql_connect("localhost","root","");
mysql_select_db('test');
while($begin <= $end){
	$year  = substr($begin,0,4);
	$month = substr($begin,4,2);
	$day = substr($begin,6);
	$max = getMouthMaxDay($year, $month);
	$day = $day+1;
	$begin = getDateStr(intval($year),intval($month),intval($day),$max);
	echo $begin;die;
// 	长生日：19940221
	$query = sprintf('insert into tbl_md5_birthday set birthday=%d,md5=\'%s\'',$begin, md5($begin));
	mysql_query($query);
	echo $begin.':'.md5($begin).',res:'.mysql_insert_id()."\n";

// 	短生日：940221
	$short = substr($begin,2);
	$query = sprintf('insert into tbl_md5_birthday set birthday=\'%s\',md5=\'%s\'',$short, md5($short));
	mysql_query($query);
	echo $short.':'.md5($short).',res:'.mysql_insert_id()."\n";

// 	滤0生日
	if(stripos($begin, '0') !== false){
		$zero = str_replace('0','',$begin);
		if(strlen($zero)>5 && strlen($zero)<8){
			$query = sprintf('insert into tbl_md5_birthday set birthday=%d,md5=\'%s\'',$zero, md5($zero));
			mysql_query($query);
			echo $zero.':'.md5($zero).',res:'.mysql_insert_id()."\n";
		}
	}
}
mysql_close($con);

function getMouthMaxDay($year, $mouth){
	$mouth30 = array(4,6,9,11);
	$maxDays = 31;
	if(in_array($mouth, $mouth30)){
		$maxDays = 30;
	}else if(2 == $mouth){
		if ($year%400==0 || ($year%100!=0 && $year%4==0)){
			$maxDays = 29;
		}else {
			$maxDays = 28;
		}
	}
	return $maxDays;
}

//获取时间
function getDateStr($year,$month,$day,$maxDay){
	$month = $day>$maxDay ? $month+1     : $month;
	$day   = $day>$maxDay ? $day-$maxDay : $day;
	$year  = $month>12    ? $year+1      : $year;
	$month = $month>12    ? $month-12    : $month;
	$month = $month<10 ? '0'.$month : $month;
	$day   = $day  <10 ? '0'.$day   : $day;
	return intval($year.$month.$day);
}

?>