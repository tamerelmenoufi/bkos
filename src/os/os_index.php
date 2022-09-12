<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>

<div class="p-3">
    <div class="row">
        <h5>Lista das Empresas</h5>
        <?php
            $query = "select * from empresas where situacao = '1' order by razao_social";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
        ?>
        <div class="col-md-3 mt-3">
            <button empresa="<?=$d->codigo?>" class="btn btn-primary btn-block btn-lg">
                <h2><i class="fa-solid fa-building"></i></h2>
                <?=$d->razao_social?>
            </button>
        </div>
        <?php
            }
        ?>
    </div>
</div>


<script>
    $(function(){
        Carregando('none');
        $("button[empresa]").click(function(){
            empresa = $(this).attr("empresa");

            Carregando();
            $.ajax({
                url:"src/os/os_lista.php",
                data:{
                    empresa
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            });


        });
    })
</script>