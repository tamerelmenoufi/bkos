<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'salvar'){

        $query = "insert into os_registros set
                                            cod_os = '{$_POST['cod_os']}',
                                            status = '{$_POST['status']}',
                                            titulo = '{$_POST['titulo']}',
                                            descricao = '{$_POST['descricao']}',
                                            colaborador = '{$_SESSION['QrAtivosLogin']}',
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

?>
<style>


</style>

<div class="row">
    <div class="col">


        <div class="form-floating mb-3">
            <select class="form-select" id="status" name="status" aria-label="Tipo de Situação">
                <?php
                $q = "select * from os_status where situacao = '1' and JSON_EXTRACT(deletado, \"$.usuario\") > 0";
                $r = mysqli_query($con, $q);
                while($s = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$s->codigo?>" <?=(($d->status == $s->codigo)?'selected':false)?> ><?=$s->titulo?></option>
                <?php
                }
                ?>
            </select>
            <label for="status">Situação</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" value="<?=$d->titulo?>">
            <label for="titulo">Título</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="descricao" id="descricao" class="form-control" style="height:120px;" placeholder="Descrição"><?=$d->descricao?></textarea>
            <label for="descricao">Descricão</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div style="display:flex; justify-content:end">
            <button SalvarRegistro class="btn btn-success btn-ms">Salvar</button>
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
                $(".ListarRegistros").html(dados);
            }
        });

        $("button[SalvarRegistro]").click(function(){

            cod_os = $("#cod_os").val();
            status = $("#status").val();
            titulo = $("#titulo").val();
            descricao = $("#descricao").val();

            $("#titulo").val('');
            $("#descricao").val('');

            $.ajax({
                url:"src/os/eventos.php",
                type:"POST",
                typeData:"JSON",
                data:{
                    cod_os,
                    status,
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


    })
</script>