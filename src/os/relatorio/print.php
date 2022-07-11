<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    header('Content-Type: application/pdf');
    // header('Content-Length: '.strlen( $content ));
    // header('Content-disposition: inline; filename="' . $name . '"');
    // header('Cache-Control: public, must-revalidate, max-age=0');
    // header('Pragma: public');
    // header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    // header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

    $query = "select a.*, b.nome as executor from os a left join colaboradores b on a.executor = b.codigo where a.codigo = '{$_GET['os']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $query = "select a.*, b.nome as responsavel from os a left join colaboradores b on a.responsavel = b.codigo where a.codigo = '{$d->vinculo}'";
    $result = mysqli_query($con, $query);
    $v = mysqli_fetch_object($result);



$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório O.S. #'.str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT).'</title>
    <style>
        .corpo{
            position:relative;
            width:100%;
            clear:both;
        }
        .titulo{
            font-size:40px;
            text-align:center;
            margin-top:60px;
            margin-bottom:60px;
        }
        .descricao{
            font-size:20px;
            text-align:justify;
            padding:40px;
        }
        .titulo_topo{
            position:relative;
            width:100%;
            height:510px;
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            background-image:url(http://os.bkmanaus.com.br/img/titulo_relatorio.png);
        }
        .divImg{
            position:relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width:50%;
            float:left;
            margin-bottom:20px;
        }
        .img{
            position:relative;
            width:80%;
            border:solid 1px green;
            border-radius:5px;
            height:250px;
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
        }


        .divReg{
            position:relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width:80%;
            float:none;
            margin-bottom:20px;
            border:solid 1px #ccc;
            border-radius:10px;
            padding:20px;
            margin-left:75px;
        }
        .descricao_registro{
            width:100%;
            text-align:justify;
            margin-top:5px;
            paddin:5px;
        }

        .descricao_registro_usuario{
            width:100%;
            text-align:right;
            margin-top:5px;
            paddin:5px;
        }


        .titulo_imagem{
            width:100%;
            text-align:center;
            margin-top:5px;
            paddin:5px;
        }
        .servico_descricao{
            position:absolute;
            color:#fff;
            font-size:20px;
            width:550px;
            padding:20px;
            text-align:justify;
            text-shadow: 0 0 0.2em #101010
        }
        .servico_descricao_titulo{
            font-size:25px;
        }

        .servico_numero_os{
            position:absolute;
            right:0px;
            top:0px;
            color:#fff;
            font-size:40px;
            width:auto;
            padding:20px;
            text-align:justify;
            text-shadow: 0 0 0.2em #101010
        }
        .servico_dados{
            position:absolute;
            left:30px;
            bottom:0px;
            color:#333;
            font-size:12px;
            width:auto;
            padding:5px;
            text-align:justify;
            text-shadow: 0 0 0.2em #fff
        }
        .servico_dados_os{
            position:absolute;
            right:20px;
            top:70px;
            color:#000;
            font-size:12px;
            padding:2px;
            text-align:right;
            width:auto;
            background-color:#fff;
            opacity:0.8;
            border-radius:3px;
        }
    </style>
</head>
<body>

    <div class="titulo_topo">
        <div class="servico_numero_os">O.S. #'.str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT).'</div>
        <div class="servico_dados_os">Executor: '.$d->executor.'<br>Em: '.$d->data_cadastro.'</div>
        <div class="servico_dados">Responsável: '.$v->responsavel.' - Em: '.$v->data_cadastro.'</div>

        <div class="servico_descricao">
            <span class="servico_descricao_titulo">Serviço N°: <b>'.str_pad($v->codigo , 6 , '0' , STR_PAD_LEFT).'</b></span><br><br>
            <span class="servico_descricao_titulo"><b>'.$v->titulo.'</b></span><br><br>
            '.$v->descricao.''.$v->descricao.''.$v->descricao.''.$v->descricao.'
        </div>
    </div>
    <div class="corpo">
        <h2 class="titulo">'.$d->titulo.'</h2>
        <p class="descricao">'.$d->descricao.'</p>
    </div>';

    //Registros Fotográficos
    $html .= '<div style="page-break-before: always;"></div>
    <div class="corpo">';

    $q = "select * from os_fotos where cod_os = '{$d->codigo}'";
    $r = mysqli_query($con, $q);
    $i=0;
    while($e = mysqli_fetch_object($r)){
        if($i%2 == 0){
            $html .= '<div class="corpo"></div>';
            // $html .= '<h2 class="titulo">REGISTRO FOTOGRÁFICO</h2>';
        }
        if($i%6 == 0 and $i > 0){
            $html .= '<div style="page-break-before: always;"></div>';
            $html .= '<h2 class="titulo">REGISTRO FOTOGRÁFICO</h2>';
        }else if($i == 0){
            $html .= '<h2 class="titulo">REGISTRO FOTOGRÁFICO</h2>';
        }

        $html .= '<div class="divImg">
                    <div class="img" style="background-image:url(http://os.bkmanaus.com.br/src/os/fotos/'.$d->codigo.'/'.$e->foto.')"></div>
                    <div class="titulo_imagem">'.$e->titulo.'</div>
                  </div>';
        $i++;
    }

    $html .= '</div>';

    //Registro de Ocorrências
    $html .= '<div style="page-break-before: always;"></div>
    <div class="corpo">';

    $q = "select
                a.*,
                b.titulo as status,
                c.titulo as classificacao,
                d.nome as colaborador
            from os_registros a
                left join os_status b on a.status = b.codigo
                left join os_classificacao c on a.classificacao = c.codigo
                left join colaboradores d on a.colaborador = d.codigo

            where a.cod_os = '{$d->codigo}' order by a.data_cadastro asc";
    $r = mysqli_query($con, $q);
    $i=0;
    while($e = mysqli_fetch_object($r)){
        if($i%2 == 0){
            $html .= '<div class="corpo"></div>';
            // $html .= '<h2 class="titulo">REGISTRO FOTOGRÁFICO</h2>';
        }
        if($i%6 == 0 and $i > 0){
            $html .= '<div style="page-break-before: always;"></div>';
            $html .= '<h2 class="titulo">REGISTRO DE EVENTOS</h2>';
        }else if($i == 0){
            $html .= '<h2 class="titulo">REGISTRO DE EVENTOS</h2>';
        }

        $html .= '<div class="divReg">
                    <div class="descricao_registro"><h2>'.$e->classificacao.' - <small>'.$e->status.'</small></h2></div>
                    <div class="descricao_registro">'.$e->descricao.'</div>
                    <div class="descricao_registro_usuario">'.$e->colaborador.' <small> em: '.$e->data_cadastro.'</small></div>
                  </div>';
        $i++;
    }

    $html .= '</div>';


$html .= '</body>
</html>';


$postdata = http_build_query(
    array(
        'html' => base64_encode($html)
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
$result = file_get_contents('http://html2pdf.mohatron.com/', false, $context);

$result = json_decode($result);
echo base64_decode($result->doc);
// echo $html;