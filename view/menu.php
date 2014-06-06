<?php 
require_once '../lib/class/comm/class.file.php';
require_once '../lib/class/user/class.user.php';

// $url = ('https://www.google.com.hk/complete/search?client=serp&hl=zh-CN&gs_rn=36&gs_ri=serp&pq=test&cp=1&gs_id=2ia&q=t&xhr=t');
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// $result = curl_exec($ch);
// print_r($result);

$objUser = new User();
$objUser->checkAuth();
if($_POST['menuName'] && $_POST['menuUrl'] && $_POST['todo'] !== null){
	$menu[0] = $_POST['menuName'];
	$menu[1] = $_POST['menuUrl'];
	$menu[2] = $_POST['menuColor'];
	$menu[3] = $_POST['menuType'] ? trim($_POST['menuType']) : '未命名';
	$menu[4] = $objUser->getUserIp();
	$menu[5] = $_POST['menuTarget'];
	$info = join('|', $menu)."\n";
	if($_POST['todo'] === 'upd'){
		$menu = File::getFileInfo('../lib/conf','menu.conf');
		$menu[$_GET['chg']] = $info;
		$info = join('',$menu);
		File::writeFile('../lib/conf','menu.conf', $info, true);
	}else{
		File::writeFile('../lib/conf','menu.conf', $info);
	}
	Header("Location:".$_SERVER['SCRIPT_URI']);
}else if($_GET['del'] !== null){
	$menu = File::getFileInfo('../lib/conf','menu.conf');
	unset($menu[$_GET['del']]);
	$info = join('',$menu);
	File::writeFile('../lib/conf','menu.conf', $info, true);
	Header("Location:".$_SERVER['SCRIPT_URI']);  
}else if($_GET['chg'] !== null){
	$info = File::getFileInfo('../lib/conf', 'menu.conf', '|', $_GET['chg']);
}else if($_GET['top'] !== null){
	$index = $_GET['top'];
	$menu  = File::getFileInfo('../lib/conf','menu.conf');
	$info1 = $menu[$index];
	$info2 = $menu[$index+1];
	$menu[$index  ] = $info2;
	$menu[$index+1] = $info1;
	$info = join('',$menu);
	File::writeFile('../lib/conf','menu.conf', $info, true);
	Header("Location:".$_SERVER['SCRIPT_URI']);
}else if($_GET['btm'] !== null){
	$index = $_GET['btm'];
	$menu  = File::getFileInfo('../lib/conf','menu.conf');
	$info1 = $menu[$index-1];
	$info2 = $menu[$index];
	$menu[$index  ] = $info1;
	$menu[$index-1] = $info2;
	$info = join('',$menu);
	File::writeFile('../lib/conf','menu.conf', $info, true);
	Header("Location:".$_SERVER['SCRIPT_URI']);
}

