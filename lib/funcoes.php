<?php
    function dataBr($dt){
        list($d, $h) = explode(" ",$dt);
        list($a, $m, $d) = explode("-",$d);
        if($a === '0000' and $m === '00' and $d === '00'){
            return '';
        }else{
            return "{$d}/{$m}/{$a}".(($h)?" ".$h:false);
        }
    }