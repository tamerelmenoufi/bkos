<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    $query = "select *, if(situacao = '1', 'Liberado', 'Bloqueado') as situacao from os_status order by titulo";
    $result = mysqli_query($con, $query);

?>

<div class="row">
    <div class="col">
        <div class="col d-flex justify-content-between">
            <div class="p-2"><h5>Status da OS</h5></div>
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
</div>
<div class="row">
    <div class="col">
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

        $("button[offcanvasDireita]").click(function(){
            $.ajax({
                url:"src/os/status/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });


        $("tr[linha]").click(function(){
            status = $(this).attr("linha");

            $.ajax({
                url:"src/os/status/form.php",
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