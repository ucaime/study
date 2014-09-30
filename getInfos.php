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
$ctime = time();
$sql=mysqli_query($connect,"select * from wx_type order by ctime desc");
$zhtime = mysqli_query($connect,"select * from wx_keys order by ctime desc");
$zhrow = mysqli_fetch_row($zhtime);
$ktime = 100 - ($ctime-$zhrow[2])/60;
if(isset($_POST['tijiao'])){
$gname = $_POST['gname'];
$gnumber = $_POST['gnumber'];
$type = $_POST['gtype'];
$wzurl = $_POST['wzurl'];
$numbers = $_POST['numbers'];
$days = $_POST['days'];
$gtime = strtotime($_POST['gtime']);
$cname = $_POST['cname'];
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
  $sql = mysqli_query($connect,"SELECT * FROM `wx_pinfo` WHERE `gnumber`='$gnumber';");
    $row = mysqli_fetch_array($sql);
    if($row){
        echo "该公众号已经收录,文章会定期更新.";
    }else{
	$sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_pinfo` 
(`tid`, `gname`, `gnumber`, `ctime`, `cname`, `days`, `ntime`,`gtime`, `numbers`,`updates`) VALUES 
('$type', '$gname', '$gnumber', '$ctime', '$cname', '$days', '$gtime','$gtime', '$numbers','$update');") or die("失败");
}}
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
if(isset($_POST['typesadd'])){
  $ctime = time();
  $types = $_POST['types'];
  $uname = $_POST['uname'];
  $sql = mysqli_query($connect,"INSERT INTO `wx_reads`.`wx_type` 
(`wx_type`, `ctime`,`cname`) VALUES 
('$types', '$ctime','$uname');") or die("失败");
  header("Location: getInfos.php");
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
  <input type="text" name="wzurl" >选填
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
<?php if($ktime>0){ ?>
key还有:<?php echo $ktime; ?>分钟失效
<?php }else{ ?>
没有有效的key
<?php } ?>
<form action="getInfos.php" method="post">
公众号类别:<input type="text" name="types">
提交人:<input type="text" name="uname">
<input type="submit" name="typesadd" value="提交">
</form>
</div>
<div style="clear:both"></div>
<br>
<hr>
<?php
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

$gzh=mysqli_query($connect,"select a.`id`,b.`wx_type`,a.`gname`,a.`gnumber`,a.`gtime`,a.`numbers`,a.`state`,a.`ctime`,a.`updates` from wx_pinfo AS a LEFT JOIN wx_type AS b on a.tid=b.id order by ctime desc limit $offset,$page_size");
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
if($page!=$page_num_acc){
$key.=" <a href=\"".$_SERVER['PHP_SELF']."?page=".($page+1)."\">下一页</a> ";//下一页
$key.="<a href=\"".$_SERVER['PHP_SELF']."?page={$page_num_acc}\">最后一页</a>"; //最后一页
}else {
$key.="下一页 ";//下一页
$key.="最后一页"; //最后一页
}
$key.='</div>';
?>
<tr>
<td colspan="9" bgcolor="#E0EEE0"><?php echo $key?></td>
</tr>
  </tbody>
</table>
</form>
文章链接管理
<form id="wzlj" action="getInfos.php" method="post">
<input type="submit" name="urlchange" value="更改选中状态">
<table border="1">
  <tbody>
    <tr>
      <td>全选<input type="checkbox" onclick='chkall("wzlj",this)' value="全选"></td>
      <td>编号</td>
      <td>公账号名称</td>
      <td>微信号</td>
      <td>所属类型</td>
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
    //分页
$page_size_url=20;
$result_url=mysqli_query($connect,"select count(*) from wx_article");
$count_row_url=mysqli_fetch_row($result_url);
$count_url=$count_row_url[0];
$page_count_url=ceil($count_url/$page_size_url);
$page_num_url=$page_count_url;
$init_url=1;
$page_len_url=7;
//判断当前页码
if(empty($_GET['page_url'])||$_GET['page_url']<0){
$page_url=1;
}else {
$page_url=$_GET['page_url'];
}
$offset_url=$page_size_url*($page_url-1);
$gurl=mysqli_query($connect,"select c.`id`,a.`gname`,a.`gnumber`,b.`wx_type`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`gtime`,c.`numbers`,c.`days`,c.state,c.uctime from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id order by uctime desc limit $offset_url,$page_size_url;");
    while($wenzhang = mysqli_fetch_array($gurl))//通过循环读取数据内容
{
  ?>
    <tr>
      <td><input name="wz[]" type="checkbox" id="wz[]" value="<?php echo $wenzhang['id'] ?>"/></td>
      <td><?php echo $wenzhang['id']; ?></td>
      <td><?php echo $wenzhang['gname']; ?></td>
      <td><?php echo $wenzhang['gnumber']; ?></td>
      <td><?php echo $wenzhang['wx_type']; ?></td>
      <td><?php echo th($wenzhang['wztitle']); ?></td>
      <td><?php echo $wenzhang['wzreads']; ?></td>
      <td><?php echo $wenzhang['wzsuports']; ?></td>
      <td><?php echo date('Y-m-d', $wenzhang['gtime']); ?></td>
      <td><?php echo $wenzhang['numbers']; ?></td>
      <td><?php echo $wenzhang['days']; ?></td>
      <td><?php states($wenzhang['state']); ?></td>
      <td><?php echo date('Y-m-d H:i:s', $wenzhang['uctime']); ?></td>
    </tr>
        <?php
}
$page_len_url = ($page_len_url%2)?$page_len_url:$pagelen_url+1;//页码个数
$pageoffset_url = ($page_len_url-1)/2;//页码个数左右偏移量

$key_url='<div class="page">';
$key_url.="<span>$page_url/$page_num_url</span> "; //第几页,共几页
if($page_url!=1){
$key_url.="<a href=\"".$_SERVER['PHP_SELF']."?page_url=1\">第一页</a> "; //第一页
$key_url.="<a href=\"".$_SERVER['PHP_SELF']."?page_url=".($page_url-1)."\">上一页</a>"; //上一页
}else {
$key_url.="第一页 ";//第一页
$key_url.="上一页"; //上一页
}
if($page_count_url>$page_len_url){
//如果当前页小于等于左偏移
if($page_url<=$pageoffset_url){
$init_url=1;
$page_count_url = $page_len_url;
}else{//如果当前页大于左偏移
//如果当前页码右偏移超出最大分页数
if($page_url+$pageoffset_url>=$page_count_url+1){
$init_url = $page_count_url-$page_len_url+1;
}else{
//左右偏移都存在时的计算
$init_url = $page_url-$pageoffset_url;
$page_count_url = $page_url+$pageoffset_url;
}
}
}
for($i=$init_url;$i<=$page_count_url;$i++){
if($i==$page_url){
$key_url.=' <span>'.$i.'</span>';
} else {
$key_url.=" <a href=\"".$_SERVER['PHP_SELF']."?page_url=".$i."\">".$i."</a>";
}
}
if($page_url!=$page_num_url){$key_url.=" <a href=\"".$_SERVER['PHP_SELF']."?page_url=".($page_url+1)."\">下一页</a> ";//下一页
$key_url.="<a href=\"".$_SERVER['PHP_SELF']."?page_url={$page_num_url}\">最后一页</a>"; //最后一页
}else {
$key_url.="下一页 ";//下一页
$key_url.="最后一页"; //最后一页
}
$key_url.='</div>';
?>
<tr>
<td colspan="13" bgcolor="#E0EEE0"><?php echo $key_url?></td>
</tr>
  </tbody>
</table>
</form>
</body>
</html>