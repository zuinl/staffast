<?php
session_start();
include('src/meta.php');
if(isset($_SESSION['login']) && $_SESSION['login'] == 1) header('Location: empresa/home.php');

if(isset($_COOKIE['staffast_login_email'])) header('Location: database/login.php?login=true&email='.$_COOKIE['staffast_login_email']);

$action = '';
if(isset($_GET['historicoPonto'])) $action = 'historicoPonto=true';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bem-vindo ao Staffast</title>
</head>
<body style="margin-top: 0em;">
	<div class="container">
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <img src="img/logo_staffast.png" width="300">
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <form action="database/login.php?login=true&<?php echo $action; ?>" method="POST">
                <h5 class="text">E-mail</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 offset-sm-4" style="text-align: center;">
                <input type="email" class="all-input" name="email" id="email">
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-sm" style="text-align: center;">
                <h5 class="text">Senha</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 offset-sm-4" style="text-align: center;">
                <input type="password" class="all-input" name="senha" id="senha">
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-sm-4 offset-sm-4" style="text-align: center;">
                <input type="submit" class="button button1" value="Entrar">
                </form>
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-sm-4 offset-sm-4" style="text-align: center;">
                <a href="recuperarSenha.php">Esqueceu sua senha?</a>
            </div>
        </div>

        <?php
        if(isset($_SESSION['msg'])) {
            ?>
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
            <?php
        }
        ?>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="suporte/"><button class="button button2">Entre em contato conosco</button></a>
            </div>
            <div class="col-sm">
                <a href="avaliacao-empresa/"><button class="button button2">Avalie sua empresa usando um código</button></a>
            </div>
            <div class="col-sm">
                <a href="processos-seletivos/"><button class="button button2">Candidate-se a um processo seletivo</button></a>
            </div>
            <!-- <div class="col-sm">
                <a href="contratarStaffast.php"><button class="button button2">Contratar o Staffast</button></a>
            </div> -->
        </div>

    </div>
    
</html>