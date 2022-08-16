<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'compartilhar'){
        echo $q = "update os SET responsavel = '{$_POST['responsavel']}' where codigo = '{$_POST['os']}'";
        mysqli_query($con, $q);
        exit();
    }

    $query = "select * from os where codigo = '{$_POST['os']}'";
    $result = mysqli_query($con, $query);
    $o = mysqli_fetch_object($result);

    $os = $o->os;
    $responsavel = $o->responsavel;

?>
<style>

</style>

<div class="row">
    <div class="col">
        <h4>O.S. #<?=str_pad($o->codigo , 6 , '0' , STR_PAD_LEFT)?></h4>
        <p><?=$d->titulo?></p>
        <hr>
    <?php
    $query = "select * from colaboradores where (cria_os = '1' or adm = '1') and situacao = '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
    ?>
    <div class="form-check">
    <input responsavel="<?=$d->codigo?>" nome="<?=$d->nome?>" class="form-check-input" type="radio" name="responsavel" id="responsavel<?=$d->codigo?>" <?=(($responsavel == $d->codigo)?'checked':false)?>>
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
            nome = $(this).attr("nome");
            os = '<?=$os?>';
            $.confirm({
                content:`Confirma o compartilhamento da <b>OS #<?=str_pad($o->codigo , 6 , '0' , STR_PAD_LEFT)?></b> com o colaborador <b>${nome}?</b>`,
                title:false,
                buttons:{
                    'SIM':function(){
                        Carregando()
                        $.ajax({
                            url:"src/os/compartilhar.php",
                            type:"POST",
                            data:{
                                os,
                                responsavel,
                                acao:'compartilhar'
                            },
                            success:function(dados){
                                $.alert('Dados atualizados com sucesso!'+dados);
                                Carregando('none')
                            }
                        });
                    },
                    'NÃO':function(){
                        $("input[responsavel]").prop("checked", false);
                        $('input[responsavel="<?=$responsavel?>"]').prop("checked", true);
                    }
                }
            });

        });
    })
</script>