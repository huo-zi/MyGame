<?php 
//echo phpinfo();
define('_WWW_ROOT' , dirname(dirname(__FILE__)));
define('_PATH_SEPARATOR', preg_match("/WIN/i",PHP_OS) ? ";" : ":");
define('_VIEW_PATH', _WWW_ROOT.'/view/');
ini_set("include_path", "."._PATH_SEPARATOR._WWW_ROOT.'/lib/');
$_REQUEST['param'] = str_replace('_', '/', $_REQUEST['param']);
// var_dump(file_exists(_VIEW_PATH.$file.'.php'),_VIEW_PATH.$file.'.php');exit;
if(file_exists(_VIEW_PATH.$_REQUEST['param'].'.php')){
	require($_REQUEST['param'].'.php');
}else if(file_exists(_VIEW_PATH.$_REQUEST['param'].'.html')){
	Header("HTTP/1.1 301 Moved Permanently"); 
	require($_REQUEST['param'].'.html');
}else{
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
}
exit;
?>