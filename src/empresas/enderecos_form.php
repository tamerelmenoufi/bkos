<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/sis/lib/includes.php");

    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update empresas_enderecos set {$attr} where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into empresas_enderecos set data_cadastro = NOW(), {$attr}";
            mysqli_query($con, $query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }


    if($_POST['codigo']){
        $query = "select * from empresas_enderecos where codigo = '{$_POST['codigo']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
    }


?>
<style>


</style>
<h2 class="Topo">Dados do Título do formulário</h2>
<div class="row">
    <div class="col">
        <form id="form-<?= $md5 ?>">
        <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?=$d->nome?>">
                <label for="nome">Nome</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="estado" name="estado" placeholder="Estado" value="<?=$d->estado?>">
                <label for="estado">Estado</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="<?=$d->cidade?>">
                <label for="cidade">Cidade</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" value="<?=$d->bairro?>">
                <label for="bairro">Bairro</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" value="<?=$d->cep?>">
                <label for="cep">CEP</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="rua" name="rua" placeholder="Rua" value="<?=$d->rua?>">
                <label for="rua">Rua</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" value="<?=$d->numero?>">
                <label for="numero">Número</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Complemento" value="<?=$d->complemento?>">
                <label for="complemento">Complemento</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="situacao" name="situacao" aria-label="Situação">
                    <option value="1" <?=(($d->situacao == '1')?'selected':false)?> >Liberado</option>
                    <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                </select>
                <label for="situacao">Situação</label>
            </div>
            <input type="hidden" name="codigo" id="codigo" value="<?=$d->codigo?>">
            <input type="hidden" name="empresa" id="empresa" value="<?=$_POST['empresa']?>">
            <button
                salvar
                class="btn btn-primary"
                type="submit"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"
            >
                Salvar
            </button>
            <button
                cancelar
                class="btn btn-danger"
                type="button"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"
            >
                Cancelar
            </button>
        </form>
    </div>
</div>

<script>
    $(function(){
        $("#cep").mask("99.999-999");

        $('#form-<?=$md5?>').submit(function (e) {
            e.preventDefault();

            var codigo = $('#codigo').val();
            var empresa = $('#empresa').val();
            var campos = $(this).serializeArray();

            if (codigo) {
                campos.push({name: 'codigo', value: codigo})
            }else{
                campos.push({name: 'empresa', value: empresa})
            }

            campos.push({name: 'acao', value: 'salvar'})
            $.ajax({
                url: 'src/empresas/enderecos_form.php',
                type:"POST",
                dataType:"json",
                data: campos,
                success: function (dados) {
                    empresa = dados.codigo;
                    $.ajax({
                        url:"src/empresas/enderecos.php",
                        type:"POST",
                        // data:{
                        //     empresa,
                        // },
                        success:function(dados){
                            $(".tab-pane").html(dados);
                            // $("a[empresa]").removeClass("active");
                            // $("a[empresa]").attr("empresa",empresa);
                            // $(`a[opc="visualizar"]`).addClass("active");
                        }
                    });

                }
            })


        });
    })
</script>
