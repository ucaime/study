<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>添加</title>
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
</head>

<body>
<?php
require 'comon.php';
require_once 'Snoopy.class.php';
require_once 'login.php';
$sql=mysqli_query($connect,"select * from wx_type order by ctime desc");
if(isset($_POST['tijiao'])){
$gname = $_POST['gname'];
$gnumber = $_POST['gnumber'];
$type = $_POST['gtype'];
$wzurl = $_POST['wzurl'];
$numbers = $_POST['numbers'];
$days = $_POST['days'];
$gtime = strtotime($_POST['gtime']);
$cname = $_POST['cname'];
$ctime = time();
$update = $_POST['update'];
if(!$wzurl==''){
	$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_pinfo` 
(`tid`, `gname`, `gnumber`, `wzurl`, `ctime`, `cname`, `days`, `gtime`,`ntime`, `numbers`) VALUES 
('$type', '$gname', '$gnumber','$wzurl', '$ctime', '$cname', '$days',$gtime, '$gtime', '$numbers');") or die("失败");
$sql=mysqli_query($connect,"select `id` from `wx_pinfo` where `gnumber` = '$gnumber';");
$id = mysqli_fetch_row($sql);
$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_article` 
(`uid`, `wzurl`, `imgurl`, `wztitle`, `wzcontent`, `description`, `wzreads`, `wzsuports`, `ctime`, `gtime`, `ntime`,`numbers`, `days`, `state`, `uctime`) VALUES 
('$id[0]', '$wzurl', '', '', NULL, '', '0', '0', '$ctime', '$gtime','$gtime', '$numbers', '$days', '0', '$ctime');") or die("失败");
}else{
	$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_pinfo` 
(`tid`, `gname`, `gnumber`, `ctime`, `cname`, `days`, `ntime`,`gtime`, `numbers`,`updates`) VALUES 
('$type', '$gname', '$gnumber', '$ctime', '$cname', '$days', '$gtime','$gtime', '$numbers','$update');") or die("失败");
}
header("Location: getInfos.php");
}
if(isset($_POST['add'])){
  $ctime = time();
  $wxkeys = $_POST['wxkeys'];
  $sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_keys` 
(`keys`, `ctime`) VALUES 
('$wxkeys', '$ctime');") or die("失败");
  header("Location: getInfos.php");
}
function states($num){
  if($num==0){echo "更新中";}
  if($num==1){echo "未更新";}
}
function urlstates($num){
  if($num==1){echo "更新中";}
  if($num==0){echo "未更新";}
}
if(isset($_POST['gzhchange'])){
  $ID_gzh= implode(",",$_POST['gzh']);
  mysqli_query($connect,"UPDATE wx_pinfo SET updates=0 where id in ($ID_gzh)");
  header("Location: getInfos.php");
  exit;
}
if(isset($_POST['urlchange'])){
  $ID_url= implode(",",$_POST['wz']);
  mysqli_query($connect,"UPDATE wx_article SET state=1 where id in ($ID_url)");
  header("Location: getInfos.php");
  exit;
}
?>
<div style="float:left">
<form action="getInfos.php" method="post">
  公众号名称:
  <input type="text" name="gname" >
  <br>
  公众号微信号:
  <input type="text" name="gnumber" >
  <br>
  公众号类别:
  <select style="width:155px" name="gtype">
    <?php
while($re_row = mysqli_fetch_array($sql))//通过循环读取数据内容
{
	?>
    <option value="<?php echo $re_row['id'] ?>"><?php echo $re_row['wx_type'] ?></option>
    <?php
}
?>
  </select>
  <br>
  文章url:
  <input type="text" name="wzurl" >
  <br>
  当天更新间隔:
  <input type="text" name="numbers" >单位为小时
  <br>
  文章更新天数　:
  <input type="text" name="days" >
  <br>
  开始　时间:
  <input type="text" name="gtime" >格式为:年月日时分,例2014年9月21日12点05为:201409211205
  <br>
  创　建　人:
  <input type="text" name="cname" >
  <br>
  是否自动更新:<input type="text" name="update">不填文章url有效，0不更新，1更新
  <input type="submit" name="tijiao" value="提交">
</form>
</div>
<div style="float:left" action="getInfos.php" method="post">
<form action="getInfos.php" method="post">
key:<input type="text" name="wxkeys">
<input type="submit" name="add" value="提交">
</form>
</div>
<div style="clear:both"></div>
<br>
<hr>
<div style="float:left;">
<?php
$gzh=mysqli_query($connect,"select a.`id`,b.`wx_type`,a.`gname`,a.`gnumber`,a.`gtime`,a.`numbers`,a.`state`,a.`ctime`,a.`updates` from wx_pinfo AS a LEFT JOIN wx_type AS b on a.tid=b.id where `updates` = 1 order by ctime desc");
$gurl=mysqli_query($connect,"select c.`id`,a.`gname`,b.`wx_type`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`gtime`,c.`numbers`,c.`days`,c.state,c.ctime from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id where c.state=0 order by ctime desc;");
?>
公众号管理
<form id="gzhgl" action="getInfos.php" method="post">
<input type="submit" name="gzhchange" value="更改选中状态">
<table border="1">
  <tbody>
    <tr>
      <td>全选<input type="checkbox" onclick='chkall("gzhgl",this)' value="全选"></td>
      <td>编号</td>
      <td>类型</td>
      <td>公众号名称</td>
      <td>微信号</td>
      <td>开始时间</td>
      <td>更新间隔</td>
      <td>更新状态</td>
      <td>创建时间</td>
    </tr>
    <?php 
    while($re_row = mysqli_fetch_array($gzh))//通过循环读取数据内容
{
  ?>
    <tr>
    <td><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $re_row['id'] ?>"/></td>
      <td><?php echo $re_row['id']; ?></td>
      <td><?php echo $re_row['wx_type']; ?></td>
      <td><?php echo $re_row['gname']; ?></td>
      <td><?php echo $re_row['gnumber']; ?></td>
      <td><?php echo date('Y-m-d', $re_row['gtime']); ?></td>
      <td><?php echo $re_row['numbers']; ?></td>
      <td><?php urlstates($re_row['updates']); ?></td>
      <td><?php echo date('Y-m-d', $re_row['ctime']); ?></td>
    </tr>
        <?php
}
?>
  </tbody>
</table>
</form>
</div>
<div style="float:left;">
文章链接管理
<form id="wzlj" action="getInfos.php" method="post">
<input type="submit" name="urlchange" value="更改选中状态">
<table border="1">
  <tbody>
    <tr>
      <td>全选<input type="checkbox" onclick='chkall("wzlj",this)' value="全选"></td>
      <td>编号</td>
      <td>所属类型</td>
      <td>公账号名称</td>
      <td>文章标题</td>
      <td>阅读数</td>
      <td>点赞数</td>
      <td>开始时间</td>
      <td>更新间隔</td>
      <td>更新天数</td>
      <td>更新状态</td>
      <td>创建时间</td>
    </tr>
    <?php 
    while($wenzhang = mysqli_fetch_array($gurl))//通过循环读取数据内容
{
  ?>
    <tr>
      <td><input name="wz[]" type="checkbox" id="wz[]" value="<?php echo $wenzhang['id'] ?>"/></td>
      <td><?php echo $wenzhang['id']; ?></td>
      <td><?php echo $wenzhang['gname']; ?></td>
      <td><?php echo $wenzhang['wx_type']; ?></td>
      <td><?php echo $wenzhang['wztitle']; ?></td>
      <td><?php echo $wenzhang['wzreads']; ?></td>
      <td><?php echo $wenzhang['wzsuports']; ?></td>
      <td><?php echo date('Y-m-d', $wenzhang['gtime']); ?></td>
      <td><?php echo $wenzhang['numbers']; ?></td>
      <td><?php echo $wenzhang['days']; ?></td>
      <td><?php states($wenzhang['state']); ?></td>
      <td><?php echo date('Y-m-d', $wenzhang['ctime']); ?></td>
    </tr>
        <?php
}
?>
  </tbody>
</table>
</form>
</div>
</body>
</html>