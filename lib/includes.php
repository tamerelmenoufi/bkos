<?php
    session_start();
    include("/appinc/connect.php");
    $con = AppConnect();
    $md5 = md5(date("YmdHis"));