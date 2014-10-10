<?php
require 'comon.php';
require_once 'Snoopy.class.php';
$sg = new sogouwx();
for(;;){
    $time = time();
    $sql = mysqli_query($connect,"SELECT * FROM `wx_pinfo` WHERE `updates`=1 AND `ntime`<=$time order by `ctime` desc;");
    $row = mysqli_fetch_assoc($sql);
        $time = time();
        $ntime = $time+$row['numbers']*3600;
        $nntime = $time + 18000;
        $newsql = mysqli_query($connect,"SELECT `wzurl` FROM `wx_article` WHERE `uid`='{$row['id']}' order by `ctime` desc;");
        $newrow = mysqli_fetch_row($newsql);
        $openid = $sg->get_openid($row['gname'], $row['gnumber']);
        if($openid){
            $articles = $sg->list_article($openid,$newrow[0]);
            if($articles){
                for($i=0;$i<count($articles);$i++){
                    $wzlist = mysqli_query($connect,"select * from wx_article where wzurl='{$articles[$i]['url']}' limit 1;");
                    if(!mysqli_fetch_row($wzlist)){
                        mysqli_query($connect,"INSERT INTO `wx_article` (
                            `uid`,`wzurl`,`wztitle`,`ctime`,`gtime`,`uctime`,`ntime`) VALUES (
                            '{$row['id']}','{$articles[$i]['url']}','{$articles[$i]['title']}','{$articles[$i]['ctime']}','".time()."','".time()."','".time()."')");
                    }
                }
                mysqli_query($connect,"UPDATE `wx_reads`.`wx_pinfo` SET `ntime`='$ntime' WHERE (`id`='{$row['id']}');");
            }else{
                mysqli_query($connect,"UPDATE `wx_reads`.`wx_pinfo` SET `ntime`='$nntime' WHERE (`id`='{$row['id']}');");
            }
        }else{
            mysqli_query($connect,"UPDATE `wx_reads`.`wx_pinfo` SET `ntime`='$nntime' WHERE (`id`='{$row['id']}');");
        }
    sleep(1);
}

class sogouwx{
    private $userAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:31.0) Gecko/20100101 Firefox/31.0';
    private $UserURL = 'http://weixin.sogou.com/';
    private $cookiepath = '/cti/html/';

    function __construct(){
    
    }

    function get_openid($wxname, $weixin, $max_page=20){
        $snoopy = new Snoopy;
        $openid="";
        $page=1;
        $wxname = urlencode($wxname);
        while($openid=="" and $page<=$max_page){ 
            $UserData['page'] = $page;
            $snoopy->referer = "http://weixin.sogou.com/weixin?type=1&query={$wxname}&ie=utf8&_ast=1411002496&_asf=null&w=01029901&cid=null";
            $snoopy->userAgent="Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0";
            $snoopy->fetch("http://weixin.sogou.com/weixin?type=1&query={$wxname}&ie=utf8&_ast=1410944384&_asf=null&w=01019900&cid=null&page={$page}&sut=8605&sst0=1410946235190&lkt=9%2C1410946227425%2C1410946234536");
            $content = $snoopy->results;
            $pos=stripos($content,"<div class=\"results mt7\">");
            $block=substr($content, $pos);
            while(strlen($block)>100) {
                $cur_block="";
                $first_pos=stripos($block,"<!-- a -->");
                if($first_pos===false){
                    //echo "here!";
                    break;
                }
                $end_pos=stripos($block,"<!-- a -->",$first_pos+100);
                if($end_pos===false){
                    $end_pos=strlen($block)-1;
                }
                $cur_block=substr($block,$first_pos,$end_pos-$first_pos+5);
                $block=substr($block, $end_pos);
                //echo "<br>first_pos=$first_pos; end_pos=$end_pos<br>";
                if(preg_match("/<span>微信号：([^<]*)<\/span>/i", $cur_block, $arr)){
                    //echo "微信号：".$arr[1];
                    if($arr[1]==$weixin){//find it
                        if(preg_match("/openid=([a-z0-9_-]+)/i", $cur_block, $arr)){//find openid
                            $openid=$arr[1];
                            break;
                        }else{
                            // echo "微信号：$weixin 的OPENID出错!<br>";
                            $openid='';
                            continue;
                        }
                    }else{//isn't this weixin
                        //echo "微信名：".$UserData['query'].", 微信号：$weixin !<br>";
                        $openid='';
                        continue;
                    }
                }else{
                    // echo "这个块没有微信号标志！<br>";
                    $openid='';
                    continue;
                }
            }
            $page++;
            sleep(2);
        }
        return $openid;
    }

    function list_article($openid,$wzurl){
       $url=$this->UserURL."gzhjs?cb=sogou.weixin.gzhcb&openid=".$openid."&t=".time();
        //echo "$url<br>";
        $snoopy = new Snoopy;
        $snoopy->fetch($url);
        $content = $snoopy->results;
        preg_match('/totalPages\":([^<]*)\}\)/si', $content, $totalPages);
        // echo $totalPages[1];die;
        $content = '';
        for ($i=1; $i <= (int)$totalPages[1]; $i++) { 
            $url=$this->UserURL."gzhjs?cb=sogou.weixin.gzhcb&openid=".$openid."&page={$i}&t=".time();
            $snoopy->fetch($url);
            $content = $content.$snoopy->results;
            sleep(2);
        }
        $block = $content;
        $arts = array();
        $i=0;
        while(strlen($block)>100) {
            $cur_block="";
            $first_pos=stripos($block,"<?xml version=");
            if($first_pos===false){
                // echo "Can't find start!<br>";
                break;
            }
            $end_pos=stripos($block,"<?xml version=",$first_pos+20);
            if($end_pos===false){
                //echo "Can't find stop!<br>";
                $end_pos=strlen($block)-1;
            }
            $cur_block=substr($block,$first_pos,$end_pos-$first_pos);
            $block=substr($block, $end_pos);
            //echo "<br>first_pos=$first_pos; end_pos=$end_pos";
            if(preg_match("/<url><!\[CDATA\[([^>]*)&3rd/i", $cur_block, $arru)){
                //echo "URL：".$arru[1]."<br>";
                if($arru[1]==$wzurl){
                    return $arts;
                    break;
                }
                $arts[$i]['url']=$arru[1];
            }else{
                //echo "这个块没有URL！<br>";
                $arts[$i]['url']="";
            }
            if(preg_match("/<title><!\[CDATA\[([^>]*)\]\]><\\\\\/title>/i", $cur_block, $arrt)){
                //echo "<br>标题：".$arrt[1]."<br>";
                $arts[$i]['title']=$arrt[1];
            }else{
                //echo "这个块没有标题！<br>";
                $arts[$i]['title']="";
            }
            if(preg_match("/<imglink><!\[CDATA\[([^>]*)\]\]><\\\\\/imglink>/i", $cur_block, $arri)){

                $arts[$i]['imgurl']=$arri[1];

            }else{
                $arts[$i]['imgurl']="";
            }
            if(preg_match("/<content168><!\[CDATA\[([^>]*)\]\]><\\\\\/content168>/i", $cur_block, $description)){

                $arts[$i]['description']=$description[1];

            }else{
                $arts[$i]['description']="";
            }
            if(preg_match("/<lastModified>([^>]*)<\\\\\/lastModified>/i", $cur_block, $ctime)){

                $arts[$i]['ctime']=(int)$ctime[1];

            }else{
                $arts[$i]['ctime']="";
            }
            $i++;
        }
        return $arts;
    } 
}