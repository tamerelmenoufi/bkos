<?php
    session_start();
    include("/appinc/connect.php");
    include("funcoes.php");
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/vendor/wapp/send.php");
    include "{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/vendor/email/classes.php";
    $con = AppConnect('bkos');
    $md5 = md5(date("YmdHis"));

    //echo $local = $_SERVER['PHP_SELF'];