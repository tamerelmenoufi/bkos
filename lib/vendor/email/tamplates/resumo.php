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
        .item p{
            font-size:20px;
            color:#a1a1a1;
            padding:0px;
            margin:0;
            padding-bottom:5px;
            padding-top:15px;
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
            width:25%;
            text-align: center;
            font-size: 15px;
            color:#fff;
            padding:20px;
            border-radius:5px;
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


                <?php
                $query = "SELECT
                    (select count(*) from os) as os_geral,
                    (select count(*) from os where data_finalizacao > 0 ) as os_concluidadas,
                    (select count(*) from os where data_finalizacao = 0 ) as os_pendentes
                ";
                $result = mysqli_query($con, $query);
                $d = mysqli_fetch_object($result);
                ?>

                <div class="placas">
                    <div style="background-color:blue">
                        <b><?=$d->os_geral?></b><br>Total de O.S.
                    </div>
                    <div style="background-color:red">
                        <b><?=$d->os_pendentes?></b><br>O.S. Pendentes
                    </div>
                     <div style="background-color:green">
                        <b><?=$d->os_concluidadas?></b><br>O.S. Concluídas
                    </div>
                </div>





                <div class="item">
                    <p>Visão Geral</p>
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






                <div class="item">
                    <p>Pendências Por Responsável</p>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>Responsável</th>
                                <th>Quantidade</th>
                            </tr>
                            <?php
                            $q = "SELECT

                            a.*,
                            b.titulo as tipo,
                            c.razao_social as empresa,
                            concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                IF(e.nome != '',e.nome,'INDEFINIDO') as responsavel_nome,
                                IF(f.nome != '',f.nome,'INDEFINIDO') as executor_nome,
                                count(*) as quantidade

                    from os a

                        left join os_tipos b on a.tipo = b.codigo
                        left join empresas c on a.empresa = c.codigo
                        left join empresas_enderecos d on a.empresa_endereco = d.codigo
                        left join colaboradores e on a.responsavel = e.codigo
                        left join colaboradores f on a.executor = f.codigo

                    WHERE a.data_finalizacao = 0 group by e.codigo order by quantidade desc";

                            $r = mysqli_query($con, $q);
                            $i=0;
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=($p->responsavel_nome)?></td>
                                <td><?=$p->quantidade?> (O.S.)</td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>








                <div class="item">
                    <p>Pendências Por Executor</p>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>Executor</th>
                                <th>Quantidade</th>
                            </tr>
                            <?php
                            $q = "SELECT

                            a.*,
                            b.titulo as tipo,
                            c.razao_social as empresa,
                            concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                IF(e.nome != '',e.nome,'INDEFINIDO') as responsavel_nome,
                                IF(f.nome != '',f.nome,'INDEFINIDO') as executor_nome,
                                count(*) as quantidade

                    from os a

                        left join os_tipos b on a.tipo = b.codigo
                        left join empresas c on a.empresa = c.codigo
                        left join empresas_enderecos d on a.empresa_endereco = d.codigo
                        left join colaboradores e on a.responsavel = e.codigo
                        left join colaboradores f on a.executor = f.codigo

                    WHERE a.data_finalizacao = 0 group by f.codigo order by quantidade desc";

                            $r = mysqli_query($con, $q);
                            $i=0;
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=($p->executor_nome)?></td>
                                <td><?=$p->quantidade?> (O.S.)</td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>




                <div class="item">
                    <p>Pendências Por Tipo</p>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                            <?php
                            $q = "SELECT

                            a.*,
                            b.titulo as tipo_nome,
                            c.razao_social as empresa,
                            concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                IF(e.nome != '',e.nome,'INDEFINIDO') as responsavel,
                                IF(f.nome != '',f.nome,'INDEFINIDO') as executor,
                                count(*) as quantidade

                    from os a

                        left join os_tipos b on a.tipo = b.codigo
                        left join empresas c on a.empresa = c.codigo
                        left join empresas_enderecos d on a.empresa_endereco = d.codigo
                        left join colaboradores e on a.responsavel = e.codigo
                        left join colaboradores f on a.executor = f.codigo

                    WHERE a.data_finalizacao = 0 group by b.codigo order by quantidade desc";

                            $r = mysqli_query($con, $q);
                            $i=0;
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=($p->tipo_nome)?></td>
                                <td><?=$p->quantidade?> (O.S.)</td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>







                <div class="item">
                    <p>Pendências Por Loja</p>
                    <hr>
                    <div>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <th>Loja</th>
                                <th>Quantidade</th>
                            </tr>
                            <?php
                            $q = "SELECT

                            a.*,
                            b.titulo as tipo_nome,
                            c.razao_social as empresa_nome,
                            concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                IF(e.nome != '',e.nome,'INDEFINIDO') as responsavel,
                                IF(f.nome != '',f.nome,'INDEFINIDO') as executor,
                                count(*) as quantidade

                    from os a

                        left join os_tipos b on a.tipo = b.codigo
                        left join empresas c on a.empresa = c.codigo
                        left join empresas_enderecos d on a.empresa_endereco = d.codigo
                        left join colaboradores e on a.responsavel = e.codigo
                        left join colaboradores f on a.executor = f.codigo

                    WHERE a.data_finalizacao = 0 group by c.codigo order by quantidade desc";

                            $r = mysqli_query($con, $q);
                            $i=0;
                            while($p = mysqli_fetch_object($r)){
                            ?>
                            <tr style="background-color:<?=(($i%2 == 0)?'#ffffff':'#eee')?>">
                                <td><?=($p->empresa_nome)?></td>
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