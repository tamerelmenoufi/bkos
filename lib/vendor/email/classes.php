<?php

	// $dados = [
	// 	'from_name' => 'Mensagem de Teste - Mailgun',
	// 	'from_email' => 'mailgun@moh1.com.br',
	// 	'subject' => 'Envio html API Mailgun',
	// 	'html' => '<html><h2>Título da html de teste</h2><p>Descrição da html de teste da API</p><a href="http://os.bkmanaus.com.br" target="_blank"><img src="cid:logo.png" width="600" /></a><br><br><br><img src="cid:img_bk.png" width="600" /></html>',
	// 	'attachment' => [
	// 		'./img_bk.png',
	// 		'./cliente-mohatron.xls',
	// 		'./formulario_prato_cheio.pdf',
	// 		'https://os.bkmanaus.com.br/img/logo.png',
	// 	],
	// 	'inline' => [
	// 		'./img_bk.png',
	// 		'https://os.bkmanaus.com.br/img/logo.png',
	// 	],
	// 	'to' => [
	// 		// ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer.menoufi@gmail.com'],
	// 		// ['to_name' => 'Tamer Elmenoufi', 'to_email' => 'tamer@mohatron.com.br'],
	// 	]
	// ];


    function SendMail($dados){


        $url = "http://email.mohatron.com/send.php";
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($dados)
            ]
        ]);

        // Send a request
        $result = file_get_contents($url, false, $options);
        $result = json_decode($result);

        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";

        return $result->status;

        // foreach($result as $i => $d){
        //     if($i != 'status'){
        //         if($result->status == 'error'){
        //             foreach($d as $fild => $msg_error){
        //                 echo "Posição: ".$i;
        //                 echo "<br>";
        //                 echo "Campo: ".$fild;
        //                 echo "<br>";
        //                 echo "Erro: ".$msg_error;
        //                 echo "<br><hr>";
        //             }
        //         }else if($result->status == 'success'){

        //             echo "ID: ".$d->id;
        //             echo "<br>";
        //             echo "MESSAGE: ".$d->message;
        //             echo "<br><hr>";

        //         }

        //     }
        // }
    }