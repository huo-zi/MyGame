<?php
//生成快捷方式
// $Shortcut = "[InternetShortcut]
// URL=http://www.lio.com/
// IDList=
// [{000214A0-0000-0000-C000-000000000046}]
// Prop3=19,2
// ";
// Header("Content-type: application/octet-stream; charset=utf-8");
// header("Content-Disposition: attachment; filename=test.url;");
// echo $Shortcut;
$first = microtime();
for($i = 0; $i<20000; $i++){
	echo 'a','b';
}
echo '<br/>';
$secent = microtime();
for($i = 0; $i<20000; $i++){
	echo 'a'.'b';
}
echo '<br/>';
$third = microtime();
echo ',PK.:',$secent-$first,' : ',$third-$secent,'<br/>';
echo 'echo ,:231','121',21,21,2,12,321,'<br/>';
echo 'echo time:',time(),'<br/>';
echo 'echo $_SERVER["REQUEST_TIME"]:',$_SERVER['REQUEST_TIME'],'<br/>';
echo "preg_replace : preg_replace('/a/i','z','abaavba') = ", preg_replace('/a/i','z','abaavba'),';<br/>';
echo "str_replace : str_replace('a','z','abaavba') = ", str_replace('a','z','abaavba'),';<br/>';
echo "strtr : strtr('abaavba','a','z') = ",strtr('abaavba','a','z'),';<br/>';
$foo = '123002';
echo 'isset:$foo = "123002",isset($foo{5}) = ',isset($foo{5}),';<br/>';

for($i = 0; $i < 1; $i++){
	echo $i.'<br />';
	ob_flush();
	flush();
	sleep(1);
}
ob_end_flush();
?>


