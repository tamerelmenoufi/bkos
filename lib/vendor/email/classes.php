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



    function ReplaceVar($html, $cod){

        global $con;

        global $_SESSION;

        $_SESSION['MailFotosInline'] = [];

        $Str = [];

        $query = "select a.*,
                        b.nome as executor,
                        c.nome as responsavel,
                        d.titulo as tipo,
                        date_format(a.data_cadastro,'%d/%m/%Y %H:%i:%s') as data_formatada,
                        e.razao_social,
                        e.cnpj
                    from os a
                    left join colaboradores b on a.executor = b.codigo
                    left join colaboradores c on a.responsavel = c.codigo
                    left join os_tipos d on a.tipo = d.codigo
                    left join empresas e on a.empresa = e.codigo
                    where a.codigo = '{$cod}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

    //////////////////////////////////////////////////////////////////////////////

        $Str['os']['codigo'] = str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT);
        $Str['os']['executor'] = (($d->executor)?:'Não Informado');
        $Str['os']['data_cadastro'] = (($d->data_formatada)?:'Não Informado');
        $Str['os']['responsavel'] = (($d->responsavel)?:'Não Informado');
        $Str['os']['titulo'] = (($d->titulo)?:'Não Informado');
        $Str['os']['descricao'] = (($d->descricao)?:'Não Informado');
        $Str['os']['tipo'] = (($d->tipo)?:'Não Informado');
        $Str['os']['empresa'] = "{$d->razao_social} ({$d->cnpj})";
        $Str['os']['data_atual'] = date("d/m/Y H:i:s");
        $Str['os']['nome_usuario'] = $_SESSION['BkOsLogin']->nome;



        $q = "select a.*, b.nome as colaborador, date_format(a.data_cadastro,'%d/%m/%Y %H:%i:%s') as data_formatada from os_fotos a left join colaboradores b on a.colaborador = b.codigo where a.cod_os = '{$d->codigo}' and a.situacao = '1' and JSON_EXTRACT(a.deletado,\"$.usuario\") = ''";
        $r = mysqli_query($con, $q);
        $i=0;
        while($e = mysqli_fetch_object($r)){
            //////////////////////////////////////////////////////////////////////////////
            $Str['os_fotos'][$i]['foto'] = 'http://os.bkmanaus.com.br/src/os/fotos/'.$d->codigo.'/'.$e->foto;
            $Str['os_fotos'][$i]['titulo'] = $e->titulo;
            $Str['os_fotos'][$i]['descricao'] = $e->descricao;
            $Str['os_fotos'][$i]['colaborador'] = $e->colaborador;
            $Str['os_fotos'][$i]['data_cadastro'] = $e->data_formatada;
            //////////////////////////////////////////////////////////////////////////////
            $i++;
        }



        $q = "select
                    a.*,
                    b.titulo as status,
                    c.titulo as classificacao,
                    d.nome as colaborador,
                    date_format(a.data_cadastro,'%d/%m/%Y %H:%i:%s') as data_formatada
                from os_registros a
                    left join os_status b on a.status = b.codigo
                    left join os_classificacao c on a.classificacao = c.codigo
                    left join colaboradores d on a.colaborador = d.codigo

                where a.cod_os = '{$d->codigo}' and a.situacao = '1' and JSON_EXTRACT(a.deletado,\"$.usuario\") = '' order by a.data_cadastro asc";
        $r = mysqli_query($con, $q);
        $i=0;
        while($e = mysqli_fetch_object($r)){
            //////////////////////////////////////////////////////////////////////////////
            $Str['os_registros'][$i]['classificacao'] = $e->classificacao;
            $Str['os_registros'][$i]['status'] = $e->status;
            $Str['os_registros'][$i]['descricao'] = $e->descricao;
            $Str['os_registros'][$i]['colaborador'] = $e->colaborador;
            $Str['os_registros'][$i]['data_cadastro'] = $e->data_formatada;
            //////////////////////////////////////////////////////////////////////////////
            $i++;
        }

        foreach($Str['os'] as $i => $v){
            $html = str_replace("{{os-{$i}}}", $v, $html);
        }

        $p1 = stripos($html, '{{os_fotos}}');
        $p2 = (strripos($html, '{{os_fotos}}') - $p1);
        $fotos = substr($html, $p1, $p2);
        $html = str_replace($fotos,'{{os_fotos}}',$html);
        $fotos = str_replace('{{os_fotos}}', false, $fotos);

        if($Str['os_fotos']){
            $AddFotos = $fotos;
            $AddF = [];
            foreach($Str['os_fotos'] as $i => $v){

                $foto = explode("/", $v['foto']);
                $foto = $foto[count($foto) - 1];
                $_SESSION['MailFotosInline'][] = $v['foto'];

                $AddFotos = str_replace("{{os_fotos-foto}}", $foto, $fotos);
                $AddFotos = str_replace("{{os_fotos-titulo}}", $v['titulo'], $AddFotos);
                $AddFotos = str_replace("{{os_fotos-descricao}}", $v['descricao'], $AddFotos);
                $AddFotos = str_replace("{{os_fotos-colaborador}}", $v['colaborador'], $AddFotos);
                $AddFotos = str_replace("{{os_fotos-data_cadastro}}", $v['data_cadastro'], $AddFotos);
                $AddFotos = str_replace("{{os_fotos-titulo}}", $v['titulo'], $AddFotos);
                $AddF[] = $AddFotos;
            }

            if($AddF){
                $html = str_replace('{{os_fotos}}',implode("",$AddF),$html);
            }
        }else{
            $html = str_replace('{{os_fotos}}',false,$html);
        }

        return $html;
    }


    function sendContatos($cod){
        $query = "select a.*,
                        b.nome as executor,
                        b.telefone as executor_telefone,
                        b.email as executor_email,
                        c.nome as responsavel,
                        c.telefone as responsavel_telefone,
                        c.email as responsavel_email
                    from os a
                    left join colaboradores b on a.executor = b.codigo
                    left join colaboradores c on a.responsavel = c.codigo
                    where a.codigo = '{$cod}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        return [
            'to' => [
                ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer.menoufi@gmail.com'],
                (($d->executor and $d->executor_email)?['to_name' => $d->executor, 'to_email' => $d->executor_email]:false),
                (($d->responsavel and $d->responsavel_email)?['to_name' => $d->responsavel, 'to_email' => $d->responsavel_email]:false),
            ],

            'wapp' => [
                ['nome' => 'Tamer Mohamed', 'telefone' => '92991886570'],
                (($d->executor and $d->executor_telefone)?['nome' => $d->executor, 'telefone' => $d->executor_telefone]:false),
                (($d->responsavel and $d->responsavel_telefone)?['nome' => $d->responsavel, 'telefone' => $d->responsavel_telefone]:false),
            ],
        ];


    }