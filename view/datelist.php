<?php 

$con = mysql_connect("localhost","root","");
mysql_select_db('test');
$query = 'select count(*) from tbl1';
$result= mysql_query($query);

var_dump(mysql_free_result($result));
mysql_close($con);exit;

$con = new mysqli('localhost','root','','test');
$sql = 'select * from help_relation limit 0,100';
$res = $con->query($sql);
if($res->num_rows > 0){
	while ($row = $res->fetch_array()){
		var_dump($row);
	}
}
$res->close();
$con->close();
exit;
?>