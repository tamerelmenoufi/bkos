<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/sis/lib/includes.php");

    $query = "select *, if(situacao = '1', 'Liberado', 'Bloqueado') as situacao from empresas order by razao_social";
    $result = mysqli_query($con, $query);

?>
<div class="col">
    <div class="col d-flex justify-content-between">
        <div class="p-2">Dados da tabel de Empresas (<?=$_POST['opc']?>)</div>
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
<table id="TableEmpresas" class="table table-hover" style="width:100%">
    <thead>
        <tr>
            <th>CNPJ</th>
            <th>Razão Social</th>
            <th>Situação</th>
            <!-- <th>Ações</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        while($d = mysqli_fetch_object($result)){
        ?>
        <tr linha='<?=$d->codigo?>'>
            <td><?=$d->cnpj?></td>
            <td><?=$d->razao_social?></td>
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

<script>
    $(document).ready(function () {

        $("button[offcanvasDireita]").click(function(){
            $.ajax({
                url:"src/empresas/empresa_form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });


        $("tr[linha]").click(function(){
            empresa = $(this).attr("linha");

            $("a[empresa]").attr("empresa",empresa);
            $("a[empresa]").removeClass("active");
            $('a[opc="visualizar"]').addClass("active");

            $.ajax({
                url:"src/empresas/visualizar.php",
                type:"POST",
                data:{
                    empresa,
                },
                success:function(dados){
                    $(".tab-pane").html(dados);
                }
            });
        });



    });
</script>