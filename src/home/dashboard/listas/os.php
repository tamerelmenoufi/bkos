<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    switch($_POST['opc']){
        case 'concluidas':{
            $query = "
                        select a.*,
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
                                e.nome as responsavel,
                                f.nome as executor


                            from os a

                            left join os_tipos b on a.tipo = b.codigo
                            left join empresas c on a.empresa = c.codigo
                            left join empresas_enderecos d on a.empresa_endereco = d.codigo
                            left join colaboradores e on a.responsavel = e.codigo
                            left join colaboradores f on a.executor = f.codigo

                        where a.data_finalizacao > 0";
            break;
        }
        case 'pendentes':{
            $query = "
            select a.*,
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
                    e.nome as responsavel,
                    f.nome as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo


            where a.data_finalizacao = 0";
            break;
        }
        case 'geral':{
            $query = "
            select a.*,
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
                    e.nome as responsavel,
                    f.nome as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo

            limit 100";
            break;
        }
        default:{

            break;
        }
    }
?>
<style>
    .detalhes{
        width:100%;
    }
    .detalhes span{
        width:100%;
        padding:0;
        margin:0;
        font-size:9px;
        color:#a1a1a1;
        text-align:left;
    }
    .detalhes p{
        padding:0;
        margin:0;
        font-size:12px;
        text-align:left;
        color:#000;
        width:100%;
    }
    .busca{
        position:fixed;
        top:50px;
        left:30px;
        right:40px;
        height:70px;
        background:#fff;
        z-index:10;
    }
    .relatorio-body{
        margin-top:70px;
    }
</style>
<div class="busca">

</div>

<div class="relatorio-body">
<?php
    // Lista dos itens pendentes
    if($_POST['opc'] == 'pendentes'){
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Data da Solicitação</th>
            <th>Dias em atraso</th>
            <th>Quantidade de O.S.</th>
        </tr>
    </thead>
    <tbody>
<?php
        $q = "SELECT

                    a.data_cadastro,
                    DATEDIFF(CURDATE(), a.data_cadastro) as dias,
                    count(*) as quantidade

            from os a

            WHERE a.data_finalizacao = 0 group by dias desc";

        $r = mysqli_query($con, $q);
        while($p = mysqli_fetch_object($r)){
?>
        <tr>
            <td><?=$p->data_cadastro?></td>
            <td>
                <div style="background-color:red; color:#fff; padding:3px; width:<?=($p->dias*2)?>px; border-radius:5px;">
                    <?=$p->dias?>
                </div>
            </td>
            <td><?=$p->quantidade?></td>
        </tr>
<?php
        }
?>
    </tbody>
</table>
<?php
    }
    // FIM Lista dos itens pendentes



    if($query){
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
?>
    <div class="card m-3">
    <div class="card-body">
        <h5 class="card-title"><?=$d->titulo?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?=$d->descricao?></h6>
        <p class="card-text">
            <div class="detalhes">
                <span>Responsável</span>
                <p><?=$d->responsavel?></p>
            </div>
        </p>
    </div>
    </div>
<?php
        }
    }
?>
</div>
