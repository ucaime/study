<?php
$connect = mysqli_connect("127.0.0.1", "root", "root") or die("链接数据库失败！");
mysqli_select_db($connect,"wx_reads" ) or die("选择数据库失败");
mysqli_query($connect,"SET NAMES 'utf8'");
function p($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}
function arr_type($row='',$type_cur=''){
	$type_html = "";
	foreach($row as $key=>$k){
		if($k[2] == $type_cur){
			$type_html = $type_html."<option selected='selected' value={$k[2]}>{$k[2]}</option>";
			}else{
				$type_html = $type_html."<option value={$k[2]}>{$k[2]}</option>";
			}
		}
	return $type_html;
}
function search_row($biao,$ziduan1,$ziduan2,$value){
	$connect = mysqli_connect("127.0.0.1", "root", "root") or die("链接数据库失败！");
	mysqli_select_db($connect,"wx_reads" ) or die("选择数据库失败");
	mysqli_query($connect,"SET NAMES 'utf8'");
	$result = mysqli_query($connect,"select * from `{$biao}` where `{$ziduan1}` = '{$value}' || `{$ziduan2}` = '{$value}';");
	return mysqli_fetch_array($result);
}
function th($str){
  $title = str_replace(array("u201c","u201d","u2026","u200b","u2014","u2022"),array("“","”","...","","——","·"),$str);
  return $title;
}
function states($num){
  if($num==1){echo "更新中";}
  if($num==0){echo "未更新";}
}
function urlstates($num){
  if($num==0){echo "更新中";}
  if($num==1){echo "未更新";}
}
function urlsocket($num){
  if($num==0){echo "显示";}
  if($num==1){echo "不显示";}
}
?>