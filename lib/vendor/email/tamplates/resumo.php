<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body{
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item{
            width:100%;
            padding:0px;
            margin:0;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item span{
            font-size:20px;
            color:#a1a1a1;
            padding:0px;
            margin:0;
            margin-bottom:5px;
            margin-top:10px;
        }
        .item div{
            width:100%;
            padding:0px;
            margin:0;
        }

        .item_foto{
            width:100%;
            padding:0px;
            margin:10px;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
            border-radius:7px;
            border:solid #ccc 2px;
            background-color:#eee;
            text-align:center;
        }

        .item_foto p{
            width:100%;
            padding:0px;
            margin:0;
            text-align:center;
        }
        .placas{
            display: flex;
            justify-content: space-between;
        }
        .placas div{
            width:33%;
            text-align: center;
            font-size: 15px;
            color:#fff;
            padding:20px;
        }
        th{
            text-align:left;
        }
        td{
            padding:5px;
        }
    </style>
</head>
<body>

    <table cellspacing="0" cellpadding="0" style="border:1px #ccc solid; width:600px;">
        <tr>
            <td style="width:120px">
                <img src="cid:logo.png" style="width:120px" >
            </td>
            <td style="padding:10px;">
                <h2>Quadro de Situação das Ordens de Serviços</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="width:600px; padding:20px;">

                <div class="placas">
                    <div style="background-color:blue">
                        <b>156</b><br>Total de OS
                    </div>
                    <div style="background-color:red">
                        <b>100</b><br>OS Pendentes
                    </div>
                     <div style="background-color:green">
                        <b>156</b><br>OS Concluídas
                    </div>
                </div>

                <div class="item">
                    <span>Visão Geral</span>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>Data</th>
                                <th>Dias em atraso</th>
                                <th>Quantidade</th>
                            </tr>
                            <?php
                            $q = "SELECT
                                    a.data_cadastro,
                                    concat(day(a.data_cadastro),'/',month(a.data_cadastro),'/',year(a.data_cadastro)) as data_cadastro_br,
                                    DATEDIFF(CURDATE(), a.data_cadastro) as dias,
                                    count(*) as quantidade

                            from os a

                            WHERE a.data_finalizacao = 0 group by dias desc";

                            $r = mysqli_query($con, $q);
                            $i=0;
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=($p->data_cadastro_br)?></td>
                                <td><?=$p->dias?> dia(s)</td>
                                <td><?=$p->quantidade?> (O.S.)</td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>

            </td>
        </tr>

    </table>
</body>
</html>