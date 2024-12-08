<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");
?>
<div class="m-3">
    <h1>Relatórios</h1>
    <div class="input-group mb-3">
        <label class="input-group-text" for="inputGroupSelect01">Buscar entre</label>
        <input type="text" class="form-control" aria-label="Text input with dropdown button">
        <label class="input-group-text" for="inputGroupSelect01">e</label>
        <input type="text" class="form-control" aria-label="Text input with dropdown button">
        <button type="button" class="btn btn-outline-secondary">Buscar</button>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');

    })
</script>