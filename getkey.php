<?php
require 'comon.php';
$ctime = time();
$wxkeys = $_POST['wxkey'];
echo "success";
$sql = mysqli_query($connect,"UPDATE `wx_reads`.`wx_keys` SET `keys`= '{$wxkeys}',`ctime`='{$ctime}' where id = 1;");
?>