<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'compartilhar'){
        $q = "update os SET responsavel = '{$_POST['responsavel']}' where codigo = '{$_POST['os']}'";
        mysqli_query($con, $q);
        $MsgWapp = "Olá  {$_POST['nome']}, a O.S. #{$_POST['os']} foi direcionada para você. Acesse o endereço https://os.bkmanaus.com.br para mais informações.";
        SendWapp('92991886570', "A O.S. de Código #{$_POST['os']} foi trasferida de {$_POST['nome_atual']} para {$_POST['nome']}");
        exit();
    }

    if($_POST['os']){
        $query = "select a.*, b.nome as nome_responsavel from os a left join colaboradores b on a.responsavel = b.codigo where a.codigo = '{$_POST['os']}'";
        $result = mysqli_query($con, $query);
        $o = mysqli_fetch_object($result);

        $os = $o->codigo;
        $responsavel = $o->responsavel;
        $nome = $o->nome_responsavel;
    }

    if($_POST['oss']){
        $query = "select * from os where codigo IN (".implode(",",$_POST['oss']).")";
        $result = mysqli_query($con, $query);
        while($o = mysqli_fetch_object($result)){
            $oss['codigo'][] = $o->codigo;
            $oss['responsavel'][] = $o->responsavel;
        }
    }
?>
<style>

</style>

<div class="row">
    <div class="col">
        <?php
        if($_POST['os']){
        ?>
        <h4>O.S. #<?=str_pad($o->codigo , 6 , '0' , STR_PAD_LEFT)?></h4>
        <p><?=$o->titulo?></p>
        <hr>
        <?php
        }
        if($_POST['oss']){
        ?>

        <?php
        }
        ?>
    <?php
    $query = "select * from colaboradores where (cria_os = '1' or adm = '1') and situacao = '1' order by nome asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
    ?>
    <div class="form-check">
    <input
        responsavel="<?=$d->codigo?>"
        nome="<?=$d->nome?>"

        responsavel_atual="<?=$responsavel?>"
        nome_atual="<?=$nome?>"

        class="form-check-input"
        type="radio"
        name="responsavel"
        id="responsavel<?=$d->codigo?>" <?=(($responsavel == $d->codigo)?'checked':false)?>>
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
            responsavel_atual = $(this).attr("responsavel_atual");
            nome_atual = $(this).attr("nome_atual");
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
                                nome,
                                responsavel_atual,
                                nome_atual,
                                acao:'compartilhar'
                            },
                            success:function(dados){
                                $.alert('Dados atualizados com sucesso!');
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