<?php
    session_start();
    include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Candidatar-se - Staffast</title>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="../img/logo_staffast.png" width="200">
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h1 class="high-text">Bem-vindo, candidato(a)</h1>
        </div>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            </div>
		</div>
        <?php
    }
    ?>
</div>
<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <form action="candidatar.php" method="POST">
            <input type="text" class="all-input all-input-lg" name="codigo" id="codigo" placeholder="Insira o código fornecido pela empresa" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <input type="submit" class="button button1" value="Candidatar-me">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <h3 class="high-text">O que estou fazendo nesta página?</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-sm">
            <h6 class="text">Fique tranquilo(a), a empresa que você vai se candidatar é parceira do 
            Staffast. O Staffast é uma plataforma de avaliação de colaboradores e candidaturas online. Seus dados 
            estarão seguros e acessíveis apenas pelos responsáveis pelo processo seletivo na empresa.</h6>
            <h5 class="destaque-text">Boa sorte :D</h5>
        </div>
    </div>