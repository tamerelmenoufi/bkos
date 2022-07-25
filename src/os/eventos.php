<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'data_finalizacao'){
        mysqli_query($con, "update os set data_finalizacao = '{$_POST['data_finalizacao']}' where codigo = '{$_POST['cod']}'");
        exit();
    }

    if($_POST['acao'] == 'salvar'){

        $query = "insert into os_registros set
                                            cod_os = '{$_POST['cod_os']}',
                                            status = '{$_POST['status']}',
                                            classificacao = '{$_POST['classificacao']}',
                                            titulo = '{$_POST['titulo']}',
                                            descricao = '{$_POST['descricao']}',
                                            colaborador = '{$_SESSION['BkOsLogin']->codigo}',
                                            data_cadastro = NOW(),
                                            situacao = '1',
                                            deletado = '{\"usuario\":\"\", \"data\":\"\"}'";
        if(mysqli_query($con, $query)){
            $retorno = [
                'status' => true,
                'msg' => 'Imagem Cadastrada com sucesso!',
            ];
        }else{
            $retorno = [
                'status' => false,
                'msg' => 'Ocorreu um erro na inserção!',
            ];
        }
        echo json_encode($retorno);
        exit();
    }

    // $query = "select * from os where codigo = '{$_POST['os']}'";
    $query = "select
            a.*,
            if(a.situacao = '1', 'Liberado', 'Bloqueado') as situacao,
            b.razao_social as nome_empresa,
            c.nome as executor
        from os a
        left join empresas b on a.empresa = b.codigo
        left join colaboradores c on a.executor = c.codigo
        where a.codigo = '{$_POST['os']}'
        order by a.titulo";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);



    $query = "select
                a.*,
                if(a.situacao = '1', 'Liberado', 'Bloqueado') as situacao,
                b.razao_social as nome_empresa,
                c.nome as responsavel,
                if(a.data_finalizacao > 0,'checked','') as data_finalizacao
            from os a
            left join empresas b on a.empresa = b.codigo
            left join colaboradores c on a.responsavel = c.codigo
            where a.codigo = '{$d->vinculo}'
            order by a.titulo";
            // "select a.*, if(a.situacao = '1', 'Ativa','Desativada') as situacao, b.razao_social, b.cnpj, c.nome as responsavel from os a left join empresas b on a.empresa = b.codigo left join colaboradores c on a.responsavel = c.codigo where (a.codigo = '{$d->vinculo}')"
    $e = mysqli_fetch_object(mysqli_query($con, $query));


?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .Rodape<?=$md5?> {
        position:absolute;
        left:0px;
        bottom:0px;
        right:20px;
        z-index:10;
        height:50px;
        background-color:#fff;
    }
    .ListarRegistros{
        margin-bottom:50px;
    }
</style>
<h4 class="Titulo<?=$md5?>">Eventos da OS #<?=str_pad($_POST['os'] , 6 , '0' , STR_PAD_LEFT)?></h4>

<div class="row">
    <div class="col">
        <!-- <div class="card mb-3 mt-3 p-3">
            <small>Esta O.S. está vinculada a solicitação:</small>
            <h5><?=$d->titulo?></h5>
            <p><?=$d->descricao?></p>
            <p style="font-size:10px; color:#a1a1a1">
                <b>Responsavel</b>: <?=$e->responsavel?><br>
                <?=$e->data_cadastro?><br>
            </p>
        </div> -->
        <div class="card mb-3 mt-3 p-3">
            <small>Informações da O.S.:</small>
            <h5><?=$d->titulo?></h5>
            <p><?=$d->descricao?></p>
            <p style="font-size:10px; color:#a1a1a1">
                <b>Executor</b>: <?=$d->executor?><br>
                <?=$d->data_cadastro?><br>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">


        <div class="form-floating mb-3">
            <select class="form-select" id="status" name="status" aria-label="Tipo de Situação">
                <?php
                $q = "select * from os_status where situacao = '1' and JSON_EXTRACT(deletado, \"$.usuario\") > 0";
                $r = mysqli_query($con, $q);
                while($s = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$s->codigo?>"><?=$s->titulo?></option>
                <?php
                }
                ?>
            </select>
            <label for="status">Situação*</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" id="classificacao" name="classificacao" aria-label="Tipo de Classificação">
                <?php
                $q = "select * from os_classificacao where situacao = '1' and JSON_EXTRACT(deletado, \"$.usuario\") > 0";
                $r = mysqli_query($con, $q);
                while($s = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$s->codigo?>"><?=$s->titulo?></option>
                <?php
                }
                ?>
            </select>
            <label for="classificacao">Classificação*</label>
        </div>

        <!-- <div class="form-floating mb-3">
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" value="">
            <label for="titulo">Título</label>
        </div> -->

        <div class="form-floating mb-3">
            <textarea name="descricao" id="descricao" class="form-control" style="height:120px;" placeholder="Descrição"></textarea>
            <label for="descricao">Descricão*</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">

    </div>
</div>

<div class="Rodape<?=$md5?>">
    <div class="d-flex justify-content-between" >
        <div class="p-4">
            <?php
                if($_SESSION['BkOsPerfil'] == 'adm' or $_SESSION['BkOsLogin']->cria_os == '1'){
            ?>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="data_finalizacao" <?=$d->data_finalizacao?>>
                <label class="form-check-label" for="data_finalizacao">OS. Concluída?</label>
            </div>
            <?php
                }
            ?>
        </div>
        <div class="p-2">
            <button type="submit" SalvarRegistro class="btn btn-success btn-ms">Salvar</button>
            <input type="hidden" id="cod_os" value="<?=$_POST['os']?>" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="ListarRegistros"></div>
    </div>
</div>


<script>
    $(function(){

        $.ajax({
            url:"src/os/eventos_lista.php",
            type:"POST",
            data:{
                os:'<?=$_POST['os']?>'
            },
            success:function(dados){
                Carregando('none');
                $(".ListarRegistros").html(dados);
            }
        });

        $("button[SalvarRegistro]").click(function(){

            cod_os = $("#cod_os").val();
            status = $("#status").val();
            classificacao = $("#classificacao").val();
            titulo = $("#titulo").val();
            descricao = $("#descricao").val();

            if(!status || !classificacao || !descricao){
                $.alert('Favor preencher os campos obrigatórios (*)');
                return false;
            }


            $("#titulo").val('');
            $("#descricao").val('');
            Carregando();
            $.ajax({
                url:"src/os/eventos.php",
                type:"POST",
                typeData:"JSON",
                data:{
                    cod_os,
                    status,
                    classificacao,
                    titulo,
                    descricao,
                    acao:'salvar'
                },
                success:function(dados){
                    // if(dados.status){
                        console.log(dados.status);
                        $.ajax({
                            url:"src/os/eventos_lista.php",
                            type:"POST",
                            data:{
                                os:'<?=$_POST['os']?>'
                            },
                            success:function(dados){
                                $(".ListarRegistros").html(dados);
                            }
                        });

                    // }
                }
            });

        });

        $("#data_finalizacao").click(function(){
            if($(this).prop("checked") == true){
                data_finalizacao = date("Y-m-d H:i:s");
            }else{
                data_finalizacao = '0';
            }
            $.ajax({
                url:"src/os/eventos.php",
                type:"POST",
                data:{
                    acao:'data_finalizacao',
                    data_finalizacao,
                    cod:'<?=$_POST['os']?>'
                },
                success:function(dados){
                    alert('Ação da Finalização foi confirmada com sucesso!');
                },
                error:function(){
                    alert('erro!');
                }
            })
        });


    })
</script>