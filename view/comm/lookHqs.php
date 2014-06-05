<?php 
	$ip = trim($_REQUEST['ip']);
	$duan = trim($_REQUEST['duan']);
	$name = trim($_REQUEST['name']);
	if($ip && $duan && $name){
		$strUrl = "http://{$ip}:{$duan}/?name={$name}&opt=status";
		$strRes = file_get_contents($strUrl);
	}
?>
<html>
<head>
<title>队列查看</title>
</head>
<body>
<div style="text-align:center;margin-top:50px;">
<h2>队列查看，算是个器</h2>
<form name="" method="post">
IP:<input type="text" name="ip" value="<?php echo empty($ip) ? '192.168.99.36' : $ip; ?>" size="14"/>&nbsp; &nbsp;
端口:<input type="text" name="duan" value="<?php echo empty($duan) ? '1218' : $duan; ?>" size="4"/>&nbsp; &nbsp;
队列名:<input type="text" name="name" value="<?php echo empty($name) ? 'CTRIP_IMAGE_ZERO_2' : $name; ?>" />
<input type="submit" value="走你"/>
</form>
<?php if($strRes){ ?>
这里是结果:<br/>
<textarea cols="73" rows="20"><?php echo $strRes;?></textarea>
<?php }?>
</div>
</body>
</html>