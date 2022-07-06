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
            $query = "update colaboradores set {$attr} where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into colaboradores set data_cadastro = NOW(), {$attr}";
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


    if($_POST['colaborador']){
        $query = "select * from colaboradores where codigo = '{$_POST['colaborador']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
    }


?>
<style>
    .Topo<?=$md5?> {
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Topo<?=$md5?>">Dados do Colaborador</h4>
<div class="row">
    <div class="col">
        <form id="form-<?= $md5 ?>">
        <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?=$d->nome?>" required>
                <label for="cnpj">Nome*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" value="<?=$d->cpf?>" required>
                <label for="cpf">CPF*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" value="<?=$d->telefone?>" required>
                <label for="telefone">Telefone*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="email" name="email" placeholder="E-mail" value="<?=$d->email?>">
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="departamento" name="departamento" placeholder="Departamento" value="<?=$d->departamento?>" required>
                <label for="departamento">Departamento*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Cargo" value="<?=$d->cargo?>" required>
                <label for="cargo">Cargo*</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" id="situacao" name="situacao" aria-label="Situação">
                    <option value="1" <?=(($d->situacao == '1')?'selected':false)?> >Liberado</option>
                    <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                </select>
                <label for="situacao">Situação*</label>
            </div>
            <input type="hidden" name="codigo" id="codigo" value="<?=$d->codigo?>">
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
        $("#telefone").mask("(99) 9 9999-9999");
        $("#cpf").mask("999.999.999-99");

        $('#form-<?=$md5?>').submit(function (e) {
            e.preventDefault();

            var codigo = $('#codigo').val();
            var campos = $(this).serializeArray();

            if (codigo) {
                campos.push({name: 'codigo', value: codigo})
            }

            campos.push({name: 'acao', value: 'salvar'})
            $.ajax({
                url: 'src/colaboradores/form.php',
                type:"POST",
                dataType:"json",
                data: campos,
                success: function (dados) {
                    empresa = dados.codigo;
                    $.ajax({
                        url:"src/colaboradores/index.php",
                        type:"POST",
                        success:function(dados){
                            $("#paginaHome").html(dados);
                        }
                    });

                }
            })


        });
    })
</script>
