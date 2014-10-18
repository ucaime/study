<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>排行</title>
<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script language="JavaScript">
    function chkall(input1,input2)
    {
        var objForm = document.forms[input1];
        var objLen = objForm.length;
        for (var iCount = 0; iCount < objLen; iCount++)
        {
            if (input2.checked == true)
            {
                if (objForm.elements[iCount].type == "checkbox")
                {
                    objForm.elements[iCount].checked = true;
                }
            }
            else
            {
                if (objForm.elements[iCount].type == "checkbox")
                {
                    objForm.elements[iCount].checked = false;
                }
            }
        }
    }
</script>
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
if(isset($_POST['change'])){
  $ID_gzh= implode(",",$_POST['wz']);
  mysqli_query($connect,"UPDATE `wx_article` SET `socket`=0 where `id` in ($ID_gzh)");
  header("Location: paihang.php");
  exit;
}
if(isset($_POST['unchange'])){
  $ID_gzh= implode(",",$_POST['wz']);
  mysqli_query($connect,"UPDATE `wx_article` SET `socket`=1 where `id` in ($ID_gzh)");
  header("Location: paihang.php");
  exit;
}
?>
<form id="wzlj" action="" method="post">
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
    <tr>
    <td colspan="2">日期<input style="width:100px;" class="Wdate" type="text" onClick="WdatePicker()" name="dates">阅读权<input name="r1" value="0.5" style="width:50px;">点赞权<input style="width:50px;" type="text" name="r2" value="0.3">频次权<input style="width:50px;" name="r3" value="0.2"></td>
    <td><input type="submit" name="chaxun" value="查看"></td>
    <?php
  if($_SESSION['grade'] == 1){
?>
    <td><input type="submit" name="change" value="选中文章排行中显示"></td>
    <td><input type="submit" name="unchange" value="选中文章排行中不显示"></td>
    <td><input type="submit" name="create" value="生成该天排行"></td>
    <td><input type="submit" name="createw" value="生成上周排行"></td>
    <?php
  }
?>
</tr>
   </tbody>
