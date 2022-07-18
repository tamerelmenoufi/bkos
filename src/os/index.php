<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    $query = "select
                    a.*,
                    if(a.situacao = '1', 'Liberado', 'Bloqueado') as situacao,
                    b.razao_social as nome_empresa,
                    (select count(*) from os where vinculo = a.codigo) as quantidade
                from os a
                left join empresas b on a.empresa = b.codigo
                where empresa = '{$_SESSION['empresa']}' and vinculo = '0'
                order by a.titulo";
    $result = mysqli_query($con, $query);

?>
<div class="col">
    <div class="col d-flex justify-content-between">
        <div class="p-2"><h5>Ordem de Serviços</h5></div>
        <div class="p-2">
            <button
                class="btn btn-primary"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"
                offcanvasDireita
            >
                <i class="fa-solid fa-plus"></i>
                Novo
            </button>
        </div>
    </div>
</div>

<div class="col">


    <div class="row">
        <div class="col-md-2">N° Solicitação</div>
        <div class="col-md-2">Título</div>
        <div class="col-md-2">Empresa</div>
        <div class="col-md-2">O.S. Vinculadas</div>
        <div class="col-md-2">Situação</div>
        <div class="col-md-2">Ações</div>
    </div>




<!-- <table id="TableColaboradores" class="table table-hover" style="width:100%">
    <thead>
        <tr>
            <th>N° Solicitação</th>
            <th>Título</th>
            <th>Empresa</th>
            <th>O.S. Vinculadas</th>
            <th>Situação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody> -->
        <?php
        while($d = mysqli_fetch_object($result)){
        ?>


    <div class="row">
        <div class="col-md-2"><?=str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT)?></div>
        <div class="col-md-2"><?=$d->titulo?></div>
        <div class="col-md-2"><?=$d->nome_empresa?></div>
        <div class="col-md-2"><?=$d->quantidade?> OS</div>
        <div class="col-md-2"><?=$d->situacao?></div>
        <div class="col-md-2">

            <div class="dropdown">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="acoesOs" data-bs-toggle="dropdown" aria-expanded="false">
                    Ações
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="acoesOs">
                    <li linha='<?=$d->codigo?>'><a class="dropdown-item" href="#">Editar</a></li>
                    <li servico='<?=$d->codigo?>'><a class="dropdown-item" href="#">Ordem de Serviços</a></li>
                    <!-- <li><a class="dropdown-item" href="#">Something else here</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Separated link</a></li> -->
                </ul>
            </div>

        </div>
    </div>

        <!-- <tr>
            <td><?=str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT)?></td>
            <td><?=$d->titulo?></td>
            <td><?=$d->nome_empresa?></td>
            <td><?=$d->quantidade?> OS</td>
            <td><?=$d->situacao?></td>
            <td>

                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="acoesOs" data-bs-toggle="dropdown" aria-expanded="false">
                        Ações
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="acoesOs">
                        <li linha='<?=$d->codigo?>'><a class="dropdown-item" href="#">Editar</a></li>
                        <li servico='<?=$d->codigo?>'><a class="dropdown-item" href="#">Ordem de Serviços</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                </div>

                <button
                    editar="<?=$d->codigo?>"
                    class="btn btn-success btn-xs"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                >
                    Ed
                </button>
                <button excluir="<?=$codigo?>" class="btn btn-danger btn-xs">ex</button>
            </td>
        </tr> -->
        <?php
        }
        ?>
    <!-- </tbody>
</table> -->
</div>
<script>
    $(document).ready(function () {
        Carregando('none');
        $("button[offcanvasDireita]").click(function(){
            Carregando();
            $.ajax({
                url:"src/os/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });


        $("li[linha]").click(function(){
            Carregando();
            os = $(this).attr("linha");
            $.ajax({
                url:"src/os/form.php",
                type:"POST",
                data:{
                    os,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                    let myOffCanvas = document.getElementById('offcanvasDireita');
                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                    openedCanvas.show();
                }
            });
        });



        $("li[servico]").click(function(){
            Carregando();
            servico = $(this).attr("servico");
            $.ajax({
                url:"src/os/servicos.php",
                type:"POST",
                data:{
                    servico,
                },
                success:function(dados){
                    // $(".LateralDireita").html(dados);
                    $(".tab-pane").html(dados);
                }
            });
        });


    });
</script>