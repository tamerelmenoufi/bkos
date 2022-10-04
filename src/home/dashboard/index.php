<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    $query = "SELECT
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
  .acessos{
    width:100%;
  }
  .acessos div{
    text-align:right;
    opacity:0;
    color:#fff;
    cursor:pointer;
  },
  .moldura{
    border-left:1px solid #ccc;
    border-right:1px solid #ccc;
    border-bottom:1px solid #ccc;
  }
  .tdExpandir{
    width:30px;
    cursor:pointer;
  }
  .popupOs{
    position:fixed;
    left:0;
    right:0;
    top:0;
    bottom:0;
    background-color:#fff;
    z-index:999;
    display:none;
  }
  .popupOs span{
    position:fixed;
    right:10px;
    top:10px;
    color:#333;
    font-size:30px;
    z-index:992;
    cursor: pointer;
  }

  .popupOs div{
    position:fixed;
    left:0;
    right:0;
    top:0;
    bottom:0;
    z-index:991;
    overflow:auto;
  }
</style>

<div class="col-12">

    <div class="m-3">
      <h4>Título da página</h4>
      <div class="row">

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block acessos" opc="empresas">
            <h2><?=$d->empresas?></h2>
            <span>Empresas</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block acessos" opc="colaboradores">
            <h2><?=$d->colaboradores?></h2>
            <span>Colaboradores</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block acessos" opc="gestores">
            <h2><?=$d->gestores?></h2>
            <span>Gestores</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block acessos" opc="administradores">
            <h2><?=$d->administradores?></h2>
            <span>Administradores</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>


        <div class="col-md-4 mb-3">
          <button class="btn btn-primary btn-block acessos" opc="os-geral">
            <h2><?=$d->os_geral?></h2>
            <span>Total de O.S.</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>

        <div class="col-md-4 mb-3">
          <button class="btn btn-danger btn-block acessos" opc="os-pendentes">
            <h2><?=$d->os_pendentes?></h2>
            <span>O.S. Pendentes</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>

        <div class="col-md-4 mb-3">
          <button class="btn btn-secondary btn-block acessos" opc="os-concluidas">
            <h2><?=$d->os_concluidadas?></h2>
            <span>O.S. Concluídas</span>
            <div>
              <span>
                <i class="fa-solid fa-up-right-from-square"></i>
              </span>
            </div>
          </button>
        </div>


      </div>

    </div>








</div>


    <div class="col-md-12">
      <div class="card m-3">
        <div class="card-body">
            <h5 class="card-title">Quadro Crítico</h5>
            <h6 class="card-subtitle mb-2 text-muted">Lista de OS em atraso</h6>
            <p class="card-text">

              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active painelCritico" id="visao_geral" data-bs-toggle="tab" data-bs-target="#painelCritico" type="button" role="tab" aria-controls="visaGeral" aria-selected="true">Visão Geral</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link painelCritico" id="responsavel" data-bs-toggle="tab" data-bs-target="#painelCritico" type="button" role="tab" aria-controls="responsavel" aria-selected="false">Responsável</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link painelCritico" id="executor" data-bs-toggle="tab" data-bs-target="#painelCritico" type="button" role="tab" aria-controls="executor" aria-selected="false">Executor</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link painelCritico" id="tipo" data-bs-toggle="tab" data-bs-target="#painelCritico" type="button" role="tab" aria-controls="tipo" aria-selected="false" >Tipo</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div
                    class="tab-pane fade show active"
                    id="painelCritico"
                    role="tabpanel"
                    aria-labelledby="home-tab"
                    tabindex="0"
                    style="
                            border-left:1px solid #dee2e6;
                            border-right:1px solid #dee2e6;
                            border-bottom:1px solid #dee2e6;
                            padding:10px;
                          "
                >...</div>
                <!-- <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
                <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div> -->
              </div>
            </p>
          </div>
        </div>
      </div>



  <div class="col-12">
      <div class="m-3">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                Gráfico por Situação
              </div>
            <div class="card-body">
              <canvas id="Registros<?= $md5 ?>" width="400" height="400"></canvas>
            </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
              Gráfico por Tipos
              </div>
            <div class="card-body">
              <canvas id="Tipos<?= $md5 ?>" width="400" height="400"></canvas>
            </div>
            </div>
          </div>
        </div>
      </div>
  </div>


<div class="popupOs">
  <span>Fechar</span>
  <div></div>
</div>


