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

                if(plano == 'Ponto') {
                    if(numero >= 1 && numero <= 5) { //19,90
                        preco = numero * 19.90;
                    } 
                    if(numero >= 6 && numero <= 15) { //16,90
                        preco = numero * 16.90;
                    } 
                    if(numero >= 16 && numero <= 30) { //13,90
                        preco = numero * 13.90;
                    } 
                    if(numero >= 31 && numero <= 50) { //11,90
                        preco = numero * 11.90;
                    } 
                    if(numero > 50) { //9,90
                        preco = numero * 9.90;
                    } 
                } else if(plano == 'Avaliação') {
                    if(numero >= 1 && numero <= 5) { //24,90
                        preco = numero * 24.90;
                    } 
                    if(numero >= 6 && numero <= 15) { //22,90
                        preco = numero * 22.90;
                    } 
                    if(numero >= 16 && numero <= 30) { //19,90
                        preco = numero * 19.90;
                    } 
                    if(numero >= 31 && numero <= 50) { //17,90
                        preco = numero * 17.90;
                    } 
                    if(numero > 50) { //15,90
                        preco = numero * 15.90;
                    } 
                } else if(plano == 'Revolução') {
                    if(numero >= 1 && numero <= 5) { //27,90
                        preco = numero * 27.90;
                    } 
                    if(numero >= 6 && numero <= 15) { //25,90
                        preco = numero * 25.90;
                    } 
                    if(numero >= 16 && numero <= 30) { //23,90
                        preco = numero * 23.90;
                    } 
                    if(numero >= 31 && numero <= 50) { //21,90
                        preco = numero * 21.90;
                    } 
                    if(numero > 50) { //19,90
                        preco = numero * 19.90;
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
            <th>Staffast Ponto</th>
            <th>Staffast Avaliação</th>
            <th>Staffast Revolução</th>
        </tr>
        <tr>
            <td><img src="empresa/img/team-1.png" width="90"><h6 class="text">Cadastros (gestores, colaboradores e setores)</h6></td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/clock.png" width="90"><h6 class="text">Registro de Ponto</h6>
                <img src="empresa/img/google-play.png" width="15"> 
                    <span style="font-size: 0.7em;"> Disponível no app </span>
                <img src="empresa/img/app.png" width="15">
            </td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: blue;">OPCIONAL</td>
            <td style="color: blue;">OPCIONAL</td>
        </tr>
        <tr>
            <td><img src="empresa/img/feedback.png" width="90"><h6 class="text"><i>Feedback</i></h6>
                <img src="empresa/img/google-play.png" width="15"> 
                    <span style="font-size: 0.7em;"> Em breve no app </span>
                <img src="empresa/img/app.png" width="15">
            </td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/checklist.png" width="90"><h6 class="text">Avaliações (4 tipos)</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/file.png" width="90"><h6 class="text">Documentos</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/goal.png" width="90"><h6 class="text">Metas OKR</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/round-table.png" width="90"><h6 class="text">Reuniões</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/calendar.png" width="90"><h6 class="text">Eventos</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/interview.png" width="90"><h6 class="text">Processos Seletivos</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
        <tr>
            <td><img src="empresa/img/pdi.png" width="90"><h6 class="text">Planos de Desenvolvimento Individual</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: red;">NÃO INCLUSO</td>
            <td style="color: green;">INCLUSO</td>
        </tr>
    </table>

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
            <td><b>Staffast Ponto</b></td>
            <td>R$ 19,90 </td>
            <td>R$ 16,90 </td>
            <td>R$ 13,90 </td>
            <td>R$ 11,90 </td>
            <td>R$ 9,90 </td>
        </tr>
        <tr>
            <td><b>Staffast Avaliação</b></td>
            <td>R$ 24,90 </td>
            <td>R$ 22,90 </td>
            <td>R$ 19,90 </td>
            <td>R$ 17,90 </td>
            <td>R$ 15,90 </td>
        </tr>
        <tr>
            <td><b>Staffast Revolução</b></td>
            <td>R$ 27,90 </td>
            <td>R$ 25,90 </td>
            <td>R$ 23,90 </td>
            <td>R$ 21,90 </td>
            <td>R$ 19,90 </td>
        </tr>
    </table>

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