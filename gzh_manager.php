<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>公账号·查看/管理</title>
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
if(!isset($_SESSION['wx_uname'])){
 header("Location:index.php");
  exit ;
}
require 'comon.php';
$sql=mysqli_query($connect,"select * from wx_type order by ctime desc");
$j = 0;
while($type_row = mysqli_fetch_array($sql)){
  $type_result[$j] = $type_row;
  $j++;
}
if(isset($_POST['change'])){
  $ID_gzh= implode(",",$_POST['gzh']);
  mysqli_query($connect,"UPDATE wx_pinfo SET updates=0 where id in ($ID_gzh)");
  header("Location: gzh_manager.php");
  exit;
}
if(isset($_POST['unchange'])){
  $ID_gzh= implode(",",$_POST['gzh']);
  mysqli_query($connect,"UPDATE wx_pinfo SET updates=1 where id in ($ID_gzh)");
  header("Location: gzh_manager.php");
  exit;
}
if(isset($_POST['del'])){
  $ID_gzh= implode(",",$_POST['gzh']);
  mysqli_query($connect,"delete from wx_pinfo where id in ($ID_gzh)");
  header("Location: gzh_manager.php");
  exit;
}
if(isset($_POST['type_change'])){
	$ID_gzh = $_POST['gzh'];
	foreach($ID_gzh as $kid){
		$gtype = $_POST[$kid];
		$type_id = mysqli_query($connect,"select id from `wx_type` where `wx_type`='$gtype'");
		$id_result = mysqli_fetch_assoc($type_id);
		mysqli_query($connect,"UPDATE wx_pinfo SET `tid`='{$id_result['id']}' where id=$kid");
	}
	header("Location: gzh_manager.php");
  	exit;
}
if(isset($_POST['logout'])){
  $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}
//分页
$page_size=20;
$result=mysqli_query($connect,"select count(*) from wx_pinfo");
$count_row=mysqli_fetch_row($result);
$count=$count_row[0];
$page_count=ceil($count/$page_size);
$page_num_acc = $page_count;
$init=1;
$page_len=7;
//判断当前页码
if(empty($_GET['page'])||$_GET['page']<0){
$page=1;
}else {
$page=$_GET['page'];
}
$offset=$page_size*($page-1);
?>
<form id="gzhgl" action="gzh_manager.php" method="post">
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
   </tbody>
</table>
  <table border="1" align="center">
    <tbody>
      <tr>
        <td>全选
          <input type="checkbox" onclick='chkall("gzhgl",this)' value="全选"></td>
        <td>编号</td>
        <td>类型</td>
        <td>公众号名称</td>
        <td>微信号</td>
        <td>更新间隔</td>
        <td>更新状态</td>
        <td>创建时间</td>
        <td>创建人</td>
      </tr>
