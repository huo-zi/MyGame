<style>
.table_data{
	width:90px;
	height:20px;
	border-style:ridge;
	border-width:1;
	border-color:RGB(218,220,221);
}
.td_header{
	height:20px;
	width:30px;
	background-image:url("/source/image/excel.png");
	background-repeat:no-repeat;
	background-position:21px 8px;
}
.tab_base{	
	background:#C5D0DD;
	border-style:ridge;
	border-width:1;
	cursor:pointer;
	width:90;height:20;
}
.table_sub_heading{
	background:RGB(223,227,232);
	font-weight:bold;
	border-style:ridge;
	border-width:1;
	text-align:center;
	border-color:RGB(177,181,186);
}
.table_body{
	background:#F0F0F0;
	font-wieght:normal;
	font-size:12;
	font-family:sans-serif;
	border-style:ridge;
	border-width:1;
	border-spacing: 0px;
	border-collapse: collapse;
}
.tab_loaded
{
	background:#222222;
	color:white;
	font-weight:bold;
	border-style:groove;
	border-width:1;
	cursor:pointer;
	width:90;height:20;
}
</style>
<?
$allow_url_override = 1; // Set to 0 to not allow changed VIA POST or GET
if(!$allow_url_override || !isset($file_to_include)){
	$file_to_include = "source/upload/abc.xls";
}

if(!$allow_url_override || !isset($max_rows)){
	$max_rows = 0; //USE 0 for no max
}

if(!$allow_url_override || !isset($max_cols)){
	$max_cols = 5; //USE 0 for no max
}

if(!$allow_url_override || !isset($debug)){
	$debug = 0;  //1 for on 0 for off
}

if(!$allow_url_override || !isset($force_nobr)){
	$force_nobr = 1;  //Force the info in cells not to wrap unless stated explicitly (newline)
}

require_once '../lib/class/comm/Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CPa25a');
$data->read($file_to_include);
error_reporting(E_ALL ^ E_NOTICE);

function make_alpha_from_numbers($number){
	$numeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	if($number<strlen($numeric)){
		return $numeric[$number];
	}else{
		$dev_by = floor($number/strlen($numeric));
		return "" . make_alpha_from_numbers($dev_by-1) . make_alpha_from_numbers($number-($dev_by*strlen($numeric)));
	}
}
echo '<script type="text/javascript">';
echo "var sheet_HTML = Array();\n";
for($sheet=0;$sheet<count($data->sheets);$sheet++){
	$table_output[$sheet] .= "<table CLASS='table_body'>
	<tr>
		<TD class='td_header'>&nbsp;</TD>";
	for($i=0;$i<$data->sheets[$sheet]['numCols']&&($i<=$max_cols||$max_cols==0);$i++)
	{
		$table_output[$sheet] .= "<TD CLASS='table_sub_heading' ALIGN=CENTER>" . make_alpha_from_numbers($i) . "</TD>";
	}
	for($row=1;$row<=$data->sheets[$sheet]['numRows']&&($row<=$max_rows||$max_rows==0);$row++)
	{
		$table_output[$sheet] .= "<tr><TD CLASS='table_sub_heading'>" . $row . "</TD>";
		for($col=1;$col<=$data->sheets[$sheet]['numCols']&&($col<=$max_cols||$max_cols==0);$col++)
		{
			if($data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] >=1 && $data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] >=1)
			{
				$this_cell_colspan = " COLSPAN=" . $data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];
				$this_cell_rowspan = " ROWSPAN=" . $data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];
				for($i=1;$i<$data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$i++)
				{
					$data->sheets[$sheet]['cellsInfo'][$row][$col+$i]['dontprint']=1;
				}
				for($i=1;$i<$data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];$i++)
				{
					for($j=0;$j<$data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$j++)
					{
						$data->sheets[$sheet]['cellsInfo'][$row+$i][$col+$j]['dontprint']=1;
					}
				}
			}
			else if($data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'] >=1)
			{
				$this_cell_colspan = " COLSPAN=" . $data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];
				$this_cell_rowspan = "";
				for($i=1;$i<$data->sheets[$sheet]['cellsInfo'][$row][$col]['colspan'];$i++)
				{
					$data->sheets[$sheet]['cellsInfo'][$row][$col+$i]['dontprint']=1;
				}
			}
			else if($data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'] >=1)
			{
				$this_cell_colspan = "";
				$this_cell_rowspan = " ROWSPAN=" . $data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];
				for($i=1;$i<$data->sheets[$sheet]['cellsInfo'][$row][$col]['rowspan'];$i++)
				{
					$data->sheets[$sheet]['cellsInfo'][$row+$i][$col]['dontprint']=1;
				}
			}
			else
			{
				$this_cell_colspan = "";
				$this_cell_rowspan = "";
			}
			if(!($data->sheets[$sheet]['cellsInfo'][$row][$col]['dontprint']))
			{
				$table_output[$sheet] .= "<TD CLASS='table_data' $this_cell_colspan $this_cell_rowspan>&nbsp;";
				if($force_nobr)
				{
					$table_output[$sheet] .= "<NOBR>";
				}
				$table_output[$sheet] .= nl2br(htmlentities($data->sheets[$sheet]['cells'][$row][$col]));
				if($force_nobr)
				{
					$table_output[$sheet] .= "</NOBR>";
				}
				$table_output[$sheet] .= "</TD>";
			}
		}
		$table_output[$sheet] .= "</tr>";
	}
	$table_output[$sheet] .= "</table>";
	$table_output[$sheet] = str_replace("\n","",$table_output[$sheet]);
	$table_output[$sheet] = str_replace("\r","",$table_output[$sheet]);
	$table_output[$sheet] = str_replace("\t"," ",$table_output[$sheet]);
	if($debug)
	{
		$debug_output = print_r($data->sheets[$sheet],true);
		$debug_output = str_replace("\n","\\n",$debug_output);
		$debug_output = str_replace("\r","\\r",$debug_output);
		$table_output[$sheet] .= "<PRE>$debug_output</PRE>";
	}
	echo "sheet_HTML[$sheet] = \"$table_output[$sheet]\";\n";
}
echo "
function change_tabs(sheet)
{
	//alert('sheet_tab_' + sheet);
	for(i=0;i<" , count($data->sheets) , ";i++)
	{
		document.getElementById('sheet_tab_' + i).className = 'tab_base';
	}
	document.getElementById('table_loader_div').innerHTML=sheet_HTML[sheet];
	document.getElementById('sheet_tab_' + sheet).className = 'tab_loaded';

}
</script>";

echo "<table CLASS='table_body' NAME='tab_table'>
<tr>";
for($sheet=0;$sheet<count($data->sheets);$sheet++){
	echo "<TD CLASS='tab_base' ID='sheet_tab_$sheet' ALIGN=CENTER
		ONMOUSEDOWN=\"change_tabs($sheet);\">", $data->boundsheets[$sheet]['name'] , "</TD>";
}
echo "	<tr>\n";
echo "</table><br/>\n";
echo "<DIV ID=table_loader_div></DIV>\n";
echo '<script type="text/javascript">change_tabs(0);</script>';
?>