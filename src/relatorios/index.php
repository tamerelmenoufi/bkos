<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    if($_POST['data_inicio']) $_SESSION['data_inicio'] = $_POST['data_inicio'];
    if($_POST['data_fim']) $_SESSION['data_fim'] = $_POST['data_fim'];
    if($_POST['situacao']) $_SESSION['situacao'] = $_POST['situacao'];

    if($_POST['acao'] == 'limpar'){
        $_SESSION['data_inicio'] = false;
        $_SESSION['data_fim'] = false;
        $_SESSION['situacao'] = false;
    }

    if($_SESSION['data_inicio'] and !$_SESSION['data_fim']){
        $where = " and a.data_cadastro like '%{$_SESSION['data_inicio']}%'";
    }elseif(!$_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and a.data_cadastro like '%{$_SESSION['data_fim']}%'";
    }elseif($_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and a.data_cadastro between '{$_SESSION['data_inicio']} 00:00:00' and '{$_SESSION['data_fim']} 23:59:59'";
    }else{
        $where = " and a.data_cadastro between '".date("Y-m-d")." 00:00:00' and '".date("Y-m-d")." 23:59:59'";
    }

    if($_SESSION['situacao'] == 'p'){
        $where .= " and a.data_finalizacao = 0";
    }else if($_SESSION['situacao'] == 'c'){
        $where .= " and a.data_finalizacao > 0";
    }

?>
<div class="m-3">
    <h1>Ordem de Serviço</h1>
    <div class="input-group mb-3">
        <label class="input-group-text" for="data_inicio">Buscar entre</label>
        <input type="date" data_inicio class="form-control" id="data_inicio" value="<?=$_SESSION['data_inicio']?>" >
        <label class="input-group-text" for="data_fim">e</label>
        <input type="date" data_fim class="form-control" id="data_fim" value="<?=$_SESSION['data_fim']?>">
        <select class="form-select" id="situacao">
            <option value="t">Todos</option>
            <option value="p" <?=(($_SESSION['situacao'] == 'p')?'selected':false)?>>Pendentes</option>
            <option value="c" <?=(($_SESSION['situacao'] == 'c')?'selected':false)?>>Concluídas</option>
        </select>
        <button type="button" filtrar class="btn btn-outline-secondary">Buscar</button>
        <button type="button" limpar class="btn btn-outline-danger">Limpar</button>
    </div>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <!-- <th>Descrição</th> -->
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
    $query = "select a.*, b.titulo as tipo_nome, c.razao_social, d.nome as responsavel_nome, e.nome as executor_nome from os a left join os_tipos b on a.tipo = b.codigo left join empresas c on a.empresa = c.codigo left join colaboradores d on a.responsavel = d.codigo left join colaboradores e on a.executor = e.codigo where 1 {$where}";
    $result = mysqli_query($con, $query);
    $i = 1;
    while($d = mysqli_fetch_object($result)){
?>
        <tr>
            <td><?=$i?></td>
            <td><?=$d->titulo?></td>
            <!-- <td><?=$d->descricao?></td> -->
            <td><?=$d->tipo_nome?></td>
            <td><?=$d->reazao_social?></td>
            <td><?=$d->responsavel_nome?></td>
            <td><?=(($d->executor_nome)?:"<span class='text-danger'>Não Identificado</span>")?></td>
            <td><?=dataBr($d->data_cadastro)?></td>
            <td><?=((dataBr($d->data_finalizacao))?:"<span class='text-danger'>Pendente</span>")?></td>
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
            situacao = $("#situacao").val();
            if(!data_inicio && !data_fim){
                $.alert('Digite pelo menos uma data!');
                return;
            }
            $.ajax({
                type:"POST",
                data:{
                    data_inicio,
                    data_fim,
                    situacao
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