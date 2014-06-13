<?php
require_once 'class/comm/class.file.php';
if($_FILES){
// 	var_dump($_FILES);
	if($_FILES['file']['type'] == 'application/vnd.ms-excel'){
		if ($_FILES['file']['error'] > 0){
			$error = "Error:".$_FILES['file']['error'];
		}else{
// 			echo "Upload: " . $_FILES['file']['name'] . "<br />";
// 			echo "Type: " . $_FILES['file']['type'] . "<br />";
// 			echo "Size: " . ($_FILES['file']['size'] / 1024) . " Kb<br />";
// 			echo "Stored in: " . $_FILES['file']['tmp_name'];
// 			if (!file_exists('source/upload/'.$_FILES['file']['name'])){
// 				$res = move_uploaded_file($_FILES['file']['tmp_name'], 'source/upload/' . $_FILES['file']['name']);
// 			}else{
// 				$res = 1;
// 			}
			$res = move_uploaded_file($_FILES['file']['tmp_name'], 'source/upload/' . $_FILES['file']['name']);
			$file = 'source/upload/'.$_FILES['file']['name'];
		}
	}else{
		$error = '只支持xls格式...';
	}
}
$type = intval($_REQUEST['type']);
if(intval($_REQUEST['type'])){
	if($type == 1){
		require_once 'class/comm/Excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		// 设置输入编码 UTF-8/GB2312/CP936等等
		$data->setOutputEncoding('GB2312');
		$data->read($_REQUEST['path']);
		$channels = array();
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$channel = array();
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
				$channel[] = $data->sheets[0]['cells'][$i][$j];
				if($_REQUEST['first'])break;
			}
			$channels[] = join('|', $channel);
		}
		echo json_encode($channels);exit;
	}else{
		$confs = File::pathFileList('../lib/conf/xls');
		echo json_encode($confs);exit;
	}
}

if($_REQUEST['save']&&$_REQUEST['name']&&$_REQUEST['data']){
	$info = explode(',',$_REQUEST['data']);
	File::writeFile('../lib/conf/xls',$_REQUEST['name'].'.conf', join("\n",$info), true);
	echo 1;exit;
}

