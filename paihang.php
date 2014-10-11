<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>排行</title>
<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
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
if(isset($_POST['logout'])){
  $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<?php
if(!isset($_SESSION['wx_uname'])){
 header("Location:index.php");
  exit ;
}
require 'comon.php';
require 'libs/Smarty.class.php';
$smarty = new Smarty;
//实例化Smarty和配置Smarty属性  
$smarty = new Smarty();    //实例化Smarty对象  
$smarty->template_dir = "templates";    //模板文件的目录  
$smarty->compile_dir = "templates_c";    //编译的模板文件  
$smarty->config_dir = "configs";        //配置文件目录  
$smarty->cache_dir = "cache";           //缓存的所有文件  
$smarty->caching = false;
$smarty->left_delimiter = "<{";  
$smarty->right_delimiter = "}>";
$type = mysqli_query($connect,"select * from wx_type;");
?>
<form id="gzhgl" action="" method="post">
<table border="1" align="center">
  <tbody>
    <tr bgcolor="#E0EEE0">
      <td><a href="index.php">公众号·添加</a></td>
      <td><a href="gzh_manager.php">公众号·查看/管理</a></td>
      <td><a href="type_manager.php">公众号类别·查看/管理</a></td>
      <td><a href="article_manager.php">文章·查看/管理</a></td>
      <td><a href="paihang.php">排行</a></td>
      <?php
  if($_SESSION['grade'] == 1){
?>
      <td><a href="user_manager.php">用户·查看/管理</a></td>
      <?php } ?>
      <td><input type="submit" name="logout" value="退出" ></td>
    </tr>
    <tr><td colspan="5"><input class="Wdate" type="text" onClick="WdatePicker()" name="dates"></td><td><input type="submit" name="chaxun" value="查看"></td><td><input type="submit" name="create" value="生成页面"></td></tr>
   </tbody>
</table>
</form>
<?php
if(isset($_POST['chaxun'])){
  $ptime = strtotime($_POST['dates']);
  $ntime = $ptime + 86400;
  ?>
  <h2 align="center"><?php echo $_POST['dates'];?>排行</h2>
<?php
$j=1;
while($type_row = mysqli_fetch_array($type))//通过循环读取数据内容
{
  $rarray[$j]['type_name']= $type_row['wx_type'];
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
$wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`ctime` from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$ntime and socket=0 order by c.wzreads desc,c.wzsuports desc LIMIT 20;");
$i=1;
while($wz_row = mysqli_fetch_assoc($wz)){
  $rarray[$j]['lists'][$i] = $wz_row;
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
  } 
$j++;
?>
  </tbody>
</table>
<br>
<?php
  }
}
if(isset($_POST['create'])){
  $ptime = strtotime($_POST['dates']);
  $ntime = $ptime + 86400;
  $j=1;
  while($type_row = mysqli_fetch_array($type)){
    $rarray[$j]['type_name']= $type_row['wx_type'];
    $wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`ctime` from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$ntime and socket=0 order by c.wzreads desc,c.wzsuports desc LIMIT 20;");
    $i=1;
    while($wz_row = mysqli_fetch_assoc($wz)){
      $rarray[$j]['lists'][$i] = $wz_row;
      $i++;
      } 
    $j++;
  }
echo "<pre>";
print_r($rarray);
echo "</pre>";
$smarty->assign("dates",$_POST['dates']);
$smarty->assign("contect", $rarray);  
$contect = $smarty->fetch("index.tpl");  
file_put_contents("paihang.html", $contect);
}
?>
</body>
</html>