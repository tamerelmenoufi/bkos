
<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Data da Solicitação</th>
            <th>Dias em atraso</th>
            <th>Qt. de O.S.</th>
        </tr>
    </thead>
    <tbody>
<?php
$q = "SELECT

        concat(day(a.data_cadastro),'/',month(a.data_cadastro),'/',year(a.data_cadastro)) as data_cadastro,
        DATEDIFF(CURDATE(), a.data_cadastro) as dias,
        count(*) as quantidade

from os a

WHERE a.data_finalizacao = 0 group by dias desc";

$r = mysqli_query($con, $q);
while($p = mysqli_fetch_object($r)){
?>
        <tr>
            <td><?=($p->data_cadastro)?></td>
            <td>
                <!-- <div style="background-color:red; color:#fff; padding:3px; width:<?=($p->dias*5)?>px; border-radius:5px;">
                    <?=$p->dias?>
                </div> -->



                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" role="progressbar" aria-valuenow="<?=$p->dias?>" style="width: <?=$p->dias?>px"></div>
                    <span style="margin-left:5px; font-size:10px;"><?=$p->dias?> dia(s)</span>
                </div>


            </td>
            <td><?=$p->quantidade?> <span style="margin-left:3px; font-size:10px; color:#a1a1a1">O.S.</span></td>
        </tr>
<?php
}
?>
    </tbody>
</table>