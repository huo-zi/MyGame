<?php 
class File{
	
	public static function getFileInfo($path, $name, $char = null, $line = null){
		$file = fopen($path.'/'.$name, 'r') or die("open file failure!");
		if($line !== null){
			if(version_compare(PHP_VERSION, '5.1.0', '>=')){// 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
				$fp = new SplFileObject($path.'/'.$name, 'r') or die("open file failure!");
				$fp->seek($line);			// 转到第N行, seek方法参数从0开始计数
				$row = $fp->current();		// current()获取当前行内容
				$fp->next();				// 下一行
			}else{
				for($i = 0; $i < $line; $i++){
					fgets($file);
				}
				$row = fgets($file);
			}
			$info = ($char !== null) ? explode($char, $row) : $row;
		}else{
			while(!feof($file)){
				$row = fgets($file);
				if($row){
					$row = ($char !== null) ? explode($char, $row) : $row;
					$info[] = $row;
				}
			}
		}
		fclose($file);
		return $info;
	}
	
	public static function getFileLines($filename, $startLine = 1, $endLine= 50, $showLine = false, $method='rb') {
		$content = array();
		$count = $endLine - $startLine;
		if(version_compare(PHP_VERSION, '5.1.0', '>=')){// 判断php版本（因为要用到SplFileObject，PHP>=5.1.0）
			$fp = new SplFileObject($filename, $method) or die("open file failure!");
			$fp->seek($startLine);// 转到第N行, seek方法参数从0开始计数
			for($i = 0; $i <= $count; ++$i) {
				$info = $fp->current();
				$content[] = ($showLine ? ($startLine+$i).':' : '').$info;// current()获取当前行内容
				$fp->next();// 下一行
			}
		}else{//PHP<5.1
			$fp = fopen($filename, $method) or die("open file failure!");
			if(!$fp) return 'error:can not read file';
			for ($i=1;$i<$startLine;++$i) {// 跳过前$startLine行
				fgets($fp);
			}
			for($i;$i<=$endLine;++$i){
				$content[]=fgets($fp);// 读取文件行内容
			}
			fclose($fp);
		}
		return array_filter($content); // array_filter过滤：false,null,''
	}
	
	public static function writeFile($path, $name, $info, $clear = false){
		$type = ($clear === true) ? 'w' : 'a';
		$file = fopen($path.'/'.$name, $type);
		if(!fwrite($file, $info)){
			die('File write fail...');
		}
		fclose($file);
	}
	
	public static function pathFileList($path, $subPath = '', $isFile = true){
		$pathFile = $path.'/'.$subPath;
		if(is_dir($pathFile)){
			$dp = dir($pathFile);
			while($file = $dp->read()){
				if($file != '.' && $file != '..')
					$paths[] = self::pathFileList($path, $subPath.'/'.$file, $isFile);
			}
			$dp->close();
		}else{
			$paths = $subPath;
		}
		
		return $paths;
	}
	
	public static function getFileLineCount($pathFile){
		$line = 0 ; //初始化行数
		//打开文件
		$fp = fopen($pathFile , 'r') or die("open file failure!");
		if($fp){
			//获取文件的一行内容，注意：需要php5才支持该函数；
			while(stream_get_line($fp,1000000000,"\n")){
				$line++;
			}
			fclose($fp);//关闭文件
		}
		return $line;
	} 
	
	
	private static function fileExsits($pathFile){
		if(!file_exists($pathFile)){
			die('File Not Exsits:'.$pathFile);
		}
	}
}
?>