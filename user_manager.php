<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>用户管理</title>
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
if(!(isset($_SESSION['wx_uname']) && $_SESSION['grade']==1)){
  header("Location:index.php");
  exit ;
}
if($_POST['del']){
$ID_Dele= implode(",",$_POST['ID_Dele']);
mysqli_query($connect,"DELETE FROM wx_user WHERE id in ($ID_Dele)");
header("Location: user_manager.php");
echo "删除成功";
exit;
}
if($_POST['adduser']){
    $name=$_POST['u_name'];
    $pwd=md5($_POST['u_pwd']);
    $time = time();
    $user_sql = mysqli_query($connect,"select * from `wx_user` where `name`='$name';");
    if($user_row = mysqli_fetch_assoc($user_sql)){
    	echo "账户已存在";
  	}else{
	    $sql=mysqli_query($connect,"insert into wx_user(`name`,`upwd`,`grade`,`time`) values('$name','$pwd','0','$time');");
	    if($sql){
	      header("Location: user_manager.php");
		  echo "添加成功";
	      exit;
	    }else{
	      echo "添加失败";
	      exit;
	    }
	}
  }
$sql = mysqli_query($connect,"select * from wx_user where `grade`=0;");
?>
<form action="" id="aspnetForm1" method="post">
<table border="1">
	<tbody>
		用户名:<input type="text" name="u_name">
		密码:<input type="password" name="u_pwd">
		<input type="submit" name="adduser" value="添加用户">
        <a href="index.php">返回</a>
		<tr>
			<td></td>
			<td>用户名称</td>
			<td>创建时间</td>
		</tr>
		<tr>
			<td><input type="checkbox" onclick='chkall("aspnetForm1",this)'value="全选"></td>
			<td><input type="submit" name="del" value="删除用户"></td>
			<td></td>
		</tr>
		<?PHP while ($result = mysqli_fetch_array($sql)) { ?>
		<tr>
			<td><input name="ID_Dele[]" type="checkbox" id="ID_Dele[]" value="<?php echo $result['id'] ?>"/></td>
			<td><?php echo $result['name']; ?></td>
			<td><?php echo date('Y-m-d H:i:s',$result['time']); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</form>
</body>
</html>