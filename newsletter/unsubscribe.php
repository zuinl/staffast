<?php

    session_start();
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_queryHelper.php';
    require_once '../src/meta.php';

    $conexao = new ConexaoPadrao();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $email = $_GET['email'];

    $delete = "DELETE FROM tbl_newsletter_assinantes WHERE email = '$email'";
    $helper->delete($delete);

?>
<html>
    <head>
        <title>Newsletter - Staffast</title>
    </head>
    <body>
        <div class="container" style="text-align: center;">
            <div class="row">
                <div class="col-sm">
                    <a href="../index.php"><img src="../img/logo_staffast.png" width="200"></a>
                </div>
            </div>

            <hr class="hr-divide">

            <div class="row">
                <div class="col-sm">
                    <p class="text">Você não vai mais receber e-mails de novidades do Staffast no <?php echo $email; ?></p>
                </div>
            </div>
        </div>
    </body>
</html>