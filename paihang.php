<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>排行</title>
<style>
ul{
	width:600px;
}
li{
	margin-left:10px;
	width:100px;
	float:left;
	list-style-type:none;
}
</style>
</head>

<body>
<?php
require 'comon.php';
$time = strtotime(date('Y-m-d', time()));
$ptime = $time - 86400;
$type = mysqli_query($connect,"select * from wx_type;");
while($type_row = mysqli_fetch_array($type))//通过循环读取数据内容
{
?>
<h1><?php echo $type_row['wx_type']; ?></h1>
<table border="1">
  <tbody>
    <tr>
      <td>排名</td>
      <td>微信公众号</td>
      <td>文章标题</td>
      <td>阅读数</td>
      <td>点赞数</td>
      <td>点赞率</td>
    </tr>
    <?php 
$wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`ctime` from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$time group by wztitle,uid order by c.wzreads desc,c.wzsuports desc LIMIT 20;");
$i=1;
while($wz_row = mysqli_fetch_array($wz))//通过循环读取数据内容
{
?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $wz_row['gname']; ?></td>
      <td><?php echo th($wz_row['wztitle']); ?></td>
      <td><?php echo $wz_row['wzreads']; ?></td>
      <td><?php echo $wz_row['wzsuports']; ?></td>
      <td><?php echo (floor($wz_row['wzsuports']/$wz_row['wzreads']*10000)/10000*100)."%"; ?></td>
    </tr>
    <?php $i++;} ?>
  </tbody>
</table>
<?php } ?>
</body>
</html>