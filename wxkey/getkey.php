<?php
file_put_contents("log.txt", $_POST['wxkey']."-----\n",FILE_APPEND);
if( $_POST['wxkey']){

    echo "success";
    exit;
}

echo "error";
exit;

?>