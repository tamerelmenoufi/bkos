<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_POST['acao'] == 'status'){
        $q = "update os_registros set situacao = '{$_POST['situacao']}' where codigo = '{$_POST['os']}'";
        mysqli_query($con, $q);
        exit();
    }

    if($_POST['acao'] == 'deletar'){
        $q = "update os_registros set deletado = '{\"usuario\":\"{$_SESSION['QrAtivosLogin']}\",\"data\":\"".date("Y-m-d H:i:s")."\"}' where codigo = '{$_POST['os']}'";
        mysqli_query($con, $q);
        exit();
    }

?>
<style>

</style>

<div class="row">
    <div class="col">
    <?php
    $query = "select
                    a.*,
                    b.nome as colaborador,
                    c.titulo as classificacao,
                    d.titulo as status
                from os_registros a
                left join usuarios b on a.colaborador = b.codigo
                left join os_classificacao c on a.classificacao = c.codigo
                left join os_status d on a.status = d.codigo
            where a.cod_os = '{$_POST['os']}' and JSON_EXTRACT(a.deletado,\"$.usuario\") = ''";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
    ?>
        <div bloco<?=$d->codigo?> class="card mt-3">
            <div class="row">
                <div class="col d-flex justify-content-end">

                    <div class="form-check form-switch m-3">
                        <input status="<?=$d->codigo?>" <?=(($d->situacao == '1')?'checked':false)?> class="form-check-input" type="checkbox" role="switch" id="status<?=$d->codigo?>">
                        <label class="form-check-label" for="status<?=$d->codigo?>">Ativar o evento para exibição nos relatórios</label>
                    </div>

                    <button excluir_registro="<?=$d->codigo?>" class="btn btn-danger btn-sm m-3">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row g-0">
                <div class="col">
                <div class="card-body">
                    <h5 class="card-title"><?=$d->classificacao?> - <small style="color:#3b3a3a; font-size:12px;"><?=$d->status?></small></h5>
                    <p class="card-text"><?=$d->descricao?></p>
                    <p class="card-text" style="font-size:10px;"><small class="text-muted"><?="{$d->colaborador} em {$d->data_cadastro}"?></small></p>
                </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    </div>
</div>



<script>
    $(function(){
        $("input[status]").change(function(){
            os = $(this).attr("status");
            if($(this).prop("checked") == true){
                situacao = '1';
            }else{
                situacao = '0';
            }
            $.ajax({
                url:"src/os/eventos_lista.php",
                type:"POST",
                data:{
                    os,
                    situacao,
                    acao:'status'
                },
                success:function(dados){
                    // console.log(dados);
                }
            });
        });


        $("button[excluir_registro]").click(function(){
            os = $(this).attr("excluir_registro");
            $.confirm({
                title:false,
                content:"Deseja realmente excluir o registro?",
                buttons:{
                    'SIM':function(){
                        $(`div[bloco${os}]`).remove();

                        $.ajax({
                            url:"src/os/eventos_lista.php",
                            type:"POST",
                            data:{
                                os,
                                acao:'deletar'
                            },
                            success:function(dados){
                                // console.log(dados);
                            }
                        });

                    },
                    'NÃO':function(){

                    }
                }
            });
        });
    })
</script>