<?php
header("Content-type: text/html; charset=utf-8");
$connect = mysqli_connect("localhost", "root", "root") or die("链接数据库失败！");
mysqli_select_db($connect,"me_media" ) or die(mysqli_errno($connect));
mysqli_query($connect,"SET NAMES 'utf8'");
    $sg = new sogouwx();
    $information = $sg->get_baijia();
    // foreach ($information as $key) {
    //     $q = mysqli_query($connect,"SELECT `id` FROM `information` WHERE `name`='{$key['name']}' LIMIT 1");
    //     if(!mysqli_fetch_row($q)){
    //         mysqli_query($connect,"INSERT INTO `information` (`name`,`baijia_num`,`baidu_num`) VALUES('{$key['name']}','{$key['baijia']}','{$key['baidu_num']}')");
    //     }
    // }
    // echo "<pre>";
    // print_r($information);
    // echo "</pre>";
    for($i=0;$i<count($information);$i++){
        $q = mysqli_query($connect,"SELECT * FROM `information` WHERE `name`='{$information[$i]['name']}' LIMIT 1");
        $row = mysqli_fetch_array($q);
        if(!$row){
            mysqli_query($connect,"INSERT INTO `information` (`name`,`baijia_num`,`baidu_num`) VALUES('{$information[$i]['name']}','{$information[$i]['baijia']}','{$information[$i]['baidu_num']}')");
            }else{
                mysqli_query($connect,"UPDATE `information` set `baidu_pnum`='{$row['baidu_num']}',`baijia_pnum`='{$row['baijia_num']}' where `name`='{$information[$i]['name']}'");
                mysqli_query($connect,"UPDATE `information` set `baidu_num`='{$information[$i]['baidu_num']}',`baijia_num`='{$information[$i]['baijia']}' where `name`='{$information[$i]['name']}'");
            }
    }

class sogouwx{
    private $userAgent = 'Mozilla/5.0 (Windows NT 6.1; rv:31.0) Gecko/20100101 Firefox/31.0';
    private $baijiaURL = 'http://baijia.baidu.com/?tn=listauthor';
    private $baiduURL = 'http://www.baidu.com/s?wd=%E6%95%96%E5%B0%8F%E7%99%BD&rsv_spt=1&issp=1&rsv_bp=0&ie=utf-8&tn=sitehao123&rsv_sug3=8&rsv_sug4=164&rsv_sug1=6&rsv_sug2=0&inputT=2650';
    private $cookiepath = '/cti/html/';

    function __construct(){
    
    }

    function get_baijia(){
        $UserData = array();
        $UserData['query'] = iconv("utf-8","gb2312",$UserData['query']);
            $content = $this->post_url($this->baijiaURL,$UserData,$this->cookiepath);
            $pos=stripos($content,"data-name=\"互联网\"");
            $block=substr($content, $pos);
                $cur_block="";
                $first_pos=stripos($block,"data-name=\"互联网\"");
                $end_pos=stripos($block,"data-name=\"文化\"");
                $cur_block=substr($block,$first_pos+23,$end_pos-66);
                preg_match_all("/>([^<]*)<\/a/i", $cur_block, $arr);
                preg_match_all("/阅读<span>([^<]*)<\/span>/i", $cur_block, $num);
                $arr = $arr[1];
                $num = $num[1];
                for($i=0;$i<count($arr);$i++){
                    $bdurl = "http://www.baidu.com/s?wd=".urlencode($arr[$i]);
                    $baidu_num = $this->get_baidu($bdurl);
                    $arr[$i]=array(
                        'name'=>$arr[$i],
                        'baijia'=>(int)str_replace(',','',$num[$i]),
                        'baidu_num'=>$baidu_num
                        );
                }
                return $arr;

    }

    function get_baidu($url){
        $UserData = array();
        $UserData['query'] = iconv("utf-8","gb2312",$UserData['query']);
        $baidu = $this->gets_url($url);
        preg_match('/百度为您找到相关结果约([0-9\,]+)个/si', $baidu, $p);
        $baidu_num = (int)str_replace(',','',$p[1]);
        return $baidu_num;
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

    function gets_url($url){
    // 1. 初始化
    $ch = curl_init();
    // 2. 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // 3. 执行并获取HTML文档内容
    $baidu = curl_exec($ch);
    // 4. 释放curl句柄
    curl_close($ch);
    return $baidu;
    }
}