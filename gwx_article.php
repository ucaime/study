<?php
header("Content-type: text/html; charset=utf-8");
$conn = mysql_connect("localhost", "sonet", "son0326") or die("MySQL联接失败");
mysql_select_db("sonet") or die("选择wireless库失败");
@mysql_query("set names utf8");

$sql="select * from son_weixin where status=1 and user_id is not null";
$rs=mysql_query($sql);
if(!$rs) die("query error!");
$daysago = date("Y-m-d H:i:s", strtotime("2 days ago"));
while($list=mysql_fetch_array($rs)){

    $sg = new sogouwx();
    $wxname = $list['name'];
    $weixin = $list['weixinhao'];
    $user_id = $list['user_id'];
    $openid_arr = $sg->get_openid($wxname, $weixin);
    if(!$openid_arr) die("没有找到微信号：$weixin 的OPENID!<br>");
    $openid=$openid_arr['openid'];
    $portrait=$openid_arr['portrait'];
    echo "openid=$openid, portrait=$portrait<br>";
    if(empty($list['portrait'])) 
        @mysql_query("update son_weixin set portrait='$portrait' where id=".$list['id']);
    $rs1=mysql_query("select id,title,soururl,date_created,weixin_id from son_content 
        where date_created>$daysago and weixin_id=".$list['id']);

    $content = $sg->get_url("http://weixin.sogou.com/gzh?openid=".$openid,1);

    $articles = $sg->list_article($openid, $page=1);
    foreach ($articles as $art) {
        if(time()-strtotime($art['date']." 23:59:59")>60*60*24) continue;
        while($l1=mysql_fetch_array($rs)){
            if($l1['soururl']==$art['url']) continue;
        }
        $content = addslashes($sg->article_content($art['url']));
        $rs2 = mysql_query("insert into son_content (title,user_id,tag_id,level,grade,content,
            date_created,access,soururl,weixin_id,comment_number) values('".addslashes($art['title'])."',
            $user_id,196,'0-9',0,'$content',
            '".$art['date']."',0,'".$art['url']."','".$list['id']."',0)");
        if($rs2) echo "<br>".$art['title']." （".$art['date']."）添加成功！<br>";
        else{
            echo "Error: " . mysql_error()."<br>";
        }
    }
    flush();
    sleep(3);
}




class sogouwx{
    private $userAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:31.0) Gecko/20100101 Firefox/31.0';
    private $UserURL = 'http://weixin.sogou.com/';
    private $cookiepath = '/cti/html/';

    function __construct(){
    
    }

    function get_openid($wxname, $weixin, $max_page=20){
        $UserData = array(
            "query"=>$wxname,
            "_asf"=>"www.sogou.com",
            "_ast"=>"",
            "ie"=>"utf-8",
            "w"=>"01019900",
            "p"=>"40040100",
            "type"=>"1");
        $UserData['query'] = iconv("utf-8","gb2312",$UserData['query']);
        $openid="";$portrait="";
        $page=1;
        while($openid=="" and $page<$max_page){
            $UserData['page'] = $page;
            $content = $this->post_url($this->UserURL."weixin",$UserData,$this->cookiepath);
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
                        if(preg_match("/<span class=\"ico-bg\"><\/span><img [^>]*src=\"([^\"]*)\"/i", $cur_block, $arr)){
                            $portrait=$arr[1];
                            //echo "头像URL=$portrait<br>";
                        }else{
                            echo "没有取到头像！<br>";
                        }
                        if(preg_match("/openid=([a-z0-9_-]+)/i", $cur_block, $arr)){//find openid
                            $openid=$arr[1];
                            break;
                        }else{
                            echo "微信号：$weixin 的OPENID出错!<br>";
                            continue;
                        }
                    }else{//isn't this weixin
                        //echo "微信名：".$UserData['query'].", 微信号：$weixin !<br>";
                        continue;
                    }
                }else{
                    echo "这个块没有微信号标志！<br>";
                }
            }
            $page++;
        }
        if($openid) return array('openid'=>$openid,'portrait'=>$portrait);
        else return $openid;
    }

    function list_article($openid, $page=1){
        $url=$this->UserURL."gzhjs?cb=sogou.weixin.gzhcb&openid=".$openid."&page=$page&t=".time();
        //echo "$url<br>";
        $content = $this->get_url($url,1);
        //file_put_contents("abc.xml", $content);
        $block = $content;
        $arts = array();
        $i=0;
        while(strlen($block)>100) {
            $cur_block="";
            $first_pos=stripos($block,"<?xml version=");
            if($first_pos===false){
                echo "Can't find start!<br>";
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
            if(preg_match("/<title><!\[CDATA\[([^>]*)\]\]><\\\\\/title>/i", $cur_block, $arrt)){
                //echo "<br>标题：".$arrt[1]."<br>";
                $arts[$i]['title']=$arrt[1];
            }else{
                //echo "这个块没有标题！<br>";
                $arts[$i]['title']="";
            }
            if(preg_match("/<url><!\[CDATA\[([^>]*)\]\]><\\\\\/url>/i", $cur_block, $arru)){
                //echo "URL：".$arru[1]."<br>";
                $arts[$i]['url']=$arru[1];
            }else{
                //echo "这个块没有URL！<br>";
                $arts[$i]['url']="";
            }
            if(preg_match("/<date><!\[CDATA\[([^>]*)\]\]><\\\\\/date>/i", $cur_block, $arru)){
                //echo "URL：".$arru[1]."<br>";
                $arts[$i]['date']=$arru[1];
            }else{
                //echo "这个块没有日期！<br>";
                $arts[$i]['date']="";
            }
            $i++;
        }
        return $arts;
    }

    function article_content($url){
        $content = $this->get_url($url,1);
        $first_pos=stripos($content,"<div class=\"rich_media_content\" id=\"js_content\">");
        if($first_pos===false){
            echo "Can't find start!<br>";
            return "";
        }
        $end_pos=stripos($content,"<div class=\"rich_media_tool\" id=\"js_toobar\">",$first_pos+20);
        if($end_pos===false){
            //echo "Can't find stop!<br>";
            $end_pos=strlen($block)-1;
            return "";
        }
        $content=substr($content,$first_pos,$end_pos-$first_pos);
        $content=eregi_replace("<script .+</script>","",$content);
        $content=preg_replace("/<img [^>]*data-src=\"([^>]*)\" [^>]*data-w=\"([0-9]+)\" [^>]*>/i",
            "<img src=\"\\1\" width=\"\\2\">",$content);
        $content=trim($content);
        file_put_contents("content.html", $content);
        return $content;
    }

    function get_url($url,$usecookie = 0){
        $referer = $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        if($usecookie){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiepath);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    function post_url($url,$data = array()){
        $referer = $url;
        if(!is_array($data) || !$url) return '';
        $post="";
        foreach($data as $key=>$value){$post .= urlencode($key).'='.$value.'&';}
        rtrim($post ,'&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiepath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}