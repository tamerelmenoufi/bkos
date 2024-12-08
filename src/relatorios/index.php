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
            <th>#</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>tipo</th>
            <th>Empresa</th>
            <th>Responsável</th>
            <th>Executor</th>
            <th>Data Cadastro</th>
            <th>Data Finalização</th>
        </tr>
    </thead>
    <tbody>

<?php
    $query = "select * from os where 1 {$where}";
    $result = mysqli_query($con, $query);
    $i = 1;
    while($d = mysqli_fetch_object($result)){
?>
        <tr>
            <td><?=$i?></td>
            <td><?=$d->titulo?></td>
            <td><?=$d->descricao?></td>
            <td><?=$d->tipo?></td>
            <td><?=$d->empresa?></td>
            <td><?=$d->responsavel?></td>
            <td><?=$d->executor?></td>
            <td><?=$d->data_cadastro?></td>
            <td><?=$d->data_finalizacao?></td>
        </tr>
<?php
    $i++;
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
            if(!data_inicio && !data_fim){
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