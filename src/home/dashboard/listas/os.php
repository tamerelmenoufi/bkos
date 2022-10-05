<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    switch($_POST['opc']){
        case 'concluidas':{
            $query = "
                        select a.*,
                               b.titulo as tipo,
                               c.razao_social as empresa,
                               concat(
                                        d.nome,', ',
                                        d.rua,', ',
                                        d.numero,', ',
                                        d.bairro,', ',
                                        d.cidade,', ',
                                        d.estado,', ',
                                        d.cep,', ',
                                        d.complemento
                                    ) as empresa_endereco,
                                e.nome as responsavel,
                                f.nome as executor


                            from os a

                            left join os_tipos b on a.tipo = b.codigo
                            left join empresas c on a.empresa = c.codigo
                            left join empresas_enderecos d on a.empresa_endereco = d.codigo
                            left join colaboradores e on a.responsavel = e.codigo
                            left join colaboradores f on a.executor = f.codigo

                        where a.data_finalizacao > 0";
            break;
        }
        case 'pendentes':{
            $query = "
            select a.*,
                   b.titulo as tipo,
                   c.razao_social as empresa,
                   concat(
                            d.nome,', ',
                            d.rua,', ',
                            d.numero,', ',
                            d.bairro,', ',
                            d.cidade,', ',
                            d.estado,', ',
                            d.cep,', ',
                            d.complemento
                        ) as empresa_endereco,
                    e.nome as responsavel,
                    f.nome as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo


            where a.data_finalizacao = 0".
            (($_POST['data'])?" and a.data_cadastro like '%{$_POST['data']}%'":false).
            (($_POST['responsavel'])?" and a.responsavel = '{$_POST['responsavel']}'":false).
            (($_POST['executor'])?" and a.executor = '{$_POST['executor']}'":false).
            (($_POST['tipo'])?" and a.tipo = '{$_POST['tipo']}'":false)
            ;
            break;
        }
        case 'geral':{
            $query = "
            select a.*,
                   IF(b.titulo != '' and b.titulo != null, b.titulo, 'INDEFINIDO') as tipo,
                   c.razao_social as empresa,
                   concat(
                            d.nome,', ',
                            d.rua,', ',
                            d.numero,', ',
                            d.bairro,', ',
                            d.cidade,', ',
                            d.estado,', ',
                            d.cep,', ',
                            d.complemento
                        ) as empresa_endereco,
                    IF(e.nome != '' and e.nome != null, e.nome, 'INDEFINIDO') as responsavel,
                    IF(f.nome != '' and f.nome != null, f.nome, 'INDEFINIDO') as executor

                from os a

                left join os_tipos b on a.tipo = b.codigo
                left join empresas c on a.empresa = c.codigo
                left join empresas_enderecos d on a.empresa_endereco = d.codigo
                left join colaboradores e on a.responsavel = e.codigo
                left join colaboradores f on a.executor = f.codigo

            limit 100";
            break;
        }
        default:{

            break;
        }
    }
?>
<style>
    .detalhes{
        width:100%;
    }
    .detalhes span{
        width:100%;
        padding:0;
        margin:0;
        font-size:9px;
        color:#a1a1a1;
        text-align:left;
    }
    .detalhes p{
        padding:0;
        margin:0;
        font-size:12px;
        text-align:left;
        color:#000;
        width:100%;
    }
    .barraTitulo{
        position:fixed;
        top:0px;
        left:0px;
        right:0px;
        height:70px;
        background:#fff;
        z-index:10;
    }
    .relatorio-body{
        position:fixed;
        top:80px;
        left:0;
        right:0;
        bottom:0;
        overflow:auto;
    }
</style>
<div class="barraTitulo">
    <h2 class="m-3"><?=$_POST['titulo']?></h2>
</div>

<div class="relatorio-body">
<?php
    if($query){
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
?>
    <div class="card m-3">

    <div class="card-body">
        <h5 class="card-title">O.S. #<?=str_pad($d->codigo , 5 , '0' , STR_PAD_LEFT)?> - <?=$d->titulo?> (<?=$d->tipo?>)</h5>
        <h6 class="card-subtitle mb-2 text-muted">
            <?=$d->descricao?>
        </h6>
        <p class="card-text">
            <div class="d-flex justify-content-between">
                <div class="detalhes">
                    <span>Responsável</span>
                    <p><?=$d->responsavel?></p>
                    <span>Executor</span>
                    <p><?=$d->executor?></p>
                </div>
                <div class="d-flex align-items-start">
                    <button
                        class="btn btn-success btn-xs"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        os="<?=$d->codigo?>"
                        destino="eventos"
                    >
                        <i class="fa-solid fa-file-pen"></i>
                    </button>

                    <button
                        class="btn btn-success btn-xs"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        os="<?=$d->codigo?>"
                        destino="fotos"
                    >
                        <i class="fa-solid fa-file-pen"></i>
                    </button>

                </div>
            </div>
        </p>
    </div>
    </div>
<?php
        }
    }
?>
</div>
<script>
    $(function(){
        $("button[os]").click(function(){
            os = $(this).attr("os");
            destino = $(this).attr("destino");
            $.ajax({
                url:`src/os/${destino}.php`,
                type:"POST",
                data:{
                    os
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            });
        });
    })
</script>