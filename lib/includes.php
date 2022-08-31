<?php
    session_start();
    include("/appinc/connect.php");
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/vendor/wapp/send.php");
    $con = AppConnect('bkos');
    $md5 = md5(date("YmdHis"));