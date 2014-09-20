<?php
$connect = mysqli_connect("localhost", "root", "root") or die("链接数据库失败！");
mysqli_select_db($connect,"wx_reads" ) or die("选择数据库失败");
mysqli_query($connect,"SET NAMES 'utf8'");
function p($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}
?>