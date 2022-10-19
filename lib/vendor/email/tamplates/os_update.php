<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .item{
            width:100%;
            padding:0px;
            margin:0;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item span{
            font-size:10px;
            color:#a1a1a1;
            padding:0px;
            margin:0;
        }
        .item p{
            width:100%;
            padding:0px;
            margin:0;
        }

        .item_foto{
            width:100%;
            padding:0px;
            margin:10px;
            margin-top:5px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
            border-radius:7px;
            border:solid #ccc 2px;
            background-color:#eee;
        }
        .item_foto p{
            width:100%;
            padding:0px;
            margin:0;
            text-align:center;
        }
    </style>
</head>
<body>

    <table cellspacing="0" cellpadding="0" style="border:1px #ccc solid; width:600px;">
        <tr>
            <td style="width:120px">
                <img src="cid:logo.png" style="width:120px" >
            </td>
            <td style="padding:10px;">
                <h2>Atualização da O.S. #{{os-codigo}}</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="width:600px; padding:20px;">
                <p>
                    Uma nova atualização foi realizada em {{os-data_atual}}, com as seguintes informações:
                </p>
                <div class="item">
                    <span>Tipo da Solicitação</span>
                    <p>{{os-tipo}}</p>
                </div>
                <div class="item">
                    <span>Título</span>
                    <p>{{os-titulo}}</p>
                </div>
                <div class="item">
                    <span>Descrição</span>
                    <p>{{os-descricao}}</p>
                </div>
                <div class="item">
                    <span>Empresa</span>
                    <p>{{os-empresa}}</p>
                </div>
                <div class="item">
                    <span>Responsável</span>
                    <p>{{os-responsavel}}</p>
                </div>
                <div class="item">
                    <span>Executor</span>
                    <p>{{os-executor}}</p>
                </div>
            </td>
        </tr>

        {{os_fotos}}
        <tr>
            <td colspan="2" style="width:600px; padding:20px;">
                <div class="item_foto">
                    <img src="{{os_fotos-foto}}"  />
                    <p>{{os_fotos-titulo}}</p>
                    <p>{{os_fotos-descricao}}</p>
                    <p>{{os_fotos-colaborador}}</p>
                    <p>{{os_fotos-data_cadastro}}</p>
                </div>
            </td>
        </tr>
        {{os_fotos}}

    </table>
</body>
</html>