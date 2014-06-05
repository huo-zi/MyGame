<?php
define('_LIB_ROOT' , dirname(dirname(dirname(__FILE__))) . '/lib/');
define('_PATH_SEPARATOR', preg_match("/WIN/i",PHP_OS) ? ";" : ":");
ini_set("include_path", "."._PATH_SEPARATOR._LIB_ROOT);
require_once 'class/comm/class.file.php';
if($_POST['file']){
	$file  = $_POST['file'];
	$begin = $_POST['begin'] ? $_POST['begin'] : 1;
	$end = $_POST['end'] ? $_POST['end']+1 : 5;
	$showline = $_POST['showline'];
	$info = File::getFileLines('D:/Data/xiaomi_com.txt', $begin, $end, $showline);
	$info = join('',$info);
}else{
	$begin = 1;
	$end = 5;
}
?>
<html>
<body>
<form method="post">
文件名:<input name="file" value="D:/Data/xiaomi_com.txt"/><br/>
开始行:<input name="begin" value="<?php echo $_POST['begin'];?>"/><br/>
结束行:<input name="end" value="<?php echo $_POST['end'];?>"/><br/>
行号:<input type="radio" value="1" name="showline" <?php if($showline) echo 'checked="checked"';?>/>显示<input type="radio" value="0" name="showline" <?php if(!$showline) echo 'checked="checked"';?>/>不显示
<input type="submit" value="读取"/>
</form>
<textarea rows="50" cols="200"><?php echo $info;?></textarea>
</body>
</html>