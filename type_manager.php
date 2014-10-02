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
require 'comon.php';
if(!isset($_SESSION['wx_uname'])){
  header("Location:index.php");
  exit ;
}
if(isset($_POST['change_type'])){
	$ID_gzh = $_POST['gzh'];
	foreach($ID_gzh as $kid){
		$gtype = $_POST[$kid];
		mysqli_query($connect,"UPDATE `wx_type` SET `wx_type`='$gtype' where id=$kid");
	}
	header("Location: type_manager.php");
  	exit;
}
if(isset($_POST['add_type'])){
  $ctime = time();
  $types = $_POST['type_name'];
  $uname = $_POST['cname'];
  $sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_type` 
(`wx_type`, `ctime`,`cname`) VALUES 
('$types', '$ctime','$uname');") or die("失败");
  header("Location: type_manager.php");
}
if(isset($_POST['del_type'])){
  $ID_gzh= implode(",",$_POST['gzh']);
  mysqli_query($connect,"delete from wx_type where id in ($ID_gzh)");
  header("Location: type_manager.php");
  exit;
}
//分页
$page_size=20;
$result=mysqli_query($connect,"select count(*) from wx_type ");
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
<form id="gzhgl" action="" method="post">
<table border="1" align="center">
  <tbody>
  	<tr>
      <td colspan="5"><?php
	if($_SESSION['grade'] == 1){
?>
      类别名称:<input type="text" name="type_name" >
      创建人:<input type="text" name="cname">
      <input type="submit" name="add_type" value="添加类别">
      <?php } ?>
      </td>
    </tr>
    <tr>
      <td>全选<input type="checkbox" onclick='chkall("gzhgl",this)' value="全选"></td>
      <td>编号</td>
      <td>类别名称</td>
      <td>创建人</td>
      <td>创建时间</td>
    </tr>
    <?php
	$sql=mysqli_query($connect,"select * from wx_type order by ctime desc limit $offset,$page_size;");
    while($re_row = mysqli_fetch_array($sql)){
	//通过循环读取数据内容
		if($re_row['id']%2){
?>
	<tr bgcolor="#D5CBCB">
      <td><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $re_row['id'] ?>"/></td>
      <td><?php echo $re_row['id']; ?></td>
      <td><input type="text" name="<?php echo $re_row['id']; ?>" value="<?php echo $re_row['wx_type']; ?>"></td>
      <td><?php echo $re_row['cname']; ?></td>
      <td><?php echo date('Y-m-d H:i:s', $re_row['ctime']); ?></td>
    </tr>
    <?php
    	}else{
			?>
			<tr>
      <td><input name="gzh[]" type="checkbox" id="gzh[]" value="<?php echo $re_row['id'] ?>"/></td>
      <td><?php echo $re_row['id']; ?></td>
      <td><input type="text" name="<?php echo $re_row['id']; ?>" value="<?php echo $re_row['wx_type']; ?>"></td>
      <td><?php echo $re_row['cname']; ?></td>
      <td><?php echo date('Y-m-d H:i:s', $re_row['ctime']); ?></td>
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
	<tr bgcolor="#E0EEE0">
        <td colspan="2" ><?php
	if($_SESSION['grade'] == 1){
?><input type="submit" name="change_type" value="更改选中类别" ><?php } ?></td>
        <td colspan="2"><?php
	if($_SESSION['grade'] == 1){
?><input type="submit" name="del_type" value="删除选中类别" ><?php } ?></td>
        <td><a href="index.php">返回</a></td>
	</tr>
	<tr>
        <td colspan="5" bgcolor="#E0EEE0"><?php echo $key?></td>
	</tr>
  </tbody>
</table>
</form>
</body>
</html>