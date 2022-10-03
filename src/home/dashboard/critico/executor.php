<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Executor</th>
            <th>Qt. de O.S.</th>
        </tr>
    </thead>
    <tbody>
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
                    e.nome as responsavel,
                    f.nome as executor,
                    count(*) as quantidade

        from os a

            left join os_tipos b on a.tipo = b.codigo
            left join empresas c on a.empresa = c.codigo
            left join empresas_enderecos d on a.empresa_endereco = d.codigo
            left join colaboradores e on a.responsavel = e.codigo
            left join colaboradores f on a.executor = f.codigo

        WHERE a.data_finalizacao = 0 group by f.codigo order by quantidade desc";

$r = mysqli_query($con, $q);
while($p = mysqli_fetch_object($r)){
?>
        <tr>
            <td>
                <?=($p->executor)?><br>
                <span style="font-size:10px; color:#a1a1a1;"><?=($p->responsavel)?></span>
            </td>
            <td>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" aria-valuenow="<?=$p->quantidade?>" style="width: <?=$p->quantidade?>px"></div>
                    <span style="margin-left:5px; font-size:10px;"><?=$p->quantidade?> O.S.</span>
                </div>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>