<?php 
class User{
	private $ip = null;
	public function User($ip = ''){
		$this->ip = $ip;
	}
	
	public function checkAuth(){
		$ip = $this->getUserIp();
		if($ip != '127.0.1.1'){
			if($_GET['del'] !== null || $_GET['chg'] !== null){
				$id = ($_GET['del'] !== null) ? $_GET['del'] : $_GET['chg'];
				
				if(!class_exists('File')){
					require_once '../lib/class/comm/class.file.php';
				}
				$info = File::getFileInfo('../lib/conf', 'menu.conf', '|', $id);
				if($ip != $info[4]){
					header("content-type:text/html; charset=utf-8");
					die('<script>alert("似乎你对这条信息没啥权限呢...");location.href="'.$_SERVER['SCRIPT_URI'].'"</script>');
				}
			}
		}
	}
	
	public function getUserIp(){
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
}
?>