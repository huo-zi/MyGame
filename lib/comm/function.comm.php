<?php 
function getIp(){
	$ip = '';
	if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif ($_SERVER["HTTP_CLIENT_IP"]){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}elseif ($_SERVER["REMOTE_ADDR"]){
		$ip = $_SERVER["REMOTE_ADDR"];
	}elseif (getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}elseif (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	}elseif (getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}else{
		$ip = "unknown";
	}

	return $ip;
}

function excel($excelName, $arrData, $daochu = true){
	if(is_array($arrData)){
		$first = current($arrData);
		$intCol = count($first);
		$strCon = '';
		$strNav = "<tr>";
		foreach($first as $k=>$v){
			$strNav .= "<td align='center'>$k</td>";
		}
		$strNav .= "</tr>";

		foreach($arrData as $ks=>$vs){
			$strCon .= "<tr>";
			foreach($vs as $k=>$v){
				if($k == 0){
					$strCon .= "<td align='center'>$v</td>";
				}else{
					$strCon .= "<td align='center' style='vnd.ms-excel.numberformat:yyyy-mm-dd'>$v</td>";
				}
			}
			$strCon .= "</tr>";
		}
		// var_dump($strCon);exit;
		if($daochu){
			header("content-type:application/vnd.ms-excel");
			header("content-disposition:attachment;filename=".$excelName.".xls");
		}
		// 		$strExcel .=  "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
		// 		$strExcel .=  "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
		// 		$strExcel .=  "<html><head><meta http-equiv='Content-type' content='text/html;charset=gb2312' /><style id='Classeur1_16681_Styles'></style></head>";
		$strExcel .=  "<body><div id='Classeur1_16681' align=center x:publishsource='Excel'> <table border='1'>".$strNav.$strCon."</table></div>";
		$strExcel .=  "</body></html>";
		echo($strExcel);exit;
	}else{
		echo "传入的参数有误，不是数组";
		return false;
	}
}
?>