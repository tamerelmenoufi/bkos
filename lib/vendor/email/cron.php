<?php

    $dados = file("tamplates/resumo.php");

    foreach($dados as $i => $v){
        echo $v;
    }