<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    switch($_POST['opc']){
        case 'concluidas':{
            $query = "select a.* from os a where a.data_finalizacao > 0";
            break;
        }
        case 'pendentes':{
            $query = "select a.* from os a where a.data_finalizacao = 0";
            break;
        }
        case 'geral':{
            $query = "select a.* from os a limit 100";
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
</style>
<div style="position:fixed; top:60px; left:0; width:100%;height:70px; background:#ccc; opacity:0.6; border:solid 1px green;">

</div>

<div style="margin-top:70px; border-top:1px solid red">
<?php
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
                <p>Fulano de Tal</p>
            </div>
        </p>
    </div>
    </div>
<?php
        }
    }
?>
</div>