<script>
  $(function(){
    Carregando('none');
    $(".acessos").mouseover(function(){
      $(this).children("div").css("opacity","1");
    })
    $(".acessos").mouseout(function(){
      $(".acessos").children("div").css("opacity","0");
    })


    $.ajax({
      url:"src/home/dashboard/critico/visao_geral.php",
      success:function(dados){
        $("#painelCritico").html(dados);
      }
    });

    $(".painelCritico").click(function(){
      opc = $(this).attr("id");
      $.ajax({
        url:`src/home/dashboard/critico/${opc}.php`,
        success:function(dados){
          $("#painelCritico").html(dados);
        }
      });
    });

    $(document).off("click").on("click",".tdExpandir", function(){
      data = $(this).data();
      $.ajax({
        url:`src/home/dashboard/listas/os.php`,
        type:"POST",
        data,
        success:function(dados){
          // $.dialog({
          //   title:data.titulo,
          //   content:dados,
          //   columnClass:'col-md-offset-1 col-md-10'
          // });
          $(".popupOs").css("display","block");
          $(".popupOs div").html(dados);
        }
      });
    });

    $(".popupOs span").click(function(){
      $(".popupOs").css("display","none");
      $(".popupOs div").html("");
    });


  })




<?php

    $query = "
        select
              a.titulo,
              (select count(*) from os_registros b where b.status = a.codigo) as qt
        from os_status a where a.situacao = '1'
    ";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    while($d = mysqli_fetch_object($result)){
      $Rotulos[] = $d->titulo;
      $Quantidade[] = $d->qt;
    }
    $R = (($Rotulos)?"'".implode("','",$Rotulos)."'":0);
    $Q = (($Quantidade)?implode(",",$Quantidade):0);

?>

    const RegistrosCtx<?=$md5?> = document.getElementById('Registros<?=$md5?>');
    const Registros<?=$md5?> = new Chart(RegistrosCtx<?=$md5?>,
        {
            type: 'bar',
            data: {
                labels: [<?=$R?>],
                datasets: [{
                    label: [<?=$R?>],
                    data: [<?=$Q?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1,
                    rotulos: [<?=$R?>]
                }]
            },
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
                responsive: true,
                plugins: {
                    legend: false/*{
        position: 'right',
      }*/,
                    title: {
                        display: true,
                        text: 'Status de O.S.'
                    },


                    tooltip: {
                        callbacks: {
                            title: function (context) {
                                indx = context[0].parsed.y;
                                return context[0].dataset.rotulos[indx];
                            },
                            label: function (context) {
                                indx = context.parsed.y;
                                var label = ' ' + context.dataset.label[indx] || '';

                                if (label) {
                                    label += ' : ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.x + ' Registro(s)';
                                }
                                return label;
                            }
                        }
                    }

                }
            },
        }
    );

//////////////////////////////////////////////////////////////////////////////////

<?php

    $query = "
        select
              a.titulo,
              (select count(*) from os b where b.tipo = a.codigo) as qt
        from os_tipos a where a.situacao = '1'
    ";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    while($d = mysqli_fetch_object($result)){
      $Rotulos[] = $d->titulo;
      $Quantidade[] = $d->qt;
    }
    $R = (($Rotulos)?"'".implode("','",$Rotulos)."'":0);
    $Q = (($Quantidade)?implode(",",$Quantidade):0);

?>

    const TiposCtx<?=$md5?> = document.getElementById('Tipos<?=$md5?>');
    const Tipos<?=$md5?> = new Chart(TiposCtx<?=$md5?>,
        {
            type: 'bar',
            data: {
                labels: [<?=$R?>],
                datasets: [{
                    label: [<?=$R?>],
                    data: [<?=$Q?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1,
                    rotulos: [<?=$R?>]
                }]
            },
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
                responsive: true,
                plugins: {
                    legend: false/*{
        position: 'right',
      }*/,
                    title: {
                        display: true,
                        text: 'Tipos de O.S.'
                    },


                    tooltip: {
                        callbacks: {
                            title: function (context) {
                                indx = context[0].parsed.y;
                                return context[0].dataset.rotulos[indx];
                            },
                            label: function (context) {
                                indx = context.parsed.y;
                                var label = ' ' + context.dataset.label[indx] || '';

                                if (label) {
                                    label += ' : ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.x + ' Registro(s)';
                                }
                                return label;
                            }
                        }
                    }

                }
            },
        }
    );


    $(".acessos").click(function(){

      opc = $(this).attr("opc");
      opc = opc.split("-");
      tit = $(this).children("span").text();

      $.ajax({
        url:`src/home/dashboard/listas/${opc[0]}.php`,
        type:"POST",
        data:{
          opc:((opc[1])?opc[1]:''),
        },
        success:function(dados){
          $.dialog({
            title:`${tit}`,
            content:dados,
            columnClass:'col-md-offset-1 col-md-10'
          });
        }
      })

    });


</script>