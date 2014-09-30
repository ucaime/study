/**
 * chrome采集微信帐号key插件
 * @author sootoo
 * @version 1.0
 */

 if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}

var put_data_url = "http://127.0.0.1/study/getkey.php"; //ajax发送数据的url get_wx_json

var link_cgi = "https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxcheckurl?uin=2997090605&sid=J833ryYvcJdYmUaX&skey=%40crypt_b2bd179_70a0ee4678aab49015be9c2838c14b60&deviceid=e241633246951974&opcode=2&requrl=http%3A%2F%2Fmp.weixin.qq.com%2Fs%3F__biz%3DMjM5MDMyOTUxNg%3D%3D%26mid%3D200661483%26idx%3D1%26sn%3D271444880d5ceebc6b38a4b1be0adc08%23rd&scene=1&username=wxid_4lxli9zpmy5w22";
var lurl = window.location.href ;
//alert(lurl.startsWith('http://mp.weixin.qq.com/') );
window.onload = function() {

    if (window.location.href == 'https://wx.qq.com/') {
        // get url real address
        // open winpop use real url
        // wait 1 hour, continue.
        // localStorage.url = real url.

    } else if (lurl.startsWith('http://mp.weixin.qq.com/') == true) {
        // https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxcheckurl?uin=2997090605&sid=J833ryYvcJdYmUaX&skey=%40crypt_b2bd179_70a0ee4678aab49015be9c2838c14b60&deviceid=e241633246951974&opcode=2&requrl=http%3A%2F%2Fmp.weixin.qq.com%2Fs%3F__biz%3DMjM5MDMyOTUxNg%3D%3D%26mid%3D200661483%26idx%3D1%26sn%3D271444880d5ceebc6b38a4b1be0adc08%23rd&scene=1&username=wxid_4lxli9zpmy5w22

        // http://mp.weixin.qq.com/s?__biz=MjM5MDMyOTUxNg==&mid=200661483&idx=1&sn=271444880d5ceebc6b38a4b1be0adc08&key=52d358b666da3a5e40d54d5ccc209565328b7bee085f4fefd95512217e153ce8781a1fa453c26c681fd1eeac90abbf98&ascene=1&uin=Mjk5NzA5MDYwNQ%3D%3D&pass_ticket=mEOEOzjABx%2FwvAYlQc26RPjZNLQLMntBAVxlK03YJdm6U%2FoWJFpOBC3t628XmSJT

        // get key from url , 
        // send key to server.
        // close winpop.
        var link = window.location.href;
        var keytime = getNowFormatDate();
        var wkey = request(link, 'key');
 
        if(wkey != ""){
            $.post(put_data_url, {
                'wxkey': wkey,
                'keytime': keytime
            });

        }
 
       //wait 1 hour
       t = setTimeout("shuaxin()", 1000 * 60 * 60) ;//10s刷新
       //t = setTimeout("shuaxin()", 1000 * 60 * 60) //1小时刷新

    } 

}

function shuaxin() {

    window.location.href = link_cgi;
}

//解析url参数
function request(url, paras) {
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {}
    for (i = 0; j = paraString[i]; i++) {
        paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if (typeof(returnValue) == "undefined") {
        return "";
    } else {
        return returnValue;
    }
}


function getNowFormatDate() {
    var day = new Date();
    var Year = 0;
    var Month = 0;
    var Day = 0;
    var CurrentDate = "";
    //初始化时间
    //Year= day.getYear();//有火狐下2008年显示108的bug
    Year = day.getFullYear(); //ie火狐下都可以
    Month = day.getMonth() + 1;
    Day = day.getDate();
    //Hour = day.getHours();
    // Minute = day.getMinutes();
    // Second = day.getSeconds();
    CurrentDate += Year + "-";
    if (Month >= 10) {
        CurrentDate += Month + "-";
    } else {
        CurrentDate += "0" + Month + "-";
    }
    if (Day >= 10) {
        CurrentDate += Day;
    } else {
        CurrentDate += "0" + Day;
    }
    return CurrentDate;
}


function GetDateStr(AddDayCount) {
    var dd = new Date();
    dd.setDate(dd.getDate() + AddDayCount); //获取AddDayCount天后的日期
    var y = dd.getFullYear();
    var m = dd.getMonth() + 1; //获取当前月份的日期
    var d = dd.getDate();
    var CurrentDate = "";
    CurrentDate += y + "-";
    if (m >= 10) {
        CurrentDate += m + "-";
    } else {
        CurrentDate += "0" + m + "-";
    }
    if (d >= 10) {
        CurrentDate += d;
    } else {
        CurrentDate += "0" + d;
    }
    return CurrentDate;
}

function getCookie(objName){//获取指定名称的cookie的值
    var arrStr = document.cookie.split("; "); 
    for(var i = 0;i < arrStr.length;i ++){
        var temp = arrStr[i].split("=");
        if(temp[0] == objName) return unescape(temp[1]);
    }
}

function winpop1() {
 
    var iWidth=600;                          //弹出窗口的宽度;
    var iHeight=500;                        //弹出窗口的高度;
    var iTop = (window.screen.availHeight-30-iHeight)/2;       //获得窗口的垂直位置;
    var iLeft = (window.screen.availWidth-10-iWidth)/2;        //获得窗口的水平位置;
    page="https://mp.weixin.qq.com/";
    window.open(page,"addapp",'height='+iHeight+',,innerHeight='+iHeight+',width='+iWidth+',innerWidth='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no');
    //$('#huoqu').hide();
};