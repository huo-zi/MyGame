<?php
$file = $_REQUEST['file'];
$ojbFile = fopen('D:/Data/'.$file, "r");
$newFile = fopen('D:/Data/new_'.$file, "w");
$i = 1;
die($file);
if($ojbFile){
	while(!feof($ojbFile)){
		$strLine1 = fgets($ojbFile);
		$strLine2 = addslashes($strLine1);
		if($strLine2 != $strLine1){
			echo "diff:{$i}\n";
		}
		fwrite($newFile, $strLine2);
		fseek($ojbFile,0, SEEK_CUR);
		$i++;
	}
	die('clear');
}
?>