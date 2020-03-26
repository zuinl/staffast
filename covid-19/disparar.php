<?php

    session_start();
    require_once '../classes/class_email.php';
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_queryHelper.php';

    $conexao = new ConexaoPadrao();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT DISTINCT usu_email as email FROM tbl_usuario";
    $query = $helper->select($select, 1);

    $msg = '<h1 class="high-text">Nós queremos ajudar sua empresa e outras a vencer a COVID-19</h1>
            <h3 class="text">Use todas as funcionalidades do Staffast por 30 dias. Totalmente de graça.</h3>
            <h5 class="text">O Staffast ajuda os gestores a liderarem suas equipes e os colaboradores a se planejarem e 
            se desenvolverem, mesmo trabalhando remotamente.</h5>
            <br><a href="https://sistemastaffast.com/staffast/"><button class="button button1">Nos conheça melhor</button></a>
            <br><a href="https://sistemastaffast.com/staffast/covid-19/"><button class="button button3" style="font-size: 2em;">Pedir agora</button></a>';

    $mail = new Email();

    $mail->setEmailFrom();
    $mail->setAssunto('Sua empresa vai vencer a COVID-19');
    $mail->setMensagem($msg);

    while($f = mysqli_fetch_assoc($query)) {
        $mail->setEmailTo($f['email']);
        $mail->enviar();
    }

    echo 'E-mails enviados';

?>