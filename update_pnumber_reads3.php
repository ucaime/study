<?php
require_once 'Snoopy.class.php';
require_once 'comon.php';
for(;;){
	$time = time();
	$sql=mysqli_query($connect,"select * from wx_article where `state`= 0 and `ntime`<=$time;");
	$re_row = mysqli_fetch_array($sql);
	$keysql = mysqli_query($connect,"select * from wx_keys where $time-`ctime`<7000 order by ctime asc");
	$key = mysqli_fetch_row($keysql);
	if($re_row && $key){
        $ntime = $time+$re_row['numbers']*3600;
		$wz = get_read($re_row['wzurl'],$key[1]);
		if(!$wz['read']==''){
			mysqli_query($connect,"UPDATE wx_article SET `wzreads`='{$wz['read']}',`wzsuports`='{$wz['suport']}' where id = {$re_row['id']}");
		}
		mysqli_query($connect,"UPDATE `wx_reads`.`wx_article` SET `ntime`='$ntime' WHERE (`id`='{$re_row['id']}');");
		if($time-$re_row['ctime']>86400*$re_row['days']){
			mysqli_query($connect,"UPDATE `wx_reads`.`wx_article` SET `state`=1 WHERE (`id`='{$re_row['id']}');");
		}
	}
	sleep(3);
}
function get_read($url='',$key,$uid=1){
$wzurl = "{$url}&key={$key}&ascene=1&uin=Nzc5OTI2MTIx&pass_ticket=kzTtalLseTrEcwKVHswOVecGMByySYu94gRKdAXokFv2jg4UAOt%2FkrZHb0IwUlSB";
$bsurl = "http://mp.weixin.qq.com/s?{$url}";
require_once "Snoopy.class.php";
$snoopy = new Snoopy;
$snoopy->agent = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16"; //伪装浏览器
$snoopy->fetch($wzurl); //获取所有内容
$content = $snoopy->results; //显示结果
if(preg_match('/"read_num":"([^<]*)"\*1,/si', $content, $read)){
	$wz['read']=(int)$read[1];
}else{
	$wz['read']='';
}
if(preg_match('/"like_num":"([^<]*)"\*1/si', $content, $suport)){
	$wz['suport'] = (int)$suport[1];
}else{
	$wz['suport']='';
}
// $content = htmlspecialchars($content);
// $wz['content'] = str_replace(array("'", "\""),array("\\'","\\\""), $content);
return $wz;
}
?>