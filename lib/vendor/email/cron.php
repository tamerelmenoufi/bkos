<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


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


    // echo $result;

    // $contatos = sendContatos($cod);

    $to = ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer.menoufi@gmail.com'];

    $contatos = [
        'to' => $to
    ];

    $_SESSION['MailFotosInline'] = [];
    $_SESSION['MailFotosInline'][] = 'https://os.bkmanaus.com.br/img/logo.png';

    $dados = [
        'from_name' => 'SP Sistema',
        'from_email' => 'mailgun@moh1.com.br',
        'subject' => 'Resumo da Situação das O.S.',
        'html' => $result,
        // 'attachment' => [
        //     './img_bk.png',
        //     './cliente-mohatron.xls',
        //     './formulario_prato_cheio.pdf',
        //     'https://os.bkmanaus.com.br/img/logo.png',
        // ],
        'inline' => $_SESSION['MailFotosInline'],
        // [
        //     // './img_bk.png',
        //     'https://os.bkmanaus.com.br/img/logo.png',
        // ],
        'to' => $contatos['to']
    ];
    // print_r($dados);
    print_r(SendMail($dados));
    ///////////////////////////////////////////////////////