<?php 
if(isset($_POST['search'])){
	$wxnumber = $_POST['wxnumber'];
	$list = search_row('wx_pinfo','gnumber',$wxnumber);
	$t_resurt = mysqli_query($connect,"select * from wx_type where id = {$list['tid']}");
	$t_row = mysqli_fetch_assoc($t_resurt);
?>	
	<tr bgcolor="#B5B3A9">
          <td><?php if($t_row){echo "找到";}else{echo "未找到";} ?><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $list['id'] ?>"/></td>
        <td><?php echo $list['id']; ?></td>
        <td><select style="width:155px" name="<?php echo $list['id'] ?>"><?php echo arr_type($type_result,$t_row['wx_type']); ?></select></td>
        <td><?php echo $list['gname']; ?></td>
        <td><?php echo $list['gnumber']; ?></td>
        <td><?php echo $list['numbers']; ?></td>
        <td><?php states($list['updates']); ?></td>
        <td><?php echo date('Y-m-d H:i:s', $list['ctime']); ?></td>
        <td><?php echo $list['cname']; ?></td>
      </tr>
<?php
}
$gzh=mysqli_query($connect,"select a.`id`,b.`wx_type`,a.`gname`,a.`gnumber`,a.`cname`,a.`numbers`,a.`state`,a.`ctime`,a.`updates` from wx_pinfo AS a LEFT JOIN wx_type AS b on a.tid=b.id order by ctime desc limit $offset,$page_size");
while($re_row = mysqli_fetch_array($gzh)){
	//通过循环读取数据内容
	if($re_row['id']%2){
?>
      <tr bgcolor="#E0EEE0">
        <td><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $re_row['id'] ?>"/></td>
        <td><?php echo $re_row['id']; ?></td>
        <td><select style="width:155px" name="<?php echo $re_row['id'] ?>"><?php echo arr_type($type_result,$re_row['wx_type']); ?></select></td>
        <td><?php echo $re_row['gname']; ?></td>
        <td><?php echo $re_row['gnumber']; ?></td>
        <td><?php echo $re_row['numbers']; ?></td>
        <td><?php states($re_row['updates']); ?></td>
        <td><?php echo date('Y-m-d H:i:s', $re_row['ctime']); ?></td>
        <td><?php echo $re_row['cname']; ?></td>
      </tr>
      <?php
	}else{
		?>
        <tr>
        <td><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $re_row['id'] ?>"/></td>
        <td><?php echo $re_row['id']; ?></td>
        <td><select style="width:155px" name="<?php echo $re_row['id'] ?>"><?php echo arr_type($type_result,$re_row['wx_type']); ?></select></td>
        <td><?php echo $re_row['gname']; ?></td>
        <td><?php echo $re_row['gnumber']; ?></td>
        <td><?php echo $re_row['numbers']; ?></td>
        <td><?php states($re_row['updates']); ?></td>
        <td><?php echo date('Y-m-d H:i:s', $re_row['ctime']); ?></td>
        <td><?php echo $re_row['cname']; ?></td>
      </tr>
        <?php
		}
}
$page_len = ($page_len%2)?$page_len:$pagelen+1;//页码个数
$pageoffset = ($page_len-1)/2;//页码个数左右偏移量

$key='<div class="page">';
$key.="<span>$page/$page_num_acc</span> "; //第几页,共几页
if($page!=1){
$key.="<a href=\"".$_SERVER['PHP_SELF']."?page=1\">第一页</a> "; //第一页
$key.="<a href=\"".$_SERVER['PHP_SELF']."?page=".($page-1)."\">上一页</a>"; //上一页
}else {
$key.="第一页 ";//第一页
$key.="上一页"; //上一页
}
if($page_count>$page_len){
//如果当前页小于等于左偏移
if($page<=$pageoffset){
$init=1;
$page_count = $page_len;
}else{//如果当前页大于左偏移
//如果当前页码右偏移超出最大分页数
if($page+$pageoffset>=$page_count+1){
$init = $page_count-$page_len+1;
}else{
//左右偏移都存在时的计算
$init = $page-$pageoffset;
$page_count = $page+$pageoffset;
}
}
}
for($i=$init;$i<=$page_count;$i++){
if($i==$page){
$key.=' <span>'.$i.'</span>';
} else {
$key.=" <a href=\"".$_SERVER['PHP_SELF']."?page=".$i."\">".$i."</a>";
}
}
if($page!=$page_count){
$key.=" <a href=\"".$_SERVER['PHP_SELF']."?page=".($page+1)."\">下一页</a> ";//下一页
$key.="<a href=\"".$_SERVER['PHP_SELF']."?page={$page_num_acc}\">最后一页</a>"; //最后一页
}else {
$key.="下一页 ";//下一页
$key.="最后一页"; //最后一页
}
$key.='</div>';
?>
      <tr>
        <td colspan="6"><?php
	if($_SESSION['grade'] == 1){
?><input type="submit" name="change" value="更新选中账号"><input type="submit" name="unchange" value="不更新选中账号">
        
        <input type="text" name="wxnumber"><input type="submit" name="search" value="搜索微信号">
        <?php
	}
?></td>
        <td><?php
	if($_SESSION['grade'] == 1){
?><input type="submit" name="type_change" value="更改选中类别"><?php
	}
?></td>
        <td><?php
	if($_SESSION['grade'] == 1){
?><input type="submit" name="del" value="删除选中账号"><?php
	}
?></td>
        <td><a href="index.php">返回主页</a></td>
      </tr>
      <tr>
        <td colspan="9" bgcolor="#E0EEE0"><?php echo $key?></td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>