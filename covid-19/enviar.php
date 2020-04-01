<?php

    session_start();
    require_once '../classes/class_email.php';

    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $empresa = addslashes($_POST['empresa']);
    $cep = $_POST['cep'];
    $motivo = addslashes($_POST['motivo']);

    $msg = "<h1>NOVO PEDIDO - COVID-19</h1>
            <h2>EMPRESA: ".$empresa."</h2>
            <h2>NOME: ".$nome."</h2>
            <h2>E-MAIL: ".$email."</h2>
            <h2>CEP: ".$cep."</h2>
            <h2>MOTIVO: ".$motivo."</h2>";

    $mail = new Email();
    $mail->setEmailFrom();
    $mail->setEmailTo("leonardosoareszuin@gmail.com");
    $mail->setAssunto("NOVO PEDIDO COVID-19");
    $mail->setMensagem($msg);
    $mail->enviar();
    unset($mail);

    $mail = new Email();
    $mail->setEmailFrom();
    $mail->setEmailTo("contato@sistemastaffast.com");
    $mail->setAssunto("NOVO PEDIDO COVID-19");
    $mail->setMensagem($msg);
    $mail->enviar();
    unset($mail);

    $mail = new Email();
    $mail->setEmailFrom();
    $mail->setEmailTo($email);
    $mail->setAssunto("Seu pedido do Staffast");
    $mail->setMensagem('Nós recebemos o pedido de uso por 30 dias do Staffast para a empresa '.$empresa.' 
    e nossa equipe já está trabalhando para ativar a utilização. Por favor aguarde novas instruções nas próximas horas. 
    Se você quiser nos perguntar algo ou tirar dúvidas, entre em contato com o <a href="https://sistemastaffast.com/staffast/suporte/">
    suporte</a>.');
    $mail->enviar();
    unset($mail);

    $_SESSION['msg'] = 'Nós enviamos um e-mail para '.$email.' com a informação sobre os próximos passos.';
    header('Location: ./');
    die();

?>