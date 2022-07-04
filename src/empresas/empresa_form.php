<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

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
            $query = "update empresas set {$attr} where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into empresas set data_cadastro = NOW(), {$attr}";
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
        $query = "select * from empresas where codigo = '{$_POST['codigo']}'";
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
                <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ" value="<?=$d->cnpj?>">
                <label for="cnpj">CNPJ</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="razao_social" name="razao_social" placeholder="Razão Social" value="<?=$d->razao_social?>">
                <label for="razao_social">Razão Social</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="situacao" name="situacao" aria-label="Situação">
                    <option value="1" <?=(($d->situacao == '1')?'selected':false)?> >Liberado</option>
                    <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                </select>
                <label for="situacao">Situação</label>
            </div>
            <input type="hidden" name="codigo" value="<?=$d->codigo?>">
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
        $("#cnpj").mask("99.999.999/9999-99");

        $('#form-<?=$md5?>').submit(function (e) {
            e.preventDefault();

            var codigo = $('#codigo').val();
            var campos = $(this).serializeArray();

            if (codigo) {
                campos.push({name: 'codigo', value: codigo})
            }

            campos.push({name: 'acao', value: 'salvar'})
            $.ajax({
                url: 'src/empresas/empresa_form.php',
                type:"POST",
                dataType:"json",
                data: campos,
                success: function (dados) {
                    empresa = dados.codigo;
                    console.log(empresa);
                    $.ajax({
                        url:"src/empresas/visualizar.php",
                        type:"POST",
                        data:{
                            empresa,
                        },
                        success:function(dados){
                            $(".tab-pane").html(dados);
                            $("a[empresa]").removeClass("active");
                            $("a[empresa]").attr("empresa",empresa);
                            $(`a[opc="visualizar"]`).addClass("active");
                        }
                    });

                }
            })


        });
    })
</script>
