<?php
session_start();
include('src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Contratar o Staffast</title>
</head>
<body style="margin-top: 0em;">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <img src="img/logo_staffast.png" width="430">
            </div>
        </div>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h1 class="high-text">Contratar o Staffast</h1>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title">Staffast MEI</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Empresas MEI com 1 funcionário e 1 gestor</h6>
                        <h5 class="card-text"><b>R$ 29,90 / mês </b></h5>
                        <a href="suporte/index.php?trazer_empresa=true" class="card-link">Vamos conversar!</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title">Staffast Júnior</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Empresas que tenham de 3 a 10 funcionários</h6>
                        <h5 class="card-text"><b>R$ 13,90 / funcionário</b></h5><h6 class="card-text">(ou R$129,90 / mês para ter os 10 disponíveis)</h6>
                        <a href="suporte/index.php?trazer_empresa=true" class="card-link">Vamos conversar!</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title">Staffast Pleno</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Empresas que tenham de 11 a 30 funcionários</h6>
                        <h5 class="card-text"><b>R$ 9,90 / funcionário</b></h5><h6 class="card-text">(ou R$ 289,90 / mês para ter os 30 disponíveis)</h6>
                        <a href="suporte/index.php?trazer_empresa=true" class="card-link">Vamos conversar!</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title">Staffast Sênior</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Empresas que tenham de 31 a 60 funcionários</h6>
                        <h5 class="card-text"><b>R$ 8,90 / funcionário</b></h5><h6 class="card-text">(ou R$ 459,90 / mês para ter os 30 disponíveis)</h6>
                        <a href="suporte/index.php?trazer_empresa=true" class="card-link">Vamos conversar!</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title">Staffast Master</h4>
                        <h6 class="card-subtitle mb-2 text-muted">Empresas que tenham de 61 a 150 funcionários</h6>
                        <h5 class="card-text"><b>R$ 7,90 / funcionário</b></h5><h6 class="card-text">(ou R$ 899,90 / mês para ter cadastros ilimitados)</h6>
                        <a href="suporte/index.php?trazer_empresa=true" class="card-link">Vamos conversar!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</html>