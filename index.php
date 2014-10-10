<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>添加</title>
</head>

<body>
<!--登陆-->
<?php
require 'comon.php';
if(isset($_POST['login'])){
  $uname = $_POST['uname'];
  $pwd = md5($_POST['pwd']);
  $user_sql = mysqli_query($connect,"select * from `wx_user` where `name`='$uname' and `upwd`='$pwd';");
  if($user_row = mysqli_fetch_assoc($user_sql)){
    $_SESSION['wx_uname'] = $uname;
    $_SESSION['grade'] = $user_row['grade'];
    header("Location: index.php");
  }else{
    echo "账号秘密错误";
  }
}
if(!isset($_SESSION['wx_uname'])){
  echo '<br>members only, please login';
  echo '<br> <form method="post" action="index.php" >
  <p>username: <input type="text" name="uname" value="" /></p>
  <p>password: <input type="password" name="pwd" value="" /></p>
  <p><input type="submit" name="login" value="login" /></p>
  </form>';
  exit ;
}
if(isset($_POST['logout'])){
	$_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}
$ctime = time();
$sql=mysqli_query($connect,"select * from wx_type order by ctime desc");

if(isset($_POST['tijiao'])){
$gname = $_POST['gname'];
$gnumber = $_POST['gnumber'];
$type = $_POST['gtype'];
$wzurl = $_POST['wzurl'];
$numbers = $_POST['numbers'];
$days = $_POST['days'];
$gtime = strtotime($_POST['gtime']);
$cname = $_SESSION['wx_uname'];
$update = $_POST['update'];
if(!$wzurl==''){
	$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_pinfo` 
(`tid`, `gname`, `gnumber`, `wzurl`, `ctime`, `cname`, `gtime`,`ntime`) VALUES 
('$type', '$gname', '$gnumber','$wzurl', '$ctime', '$cname',$ctime, '$ctime');") or die("失败");
$sql=mysqli_query($connect,"select `id` from `wx_pinfo` where `gnumber` = '$gnumber';");
$id = mysqli_fetch_row($sql);
$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_article` 
(`uid`, `wzurl`, `imgurl`, `wztitle`, `wzcontent`, `description`, `wzreads`, `wzsuports`, `ctime`, `gtime`, `ntime`, `uctime`) VALUES 
('$id[0]', '$wzurl', '', '', NULL, '', '0', '0', '$ctime', '$ctime','$ctime', '$ctime');") or die("失败");
}else{
  $sql = mysqli_query($connect,"SELECT * FROM `wx_pinfo` WHERE `gnumber`='$gnumber';");
    $row = mysqli_fetch_array($sql);
    if($row){
        echo "该公众号已经收录,文章会定期更新.";
    }else{
	$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_pinfo` 
(`tid`, `gname`, `gnumber`, `ctime`, `cname`,`ntime`,`gtime`) VALUES 
('$type', '$gname', '$gnumber', '$ctime', '$cname', '$ctime','$ctime');") or die("失败");
}}
header("Location: index.php");
}
?>
<!--登陆-->
<form action="" method="post">
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
      <td>参数</td>
      <td>值</td>
      <td>备注</td>
    </tr>
    <tr>
      <td>公众号</td>
      <td><input type="text" name="gname" ></td>
      <td>搜狗搜到的</td>
    </tr>
    <tr>
      <td>微信号</td>
      <td><input type="text" name="gnumber" ></td>
      <td>搜狗搜到的</td>
    </tr>
    <tr>
      <td>公众号类别</td>
      <td><select style="width:155px" name="gtype">
    <?php
while($re_row = mysqli_fetch_array($sql))//通过循环读取数据内容
{
	?>
    <option value="<?php echo $re_row['id'] ?>"><?php echo $re_row['wx_type'] ?></option>
    <?php
}
?>
  </select></td>
      <td></td>
    </tr>
    <tr>
      <td>文章url</td>
      <td><input type="text" name="wzurl" ></td>
      <td>选填，作为指定更新文章</td>
    </tr>
<!--     <tr>
      <td>更新间隔</td>
      <td><input type="text" name="numbers" ></td>
      <td>单位为小时 </td>
    </tr>
    <tr>
      <td>更新天数</td>
      <td><input type="text" name="days" ></td>
      <td>录入时间开始计算，更新的几天</td>
    </tr>
    <tr>
      <td>开始时间</td>
      <td><input type="text" name="gtime" ></td>
      <td>格式为:年月日时分,例2014年9月21日12点05为:201409211205</td>
    </tr>
    <tr>
      <td>是否自动跟新</td>
      <td><input type="text" name="update"></td>
      <td>不填文章url有效，0不更新，1更新</td>
    </tr>
    <tr>
      <td>创建人</td>
      <td><input type="text" name="cname" ></td>
      <td>值</td>
    </tr> -->
    <tr>
      <td colspan="3" align="right"><input type="reset" name="reset" value="重置"><input type="submit" name="tijiao" value="提交"></td>
    </tr>
  </tbody>
</table>
</form>
</body>
</html>