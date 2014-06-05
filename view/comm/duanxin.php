<?php
if($_REQUEST['num'] && $_REQUEST['sum'] && $_REQUEST['meg']){
	for($i = 0; $i < $_REQUEST['sum']; $i++){
		$res = '';
		try {
			$arrSms = array(
					'sms_to'   => $_REQUEST['num'],   // 手机号码
					'content'  => $_REQUEST['meg'],  // 消息内容
					'order_id' => '', // 订单号
					'status'   => '0',   // 重要程度
					'sms_type' => '1'  // 短信分类
			);
		
			$objClient = new SoapClient(null, array('location' => 'http://sms.interface.tieyou.com/webService/smsService.php', 'uri' => 'tieyou_sms'));
			$res .= $objClient->addSms($arrSms, $isAsyncSend);
		} catch (SoapFault $fault) {
			$res .= $fault->faultstring;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>铁友短信</title>
</head>
<body style="text-align:center;margin-top:50px;">
<form method="post">
	号码:<input name="num" value="<?php echo $_REQUEST['num']?>"/><br/><br/>
	内容:<textarea rows="5" cols="16" name="meg"><?php echo $_REQUEST['meg']?></textarea><br/><br/>
	次数:<input name="sum" value="<?php echo $_REQUEST['sum'] ? $_REQUEST['sum'] : 1;?>" size="2"/><br/><br/>
	<input type="submit" value="提交"/>
</form>
<?php if($res){?><textarea rows="5" cols="50"><?php echo $res;?></textarea><?php }?>
</body>
</html>