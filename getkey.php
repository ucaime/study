<?php
require 'comon.php';
$key = $_POST['wxkey'];
$ctime = time();
mysqli_query($connect,"UPDATE `wx_keys` SET `keys`='$key',`ctime` = '$ctime' where id = 32 ;");
?>