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
  .btn-block{
    width:100%;
  }
</style>

<div class="col-12">

    <div class="m-3">
      <h4>Título da página</h4>
      <div class="row">

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block">
            <h2><?=$d->empresas?></h2>
            Empresas
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block">
            <h2><?=$d->colaboradores?></h2>
            Colaboradores
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block">
            <h2><?=$d->gestores?></h2>
            Gestores
          </button>
        </div>

        <div class="col-md-3 mb-3">
          <button class="btn btn-warning btn-block">
            <h2><?=$d->administradores?></h2>
            Administradores
          </button>
        </div>


        <div class="col-md-4 mb-3">
          <button class="btn btn-primary btn-block">
            <h2><?=$d->os_geral?></h2>
            Total de O.S.
          </button>
        </div>

        <div class="col-md-4 mb-3">
          <button class="btn btn-danger btn-block">
            <h2><?=$d->os_pendentes?></h2>
            O.S. Pendentes
          </button>
        </div>

        <div class="col-md-4 mb-3">
          <button class="btn btn-secondary btn-block">
            <h2><?=$d->os_concluidadas?></h2>
            O.S. Concluídas
          </button>
        </div>


      </div>

    </div>




</div>


  <div class="col-12">
      <div class="m-3">
        <div class="row">
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                Gráfico por Situação
              </div>
            <div class="card-body">
              <canvas id="Registros<?= $md5 ?>" width="400" height="400"></canvas>
            </div>
            </div>
          </div>

          <div class="col-6">
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



</script>