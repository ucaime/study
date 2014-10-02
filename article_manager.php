<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章管理</title>
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
if(isset($_POST['change'])){
  $ID_gzh= implode(",",$_POST['wz']);
  mysqli_query($connect,"UPDATE `wx_article` SET `state`=0 where `id` in ($ID_gzh)");
  header("Location: article_manager.php");
  exit;
}
if(isset($_POST['unchange'])){
  $ID_gzh= implode(",",$_POST['wz']);
  mysqli_query($connect,"UPDATE `wx_article` SET `state`=1 where `id` in ($ID_gzh)");
  header("Location: article_manager.php");
  exit;
}
//分页
$page_size_url=20;
$result_url=mysqli_query($connect,"select count(*) from wx_article");
$count_row_url=mysqli_fetch_row($result_url);
$count_url=$count_row_url[0];
$page_count_url=ceil($count_url/$page_size_url);
$page_num_url=$page_count_url;
$init_url=1;
$page_len_url=15;
//判断当前页码
if(empty($_GET['page_url'])||$_GET['page_url']<0){
$page_url=1;
}else {
$page_url=$_GET['page_url'];
}
$offset_url=$page_size_url*($page_url-1);
$gurl=mysqli_query($connect,"select c.`id`,a.`gname`,a.`gnumber`,b.`wx_type`,c.`wztitle`,c.`wzreads`,c.`wzsuports`,c.`gtime`,c.`numbers`,c.`days`,c.state,c.ctime from wx_article AS c LEFT JOIN wx_pinfo AS a on c.uid=a.id LEFT JOIN wx_type AS b on a.tid=b.id order by ctime desc limit $offset_url,$page_size_url;");
?>
<form id="wzlj" action="" method="post">
<?php
	if($_SESSION['grade'] == 1){
?>
<input type="submit" name="change" value="更新选中账号"><input type="submit" name="unchange" value="不更新选中账号">
<?php
	}
?>
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
      <td><?php echo $wenzhang['gnumber']; ?></td>
      <td><?php echo $wenzhang['wx_type']; ?></td>
      <td><?php echo th($wenzhang['wztitle']); ?></td>
      <td><?php echo $wenzhang['wzreads']; ?></td>
      <td><?php echo $wenzhang['wzsuports']; ?></td>
      <td><?php states($wenzhang['state']); ?></td>
      <td><?php echo date('Y-m-d H:i:s', $wenzhang['ctime']); ?></td>
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
if($page_url!=$page_count_url){
$key_url.=" <a href=\"".$_SERVER['PHP_SELF']."?page_url=".($page_url+1)."\">下一页</a> ";//下一页
$key_url.="<a href=\"".$_SERVER['PHP_SELF']."?page_url={$page_num_url}\">最后一页</a>"; //最后一页
}else {
$key_url.="下一页 ";//下一页
$key_url.="最后一页"; //最后一页
}
$key_url.='</div>';
?>
<tr bgcolor="#E0EEE0">
<td colspan="9"><?php echo $key_url?></td>
<td><a href="index.php">返回</a></td>
</tr>
  </tbody>
</table>
</form>
</body>
</html>