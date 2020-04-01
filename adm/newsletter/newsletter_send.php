<?php
session_start();
include('../../src/meta.php');
require_once '../../classes/class_conexao_padrao.php';
require_once '../../classes/class_queryHelper.php';
require_once '../../classes/class_email.php';

if(!isset($_GET['token'])) {
    die('Sem permissão de acesso à esta página');
}

if($_GET['token'] != "367617f269506e643722f6ce486fb98d") {
    die('Sem permissão de acesso à esta página');
}

if(isset($_GET['disparar'])) {
    $assunto = $_POST['assunto'];
    $mensagem = nl2br($_POST['mensagem']);

    $mail = new Email();
    $mail->setEmailFrom();
    $mail->setAssunto($assunto);
    $mail->setMensagem($mensagem, true);
    $mail->dispararNewsletter();

    $_SESSION['msg'] = 'E-mails disparados';
    header('Location: newsletter_send.php?token=367617f269506e643722f6ce486fb98d');
    die();
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Novo e-mail para lista de newsletter</title>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="../../img/logo_staffast.png" width="180">
</nav>

<body>

    <div class="container">

        <?php
        if(isset($_SESSION['msg'])) {
            ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Novo e-mail para lista de newsletter</h3>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <form action="newsletter_send.php?token=367617f269506e643722f6ce486fb98d&disparar=true" method="POST">
                <h4 class="text">Insira o assunto</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="text" class="all-input" name="assunto" id="assunto" required>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text">Insira a mensagem</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <textarea class="all-input" name="mensagem" id="mensagem" required></textarea>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
               <input type="submit" class="button button1" value="Disparar e-mails">
               </form>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <small class="text">Adsumus Sistemas - <?php echo date('Y'); ?></small>
            </div>
        </div>
    </div>

    </div>
</body>