if($_REQUEST['conf']&&$_REQUEST['name']){
	$info = File::getFileInfo('../lib/conf/xls', $_REQUEST['name'], ':');
	echo json_encode($info);exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>XLS文件统计</title>
<script type="text/javascript" src="/source/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/source/js/Map.js"></script>
<style type="text/css">
        body{ margin:0; padding:0; list-style:none;}
        .centerDiv{width:800px; margin:0 auto;padding:5px 5px;border:1px solid;}
        .shadow{width:100%;height:100%;position:fixed;margin:0;_height:800px;background:#FFF;filter:Alpha(opacity = 80);-moz-opacity:.9;opacity:1;_position:absolute;z-index:15;}
        .close{position:absolute;top:20px;right:20px;width:24px;height:24px;background:url('/source/image/ico_close.png') 0 0;}
        .close:hover{background:url('/source/image/ico_close.png') 0 -25px;}
        #tools{position:absolute;bottom:20px;left:35%;border:2px solid #000;}
        .miao  {width:100%;height:100%;position:fixed;display:table;text-align:center;z-index:16;}
        .miao label{display:table-cell;vertical-align:middle;height:20px;padding:0 20%;}
        .miao label span{width:150px;border:1px solid #EEE;border-radius:3px;margin-top:4px;margin-left:8px;padding:2px 5px;display:inline-block;color:#000;cursor:pointer;overflow:hidden;word-break:keep-all;white-space:nowrap;}
        .miao label span.yellow{border-color:#00F;background:yellow};
        .miao label span.gray{border-color:#F0F0F0;background:gray}
        .miao label span.gray:hover{border-color:blue;background:gray};
    </style>
</head>
<script type="text/javascript">
	function getInfo(){
		var type = $('select[name="type"]').val();
		var path = $('input[name="path"]').val();
		$.getJSON('?first=true&type='+type+'&path='+path,function(data){
			if(type == 1){
				var label = document.createElement('label');
					label.id="channel_label"
				for(var i in data){
					if(data[i]){
						label.innerHTML+= ('<span class="gray" onclick="this.style.display=\'none\';clickChannel(\''+data[i]+'\')">'+data[i]+'</span>');
					}
				}
				document.getElementById('w_miao').appendChild(label);
				document.getElementById('fenlei').style.display = 'block';
			}else{
				for(var i in data){
					if(data[i]){
						var option = document.createElement('option');
							option.value = data[i];
							option.innerHTML = data[i];
						document.getElementById('configs').appendChild(option);
					}
				}
				if(data.length){
					document.getElementById('xuanze').style.display = 'block';
				}
			}
			document.getElementById('begin').style.display = 'none';
		});
	}

	function selectConf(){
		document.getElementById('config').value = document.getElementById('configs').value;
		document.getElementById('xuanze').style.display = 'none';
		document.getElementById('jiexi').style.display = 'block';
	}
	
	function add(id){
		var info = document.createElement('tr');
			info.id = 'channel_'+id;	
			info.innerHTML = '<td valign="top"><input name="channelType" value="type'+id+'" size="5">:</td>'
			info.innerHTML+= '<td><textarea name="channelVal" id="channel'+id+'" rows="2" cols="80"></textarea></td>';
			info.innerHTML+= '<td><input type="button" value="添加" onclick="selectChannel('+id+')"/></td>';
		document.getElementById('channls').appendChild(info);
		document.getElementById('addChannel').name = parseInt(id)+1;
	}

	function deleteChannel(id){
		var channel = document.getElementById('channel_'+id);
		var channelVal = document.getElementById('channel'+id).value.split('|');
		document.getElementById('channls').removeChild(channel);
	}

	var selectId = 1;
	
	function selectChannel(id){
		document.getElementById('w_miao').style.display = 'block';
		document.getElementById('w_show').style.display = 'block';
		selectId = id;
		
	}

	function getKeywords(val){
		var lKeyCode = (navigator.appname=="Netscape") ? event.which : window.event.keyCode; //event.keyCode按的建的代码，13表示回车
		if (lKeyCode == 13){
			selectAll('key');
		}
		
		val = val.toLowerCase();
		var channls = document.getElementById('channel_label').childNodes;
		for(var i = 0; i<channls.length; i++){
			if(channls[i].style.display != 'none'){
				if(channls[i].innerHTML.toLowerCase().indexOf(val) != -1 && val != ''){
					channls[i].className = 'yellow';
				}else{
					channls[i].className = 'gray';
				}
			}
		}
	}
	
	function selectAll(type){
		document.getElementsByName('keywords')[0].value = '';
		var channls = document.getElementById('channel_label').childNodes;
		for(var i = 0; i<channls.length; i++){
			if(channls[i].style.display != 'none'){
				if(type == 'all'){
					clickChannel(channls[i].innerHTML);
					channls[i].style.display = 'none';
				}else{
					if(channls[i].className == 'yellow'){
						clickChannel(channls[i].innerHTML);
						channls[i].style.display = 'none';
					}
				}
			}
		}
	}

	function clickChannel(data){
		var channel = document.getElementById('channel'+selectId);
		channel.value += (channel.value == '' ? data : '|'+data);
	}
	
	function closeChannel(){
		document.getElementById('w_miao').style.display = 'none';
		document.getElementById('w_show').style.display = 'none';
	}

	function saveChannel(){
		var types = document.getElementsByName('channelType');
		var vals  = document.getElementsByName('channelVal');
		var name  = document.getElementById('fileName').value;
			name  = name ? name : 'xls';
		var data  = '';
		for(var i = 0; i< types.length; i++){
			data += types[i].value+':'+vals[i].value+",";
		}
		$.post('?save=true&name='+name,{'data':data},function(data){
			if(data == 1){
				alert('保存成功...');
				document.getElementById('config').value = '/'+name+'.conf';
				document.getElementById('fenlei').style.display = 'none';
				document.getElementById('jiexi').style.display = 'block';
			}
		});
	}

	function beginRead(){
		var conf = document.getElementById('config').value;
		var path = $('input[name="path"]').val();
		var types = new Map();
		$.getJSON('?conf=true&name='+conf,function(data){
			for(var i in data){
				var list = data[i][1].split('|');
				for(var j in list){
					types.put(list[j].replace(/\n/g, ''), data[i][0]);
				}
			}
			$.getJSON('?type=1&path='+path,function(data){
				var tongji = new Map;
				for(var k in data){
					if(data[k] != null){
						var rows = data[k].split('|');
						var name = rows.splice(0,1);
						var type = types.get(name);
						var info = tongji.get(type);
						if(info != undefined){
							for(var l in info){
								info[l] = parseInt(parseInt(info[l])+parseInt(rows[l])); 
							}
							tongji.put(type, info);
						}else{
							tongji.put(type, rows);
						}
					}
				}

				var array = tongji.keySet();
				var info  = '';
				for(var i in array) {
					info += (array[i]+":"+tongji.get(array[i]).join(',')+"\n");
				}
				document.getElementById('result').value = info;
			});
		});
	}
	
	window.onkeydown = function(e){
		if(document.getElementById('w_show').style.display != 'none'){
			if(e.keyCode == '27'){
				closeChannel();
			}
		}
	}
</script>
<body>
<div id="w_show" class="shadow" style="display:none;"></div>
<div id="w_miao" class="miao"   style="display:none;">
	<a class="close" id="w_close" href="javascript:void(0)" title="关闭" onclick="closeChannel()"></a>
	<div id="tools">
		<input type="button" value="全部添加" onclick="selectAll('all')"/>
		<font color="#000">关键字 ：</font><input name="keywords" onkeyup="getKeywords(this.value)"/>
		<input type="button" value="确定" onclick="selectAll('key')"/>
	</div>
</div>
<div class="centerDiv">
	<h2 align="center">XLS 统计</h2>
	<?php if (!$file){?>
	<form method="post" enctype="multipart/form-data">
		文件:<input type="file" name="file"/>
		<input type="submit" value="上传"/><label style="color:red;"><?php echo $error?></label>
	</form>
	<?php }else{?>
	<input type="button" value="重选" onclick="location.href=location.href"/>
		<?php if($res == 1){?>
		<label>文件：</label>
		<input name="file" value="<?php echo $_FILES['file']['name'];?>" title="<?php echo $_FILES['file']['name'];?>" style="border:0;" readonly="readonly"/>
		<input type="hidden" name="path" value="<?php echo $file?>"/>
		<select name="type">
			<option value="1" selected="selected">解析</option>
			<option value="2">选择文件</option>
		</select>
		<input id="begin" type="button" value="开始" onclick="getInfo()"/>
		<?php }else{?>
			上传失败
		<?php }?>
	<?php }?>
	
</div>
<br/>
<div id="fenlei" class="centerDiv" style="display:none;">
	<table id="channls">
		<tr>
			<td valign="top"><input name="channelType" value="type1" size="5">:</td>
			<td><textarea name="channelVal" id="channel1" rows="2" cols="80"></textarea></td>
			<td><input type="button" value="选择" onclick="selectChannel(1)"></td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="150"><input type="button" value="添加类型" id="addChannel" name="2" onclick="add(this.name)"></td>
			<td width="512" align="right"><input id="fileName" value="xls" size="15" style="text-align:right;">.conf</td>
			<td><input type="button" value="保存" id="saveChannel" onclick="saveChannel()"></td>
		</tr>
	</table>
</div>
<div id="xuanze" class="centerDiv" style="display:none;">
	<select id="configs"></select><input type="button" value="开始" onclick="selectConf()">
</div>
<div id="jiexi" class="centerDiv" style="display:none;">
	<input type="hidden" id="config"/>
	<input type="button" value="开始解析" id="beginRead" onclick="beginRead()">
	<textarea name="result" id="result" rows="10" cols="110"></textarea>
</div>
</body>
</html>