if(false){
	$menu = File::getFileInfo('../lib/conf', 'menu.conf', '|');
}else{
	$rows = File::getFileInfo('../lib/conf','menu.conf');
	foreach ($rows as $r_k => $r_v){
		$row = explode('|',$r_v);
		$ico = substr($row[1], strpos($row[1], '://')+3);
		
		$ico = (strpos($ico, '/') === false) ? $ico : substr($ico, 0, strpos($ico, '/'));
		
		$yum = substr($ico, strrpos($ico, '.'));
		$ico = substr($ico, 0, strrpos($ico, '.'));
		
		$ico = (strrpos($ico,'.') === false) ? $ico : substr($ico, strrpos($ico, '.')+1);
		
		$row['id'] = $r_k;
		$row['ico'] = strpos($row[1], '://') ? 'http://www.'.$ico.$yum.'/favicon.ico' : '/favicon.ico';
		$menu[$row[3]][] = $row;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>网盟菜单</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="source/css/menu.css"/>
	<script type="text/javascript" src="source/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="source/js/comm.js"></script>
	<script type="text/javascript" src="source/js/jscolor/jscolor.js"></script>
	<script type="text/javascript">
	$(function(){
		$('.menu_tab tr.list').mouseover(function(){   
			$(this).addClass("over");
			$(this).find('img').show();})
		.mouseout(function(){
			$(this).removeClass("over");
			$(this).find('img').hide();});

		$('div.menu ul').mouseover(function(){
			$(this).find('span').eq(0).addClass('btn_edt').attr('title','修改').click(function(){location.href = '?chg='+$(this).attr('key');});
			$(this).find('span').eq(1).addClass('btn_del').attr('title','删除').click(function(){if(confirm('确定删除?他娘的删除了就真没了!!!'))location.href = '?del='+$(this).attr('key');});
		}).mouseout(function(){
			$(this).find('span').eq(0).removeClass('btn_edt').removeAttr('title').unbind("click");
			$(this).find('span').eq(1).removeClass('btn_del').removeAttr('title').unbind("click");
		});

		$('select[name=menuType]').change(function(){
			if(this.value == '自定义'){
				$($(this).parent().append('<input name="menuType" size="8"/>'))
			}else{
				$('input[name=menuType]').remove();
			}
		});
	});
	
	function hidn(){
		if(get('w_show').style.display != 'none'){
			get('w_show').style.display = 'none'
			get('w_box').style.display = 'none';
			document.menuForm.todo.value = 'add';
			document.menuForm.menuName.value = '';
			document.menuForm.menuUrl.value = '';
			document.menuForm.menuTarget[1].checked = 'checked';
			document.menuForm.menuColor.value = '0000FF';
		}else{
			get('w_show').style.display = 'block'
			get('w_box').style.display = 'block';
		}
	}

	function mnag(){
		$('div.menu').find('span.edit').addClass('btn_edt').attr('title','修改').click(function(){location.href = '?chg='+$(this).attr('key');});
		$('div.menu').find('span.delet').addClass('btn_del').attr('title','删除').click(function(){location.href = '?del='+$(this).attr('key');});;
	}

	function linkBlank(){
		var objA = document.createElement('a');
			objA.target = '_blank';
			objA.href = document.getElementById('w_link').value;
		document.body.appendChild(objA);
		objA.click();
	}
	
	function Jump(){
		var lKeyCode = (navigator.appname=="Netscape") ? event.which : window.event.keyCode; //event.keyCode按的建的代码，13表示回车
		if (lKeyCode == 13 ){
			linkBlank();
		}
	}
	</script>
</head>
<body>
<div class="shadow" id="w_show" <?php if(!$info){?>style="display:none;"<?php }?>></div>
<div class="f_box"  id="w_box"  <?php if(!$info){?>style="display:none;"<?php }?> align="center">
<form name="menuForm" id="menuForm" method="post">
<table style="width:300px;background:white;font-size:12px;">
  <tr><th colspan="2">添加菜单</th></tr>
  <tr><td>菜单名称：</td><td><input<?php if($info){?> value="<?php echo $info[0]?>"<?php }?> name="menuName"/></td></tr>
  <tr><td>菜单地址：</td><td><input<?php if($info){?> value="<?php echo $info[1]?>"<?php }?> name="menuUrl"/></td></tr>
  <tr><td>菜单组别：</td>
  	  <td><select name="menuType">
  	  	<?php foreach($menu as $type => $val){ ?>
  	  	<option value="<?php echo $type;?>" <?php if($info[3] == $type){echo 'selected="selected"';}?>><?php echo $type;?></option>
  	  	<?php }?>
  	  	<option value="自定义">自定义</option>
  	  </select></td>
  </tr>
  <tr><td>新  窗   口：</td><td><input name="menuTarget" type="radio" value="1"<?php if($info[5] == 1){?> checked="checked"<?php }?>/>是 <input type="radio" name="menuTarget" value="0"<?php if($info[5] == 0){?> checked="checked"<?php }?>>否</td></tr>
  <tr><td>字体颜色：</td><td><input<?php if($info){?> value="<?php echo $info[2]?>"<?php }?> name="menuColor" class="color" value="0000FF"/></td></tr>
  <tr>
	<td align="center" colspan="2"><input type="hidden" name="todo" value="<?php echo $info ? 'upd' : 'add'; ?>"/><input type="submit" value="确定"/><input type="button" value="取消" onclick="location.href='/menu.php'"/></td>
  </tr>
</table>
</form>
</div>

<!-- table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="menu_tab">
  <tr>
  	<th colspan="3"><h1 align="center">铁友菜单</h1></th>
  	<td colspan="5"></td>
  </tr>
  <?php foreach($menu as $k => $v){ ?>
  <tr class="list">
  	<td align="right">-----------------------------&gt;</td>
    <td width="70" align="center"><a <?php if($v[5] == 1){ echo 'target="_blank"';} ?> href="<?php echo $v[1]?>" style="color:#<?php echo $v[2]?>"><?php echo $v[0]?></a></td>
    <td height="30">&lt;-----------------------------</td>
    <td width="11"><img src="source/image/p.png" alt="修改" title="修改" style="display:none;cursor:pointer;" onclick="location.href='?chg=<?php echo $k;?>'"/> </td>
    <td width="11"><img src="source/image/x.png" alt="删除" title="删除" style="display:none;cursor:pointer;" onclick="if(confirm('确认删除,他娘的删除了就真没了!!!')){location.href='?del=<?php echo $k;?>'}"/> </td>
    <?php if($k-1 >= 0){?><td width="11"><img src="source/image/t.png" alt="上移" title="上移" style="display:none;cursor:pointer;" onclick="location.href='?top=<?php echo $k-1;?>'"/></td><?php }?>
    <td width="11"><?php if($k+1 < count($menu)-1){?><img src="source/image/b.png" alt="下移" title="下移" style="display:none;cursor:pointer;" onclick="location.href='?btm=<?php echo $k+1;?>'"/><?php }?> </td>
    <?php if($k-1 < 0){?><td width="11"></td><?php }?>
    <td width="2"></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="3" align="center"><div class="btn_add" title="添加" onclick="hidn();"></div></td>
    <td colspan="5"></td>
  </tr>
</table> -->

<div class="my_menu">
	<div class="title">我的导航
		<span class="btn btn_mng" title="管理" onclick="mnag()">+</span>
		<span class="btn btn_add" title="新建" onclick="hidn()">+</span>
	</div>
	<?php foreach($menu as $type => $val){ ?>
	<div class="line">
		<div class="type"><?php echo $type;?></div>
		<div class="menu">
			<?php foreach($val as $k => $v){ ?>
			<ul>
				<a href="<?php echo $v[1]?>"<?php if($v[5] == 1){ echo ' target="_blank"';} ?>><img src="<?php echo $v['ico']?>" width="16" height="16" title="<?php echo $v[0]?>"/></a>
				<li>
					<a href="<?php echo $v[1]?>" title="<?php echo $v[0]?>" style="color:#<?php echo $v[2]?>" <?php if($v[5] == 1){ echo ' target="_blank"';} ?>><?php echo $v[0]?></a>
				</li>
				<span class="btn edit" key="<?php echo $v['id'];?>">+</span>
				<span class="btn delet" key="<?php echo $v['id'];?>">+</span>
			</ul>
			<?php }?>
		</div>
	</div>
	<?php }?>
	<div class="line"><input id="w_link" onkeydown="Jump()" style="margin:3px 5px;width:92%;"/><button onclick="linkBlank()">猛戳</button></div>
</div>
</body>
</html>