<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");

    if($_GET['s']){
        $_SESSION = [];
        header("location:./");
        exit();
    }

    if($_SESSION['QrAtivosPerfil'] == 'adm'){
        $url = "src/home/index.php";
    }else if($_SESSION['QrAtivosPerfil'] == 'os'){
        $url = "src/home/os.php";
    }else{
        $url = "src/login/index.php";
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="img/icone.png">
    <title>Gestão de Ordens de Serviços</title>
    <?php
    include("lib/header.php");
    ?>
  </head>
  <body>

    <div class="Carregando">
        <div><i class="fa-solid fa-spinner"></i></div>
    </div>

    <div class="CorpoApp"></div>

    <?php
    include("lib/footer.php");
    ?>

    <script>
        $(function(){
            Carregando();
            $.ajax({
                url:"<?=$url?>",
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            });
        })
    </script>

  </body>
</html>