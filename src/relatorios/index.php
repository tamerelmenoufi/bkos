<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


    if($_POST['data_inicio']) $_SESSION['data_inicio'] = $_POST['data_inicio'];
    if($_POST['data_fim']) $_SESSION['data_fim'] = $_POST['data_fim'];
    if($_POST['situacao']) $_SESSION['situacao'] = $_POST['situacao'];
    if($_POST['empresa']) $_SESSION['empresa'] = $_POST['empresa'];
    if($_POST['executor']) $_SESSION['executor'] = $_POST['executor'];

    if($_POST['acao'] == 'limpar'){
        $_SESSION['data_inicio'] = false;
        $_SESSION['data_fim'] = false;
        $_SESSION['situacao'] = false;
        $_SESSION['empresa'] = false;
        $_SESSION['executor'] = false;
    }

    if($_SESSION['data_inicio'] and !$_SESSION['data_fim']){
        $where = " and a.data_cadastro like '%{$_SESSION['data_inicio']}%'";
    }elseif(!$_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and a.data_cadastro like '%{$_SESSION['data_fim']}%'";
    }elseif($_SESSION['data_inicio'] and $_SESSION['data_fim']){
        $where = " and a.data_cadastro between '{$_SESSION['data_inicio']} 00:00:00' and '{$_SESSION['data_fim']} 23:59:59'";
    }else{
        $_SESSION['data_inicio'] = date("Y-m-d");
        $_SESSION['data_fim'] = date("Y-m-d");
        $where = " and a.data_cadastro between '".date("Y-m-d")." 00:00:00' and '".date("Y-m-d")." 23:59:59'";
    }

    if($_SESSION['situacao'] == 'p'){
        $where .= " and a.data_finalizacao = 0";
    }else if($_SESSION['situacao'] == 'c'){
        $where .= " and a.data_finalizacao > 0";
    }

    if($_SESSION['empresa'] == 't'){
        
    }else if($_SESSION['empresa']){
        $where .= " and a.empresa = '{$_SESSION['empresa']}'";
    }

    if($_SESSION['executor'] == 't'){
        
    }else if($_SESSION['executor']){
        $where .= " and a.executor = '{$_SESSION['executor']}'";
    }

?>
<style>
    .relatorio th, .relatorio td{
        font-size:12px;
    }
</style>
<div class="m-3">
    <h1>Ordem de Serviço</h1>
    <div class="input-group mb-3">
        <label class="input-group-text" for="data_inicio">Buscar entre</label>
        <input type="date" data_inicio class="form-control" id="data_inicio" value="<?=$_SESSION['data_inicio']?>" >
        <label class="input-group-text" for="data_fim">e</label>
        <input type="date" data_fim class="form-control" id="data_fim" value="<?=$_SESSION['data_fim']?>">

        <label class="input-group-text" for="empresa">Empresa</label>
        <select class="form-select" id="empresa">
            <option value="t">Todos</option>
            <?php
            $q = "select * from empresas order by razao_social asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($r)){
            ?>
            <option value="<?=$s->codigo?>" <?=(($_SESSION['empresa'] == $s->codigo)?'selected':false)?>><?=$s->razao_social?></option>
            <?php
            }
            ?>
        </select>

        <label class="input-group-text" for="executor">Executor</label>
        <select class="form-select" id="executor">
            <option value="t">Todos</option>
            <?php
            $q = "select * from colaboradores where situacao = '1' order by nome asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($r)){
            ?>
            <option value="<?=$s->codigo?>" <?=(($_SESSION['executor'] == $s->codigo)?'selected':false)?>><?=$s->nome?> (<?=$s->cpf?>)</option>
            <?php
            }
            ?>
        </select>
        
        <label class="input-group-text" for="situacao">Situação</label>
        <select class="form-select" id="situacao">
            <option value="t">Todos</option>
            <option value="p" <?=(($_SESSION['situacao'] == 'p')?'selected':false)?>>Pendentes</option>
            <option value="c" <?=(($_SESSION['situacao'] == 'c')?'selected':false)?>>Concluídas</option>
        </select>
        
        <button type="button" filtrar class="btn btn-outline-secondary">Buscar</button>
        <button type="button" limpar class="btn btn-outline-danger">Limpar</button>
    </div>
</div>
<table class="table table-hover relatorio">
    <thead>
        <tr>
            <th>#</th>
            <th>Ordem</th>
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
    $query = "select 
                    a.*, 
                    b.titulo as tipo_nome, 
                    c.razao_social, 
                    d.nome as responsavel_nome, 
                    e.nome as executor_nome 
                from os a 
                     left join os_tipos b on a.tipo = b.codigo 
                     left join empresas c on a.empresa = c.codigo 
                     left join colaboradores d on a.responsavel = d.codigo 
                     left join colaboradores e on a.executor = e.codigo 
                where 1 {$where} 
                order by e.nome asc, a.data_finalizacao desc
                ";
    $result = mysqli_query($con, $query);
    $i = 1;
    while($d = mysqli_fetch_object($result)){
?>
        <tr>
            <td><?=$i?></td>
            <td>#<?=str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT)?></td>
            <td><?=$d->titulo?></td>
            <!-- <td><?=$d->descricao?></td> -->
            <td><?=$d->tipo_nome?></td>
            <td><?=$d->razao_social?></td>
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
            empresa = $("#empresa").val();
            executor = $("#executor").val();

            console.log(executor);
            if(!data_inicio && !data_fim){
                $.alert('Digite pelo menos uma data!');
                return;
            }
            $.ajax({
                type:"POST",
                data:{
                    data_inicio,
                    data_fim,
                    empresa,
                    situacao,
                    executor
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