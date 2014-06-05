<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=GBK">
	<style type="text/css">
        *{ margin:0; padding:0; list-style:none;}
        
        .shadow{width:100%;height:100%;position:fixed;margin:0;_height:800px;background:#000;filter:Alpha(opacity = 80);-moz-opacity:.5;opacity:0.7;_position:absolute;z-index:15;}
        .miao  {width:100%;height:100%;position:fixed;display:table;text-align:center;z-index:16;}
        .miao span{display:table-cell;vertical-align:middle;}
        .miao span img{border:2px solid gray;}
		.close{position:absolute;top:20px;right:20px;width:24px;height:24px;background:url('/source/image/ico_close.png') 0 0;}
		.prev {position:absolute;top:50%; left :35px;width:70px;height:65px;background:url('/source/image/g_arrow.png') 0 0;}
		.next {position:absolute;top:50%; right:35px;width:70px;height:65px;background:url('/source/image/g_arrow.png') -75px 0;}
    </style>
	<script type="text/javascript">

		var picWidth = 0;
		var picHeight= 0;
		function openPic(id){
			var file  = files[id];
			document.getElementById('pic').name= id;
			document.getElementById('pic').alt = file;
			document.getElementById('pic').src = '/source/image/GIF/gif'+file;
			document.getElementById('w_miao').style.display = 'table';
			document.getElementById('w_show').style.display = 'block';
			document.getElementById('w_prev').style.display = (id == 0) ? 'none' : 'block';
			document.getElementById('w_next').style.display = (id == files.length-1) ? 'none' : 'block';

			var img = new Image();
				img.src = '/source/image/GIF/gif'+file
			if(img.complete){
				picWidth = img.width;
				picHeight= img.height;

				changeSize();
			    img = null;
			}else{
			    img.onload=function(){
			    	picWidth = img.width;
					picHeight= img.height;
			        img = null;
			        changeSize();
			    };
			}
		}

		function closePic(){
			document.getElementById('w_miao').style.display = 'none';
			document.getElementById('w_show').style.display = 'none';
		}

		function changePic(num){
			var id = document.getElementById('pic').name;
				id = parseInt(id) + parseInt(num);
				id = id < 0 ? 0 : id;
				id = id > files.length-1 ? files.length-1 : id;
			openPic(id);
		}

		window.onkeydown = function(e){
			if(document.getElementById('w_show').style.display != 'none'){
				if(e.keyCode == '27'){
					closePic();
				}else if(e.keyCode == '37'){
					changePic(-1);
				}else if(e.keyCode == '39'){
					changePic(1);
				}
			}
		}

		var changeSize = function(){
			var pic = document.getElementById('pic');

			if(window.innerWidth < picWidth || window.innerHeight < picHeight){
				if(window.innerWidth - picWidth < window.innerHeight - picHeight){
					pic.width = window.innerWidth - 50;
					pic.height= pic.width / picWidth * picHeight;
				}else{
					pic.height= window.innerHeight- 50;
					pic.width = pic.height/picHeight * picWidth;
				}
			}else{
				pic.width = picWidth;
				pic.height= picHeight;
			}
		}

		window.onresize = changeSize;
</script>
</head>
<body>
<div id="w_show" class="shadow" style="display:none;"></div>
<div id="w_miao" class="miao"   style="display:none;">
    <span><img id="pic" src="/source/image/GIF/gif/2609741_980x1200_0.jpg" /></span>
	<a class="close" id="w_close" href="javascript:void(0)" title="关闭"   onclick="closePic()"></a>
	<a class="prev"  id="w_prev"  href="javascript:void(0)" title="上一张" onclick="changePic(-1)"></a>
	<a class="next"  id="w_next"  href="javascript:void(0)" title="下一张" onclick="changePic(1)"></a>
</div>
<?php 
define('_LIB_ROOT' , dirname(dirname(dirname(__FILE__))) . '/lib/');
define('_PATH_SEPARATOR', preg_match("/WIN/i",PHP_OS) ? ";" : ":");
ini_set("include_path", "."._PATH_SEPARATOR._LIB_ROOT);
$fild = 'GIF/gif';
require_once 'class/comm/class.file.php';
$files = File::pathFileList('E:/PHPworkspace/MyGame/view/source/image/'.$fild);
echo '<div style="margin:0 auto;width:800px;border:1px solid;">';
foreach ($files as $k=>$file){
	echo '<img id="'.$k.'" width="auto" src="/source/image/'.$fild.'/'.$file.'" style="width:100px;" border="1" onclick="openPic(this.id)"/>';
}
$fileArray = "<script>var files = ['".join("','",$files)."'];</script>";

echo '</div>'.$fileArray;
?>
</body>
</html>