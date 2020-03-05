<?php
    include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Avalie sua empresa</title>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="../img/logo_staffast.png" width="200">
        </div>
    </div>
        <div class="col-sm">
            <h1 class="high-text">Avalie sua empresa</h1>
        </div>
    </div>

    <hr class="hr-divide">

</div>
<div class="container" style="text-align: center;">

    <div class="row">
        <div class="col-sm">
            <form action="carregaAvaliacao.php" method="POST">
            <label class="text">Insira o código de avaliação</label>
            <input type="text" class="all-input" name="codigo" id="codigo" maxlength="6" required placeholder="Aquele que sua empresa forneceu">
        </div>
        <div class="col-sm">
            <label class="text">Insira o e-mail que você usa no Staffast</label>
            <input type="email" class="all-input" name="email" id="email" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm" style="margin-top: 1em;">
            <input type="submit" id="btnSubmit" class="button button1" value="Avaliar">
            </form>
        </div>
    </div>

    <hr class="hr-divide"> 

    <div class="row">
        <div class="col-sm">
            <h2 class="destaque-text">Esta avaliação é <span class="high-text"><i>anônima</i></span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h4 class="text">Sua empresa saberá a data que foi realizada, mas nunca saberá quem a realizou. Contudo, mantenha-se profissional e objetivo em seus comentários e notas, lembre-se de que a importância dessas avaliações é construir um ambiente de trabalho melhor para todos. 
            <br>Sinta-se livre!</h4>
        </div>
    </div>
    <div class="row" style="text-align: left;">
        <div class="col-sm">
            <p class="text">ATENÇÃO: a empresa não será capaz de identificar quem realizou a avaliação, porém a equipe do Staffast reserva esta informação para necessidades legais e/ou extremas. 
            A informação será utilizada e/ou fornecida para a empresa em casos de:</p>
            <ul class="text">
                <li>Avaliações com teor violento para com a empresa;</li>
                <li>Avaliações com teor violento para um ou mais gestores e/ou colaboradores;</li>
                <li>Avaliações que contenham denúncias ou conteúdo de casos de violência, racismo, machismo, homofobia ou qualquer outra discriminação de qualquer natureza;</li>
                <li>Avaliações que contenham denúncias ou conteúdo de casos de corrupção, roubo ou qualquer outra infração grave;</li>
                <li>Avaliações que indiquem risco psicológico da pessoa que está avaliando (por exemplo: sinais de infelicidade e/ou insatisfação pessoal extrema, sinais de risco de suicídio, qualquer outros sinais de risco a si mesmo ou aos outros ao seu redor);</li>
                <li>Solicitação judicial.</li>
            </ul>
        </div>
    </div>

    
</div>
</body>
</html>