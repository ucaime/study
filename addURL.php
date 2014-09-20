<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>微信阅读数采集</title>
</head>

<body>
<form action="" method="post">
  文章url前缀<input type="text" name="wzurl">
  微信key<input type="text" name="key">
  <input type="submit" name="tijiao" value="提交">
</form>
<?php
if($_POST['tijiao']){
$wzurl = $_POST['wzurl'];
$key = $_POST['key'];
get_read($wzurl,$key);
mysqli_close($connect);
header("Location: http://localhost/wx_read.php");
}
function get_read($url='',$key,$uid=1){
$wzurl = "http://mp.weixin.qq.com/s?{$url}&key={$key}&ascene=1&uin=Nzc5OTI2MTIx&pass_ticket=kzTtalLseTrEcwKVHswOVecGMByySYu94gRKdAXokFv2jg4UAOt%2FkrZHb0IwUlSB";
$bsurl = "http://mp.weixin.qq.com/s?{$url}";
require_once "Snoopy.class.php";
$snoopy = new Snoopy;
$snoopy->agent = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16"; //伪装浏览器
$snoopy->fetch($wzurl); //获取所有内容
$content = $snoopy->results; //显示结果
preg_match('/<span id="readNum">([^<]*)<\/span>/si', $content, $read);
preg_match('/var likeNum = \'([^<]*)\';/si', $content, $suport);
// $content = htmlspecialchars($content);
$content = str_replace(array("'", "\""),array("\\'","\\\""), $content);
if($suport[1]=="赞"){$suprot=0;}
$read=(int)$read[1];
$suport = (int)$suport[1];
$connect = mysqli_connect("127.0.0.1", "root", "root") or die("链接数据库失败！");
mysqli_select_db($connect,"wx_read" ) or die(mysqli_errno($connect));
mysqli_query($connect,"SET NAMES 'utf8'");
$sql = mysqli_query($connect,"INSERT INTO `wx_article` (`uid`, `url`, `content`, `reads`, `suports`) VALUES ('$uid','$bsurl','$content','$read','$suport');");
echo "INSERT INTO `wx_article` (`uid`, `url`, `content`, `reads`, `suports`) VALUES ('$uid','$bsurl','$content','$read','$suport');";
}
?>
</body>
</html>