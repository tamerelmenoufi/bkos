<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['excluir']){

        $query = "SELECT * FROM `os_fotos` where cod_os = '{$_POST['excluir']}'";
        $result = mysqli_query($con, $query);
        $fotos = [];
        while($d = mysqli_fetch_object($result)){
            $fotos[] = $d->foto;
            echo "<p><img src='src/os/fotos/{$_POST['excluir']}/{$d->foto}' width='100' /></p>";
        }
        
        $query = "delete from os where codigo = '{$_POST['excluir']}'";


    }

    $query = "select
                    a.*,
                    if(a.situacao = '1', 'Liberado', 'Bloqueado') as situacao,
                    b.razao_social as nome_empresa,
                    (select count(*) from os where vinculo = a.codigo) as quantidade
                from os a
                left join empresas b on a.empresa = b.codigo
                where empresa = '{$_SESSION['empresa']}' /*and vinculo = '0'*/
                order by a.codigo desc";
    $result = mysqli_query($con, $query);

?>
<style>
    .compartilha_transfere{
        cursor:pointer;
    }
</style>
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

<div class="d-none d-md-block">
    <div class="row p-2 tb-b">
        <h5 class="col-md-2">
            <input type="checkbox" class="compartilha_todos" value="" />
            O.S.
            <i class="fa-solid fa-square-share-nodes compartilha_transfere"></i>
        </h5>
        <h5 class="col-md-4">Título</h5>
        <h5 class="col-md-2">Data</h5>
        <h5 class="col-md-2">Situação</h5>
        <h5 class="col-md-2">Ações</h5>
    </div>
</div>

<?php
while($d = mysqli_fetch_object($result)){
?>

<div class="row p-2 tb-b">
    <div class="col-md-2">
        <input type="checkbox" class="compartilha" value="<?=$d->codigo?>" />
        #<?=str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT)?>
    </div>
    <div class="col-md-4"><?=$d->titulo?></div>
    <div class="col-md-2"><?=$d->data_cadastro?></div>
    <div class="col-md-2"><?=$d->situacao?></div>
    <div class="col-md-2">


        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="acoesOs" data-bs-toggle="dropdown" aria-expanded="false">
                Ações
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="acoesOs">
                <li os="<?=$d->codigo?>" url="src/os/servicos_form.php"><a class="dropdown-item" href="#">Editar</a></li>
                <li os='<?=$d->codigo?>' url="src/os/fotos.php"><a class="dropdown-item" href="#">Registro Fotográfico</a></li>
                <li os='<?=$d->codigo?>' url="src/os/eventos.php"><a class="dropdown-item" href="#">Registro de Eventos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li os='<?=$d->codigo?>' url="src/os/compartilhar.php"><a class="dropdown-item" href="#">Compartilhar</a></li>
                <li><a class="dropdown-item" href="src/os/relatorio/print.php?os=<?=$d->codigo?>" target="_blank">Relatório</a></li>
                <li><hr class="dropdown-divider"></li>
                <li excluir='<?=$d->codigo?>' url="src/os/index.php"><a class="dropdown-item" href="#">Excluir</a></li>
            </ul>
        </div>

    </div>
</div>
<?php
}
?>
</div>



<!-- <div class="col">

    <div class="d-none d-md-block">
        <div class="row p-2 tb-b">
            <h5 class="col-md-2">N° Solicitação</h5>
            <h5 class="col-md-2">Título</h5>
            <h5 class="col-md-2">Empresa</h5>
            <h5 class="col-md-2">O.S. Vinculadas</h5>
            <h5 class="col-md-2">Situação</h5>
            <h5 class="col-md-2">Ações</h5>
        </div>
    </div>

        <?php
        while($d = mysqli_fetch_object($result)){
        ?>


    <div class="row p-2 tb-b">
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
                </ul>
            </div>

        </div>
    </div>


        <?php
        }
        ?>
</div> -->





<script>
    $(document).ready(function () {

        $(".compartilha_todos").click(function(){

            if($(this).prop("checked") == true){
                $(".compartilha").prop("checked", true);
            }else{
                $(".compartilha").prop("checked", false);
            }

        });

        $(".compartilha_transfere").click(function(){
            var opc = [];
            $(".compartilha").each(function(){
                if($(this).prop("checked") == true){
                    opc.push($(this).val());
                }
            });
            if(opc.length){

                Carregando();
                $.ajax({
                    url:'src/os/compartilhar.php',
                    type:"POST",
                    data:{
                        oss:opc,
                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);

                        let myOffCanvas = document.getElementById('offcanvasDireita');
                        let openedCanvas = new bootstrap.Offcanvas(myOffCanvas);
                        // let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                        openedCanvas.show();

                    }
                });



            }else{
                $.alert('Nenhuma O.S. Selecionada para compartilhamento!');
            }
        });

        // Carregando('none');
        // $("button[offcanvasDireita]").click(function(){
        //     Carregando();
        //     $.ajax({
        //         url:"src/os/form.php",
        //         success:function(dados){
        //             $(".LateralDireita").html(dados);
        //         }
        //     });
        // });


        // $("li[linha]").click(function(){
        //     Carregando();
        //     os = $(this).attr("linha");
        //     $.ajax({
        //         url:"src/os/form.php",
        //         type:"POST",
        //         data:{
        //             os,
        //         },
        //         success:function(dados){
        //             $(".LateralDireita").html(dados);
        //             let myOffCanvas = document.getElementById('offcanvasDireita');
        //             let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
        //             openedCanvas.show();
        //         }
        //     });
        // });



        // $("li[servico]").click(function(){
        //     Carregando();
        //     servico = $(this).attr("servico");
        //     $.ajax({
        //         url:"src/os/servicos.php",
        //         type:"POST",
        //         data:{
        //             servico,
        //         },
        //         success:function(dados){
        //             // $(".LateralDireita").html(dados);
        //             $(".tab-pane").html(dados);
        //         }
        //     });
        // });


        Carregando('none');

        $("button[voltar]").click(function(){
            Carregando();
            $.ajax({
                url:"src/os/index.php",
                success:function(dados){
                    // $(".LateralDireita").html(dados);
                    $(".tab-pane").html(dados);
                }
            });
        });

        $("button[offcanvasDireita]").click(function(){
            Carregando();
            $.ajax({
                url:"src/os/servicos_form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });


        $("li[os]").click(function(){
            os = $(this).attr("os");
            url = $(this).attr("url");
            Carregando();
            $.ajax({
                url,
                type:"POST",
                data:{
                    os,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);

                    let myOffCanvas = document.getElementById('offcanvasDireita');
                    let openedCanvas = new bootstrap.Offcanvas(myOffCanvas);
                    // let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                    openedCanvas.show();

                }
            });
        });


        $("li[excluir]").click(function(){
            excluir = $(this).attr("excluir");
            url = $(this).attr("url");

            $.confirm({
                title:"Excluir Ordem de Serviço",
                content:"Deseja realmente excluir a Ordem de Serviço?<br><br><b>Esta operação é irreversível!</b>",
                buttons:{
                    'Não':function(){

                    },
                    'Sim':function(){
                        Carregando();
                        $.ajax({
                            url,
                            type:"POST",
                            data:{
                                excluir,
                            },
                            success:function(dados){
                                $(".tab-pane").html(dados);
                            }
                        });                        
                    }
                }
            })


        });



    });
</script>