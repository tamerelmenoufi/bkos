<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<style>
    .tab-content{
        width:100%;
    }
    .nav-link{
        white-space:nowrap;
    }
    a[empresa=""]{
        color:#eee;
    }
    a[empresa=""]:hover{
        color:#eee;
    }
    .palco_dados_empresa{
        padding:20px;
        border:1px solid #dee2e6;
        border-top:0;
        border-radius:0 0 10px 10px;
    }
</style>
<div class="col">
    <div class="m-3">
        <h5>Gerenciamento de Empresas</h5>
        <div class="d-flex align-items-start">
            <!-- <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical"> -->
            <ul class="nav nav-tabs" style="width:100%;">
                <li class="nav-item">
                <a
                    empresa=""
                    opc="lista"
                    url="src/empresas/lista.php"
                    class="nav-link active"
                >
                    <i class="fa-solid fa-building"></i> Emresas
                </a>
                </li>
                <li class="nav-item">
                <a
                    empresa=""
                    opc="visualizar"
                    url="src/empresas/visualizar.php"
                    class="nav-link"
                >
                    <i class="fa-solid fa-list-check"></i> Dados da empresa
                </a>
                </li>
                <li class="nav-item">
                <a
                    empresa=""
                    opc="os"
                    url="src/os/index.php"
                    class="nav-link"
                >
                    <i class="fa-solid fa-list-check"></i> Solicitações de Serviços
                </a>
                </li>
                <li class="nav-item">
                <a
                    empresa=""
                    opc="contatos"
                    url="src/empresas/contatos.php"
                    class="nav-link"
                >
                    <i class="fa-solid fa-id-badge"></i> Contatos / Representantes
                </a>
                </li>
                <li class="nav-item">
                <a
                    empresa=""
                    opc="enderecos"
                    url="src/empresas/enderecos.php"
                    class="nav-link"
                >
                    <i class="fa-solid fa-location-dot"></i> Endereços / Filiais
                </a>
                </li>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active palco_dados_empresa">...</div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        $.ajax({
            url:"src/empresas/lista.php",
            type:"POST",
            data:{
                opc:''
            },
            success:function(dados){
                Carregando('none');
                $(".tab-pane").html(dados);
            }
        });

        $("a[empresa]").click(function(){

            empresa = $(this).attr("empresa");
            opc = $(this).attr("opc");
            url = $(this).attr("url");

            if(parseInt(empresa) > 0 || opc == 'lista'){

                $(".nav-link").removeClass("active");
                $(this).addClass("active");
                if(opc == 'lista') $("a[empresa]").attr("empresa",'');
                Carregando();
                $.ajax({
                    url,
                    type:"POST",
                    data:{
                        opc,
                        empresa
                    },
                    success:function(dados){
                        $(".tab-pane").html(dados);
                    }
                });
            }
        });

    })
</script>