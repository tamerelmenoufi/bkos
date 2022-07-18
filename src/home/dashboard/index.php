<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>

<style>
  .btn-block{
    width:90%;
  }
</style>

<div class="col">

    <div class="m-3">
      <h4>Título da página</h4>
      <div class="row">

        <div class="col-md-2">
          <button class="btn btn-danger btn-block">
            <h2>15</h2>
            Empresas
          </button>
        </div>

        <div class="col-md-2">
          <button class="btn btn-primary btn-block">
            <h2>1679</h2>
            Solicitaçõs
          </button>
        </div>

        <div class="col-md-2">
          <button class="btn btn-warning btn-block">
            <h2>7</h2>
            Produção
          </button>
        </div>

        <div class="col-md-2">
          <button class="btn btn-secondary btn-block">
            <h2>19</h2>
            O.S. Pendentes
          </button>
        </div>

        <div class="col-md-2">
          <button class="btn btn-success btn-block">
            <h2>3422</h2>
            O.S. Concluídas
          </button>
        </div>

        <div class="col-md-2">
          <button class="btn btn-danger btn-block">
            <h2>34</h2>
            Colaboradores
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