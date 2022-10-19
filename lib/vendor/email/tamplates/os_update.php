<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .item{
            width:100%;
            padding:10px;
            font-family:'verdana';
            font-size:12px;
            color:#333;
        }
        .item span{
            font-size:10px;
            color:#a1a1a1;
        }
        .item p{
            width:100%;
        }
    </style>
</head>
<body>
    <table cellspacing="0" cellpadding="0" style="border:1px #ccc solid; width:600px;">
        <tr>
            <td style="width:120px">
                <img src="cid:logo.png" style="width:120px" >
            </td>
            <td>
                <h2>Atualização da O.S. #{{numero_os}}</h2>
            </td>
        </tr>

        <tr>
            <td style="width:600px">
                <p>
                    Uma nova atualização foi realizada em {{data_atual}}, com as seguintes informações:
                </p>
                <div class="item">
                    <span>Tipo da Solicitação</span>
                    <p>{{tipo}}</p>
                </div>
                <div class="item">
                    <span>Título</span>
                    <p>{{titulo}}</p>
                </div>
                <div class="item">
                    <span>Descrição</span>
                    <p>{{descricao}}</p>
                </div>
                <div class="item">
                    <span>Empresa</span>
                    <p>{{empresa}}</p>
                </div>
                <div class="item">
                    <span>Responsável</span>
                    <p>{{responsavel}}</p>
                </div>
                <div class="item">
                    <span>Executor</span>
                    <p>{{executor}}</p>
                </div>
            </td>
        </tr>

    </table>
</body>
</html>