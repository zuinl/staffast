<?php
    require_once '../src/meta.php';
?>
<html>
<head>
    <title>Blog do Staffast</title>
</head>
<body style="margin-bottom: 4em; margin-top: 1em;">
<?php require_once 'bars.php'; ?>

<div class="container">
    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../">Staffast</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog do Staffast</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div id="artigo" style="margin-bottom: 5em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text"><a href="artigo.php">Título da matéria</a></h1>
                <h6 class="text">17/03/2020 às 18:00</h6>

                <hr class="hr-divide-super-light">
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <img src="/staffast/empresa/img/logos/logo_adsumus.png" width="300">
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h6 class="text">Primeiros 150 ou 200 caracteres do artigo...</h6>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="#"><button class="button button1">Ler o resto</button></a>
            </div>
        </div>
    </div>




    <div id="artigo" style="margin-bottom: 5em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text"><a href="#">Título da matéria</a></h1>
                <h6 class="text">17/03/2020 18:00</h6>

                <hr class="hr-divide-super-light">
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <img src="/staffast/empresa/img/google-play.png" width="300">
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h6 class="text">Primeiros 150 ou 200 caracteres do artigo...</h6>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="#"><button class="button button1">Ler o resto</button></a>
            </div>
        </div>
    </div>
    

</div>
</body>
</html>