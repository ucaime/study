<?php
$uname='wxread';
$pwd='wxread123';
if( $_POST['uname'] ==$uname && md5($_POST['pwd']) ==md5($pwd)  ){
    setcookie("voteadm-".$uname, 1, time()+86400);
    setcookie("voteadm-".md5($pwd), 2, time()+86400);
    header("Location:getInfos.php");
    //echo 'login success';
}
else{
    if($_COOKIE["voteadm-".$uname]!=1 || $_COOKIE["voteadm-".md5($pwd)]!=2){
        echo 'members only, please login';
        echo '<br> <form method="post" action="getInfos.php" >
        <p>username: <input type="text" name="uname" value="" /></p>
        <p>password: <input type="text" name="pwd" value="" /></p>
        <p><input type="submit"  value="login" /></p>
        </form>';
        exit ;
    }
}
?>