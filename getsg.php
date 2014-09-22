<?php
header("Content-type: text/html; charset=utf-8");
if(empty($_GET['level'])){
?>
<form method="GET" action="getsg.php">
    微信名：<input type=text name='name' value='头条新闻' />
    微信号：<input type=text name='weixin' value='newsxinwen' />
    <input type=hidden name="level" value="first" />
    <input type=submit name='submit' value='搜索' />
</form>
<?php
exit;
}elseif($_GET['level']=="first"){
    $sg = new sogouwx();
    $wxname = trim($_GET['name']);
    $weixin = trim($_GET['weixin']);
    $openid = $sg->get_openid($wxname, $weixin);
    if(!$openid) die("没有找到微信号：$weixin 的OPENID!<br>");
    echo "openid=$openid<br>";

    $content = $sg->get_url("http://weixin.sogou.com/gzh?openid=".$openid,1);

    $articles = $sg->list_article($openid, $page=1);
    foreach ($articles as $art) {
        echo "<br>".$art['title']." （".$art['date']."）<br>";
        echo $art['url']."<br>";
        echo $sg->article_content($art['url']);
        echo "<br><br>";
    }

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
        $openid="";
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
        return $openid;
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