<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    echo $query = "SLELECT
              (select count(*) from empresas) as empresas,
              (select count(*) from colaboradores where cria_os = '1') as gestores,
              (select count(*) from colaboradores where cria_os != '1') as colaboradores,
              (select count(*) from colaboradores where adm = '1') as administradores,
              (select count(*) from os) as os_geral,
              (select count(*) from os where data_finalizacao > 0 ) as os_concluidadas,
              (select count(*) from os where data_finalizacao = 0 ) as os_pendentes
    ";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


?>

<style>
  .btn-block{
    width:100%;
  }
</style>

<div class="col">

    <div class="m-3">
      <h4>Título da página</h4>
      <div class="row">

        <div class="col-md-2 mb-3">
          <button class="btn btn-danger btn-block">
            <h2><?=$d->empresas?></h2>
            Empresas
          </button>
        </div>

        <div class="col-md-2 mb-3">
          <button class="btn btn-danger btn-block">
            <h2><?=$d->colaboradores?></h2>
            Colaboradores
          </button>
        </div>

        <div class="col-md-2 mb-3">
          <button class="btn btn-danger btn-block">
            <h2><?=$d->gestores?></h2>
            Gestores
          </button>
        </div>

        <div class="col-md-2 mb-3">
          <button class="btn btn-danger btn-block">
            <h2><?=$d->administradores?></h2>
            Administradores
          </button>
        </div>


        <div class="col-md-2 mb-3">
          <button class="btn btn-primary btn-block">
            <h2><?=$d->os_geral?></h2>
            Total de O.S.
          </button>
        </div>

        <div class="col-md-2 mb-3">
          <button class="btn btn-warning btn-block">
            <h2><?=$d->os_pendentes?></h2>
            O.S. Pendentes
          </button>
        </div>

        <div class="col-md-2 mb-3">
          <button class="btn btn-secondary btn-block">
            <h2><?=$d->os_concluidadas?></h2>
            O.S. Concluídas
          </button>
        </div>


      </div>

    </div>




</div>

<script>
  $(function(){
    Carregando('none');
  })
</script>