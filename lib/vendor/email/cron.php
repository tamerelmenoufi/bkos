<?php

    $postdata = http_build_query(
        array(
            'acao' => 'resumo', // Receivers phonei
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context = stream_context_create($opts);
    $result = file_get_contents('https://os.bkmanaus.com.br/lib/vendor/email/tamplates/resumo.php', false, $context);


    echo $result;
