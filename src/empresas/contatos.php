<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/sis/lib/includes.php");

    if($_POST['empresa']) $_SESSION['empresa'] = $_POST['empresa'];
?>

<div class="col">
    <div class="col d-flex justify-content-between">
        <div class="p-2">Dados da tabel de Empresas (<?=$_SESSION['empresa']?>)</div>
        <div class="p-2">
            <button
                class="btn btn-primary"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"
                novoContatoEmpresa
            >
                <i class="fa-solid fa-plus"></i>
                Novo
            </button>
        </div>
    </div>
</div>
<?php
    $query = "select *, if(situacao = '1', 'Ativo', 'Bloqueado') as situacao_descricao from empresas_contatos where empresa = '{$_SESSION['empresa']}' order by nome";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
<div class="card mb-3">
  <h5 class="card-header"><?=$d->nome?></h5>
  <div class="card-body">
    <h5 class="card-title"><?=$d->cpf?></h5>
    <p class="card-text"><?=$d->telefone?></p>
    <p class="card-text"><?=$d->email?></p>
    <p class="card-text"><?=$d->departamento?></p>
    <p class="card-text"><?=$d->cargo?></p>
    <p class="card-text">Cadastrado em <?=$d->data_cadastro?></p>

    <a
      editar="<?=$d->codigo?>"
      class="btn btn-<?=(($d->situacao == '1')?'primary':'danger')?>"
      data-bs-toggle="offcanvas"
      href="#offcanvasDireita"
      role="button"
      aria-controls="offcanvasDireita"
    ><?=$d->situacao_descricao?></a>
  </div>
</div>
<?php
    }
?>

<script>
    $(function(){
        $("button[novoContatoEmpresa]").click(function(){
            $.ajax({
                url:"src/empresas/contatos_form.php",
                type:"POST",
                data:{
                    empresa:'<?=$_SESSION['empresa']?>',
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });

        $("a[editar]").click(function(){
            codigo = $(this).attr("editar");
            $.ajax({
                url:"src/empresas/contatos_form.php",
                type:"POST",
                data:{
                    codigo,
                    empresa:'<?=$_SESSION['empresa']?>',
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });

    })
</script>