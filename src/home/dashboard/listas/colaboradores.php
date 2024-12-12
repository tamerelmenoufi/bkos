<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
    // echo "Aqui é conteúde de ".$_POST['opc'];

    $query = "select * from colaboradores order by situacao desc nome asc";
    $result = mysqli_query($con, $query);
?>
<div class="m-3">
    <h2>Colaboradores Cadastrados</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Departamento</th>
                <th>Cargo</th>
                <th>Data de Cadastro</th>
                <th>Cria OS</th>
                <th>Administrador</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>


<?php
    while($d = mysqli_fetch_object($result)){
?>
            <tr>
                <td><?=$d->nome?></td>
                <td><?=$d->cpf?></td>
                <td><?=$d->telefone?></td>
                <td><?=$d->email?></td>
                <td><?=$d->departamento?></td>
                <td><?=$d->cargo?></td>
                <td><?=dataBr($d->data_cadastro)?></td>
                <td><?=(($d->cria_os)?'Sim':'Não')?></td>
                <td><?=(($d->adm)?'Sim':'Não')?></td>
                <td><?=(($d->adm)?'Ativo':'Bloqueado')?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
</div>