<?php
    session_start();
    include("/appinc/connect.php");
    include("./vendor/wapp/send.php");
    $con = AppConnect('bkos');
    $md5 = md5(date("YmdHis"));