<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<h5>Lista das Empresas</h5>
<?php
    $query = "select * from empresas where situacao = '1' order by razao_soial";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
<div class="col-3">
    <button class="btn btn-primary btn-block btn-lg">
        <?=$d->razao_social?>
    </button>
</div>
<?php
    }
?>