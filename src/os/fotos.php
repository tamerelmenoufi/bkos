<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/sis/lib/includes.php");

    if($_POST['acao'] == 'salvar'){


        file_put_contents('fotos/upload.txt', print_r($_FILE, true));

        if($_FILES['image'])
        {
            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];

            file_put_contents('fotos/upload-'.$img, $tmp);

        }

        if($_POST['foto_nome'] and $_POST['foto_tipo'] and $_POST['foto_value']){
            $img = base64_decode(str_replace("data:{$_POST['foto_tipo']};base64,", false, $_POST['foto_value']));
            if(!is_dir("fotos/{$_POST['cod_os']}")) mkdir("fotos/{$_POST['cod_os']}");
            $ext = substr($_POST['foto_nome'],strrpos($_POST['foto_nome'],'.'), strlen($_POST['foto_nome']));
            $nome = md5("{$_POST['cod_os']}{$_POST['foto_nome']}{$_POST['foto_tipo']}".date("YmdHis"))."{$ext}";
            file_put_contents("fotos/{$_POST['cod_os']}/{$nome}", $img);
        }else{
            $nome = $_POST['foto_nome'];
        }

        $query = "insert into os_fotos set
                                            cod_os = '{$_POST['cod_os']}',
                                            foto = '{$nome}',
                                            titulo = '{$_POST['titulo']}',
                                            descricao = '{$_POST['descricao']}',
                                            ordem = '{$_POST['ordem']}',
                                            colaborador = '{$_SESSION['QrAtivosLogin']}',
                                            data_cadastro = NOW(),
                                            situacao = '1',
                                            deletado = '{\"usuario\":\"\", \"data\":\"\"}'";
        if(mysqli_query($con, $query)){
            $retorno = [
                'status' => true,
                'msg' => 'Imagem Cadastrada com sucesso!',
            ];
        }else{
            $retorno = [
                'status' => false,
                'msg' => 'Ocorreu um erro na inserção!',
            ];
        }
        echo json_encode($retorno);
        exit();
    }


    $query = "select * from os where codigo = '{$_POST['os']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

