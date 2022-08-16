<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['os']) $os = $_POST['os'];
    if($_POST['responsavel']) $responsavel = $_POST['responsavel'];



?>
<style>

</style>

<div class="row">
    <div class="col">
    <?php
    $query = "select * from colaboradores where (cria_os = '1' or adm = '1') and situacao = '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
    ?>
    <div class="form-check">
    <input responsavel="<?=$d->codigo?>" class="form-check-input" type="radio" name="responsavel" id="responsavel<?=$d->codigo?>" <?=(($responsavel)?'checked':false)?>>
    <label class="form-check-label" for="responsavel<?=$d->codigo?>">
        <?=$d->nome?>
    </label>
    </div>
    <?php
    }
    ?>
    </div>
</div>



<script>
    $(function(){

        Carregando('none')
        $("input[responsavel]").change(function(){
            responsavel = $(this).attr("responsavel");
            os = '<?=$os?>';
            $.alert(`A Os ${os} foi para o usuário ${responsavel}`);

        });
    })
</script>