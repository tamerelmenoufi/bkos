<?php
    session_start();
    include("/appinc/connect.php");
    $con = AppConnect('bkos');
    $md5 = md5(date("YmdHis"));