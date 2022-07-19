<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    $query = "select *, if(situacao = '1', 'Liberado', 'Bloqueado') as situacao from os_tipos order by titulo";
    $result = mysqli_query($con, $query);

?>

<div class="col-12">
    <div class="col d-flex justify-content-between">
        <div class="p-3"><h5>Tipos da OS</h5></div>
        <div class="p-3">
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

<div class="col-12">
    <div class="p-3">
        <table id="TableStatus" class="table table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Situação</th>
                    <!-- <th>Ações</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                while($d = mysqli_fetch_object($result)){
                ?>
                <tr linha='<?=$d->codigo?>'>
                    <td><?=$d->titulo?></td>
                    <td><?=$d->situacao?></td>
                    <!-- <td>
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
                    </td> -->
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    $(document).ready(function () {
        Carregando('none');

        $("button[offcanvasDireita]").click(function(){
            Carregando();
            $.ajax({
                url:"src/os/tipos/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });


        $("tr[linha]").click(function(){
            status = $(this).attr("linha");
            Carregando();
            $.ajax({
                url:"src/os/tipos/form.php",
                type:"POST",
                data:{
                    status,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);

                    let myOffCanvas = document.getElementById('offcanvasDireita');
                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                    openedCanvas.show();

                }
            });
        });



    });
</script>