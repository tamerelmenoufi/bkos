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
                novoEnderecoEmpresa
            >
                <i class="fa-solid fa-plus"></i>
                Novo
            </button>
        </div>
    </div>
</div>
<?php
    $query = "select *, if(situacao = '1', 'Ativo', 'Bloqueado') as situacao_descricao from empresas_enderecos where empresa = '{$_SESSION['empresa']}' order by nome";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
<div class="row">
    <div class="col">
        <div class="card mb-3">
            <h5 class="card-header"><?=$d->nome?></h5>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p class="card-text"><?=$d->estado?></p>
                        <p class="card-text"><?=$d->cidade?></p>
                        <p class="card-text"><?=$d->bairro?></p>
                        <p class="card-text"><?=$d->cep?></p>
                        <p class="card-text"><?=$d->rua?></p>
                        <p class="card-text"><?=$d->numero?></p>
                        <p class="card-text"><?=$d->complemento?></p>
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
                    <div
                        class="col mapa"
                        e="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                    >
                        Dados do Mapa
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
    }
?>

<script>

    function Mapas(e){
        $.ajax({
            url:"src/empresas/visualizar_endereco.php",
            type:"POST",
            data:{
                e,
            },
            success:function(dados){
                $(`div[e="${e}"]`).html(dados);
            }
        });
    }

    $(".mapa").click(function(){
        e = $(this).attr("e");
        if(e){
            $.ajax({
                url:"src/empresas/editar_endereco.php",
                type:"POST",
                data:{
                    e,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        }
    });

    $(function(){

        $(".mapa").each(function(){
            e = $(this).attr('e');
            Mapas(e);
        });

        $("button[novoEnderecoEmpresa]").click(function(){
            $.ajax({
                url:"src/empresas/enderecos_form.php",
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
                url:"src/empresas/enderecos_form.php",
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