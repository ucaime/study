<?php session_start(); ?>
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
.title{
  font-size: 23px;
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
<table border="1" align="center">
  <tbody>
    <tr bgcolor="#308598">
      <td colspan="2" class="title"><?php echo $type_row['wx_type']; ?></td>
      <td colspan="4"></td>
    </tr>
    <tr bgcolor="#308598">
      <td>排名</td>
      <td>微信公众号</td>
      <td>文章标题</td>
      <td>阅读</td>
      <td>点赞</td>
      <td>点赞率</td>
    </tr>
    <?php 
$wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`ctime` from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$time group by wztitle,uid order by c.wzreads desc,c.wzsuports desc LIMIT 20;");
$i=1;
while($wz_row = mysqli_fetch_array($wz)){
  if($i%2){
?>
    <tr bgcolor="#DBEDF4">
      <td width="40"><?php echo $i; ?></td>
      <td width="140"><?php echo $wz_row['gname']; ?></td>
      <td width="330"><?php echo th($wz_row['wztitle']); ?></td>
      <td width="50"><?php echo $wz_row['wzreads']; ?></td>
      <td width="40"><?php echo $wz_row['wzsuports']; ?></td>
      <td width="60"><?php echo (floor($wz_row['wzsuports']/$wz_row['wzreads']*10000)/10000*100)."%"; ?></td>
    </tr>
<?php
        }else{
?>
    <tr bgcolor="#B6DDE9">
      <td width="40"><?php echo $i; ?></td>
      <td width="140"><?php echo $wz_row['gname']; ?></td>
      <td width="330"><?php echo th($wz_row['wztitle']); ?></td>
      <td width="50"><?php echo $wz_row['wzreads']; ?></td>
      <td width="40"><?php echo $wz_row['wzsuports']; ?></td>
      <td width="60"><?php echo (floor($wz_row['wzsuports']/$wz_row['wzreads']*10000)/10000*100)."%"; ?></td>
    </tr>
<?php
    }
  $i++;
} ?>
  </tbody>
</table>
<br>
<?php } ?>
</body>
</html>