<?php

    require_once '../../classes/class_ponto.php';

    $email = $_GET['email'];

    $ponto = new Ponto();

    $funcionario = $ponto->identificarFuncionario($email);

    if($funcionario['nome'] != "") {
        echo 'Funcionário: <b>'.$funcionario['nome'].'</b> - Empresa: '.$funcionario['empresa'];
        
        //Checando se funcionário está autorizado a registrar ponto no site
        $isAutorizado = $ponto->isAutorizadoSite($funcionario['database'], $funcionario['cpf']);

        if(!$isAutorizado) echo '<br>Ops... parece que você não está autorizado a registrar ponto usando o 
        site do Staffast. Por favor, use o aplicativo ou entre em contato com o RH da sua empresa.';
    } else {
        echo 'Ainda não identificado';
    }

?>