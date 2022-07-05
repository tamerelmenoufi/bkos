<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    header('Content-Type: application/pdf');
    // header('Content-Length: '.strlen( $content ));
    // header('Content-disposition: inline; filename="' . $name . '"');
    // header('Cache-Control: public, must-revalidate, max-age=0');
    // header('Pragma: public');
    // header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    // header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

    $query = "select * from os where codigo = '{$_GET['os']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $query = "select a.*, b.nome from os a left join colaboradores b on a.responsavel = b.codigo where a.codigo = '{$d->vinculo}'";
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
        .servico_dados_os{
            position:absolute;
            left:0px;
            bottom:0px;
            color:#fff;
            font-size:12px;
            width:auto;
            padding:20px;
            text-align:justify;
            text-shadow: 0 0 0.2em #101010
        }
    </style>
</head>
<body>

    <div class="titulo_topo">
        <div class="servico_numero_os">O.S. #'.str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT).'</div>
        <div class="servico_dados_os">Responsável: '.$v->responsavel.' - Criada em: '.$v->data_cadastro.'</div>

        <div class="servico_descricao">
            <span class="servico_descricao_titulo">Serviço N°: <b>'.str_pad($v->codigo , 6 , '0' , STR_PAD_LEFT).'</b></span><br><br>
            <span class="servico_descricao_titulo"><b>'.$v->titulo.'</b></span><br><br>
            '.$v->descricao.''.$v->descricao.''.$v->descricao.''.$v->descricao.'
        </div>
    </div>
    <div class="corpo">
        <h2>'.$d->titulo.'</h2>
        <p>'.$d->descricao.'</p>
    </div>
    <div class="corpo">';

    $q = "select * from os_fotos where cod_os = '{$d->codigo}'";
    $r = mysqli_query($con, $q);
    $i=0;
    while($e = mysqli_fetch_object($r)){
        if($i%2 == 0){
            $html .= '<div class="corpo"></div>';
        }
        if($i%6 == 0 and $i > 0){
            $html .= '<div style="page-break-before: always;"></div>';
        }
        $html .= '<div class="divImg">
                    <div class="img" style="background-image:url(http://os.bkmanaus.com.br/src/os/fotos/'.$d->codigo.'/'.$e->foto.')"></div>
                    <div class="titulo_imagem">'.$e->titulo.'</div>
                  </div>';
        $i++;
    }

    $html .= '</div>
</body>
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