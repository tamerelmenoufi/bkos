<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="img/logo_h60.png" style="height:60px;" alt="Sistema de Gestão BkOs">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <h5>Sistema de Gestão de O.S.</h5>

    <div class="row mb-1">
      <div class="col">
        <a url="src/home/dashboard/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Dashboard
        </a>
      </div>
    </div>

    <!-- <div class="row mb-1">
      <div class="col">
        <a url="src/os/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Ordem de Serviços
        </a>
      </div>
    </div> -->

    <div class="row mb-1">
      <div class="col">
        <a url="src/empresas/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Controle de Ordem de Serviços
        </a>
      </div>
    </div>


    <div class="row mb-1">
      <div class="col">
        <a url="src/colaboradores/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Colaboradores
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/os/status/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Status de O.S.
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/os/classificacao/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Classificação de O.S.
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/os/tipos/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Tipos de O.S.
        </a>
      </div>
    </div>
    <!-- <div class="row mb-1">
      <div class="col">
        <i class="fa-solid fa-clipboard-list"></i> Abertura de processos
      </div>
    </div> -->

  </div>
</div>

<script>
  $(function(){
    $("a[url]").click(function(){
      Carregando();
      url = $(this).attr("url");
      $.ajax({
        url,
        success:function(dados){
          $("#paginaHome").html(dados);
          let myOffCanvas = document.getElementById('offcanvasDireita');
          let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
          openedCanvas.show();
        }
      });
    });
  })
</script>