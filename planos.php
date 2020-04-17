<?php
session_start();
include('src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Planos - Staffast</title>
    <script>
            function simular() {
                var plano = document.getElementById('plano').value;
                var numero = document.getElementById('numero').value;
                var preco = 0.00;

                if(plano == 'Documento') {
                    if(numero >= 1 && numero <= 5) {
                        preco = numero * 6.90;
                    } 
                    if(numero >= 6 && numero <= 15) {
                        preco = numero * 6.49;
                    } 
                    if(numero >= 16 && numero <= 30) {
                        preco = numero * 5.99;
                    } 
                    if(numero >= 31 && numero <= 50) {
                        preco = numero * 5.59;
                    } 
                    if(numero > 50) {
                        preco = numero * 4.49;
                    } 
                } else if(plano == 'Ponto') {
                    if(numero >= 1 && numero <= 5) {
                        preco = numero * 17.90;
                    } 
                    if(numero >= 6 && numero <= 15) {
                        preco = numero * 14.90;
                    } 
                    if(numero >= 16 && numero <= 30) {
                        preco = numero * 10.90;
                    } 
                    if(numero >= 31 && numero <= 50) {
                        preco = numero * 8.90;
                    } 
                    if(numero > 50) {
                        preco = numero * 5.90;
                    } 
                } else if(plano == 'Avaliação') {
                    if(numero >= 1 && numero <= 5) {
                        preco = numero * 19.90;
                    } 
                    if(numero >= 6 && numero <= 15) {
                        preco = numero * 16.90;
                    } 
                    if(numero >= 16 && numero <= 30) {
                        preco = numero * 12.90;
                    } 
                    if(numero >= 31 && numero <= 50) {
                        preco = numero * 10.90;
                    } 
                    if(numero > 50) {
                        preco = numero * 7.90;
                    } 
                } else if(plano == 'Revolução') {
                    if(numero >= 1 && numero <= 5) {
                        preco = numero * 23.90;
                    } 
                    if(numero >= 6 && numero <= 15) {
                        preco = numero * 19.90;
                    } 
                    if(numero >= 16 && numero <= 30) {
                        preco = numero * 15.90;
                    } 
                    if(numero >= 31 && numero <= 50) {
                        preco = numero * 12.90;
                    } 
                    if(numero > 50) {
                        preco = numero * 9.90;
                    } 
                }

                document.getElementById('preco').innerHTML = "R$ "+preco.toFixed(2)+"/mês";
            }
        </script>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="img/logo_staffast.png" width="180">

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">

            <li class="nav-item" id="nav-2">
                <a class="nav-link" href="index.php">Revolucione sua empresa</a>
            </li>

            <li class="nav-item" id="nav-3">
                <a class="nav-link" href="#solucoes">Fale conosco</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="login.php" style="color: #13a378;"><b>ENTRAR NO STAFFAST</b></a>
            </li>
        </ul>
    </div>
</nav>

<body>

    <div class="container-fluid">
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Escolha a melhor forma de se revolucionar</h3>
            </div>
        </div>
    </div>

    <div class="container">

    <table class="table-site">
        <tr>
            <th>Módulos</th>
            <th>Staffast Documento</th>
            <th>Staffast Ponto</th>
            <th>Staffast Avaliação</th>
            <th>Staffast Revolução</th>
        </tr>
        <tr>
            <th><img src="empresa/img/team-1.png" width="90"><h6 class="text">Cadastros (gestores, colaboradores e setores)</h6></th>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/file.png" width="90"><h6 class="text">Documentos</h6>
                <img src="empresa/img/google-play.png" width="15"> 
                    <span style="font-size: 0.7em;"> Em breve no app </span>
                <img src="empresa/img/app.png" width="15">
            </th>
            <td style="color: green;">INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/clock.png" width="90"><h6 class="text">Registro de Ponto</h6>
                <img src="empresa/img/google-play.png" width="15"> 
                    <span style="font-size: 0.7em;"> Disponível no app </span>
                <img src="empresa/img/app.png" width="15">
            </th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/feedback.png" width="90"><h6 class="text"><i>Feedback</i></h6>
                <img src="empresa/img/google-play.png" width="15"> 
                    <span style="font-size: 0.7em;"> Em breve no app </span>
                <img src="empresa/img/app.png" width="15">
            </th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/checklist.png" width="90"><h6 class="text">Avaliações (4 tipos)</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/goal.png" width="90"><h6 class="text">Metas OKR</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/round-table.png" width="90"><h6 class="text">Reuniões</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/calendar.png" width="90"><h6 class="text">Eventos</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/interview.png" width="90"><h6 class="text">Processos Seletivos</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <th><img src="empresa/img/pdi.png" width="90"><h6 class="text">Planos de Desenvolvimento Individual</th>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
    </table>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="suporte/" target="_blank"><button class="button button2">Entre em contato e solicite um teste</button></a>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="container-fluid">
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Não custa nada comparado aos avanços que vai te proporcionar</h3>
                <h5 class="text">Os preços são referentes ao valor mensal por funcionário</h5>
            </div>
        </div>
    </div>

    <table class="table-site">
        <tr>
            <th>Plano</th>
            <th>De 1 a 5 funcionários</th>
            <th>De 6 a 15 funcionários</th>
            <th>De 16 a 30 funcionários</th>
            <th>De 31 a 50 funcionários</th>
            <th>Acima de 50 funcionários</th>
        </tr>
        <tr>
            <th><b>Staffast Documento</b></th>
            <td>R$ 6,90 </td>
            <td>R$ 6,49 </td>
            <td>R$ 5,99 </td>
            <td>R$ 5,59 </td>
            <td>R$ 4,49 </td>
        </tr>
        <tr>
            <th><b>Staffast Ponto</b></th>
            <td>R$ 17,90 </td>
            <td>R$ 14,90 </td>
            <td>R$ 10,90 </td>
            <td>R$ 8,90 </td>
            <td>R$ 5,90 </td>
        </tr>
        <tr>
            <th><b>Staffast Avaliação</b></th>
            <td>R$ 19,90 </td>
            <td>R$ 16,90 </td>
            <td>R$ 12,90 </td>
            <td>R$ 10,90 </td>
            <td>R$ 7,90 </td>
        </tr>
        <tr>
            <th><b>Staffast Revolução</b></th>
            <td>R$ 23,90 </td>
            <td>R$ 19,90 </td>
            <td>R$ 15,90 </td>
            <td>R$ 12,90 </td>
            <td>R$ 9,90 </td>
        </tr>
    </table>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="suporte/" target="_blank"><button class="button button2">Entre em contato e solicite um teste</button></a>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="container-fluid">
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Quer uma simulação?</h3>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <select id="plano" class="all-input">
                    <option value="" disabled selected>Selecione um plano</option>
                    <option value="Revolução">Staffast Revolução</option>
                    <option value="Avaliação">Staffast Avaliação</option>
                    <option value="Ponto">Staffast Ponto</option>
                    <option value="Documento">Staffast Documento</option>
                </select>
            </div>
            <div class="col-sm">
                <input type="number" id="numero" class="all-input">
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <input type="button" class="button button1" value="Simular" onclick="simular();">
            </div>
        </div>

        <div class="row" style="text-align: center; margin-top: 2em;">
            <div class="col-sm">
                <h5 class="text">O Staffast custará: <span id="preco" style="font-size: 2em;"></span></h5>
            </div>
        </div>
    </div>

    </div>
</body>