?>
<style>
    .Topo<?=$md5?> {
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .Foto{
        position:relative;
        width:100%;
        height:120px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align:center;
        background-position:center;
        background-size:100% auto;
        background-repeat:no-repeat;
        cursor:pointer;
    }
    .Foto div{
        position:absolute;
        width:100%;
        height:100%;
    }
    p[msg]{
        font-size:10px;
        color:blue;
        position:relative;
        text-align:center;
    }
    .FileFoto{
        position:absolute;
        left:0;
        top:0;
        bottom:0;
        width:100%;
        background:#eee;
        opacity:0;
        z-index:2;
    }
    .Apagar{
        position:relative;
        text-align:center;
        margin-top:0px;
        width:100%;
        opacity:1;
        z-index:3;
    }
    .Apagar span{
        padding:2px 4px 3px 4px;
        border-radius:3px;
        background-color:red;
        color:#fff;
        font-size:10px;
        cursor:pointer;
        opacity:0;
    }
    .iconeImagem{
        position:absolute;
        font-size:100px;
        color:#eee;
        left:50%;
        margin-left:-50px;
        top:7px;
    }
</style>

<h4 class="Topo<?=$md5?>">Lista de fotos da OS #<?=str_pad($_POST['os'] , 6 , '0' , STR_PAD_LEFT)?></h4>
<div class="row">
    <div class="col">
        <div class="card mb-3 mt-3 p-3">
            <small>Esta O.S. está vinculada a solicitação:</small>
            <h5><?=$d->titulo?></h5>
            <p><?=$d->descricao?></p>
        </div>
    </div>
</div>
<!-- <form id="form" method="post" enctype="multipart/form-data"> -->
    <div class="row">
        <div class="col-md-4">
            <div class="Foto">
                <div>
                    <i class="fa-solid fa-image iconeImagem"></i>
                    <input type="file" name="foto" id="foto" class="FileFoto" accept="image/*" capture="camera" />
                    <input
                            type="hidden"
                            id="encode_file"
                            nome=""
                            tipo=""
                            value=""
                    />
                </div>
            </div>
            <div class="Apagar">
                <span>
                    <i class="fa-solid fa-eraser"></i>
                </span>
            </div>

            <p msg>Selecione a imagem*</p>
        </div>
        <div class="col-md-8">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" value="">
                <label for="titulo">Título*</label>
            </div>
            <div class="form-floating mb-3">
                <textarea name="descricao" id="descricao" class="form-control" style="height:120px;" placeholder="Descrição"></textarea>
                <label for="descricao">Descricão*</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div style="display:flex; justify-content:end">
                <button type="submit" SalvarFoto class="btn btn-success btn-ms">Salvar</button>
                <input type="hidden" id="cod_os" value="<?=$_POST['os']?>" />
            </div>
        </div>
    </div>
<!-- </form> -->



<div class="row">
    <div class="col">
        <div class="ListarFotos"></div>
    </div>
</div>


<script>
    $(function(){

        $.ajax({
            url:"src/os/fotos_lista.php",
            type:"POST",
            data:{
                os:'<?=$_POST['os']?>'
            },
            success:function(dados){
                Carregando('none');
                $(".ListarFotos").html(dados);
            }
        });

        // $(".Foto, .Apagar").mouseover(function(){
        //     if($("#encode_file").attr("nome")){
        //         $(".Apagar span").css("opacity","1");
        //     }
        // }).mouseout(function(){
        //     $(".Apagar span").css("opacity","0");
        // });

        $(".Apagar span").click(function(){

            $("#encode_file").val('');
            $("#encode_file").attr("nome", '');
            $("#encode_file").attr("tipo", '');
            $(".Foto").css("background-image",'');
            $(".Foto div i").css("opacity","1");
            $(".Apagar span").css("opacity","0");

        });


        if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {

                if ($(this).val()) {
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {



                            //////////////////////////////////////////////////////////////////

                            var img = new Image();
                            img.src = f.target.result;

                            img.onload = function () {

                                // CREATE A CANVAS ELEMENT AND ASSIGN THE IMAGES TO IT.
                                var canvas = document.createElement("canvas");

                                var value = 50;

                                // RESIZE THE IMAGES ONE BY ONE.
                                w = img.width;
                                h = img.height;
                                img.width = 800 //(800 * 100)/img.width // (img.width * value) / 100
                                img.height = (800 * h / w) //(img.height/100)*img.width // (img.height * value) / 100

                                var ctx = canvas.getContext("2d");
                                ctx.clearRect(0, 0, canvas.width, canvas.height);
                                canvas.width = img.width;
                                canvas.height = img.height;
                                ctx.drawImage(img, 0, 0, img.width, img.height);

                                // $('.Foto').append(img);      // SHOW THE IMAGES OF THE BROWSER.
                                console.log(canvas.toDataURL(file.type));

                                ///////

                                var Base64 = canvas.toDataURL(file.type); //f.target.result;
                                var type = file.type;
                                var name = file.name;

                                $("#encode_file").val(Base64);
                                $("#encode_file").attr("nome", name);
                                $("#encode_file").attr("tipo", type);

                                $(".Foto").css("background-image",`url(${Base64})`);
                                $(".Foto div i").css("opacity","0");
                                $(".Apagar span").css("opacity","1");

                                //////



                            }

                            //////////////////////////////////////////////////////////////////





                            };
                            fileReader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            });
        } else {
        alert('Nao suporta HTML5');
        }


        $("button[SalvarFoto]").click(function(){


            cod_os = $("#cod_os").val();
            foto_nome = $("#encode_file").attr('nome');
            foto_tipo = $("#encode_file").attr('tipo');
            foto_value = $("#encode_file").val();
            titulo = $("#titulo").val();
            descricao = $("#descricao").val();

            if(
                !cod_os ||
                !foto_nome ||
                !foto_tipo ||
                !foto_value ||
                !titulo ||
                !descricao
            ){
                $.alert('Registro fotográfico não pode ser inserido<br>Dados Obrigatórios incompletos!');
                return false;
            }



            $("#encode_file").attr('nome','');
            $("#encode_file").attr('tipo','');
            $("#encode_file").val('');
            $("#titulo").val('');
            $("#descricao").val('');

            $(".Foto").css("background-image",'');
            $(".Foto div i").css("opacity","1");

            Carregando();
            $.ajax({
                url:"src/os/fotos.php",
                type:"POST",
                typeData:"JSON",
                mimeType: 'multipart/form-data',
                data:{
                    cod_os,
                    foto_nome,
                    foto_tipo,
                    foto_value,
                    titulo,
                    descricao,
                    acao:'salvar'
                },
                success:function(dados){
                    // if(dados.status){
                        console.log(dados.status);
                        $.ajax({
                            url:"src/os/fotos_lista.php",
                            type:"POST",
                            data:{
                                os:'<?=$_POST['os']?>'
                            },
                            success:function(dados){
                                $(".ListarFotos").html(dados);
                                //$.alert('Registro inserido com sucesso!');
                            }
                        });
                    // }
                },
                error:function(erro){

                    // $.alert('Ocorreu um erro!' + erro.toString());
                    //dados de teste
                }
            });

        });


    })
</script>