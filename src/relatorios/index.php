<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    if($_POST['data_inicio']) $_SESSION['data_inicio'] = $_POST['data_inicio'];
    if($_POST['data_fim']) $_SESSION['data_fim'] = $_POST['data_fim'];

    if($_POST['acao'] == 'limpar'){
        $_SESSION['data_inicio'] = false;
        $_SESSION['data_fim'] = false;
    }

    if($_SESSION['data_inicio'] and !$_SESSION['data_fim']){
        $where = " and data_cadastro like '%{$_SESSION['data_inicio']}%'";
    }elseif(!$_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and data_cadastro like '%{$_SESSION['data_fim']}%'";
    }elseif($_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and data_cadastro between '{$_SESSION['data_inicio']} 00:00:00' and '{$_SESSION['data_fim']} 23:59:59'";
    }else{
        $where = " and data_cadastro between '".date("Y-m-d")." 00:00:00' and '".date("Y-m-d")." 23:59:59'";
    }

?>
<div class="m-3">
    <h1>Ordem de Serviço em atraso</h1>
    <div class="input-group mb-3">
        <label class="input-group-text" for="inputGroupSelect01">Buscar entre</label>
        <input type="date" data_inicio class="form-control" value="<?=$_SESSION['data_inicio']?>" >
        <label class="input-group-text" for="inputGroupSelect01">e</label>
        <input type="date" data_fim class="form-control" value="<?=$_SESSION['data_fim']?>">
        <button type="button" filtrar class="btn btn-outline-secondary">Buscar</button>
    </div>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Título</th>
        </tr>
        <tr>
            <th>Descrição</th>
        </tr>
        <tr>
            <th>tipo</th>
        </tr>
        <tr>
            <th>Empresa</th>
        </tr>
        <tr>
            <th>Responsável</th>
        </tr>
        <tr>
            <th>Executor</th>
        </tr>
        <tr>
            <th>Data Cadastro</th>
        </tr>
        <tr>
            <th>Data Finalização</th>
        </tr>
    </thead>
    <tbody>

<?php
    $query = "select * from os where 1 {$where}";
    $result = mysqli_query($query);
    while($d = mysqli_fetch_object($result)){
?>
        <tr>
            <td><?=$d->titulo?></td>
        </tr>
        <tr>
            <td><?=$d->descricao?></td>
        </tr>
        <tr>
            <td><?=$d->tipo?></td>
        </tr>
        <tr>
            <td><?=$d->empresa?></td>
        </tr>
        <tr>
            <td><?=$d->responsavel?></td>
        </tr>
        <tr>
            <td><?=$d->executor?></td>
        </tr>
        <tr>
            <td><?=$d->data_cadastro?></td>
        </tr>
        <tr>
            <td><?=$d->data_finalizacao?></td>
        </tr>
<?php
    }
?>   
    </tbody>
</table>


<script>
    $(function(){
        Carregando('none');

        $("button[filtrar]").click(function(){
            data_inicio = $("input[data_inicio]").val();
            data_fim = $("input[data_fim]").val();
            if(!data_inicio || !data_fim){
                $.alert('Digite pelo menos uma data!');
                return;
            }
            $.ajax({
                type:"POST",
                data:{
                    data_inicio,
                    data_fim
                },
                url:"src/relatorios/index.php",
                success:function(dados){
                $("#paginaHome").html(dados);
                }
            });
        })

        $("button[limpar]").click(function(){
            $.ajax({
                type:"POST",
                data:{
                    acao:'limpar'                },
                url:"src/relatorios/index.php",
                success:function(dados){
                $("#paginaHome").html(dados);
                }
            });
        })

    })
</script>