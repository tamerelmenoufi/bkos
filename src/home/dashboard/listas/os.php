<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    switch($_POST['opc']){
        case 'concluidas':{
            $query = "select * from os where data_finalizacao > 0";
            break;
        }
        case 'pendentes':{
            $query = "select * from os where data_finalizacao = 0";
            break;
        }
        case 'geral':{
            $query = "select * from os limit 100";
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
<?php
    if($query){
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
?>
    <div class="card">
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