</table>
<?php
if(isset($_POST['chaxun'])){
  $r1=$_POST['r1'];
  $r2=$_POST['r2'];
  $r3=$_POST['r3'];
  $ptime = strtotime($_POST['dates']);
  $ntime = $ptime + 86400;
  $stime = strtotime(date("Ymd",strtotime('-7 day',$ptime)));
  ?>
  <h2 align="center"><?php echo $_POST['dates'];?>排行</h2>
<?php
while($type_row = mysqli_fetch_array($type))//通过循环读取数据内容
{
  $maxsql = mysqli_query($connect,"select MAX(wzreads) as `maxreads`,MAX(wzsuports) as `maxwzsuports` from `wx_article` where `ctime`>$ptime and `ctime`<$ntime and `uid` in (select `id` from `wx_pinfo` where `tid` = {$type_row['id']}) ORDER BY `ctime` DESC LIMIT 100;");
  $maxread = mysqli_fetch_assoc($maxsql);
?>
<table border="1" align="center">
  <tbody>
    <tr bgcolor="#308598">
      <td colspan="2" class="title"><?php echo $type_row['wx_type']; ?></td>
      <td colspan="6"></td>
    </tr>
    <tr bgcolor="#308598">
    <td>全选<input type="checkbox" onclick='chkall("wzlj",this)' value="全选"></td>
      <td>排名</td>
      <td>微信公众号</td>
      <td>文章标题</td>
      <td>阅读</td>
      <td>点赞</td>
      <td>点赞率</td>
      <td>更新频次</td>
      <td>综合排名</td>
      <td>排行</td>
    </tr>
    <?php
$wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`ctime`,c.id,c.uid,c.socket,c.wzurl from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$ntime order by c.totals desc, c.wzreads desc,c.wzsuports desc;");
$i=1;
while($wz_row = mysqli_fetch_assoc($wz)){
$pinsql = mysqli_query($connect,"select count(*) from wx_article where uid ={$wz_row['uid']} and ctime>$stime and ctime<$ptime group by from_unixtime(ctime,'%Y-%m-%d');");
$pinci = mysqli_num_rows($pinsql);
$totals = round($r1*($wz_row['wzreads']/$maxread['maxreads'])*100+$r2*($wz_row['wzsuports']/$maxread['maxwzsuports'])*100+$r3*($pinci/7)*100);
mysqli_query($connect,"UPDATE `wx_reads`.`wx_article` SET `pinci`='$pinci',`totals`='$totals' WHERE (`id`='{$wz_row['id']}');");
  if($i%2){
?>
    <tr bgcolor="#DBEDF4">
    <td><input name="wz[]" type="checkbox" id="wz[]" value="<?php echo $wz_row['id'] ?>"/></td>
      <td width="40"><?php echo $i; ?></td>
      <td width="140"><?php echo $wz_row['gname']; ?></td>
      <td width="330"><a href="<?php echo $wz_row['wzurl'] ?>" target='_blank'><?php echo th($wz_row['wztitle']); ?></a></td>
      <td width="50"><?php echo $wz_row['wzreads']; ?></td>
      <td width="40"><?php echo $wz_row['wzsuports']; ?></td>
      <td width="60"><?php echo (floor($wz_row['wzsuports']/$wz_row['wzreads']*10000)/10000*100)."%"; ?></td>
      <td><?php echo $pinci; ?></td>
      <td><?php echo $totals; ?></td>
      <td><?php urlsocket($wz_row['socket']); ?></td>
    </tr>
<?php
        }else{
?>
    <tr bgcolor="#B6DDE9">
    <td><input name="wz[]" type="checkbox" id="wz[]" value="<?php echo $wz_row['id'] ?>"/></td>
      <td width="40"><?php echo $i; ?></td>
      <td width="140"><?php echo $wz_row['gname']; ?></td>
      <td width="330"><a href="<?php echo $wz_row['wzurl'] ?>" target='_blank'><?php echo th($wz_row['wztitle']); ?></a></td>
      <td width="50"><?php echo $wz_row['wzreads']; ?></td>
      <td width="40"><?php echo $wz_row['wzsuports']; ?></td>
      <td width="60"><?php echo (floor($wz_row['wzsuports']/$wz_row['wzreads']*10000)/10000*100)."%"; ?></td>
      <td><?php echo $pinci; ?></td>
      <td><?php echo $totals; ?></td>
      <td><?php urlsocket($wz_row['socket']); ?></td>
    </tr>
<?php
    }
  $i++;
  } 
$j++;}
?>
  </tbody>
</table>
</form>
<?php
  
}
if(isset($_POST['create'])){
  $r1=$_POST['r1'];
  $r2=$_POST['r2'];
  $r3=$_POST['r3'];
  $ptime = strtotime($_POST['dates']);
  $ntime = $ptime + 86400;
  $stime = strtotime(date("Ymd",strtotime('-7 day',$ptime)));
  $j=0;
  while($type_row = mysqli_fetch_array($type)){
    $rarray[$j]['type_name']= $type_row['wx_type'];
    $wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`pinci`,c.`totals`,c.`ctime`,c.id,c.uid,c.socket,c.wzurl from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$ntime order by c.totals desc, c.wzreads desc,c.wzsuports desc LIMIT 20;");
    $i=1;
    while($wz_row = mysqli_fetch_assoc($wz)){
      $rarray[$j]['lists'][$i] = $wz_row;
      $rarray[$j]['lists'][$i]['wztitle']=th($wz_row['wztitle']);
      $i++;
      } 
    $j++;
  }
$smarty->assign("dates",$_POST['dates']);
$smarty->assign("contect", $rarray);  
$contect = $smarty->fetch("index.tpl");  
file_put_contents("web/{$_POST['dates']}.html", $contect);
echo "<a href='web/{$_POST['dates']}.html' target='_blank' style='text-align:center; display:block;'>查看生成的网页</a>";
}
if(isset($_POST['createw'])){
  $ptime = mktime(0, 0, 0,date("m"),date("d")-date("w")+1-7,date("Y"));
  $ntime = mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
  $p_time = date("Y-m-d",mktime(0, 0, 0,date("m"),date("d")-date("w")+1-7,date("Y")));
  $n_time = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
  $j=0;
  while($type_row = mysqli_fetch_array($type)){
    $rarray[$j]['type_name']= $type_row['wx_type'];
    $wz = mysqli_query($connect,"select a.`gname`,c.`wztitle`,c.socket,c.`wzreads`,c.`wzsuports`,c.`pinci`,c.`totals`,c.`ctime`,c.wzurl from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where b.`wx_type`='{$type_row['wx_type']}' and c.ctime>$ptime and c.ctime<$ntime and c.socket=0 order by c.totals desc, c.wzreads desc,c.wzsuports desc LIMIT 20;");
    $i=1;
    while($wz_row = mysqli_fetch_assoc($wz)){
      $rarray[$j]['lists'][$i] = $wz_row;
      $rarray[$j]['lists'][$i]['wztitle']=th($wz_row['wztitle']);
      $i++;
      } 
    $j++;
  }
  p($rarray);
$smarty->assign("week",1);
$smarty->assign("datesp",$ptime);
$smarty->assign("datesn",$ntime);
$smarty->assign("contect", $rarray);
$contect = $smarty->fetch("index.tpl");
file_put_contents("web/{$p_time}-{$n_time}.html", $contect);
echo "<a href='web/{$p_time}-{$n_time}.html' target='_blank' style='text-align:center; display:block;'>查看生成的网页</a>";
}
?>
</body>
</html>