<?php 
echo $_SERVER['argv'][1];die;
$emial = '!&#$%^&#$%^&*#$%^&*(#$%^&*()#$%^&*()a#$%^&*()_#$%^&*()_+#$%^&*()_+|#$%^&*(0#$%^&*90#$%^&8#$&#$&123#$)(*&#$56&*#%&#%&(#%&*#%&@$^*#%@)&!@#&#&$^%#&%#&&&*#&(#&(*!#&*#*$&%^#2809100&#4%6&8(0#456&*(#<>?#<>?b1j2h3#d->wa#gexl37&#jun&*#kanhongna&*#zhs&681421';
$emial = explode("#",$emial);
$con = mysql_connect("localhost","root","");
mysql_select_db('test');
foreach ($emial as $v){
// 	var_dump($emial[0],$emial[1],$emial[2],$v);exit;
	$sql = 'UPDATE tbl_csdn_info SET email=user_name WHERE email=\''.$v.'\'';
	$res = mysql_query($sql);
	echo 'sql:'.$sql.',res:'.mysql_affected_rows()."\n";
}
mysql_close($con);
die('celar');
print_r($emial);
?>