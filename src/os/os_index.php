<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<div class="row p-3">
    <h5>Lista das Empresas X</h5>
    <?php
        $query = "select * from empresas where situacao = '1' order by razao_social";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
            for($i=0;$i<10;$i++){
    ?>
    <div class="col-3 mt-3">
        <button class="btn btn-primary btn-block btn-lg">
            <h2><i class="fa-solid fa-building"></i></h2>
            <?=$d->razao_social?>
        </button>
    </div>
    <?php
            }
        }
    ?>
</div>
