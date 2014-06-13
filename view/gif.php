<body bgcolor="gray">
<?php 
require_once 'class/comm/class.file.php';
$files = File::pathFileList('E:/PHPworkspace/MyGame/view/source/image/GIF');

function digui($files){
	foreach ($files as $file){
		if(is_array($file)){
			digui($file);
		}else{
			echo '<img border="1" style="margin:1px;" src="source/image/gif/'.$file.'"/>';
		}
	}
}
digui($files);
?>
</body>