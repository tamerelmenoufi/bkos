<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['data']) $_SESSION['data'] = $_POST['data'];

    if(!$_SESSION['data']) $_SESSION['data'] = date("Y-m");

    $opc = 'concluidas'; // $_POST['opc'];

    switch($opc){
        case 'concluidas':{
            $query = "
                        select a.*,
                               date(a.data_finalizacao) as data,
                               b.titulo as tipo,
                               c.razao_social as empresa_nome,
                               concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                e.nome as responsavel,
                                f.nome as executor


                            from os a

                            left join os_tipos b on a.tipo = b.codigo
                            left join empresas c on a.empresa = c.codigo
                            left join empresas_enderecos d on a.empresa_endereco = d.codigo
                            left join colaboradores e on a.responsavel = e.codigo
                            left join colaboradores f on a.executor = f.codigo

                        where a.data_finalizacao > 0 and a.data_finalizacao like '{$_SESSION['data']}%'";
            break;
        }
        case 'pendentes':{
            $query = "
            select a.*,
                   date(a.data_finalizacao) as data,
                   b.titulo as tipo,
                   c.razao_social as empresa_nome,
                   concat(
                            d.nome,', ',
                            d.rua,', ',
                            d.numero,', ',
                            d.bairro,', ',
                            d.cidade,', ',
                            d.estado,', ',
                            d.cep,', ',
                            d.complemento
                        ) as empresa_endereco,
                    e.nome as responsavel,
                    f.nome as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo


            where a.data_finalizacao = 0".
            (($_POST['data'])?" and a.data_cadastro like '%{$_POST['data']}%'":false).
            (($_POST['responsavel'])?" and a.responsavel = '{$_POST['responsavel']}'":false).
            (($_POST['executor'])?" and a.executor = '{$_POST['executor']}'":false).
            (($_POST['tipo'])?" and a.tipo = '{$_POST['tipo']}'":false).
            (($_POST['loja'])?" and a.empresa = '{$_POST['loja']}'":false)
            ;
            break;
        }
        case 'geral':{
            $query = "
            select a.*,
                   date(a.data_finalizacao) as data,
                   IF(b.titulo != '' and b.titulo != null, b.titulo, 'INDEFINIDO') as tipo,
                   c.razao_social as empresa_nome,
                   concat(
                            d.nome,', ',
                            d.rua,', ',
                            d.numero,', ',
                            d.bairro,', ',
                            d.cidade,', ',
                            d.estado,', ',
                            d.cep,', ',
                            d.complemento
                        ) as empresa_endereco,
                    IF(e.nome != '' and e.nome != null, e.nome, 'INDEFINIDO') as responsavel,
                    IF(f.nome != '' and f.nome != null, f.nome, 'INDEFINIDO') as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo

            limit 100";
            break;
        }
        default:{

            break;
        }
    }


     if($query){
        
        $result = mysqli_query($con, $query);
        $retorno = [];
        while($d = mysqli_fetch_object($result)){
            $retorno[$d->data][$d->empresa][] = [
                'tipo' => $d->tipo,
                'titulo' => $d->titulo,
                'descricao' => $d->descricao,
                'responsavel' => $d->responsavel,
                'executor' => $d->executor
            ];
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
    .barraTitulo{
        position:fixed;
        top:0px;
        left:0px;
        right:0px;
        height:70px;
        background:#fff;
        z-index:10;
    }
    .relatorio-body{
        position:fixed;
        top:80px;
        left:0;
        right:0;
        bottom:0;
        overflow:auto;
    }





    .tabela-wrapper {
        top: 150px;
        bottom:0px;
        left:20px;
        right: 20px;
      overflow: auto;
      position: absolute;
      border: 1px solid #ccc;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      min-width: 600px;
    }

    th, td {
      padding: 8px 12px;
      border: 1px solid #ccc;
      background: #fff;
      white-space: nowrap;
    }

    thead th {
      position: sticky;
      top: 0;
      background: #f2f2f2;
      z-index: 2;
    }

    /* Primeira coluna */
    th:first-child,
    td:first-child {
      position: sticky;
      left: 0;
      background: #f9f9f9;
      z-index: 1;
    }

    /* Para evitar sobreposição do cabeçalho na primeira célula */
    thead th:first-child {
      z-index: 3;
    }
</style>
<div class="barraTitulo">
    <h2 class="m-3"><button class="btn btn-primary btn-sm voltar">Ordem de Serviço Concluídos (Gestão dos registros)</button><?=$_POST['titulo']?></h2>
</div>

<div class="relatorio-body">

    <div class="d-flex flex-row-reverse m-3">
        <div class="input-group mb-3">
            <label class="input-group-text">Selecione o mês de análise</label>
            <input type="month" class="form-control data_relatorio" value="<?=$_SESSION['data']?>" />
            <button class="btn btn-secondary selecionar" >Listar Relatórios</button>
        </div>
    </div>


<?php
list($ano, $mes) = explode("-", $_SESSION['data']);

$ultimoDia = (new DateTime("$ano-$mes-01"))->format('t');

$diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

?>

    <div class="tabela-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Empresas/Dias</th>
                <?php
                    $q = "select * from empresas where situacao = '1' order by razao_social";
                    $r = mysqli_query($con, $q);
                    
                    while($e = mysqli_fetch_object($r)){
                        $nc[] = [
                            'codigo' => $e->codigo,
                            'razao_social' => $e->razao_social,
                        ];
                    }

                    for($i=0;$i<count($nc); $i++){
                ?>
                    <th><?=$nc[$i]['razao_social']?></th>
                <?php
                    }
                ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    for ($dia = 1; $dia <= $ultimoDia; $dia++) {
                        $data = DateTime::createFromFormat('Y-n-j', "$ano-$mes-$dia");

                        $diaSemana = $diasSemana[$data->format('w')];
                ?>
                <tr>
                    <td><?=$data->format('d/m/Y')?></td>
                <?php
                        for($i = 0; $i < count($nc); $i++ ){
                ?>
                    <td>
                        <!-- data = <?=$data->format('Y-m-d')?><br>
                        codigo empresa = <?=$nc[$i]['codigo']?><br> -->
                        <?php
                            if($retorno[$data->format('Y-m-d')][$nc[$i]['codigo']]){
                                foreach($retorno[$data->format('Y-m-d')][$nc[$i]['codigo']] as $i1 => $v){
                        ?>
                        <div class="card m-3">
                            <div class="card-header">
                                Título: <?=$v['titulo']?>
                            </div>
                            <div class="card-body">
                                <!-- <h5 class="card-title">Special title treatment</h5> -->
                                <p class="card-text">Descriução: <?=$v['descricao']?></p>
                                <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                            </div>
                            <div class="card-footer text-muted">
                                Executor: <?=$v['executor']?>
                            </div>
                        </div>
                        <?php
                                }
                            }
                        ?>
                    </td>
                <?php
                        }
                ?>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>        
    </div>

        


<?php


/*

    if($query){
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
?>
    <div class="card m-3">

    <div class="card-body">
        <h5 class="card-title">O.S. #<?=str_pad($d->codigo , 5 , '0' , STR_PAD_LEFT)?> - <?=$d->titulo?> (<?=$d->tipo?>)</h5>
        <h6 class="card-title"><?=$d->empresa_nome?></h6>
        <h6 class="card-subtitle mb-2 text-muted">
            <?=$d->descricao?>
        </h6>
        <p class="card-text">
            <div class="d-flex justify-content-between">
                <div class="detalhes">
                    <span>Responsável</span>
                    <p><?=$d->responsavel?></p>
                    <span>Executor</span>
                    <p><?=$d->executor?></p>
                </div>
                <div class="d-flex align-items-start">
                    <button
                        class="btn btn-success btn-xs m-1"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        os="<?=$d->codigo?>"
                        destino="eventos"
                    >
                        <i class="fa-solid fa-file-pen"></i>
                    </button>

                    <button
                        class="btn btn-primary btn-xs m-1"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        os="<?=$d->codigo?>"
                        destino="fotos"
                    >
                        <i class="fa-solid fa-camera"></i>
                    </button>

                </div>
            </div>

            <button
                class="btn btn-secondary btn-xs m-1"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"
                os="<?=$d->codigo?>"
                destino="servicos_form"
            >
                <i class="fa-solid fa-gear"></i> Editar O.S.
            </button>
        </p>
    </div>
    </div>
<?php
        }
    }
        //*/
?>
</div>
<script>
    $(function(){
        $("button[os]").click(function(){
            os = $(this).attr("os");
            destino = $(this).attr("destino");
            Carregando();
            $.ajax({
                url:`src/os/${destino}.php`,
                type:"POST",
                data:{
                    os
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                    Carregando('none');
                }
            });
        });



        $(".selecionar").click(function(){
            data = $(".data_relatorio").val();
            if(!data){
                $.alert('Favor selecione a data para consulta!');
                return;
            }

            Carregando();
            $.ajax({
                url:`src/home/dashboard/listas/os_mapa.php`,
                type:"POST",
                data:{
                    opc:'<?=$_POST['opc']?>',
                    data
                },
                success:function(dados){

                    $(".popupOs").css("display","block");
                    $(".popupOs .dataOs").html(dados);
                    $("body").css("overflow","hidden");
                    Carregando('none');

                }
            })
        })


        $(".voltar").click(function(){
            Carregando();
            $.ajax({
                url:`src/home/dashboard/listas/os.php`,
                type:"POST",
                data:{
                    opc:'<?=$_POST['opc']?>',
                },
                success:function(dados){

                    $(".popupOs").css("display","block");
                    $(".popupOs .dataOs").html(dados);
                    $("body").css("overflow","hidden");
                    Carregando('none');

                }
            })
        })


    })
</script>