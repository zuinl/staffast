<?php

    require_once '../../classes/class_ponto.php';

    $email = $_GET['email'];

    $ponto = new Ponto();

    $funcionario = $ponto->identificarFuncionario($email);

    if($funcionario['nome'] != "") {
        echo 'Funcionário: <b>'.$funcionario['nome'].'</b> - Empresa: '.$funcionario['empresa'];
    } else {
        echo 'Ainda não identificado';
    }

?>