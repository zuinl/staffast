<?php
session_start();
include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>COVID-19 - Ajude sua empresa</title>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="../img/logo_staffast.png" width="180">
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

        <div class="row" style="text-align: center; margin-bottom: 1.5em;">
            <div class="col-sm">
                <img src="virus.png" width="120">
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Nós queremos ajudar sua empresa a superar a COVID-19</h3>
            </div>
        </div>

        <div class="row" style="text-align: left;">
            <div class="col-sm">
                <h5 class="text">O momento atual é de <b>união</b> e <b>luta</b> contra o novo coronavírus que se espalhou por todo o mundo 
                e hoje está afetando negativamente a rotina dos brasileiros.</h5>
            </div>
        </div>

        <div class="row" style="text-align: right;">
            <div class="col-sm">
                <h5 class="text">Isso inclui a forma como as <b>empresas</b> brasileiras estão prosseguindo com suas atividades. 
                Assim como nós, muitas organizações optaram pelo <b>trabalho remoto</b> para não precisarem paralisar completamente 
                suas atividades.</h5>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center; margin-bottom: 1.5em;">
            <div class="col-sm">
                <img src="map.png" width="120">
            </div>
        </div>

        <div class="row" style="text-align: center; margin-top: 2em;">
            <div class="col-sm">
                <h3 class="text">Nos deixe te ajudar</h3>
            </div>
        </div>

        <div class="row" style="text-align: left;">
            <div class="col-sm">
                <h5 class="text">O Staffast está oferecendo para todas as empresas que se interessem o <b>uso irrestrito de nossas funcionalidades 
                por 30 dias</b>. No Staffast, você consegue gerenciar reuniões com seus colaboradores, metas na metodologia OKR, criar Planos de 
                Desenvolvimento Individual para os mesmos, enviar documentos, holerites, <i>feedbacks</i> para incentivá-los, entre diversas outras 
                ferramentas que podem ser úteis durante o período em que sua empresa estiver trabalhando usando o método remoto.</h5>
            </div>
        </div>

        <div class="row" style="text-align: center; margin-top: 2em;">
            <div class="col-sm">
                <a href="../" target="_blank"><button class="button button1">Conheça melhor nossas funcionalidades</button></a>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <h3 class="text">Solicitar uso do Staffast</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <form action="enviar.php" method="POST">
                        <input type="text" name="nome" class="all-input" placeholder="Nome do responsável" required>
                    </div>

                    <div class="col-sm">
                        <input type="email" name="email" class="all-input" placeholder="E-mail do responsável" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <input type="text" name="empresa" class="all-input" placeholder="Nome da empresa" required> 
                    </div>

                    <div class="col-sm">
                        <input type="text" name="cep" class="all-input" placeholder="CEP da empresa">
                    </div>
                </div>

                <div class="row" style="margin-top: 1.5em;">
                    <div class="col-sm">
                        <textarea name="motivo" class="all-input" placeholder="Descreva brevemente o motivo de estar aceitando nossa oferta de ajuda" required></textarea>
                    </div>
                </div>

                <div class="row" style="margin-top: 1.5em; text-align: center;">
                    <div class="col-sm">
                        <input type="submit" class="button button2" value="Enviar pedido">
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</body>