<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/sis/lib/includes.php");


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

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório O.S. #'.$d->codigo.'</title>
    <style>
        .corpo{
            position:relative;
            width:100%;
            clear:both;
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
    </style>
</head>
<body>
    <h2>'.$d->titulo.'</h2>
    <p>'.$d->descricao.'</p>

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
                    <div class="img" style="background-image:url(http://qrativoserp.com.br/src/os/fotos/'.$d->codigo.'/'.$e->foto.')"></div>
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