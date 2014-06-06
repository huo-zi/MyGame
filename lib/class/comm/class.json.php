<?php 
if (!function_exists('json_encode')) {
	function json_encode($array = array()) {
		if(!is_array($array)) return null;
		$json = "";
		$i = 1;
		$comma = ",";
		$count = count($array);
		foreach($array as $k=>$v){
			if($i==$count) $comma = "";
			if(!is_array($v)){
				$v = addslashes($v);
				$json .= '"'.$k.'":"'.$v.'"'.$comma;
			}
			else{
				$json .= '"'.$k.'":'.json_encode($v).$comma;
			}
			$i++;
		}
		$json = '{'.$json.'}';
		return $json;
	}
}

if (!function_exists('json_decode')) {
	function json_decode($json, $assoc = true) {
		$comment = false;
		$out     = '$x=';
		$json = preg_replace('/:([^"}]+?)([,|}])/i', ':"\1″\2', $json);
		for ($i=0; $i<strlen($json); $i++) {
			if (!$comment) {
				if (($json[$i] == '{') || ($json[$i] == '[')) {
					$out .= 'array(';
				}
				elseif (($json[$i] == '}') || ($json[$i] == ']')) {
					$out .= ')';
				}
				elseif ($json[$i] == ':') {
					$out .= '=>';
				}
				elseif ($json[$i] == ',') {
					$out .= ',';
				}
				elseif ($json[$i] == '"') {
					$out .= '"';
				}
			}
			else $out .= $json[$i] == '$' ? '\$' : $json[$i];
			if ($json[$i] == '"' && $json[($i-1)] != '\\')  $comment = !$comment;
		}
		eval($out. ';');
		return $x;
	}
}
?>