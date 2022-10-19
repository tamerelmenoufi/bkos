<?php

    function ReplaceVar($html, $cod){

        global $con;

        $Str = [];

        $query = "select a.*,
                        b.nome as executor,
                        c.nome as responsavel,
                        d.titulo as tipo
                    from os a
                    left join colaboradores b on a.executor = b.codigo
                    left join colaboradores c on a.responsavel = c.codigo
                    left join os_tipos d on a.tipo = d.codigo
                    where a.codigo = '{$cod}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

    //////////////////////////////////////////////////////////////////////////////

        $Str['os']['codigo'] = str_pad($d->codigo , 6 , '0' , STR_PAD_LEFT);
        $Str['os']['executor'] = $d->executor;
        $Str['os']['data_cadastro'] = $d->data_cadastro;
        $Str['os']['responsavel'] = $d->responsavel;
        $Str['os']['titulo'] = $d->titulo;
        $Str['os']['descricao'] = $d->descricao;
        $Str['os']['tipo'] = $d->tipo;


        $q = "select a.*, b.nome as colaborador from os_fotos a left join colaboradores b on a.colaborador = b.codigo where a.cod_os = '{$d->codigo}' and a.situacao = '1' and JSON_EXTRACT(a.deletado,\"$.usuario\") = ''";
        $r = mysqli_query($con, $q);
        $i=0;
        while($e = mysqli_fetch_object($r)){
            //////////////////////////////////////////////////////////////////////////////
            $Str['os_fotos'][$i]['foto'] = 'http://os.bkmanaus.com.br/src/os/fotos/'.$d->codigo.'/'.$e->foto;
            $Str['os_fotos'][$i]['titulo'] = $e->titulo;
            $Str['os_fotos'][$i]['descricao'] = $e->descricao;
            $Str['os_fotos'][$i]['colaborador'] = $e->colaborador;
            $Str['os_fotos'][$i]['data_cadastro'] = $e->data_cadastro;
            //////////////////////////////////////////////////////////////////////////////
            $i++;
        }



        $q = "select
                    a.*,
                    b.titulo as status,
                    c.titulo as classificacao,
                    d.nome as colaborador
                from os_registros a
                    left join os_status b on a.status = b.codigo
                    left join os_classificacao c on a.classificacao = c.codigo
                    left join colaboradores d on a.colaborador = d.codigo

                where a.cod_os = '{$d->codigo}' and a.situacao = '1' and JSON_EXTRACT(a.deletado,\"$.usuario\") = '' order by a.data_cadastro asc";
        $r = mysqli_query($con, $q);
        $i=0;
        while($e = mysqli_fetch_object($r)){
            //////////////////////////////////////////////////////////////////////////////
            $Str['os_registros'][$i]['classificacao'] = $e->classificacao;
            $Str['os_registros'][$i]['status'] = $e->status;
            $Str['os_registros'][$i]['descricao'] = $e->descricao;
            $Str['os_registros'][$i]['colaborador'] = $e->colaborador;
            $Str['os_registros'][$i]['data_cadastro'] = $e->data_cadastro;
            //////////////////////////////////////////////////////////////////////////////
            $i++;
        }

        foreach($Str['os'] as $i => $v){
            $html = str_replace("{{os-{$i}}}", $v, $html);
        }

        $p1 = stripos($html, '{{os_fotos}}');
        $p2 = (strripos($html, '{{os_fotos}}') - $p1);
        $fotos = substr($html, $p1, $p2);
        $html = str_replace($fotos,'{{os_fotos}}',$html);
        $fotos = str_replace('{{os_fotos}}', false, $fotos);

        $AddFotos = $fotos;
        $AddF = [];
        foreach($Str['os_fotos'] as $i => $v){
            $AddFotos = str_replace("{{os_fotos-foto}}", $v['foto'], $fotos);
            $AddFotos = str_replace("{{os_fotos-titulo}}", $v['titulo'], $AddFotos);
            $AddFotos = str_replace("{{os_fotos-descricao}}", $v['descricao'], $AddFotos);
            $AddFotos = str_replace("{{os_fotos-colaborador}}", $v['colaborador'], $AddFotos);
            $AddFotos = str_replace("{{os_fotos-data_cadastro}}", $v['data_cadastro'], $AddFotos);
            $AddFotos = str_replace("{{os_fotos-titulo}}", $v['titulo'], $AddFotos);
            $AddF[] = $AddFotos;
        }

        if($AddF){
            $html = str_replace('{{os_fotos}}',implode("",$AddF),$html);
        }

        return $html;
    }