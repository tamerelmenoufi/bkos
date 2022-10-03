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
  <div class="col-12">
      <div class="row">
        <div class="col-md-12">


        <div class="card m-3">
        <div class="card-body">
            <h5 class="card-title">Quadro Crítico</h5>
            <h6 class="card-subtitle mb-2 text-muted">Lista de OS em atraso</h6>
            <p class="card-text">

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
            </p>
        </div>
    </div>


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


<script>
  $(function(){
    Carregando('none');
    $(".acessos").mouseover(function(){
      $(this).children("div").css("opacity","1");
    })
    $(".acessos").mouseout(function(){
      $(".acessos").children("div").css("opacity","0");
    })
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