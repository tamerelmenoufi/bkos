<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'compartilhar'){
        $q = "update os SET responsavel = '{$_POST['responsavel']}' where codigo = '{$_POST['os']}'";
        mysqli_query($con, $q);

        $email = mysqli_affected_rows($con);

        if($email){
            $cod = $_POST['os'];
            ///////////////////////////////////////////////////////
            $html = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/vendor/email/tamplates/os_update.php");
            $html = ReplaceVar($html, $cod);
            $contatos = sendContatos($cod);
            $_SESSION['MailFotosInline'][] = 'https://os.bkmanaus.com.br/img/logo.png';

            $dados = [
                'from_name' => 'SP Sistema',
                'from_email' => 'mailgun@moh1.com.br',
                'subject' => 'Atualização O.S. #' . str_pad($cod , 5 , '0' , STR_PAD_LEFT),
                'html' => $html,
                // 'attachment' => [
                //     './img_bk.png',
                //     './cliente-mohatron.xls',
                //     './formulario_prato_cheio.pdf',
                //     'https://os.bkmanaus.com.br/img/logo.png',
                // ],
                'inline' => $_SESSION['MailFotosInline'],
                // [
                //     // './img_bk.png',
                //     'https://os.bkmanaus.com.br/img/logo.png',
                // ],
                'to' => $contatos['to'],
            ];

            SendMail($dados);
            ///////////////////////////////////////////////////////

            $os = str_pad($_POST['os'] , 6 , '0' , STR_PAD_LEFT);
            //Mensagem Wapp para o Gestor
            $wapp = $contatos['wapp'];
            for($i=0;$i<count($wapp);$i++){
                SendWapp($wapp[$i]['telefone'], "A O.S. de Código #{$os} foi trasferida de {$_POST['nome_atual']} para {$_POST['nome']}. Acesse o endereço https://os.bkmanaus.com.br para mais informações.");
            }


        }




    }

    if($_POST['os']){
        $query = "select a.*, b.nome as nome_responsavel, b.telefone as telefone_atual from os a left join colaboradores b on a.responsavel = b.codigo where a.codigo = '{$_POST['os']}'";
        $result = mysqli_query($con, $query);
        $o = mysqli_fetch_object($result);

        $os = $o->codigo;
        $responsavel = $o->responsavel;
        $nome = $o->nome_responsavel;
        $telefone = $o->telefone_atual;
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

<div class="row"
    atual
    responsavel="<?=$responsavel?>"
    nome="<?=$nome?>"
    telefone="<?=$telefone?>"
>
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
        telefone="<?=$d->telefone?>"
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
            telefone = $(this).attr("telefone");

            responsavel_atual = $("div[atual]").attr("responsavel");
            nome_atual = $("div[atual]").attr("nome");
            telefone_atual = $("div[atual]").attr("telefone");
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
                                telefone,
                                responsavel_atual,
                                nome_atual,
                                telefone_atual,
                                acao:'compartilhar'
                            },
                            success:function(dados){
                                $.alert('Dados atualizados com sucesso!');
                                $(".LateralDireita").html(dados);
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