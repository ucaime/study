<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<style>
.biaodan{
	width:1000px;
	margin:0 auto;
}
.biaodan input{
	width:50px;
}
</style>
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
$connect = mysqli_connect("127.0.0.1", "cti", "son0326") or die("链接数据库失败！");
mysqli_select_db($connect,"wx_read" ) or die(mysqli_errno($connect));
mysqli_query($connect,"SET NAMES 'utf8'");
$sql=mysqli_query($connect,"select * from wx_url where socket = 0");
// $re_row = mysqli_fetch_all($sql,MYSQLI_ASSOC);


?>
<div class="biaodan">
<form action="" id="aspnetForm1" method="post">
微信号:<input type="text" name="wx_num">
昵称:<input type="text" name="wx_name">
文章url:<input style="width:150px;" type="text" name="url">
目标阅读数:<input type="text" name="yuedu">

<input type="submit" name="submit" value="添加">
<input type="submit" name="reset" value="重置">
</div>
<?php
if($_POST['submit']){
  $wx_num=$_POST['wx_num'];
  $wx_name=$_POST['wx_name'];
  $url=$_POST['url'];
  $yuedu=$_POST['yuedu'];
  $createtime=$_POST['ctime'];
  $count=$_POST['count'];
  $time = time();
  $sql=mysqli_query($connect,"insert into wx_url(wx_num,wx_name,url,yuedu,createtime,count) values('$wx_num','$wx_name','$url','$yuedu','$time',0);");
  if($sql){
    echo "添加成功";
    header("Location: http://st1.vmeti.com:8012/admin.php");
    exit;
  }else{
    echo "添加失败";
  }
}
  if($_POST['socket']){
    $ID_Dele= implode(",",$_POST['ID_Dele']);
    // $SQL="delete from `doing` where id in ($ID_Dele)"; 
    mysqli_query($connect,"UPDATE wx_url SET socket=1 where id in ($ID_Dele)");
    header("Location: http://st1.vmeti.com:8012/admin.php");
    exit;
  }

  if($_POST['reset']){
    mysqli_query($connect,"UPDATE wx_user SET urlid1=0");
    header("Location: http://st1.vmeti.com:8012/admin.php");
    exit;
  }

?>
<table width="1000" border="1" align="center">
  <tbody>
  <tr>
  <td></td>
      <td>编号</td>
      <td>微信号</td>
      <td width="65">昵称</td>
      <td>文章链接</td>
      <td>目标阅读数</td>
      <td>创建时间</td>
      <td>已完成阅读数</td>
    </tr>
    <tr><td><input type="checkbox" onclick='chkall("aspnetForm1",this)'
                        value="全选"></td>

<td></td><td></td><td></td><td></td><td></td><td></td><td><input type="submit" name="socket" value="禁用链接"></td>
                        </tr>
<?php
while($re_row = mysqli_fetch_array($sql))//通过循环读取数据内容
{
?>

<tr>
<td><input name="ID_Dele[]" type="checkbox" id="ID_Dele[]" value="<?php echo $re_row['id'] ?>"/></td>
      <td><?php echo $re_row['id'];?></td>
      <td><?php echo $re_row['wx_num'];?></td>
      <td><?php echo $re_row['wx_name'];?></td>
      <td><?php echo $re_row['url'];?></td>
      <td><?php echo $re_row['yuedu'];?></td>
      <td><?php echo date('Y-m-d', $re_row['createtime']);?></td>
      <td><?php echo $re_row['count'];?></td>
    </tr>
<?php
}
?>
  </tbody>
</table>
</form>
</body>
</html>