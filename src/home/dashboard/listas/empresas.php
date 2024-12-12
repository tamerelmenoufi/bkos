<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
    // echo "Aqui é conteúde de ".$_POST['opc'];

    $query = "select * from empresas order by razao_social asc";
    $result = mysqli_query($con, $query);
?>
<div class="m-3">
    <h2>Empresas Cadastradas</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>CNPJ</th>
                <th>Razão Social</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>


<?php
    while($d = mysqli_fetch_object($result)){
?>
            <tr>
                <td><?=$d->cnpj?></td>
                <td><?=$d->razao_social?></td>
                <td><?=dataBr($d->data_cadastro)?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
</div>