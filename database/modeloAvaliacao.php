<?php

include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_modelo_avaliacao.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

$modelo = new ModeloAvaliacao();

if (isset($_GET['novo'])) {

    $modelo->setCpfCriador($_SESSION['user']['cpf']);
    $modelo->setTitulo(addslashes($_POST['titulo']));
    $modelo->setUm(addslashes($_POST['um']));
    $modelo->setDois(addslashes($_POST['dois']));
    $modelo->setTres(addslashes($_POST['tres']));
    $modelo->setQuatro(addslashes($_POST['quatro']));
    $modelo->setCinco(addslashes($_POST['cinco']));
    $modelo->setSeis(addslashes($_POST['seis']));
    $modelo->setSete(addslashes($_POST['sete']));
    $modelo->setOito(addslashes($_POST['oito']));
    $modelo->setNove(addslashes($_POST['nove']));
    $modelo->setDez(addslashes($_POST['dez']));
    $modelo->setOnze(addslashes($_POST['onze']));
    $modelo->setDoze(addslashes($_POST['doze']));
    $modelo->setTreze(addslashes($_POST['treze']));
    $modelo->setQuatorze(addslashes($_POST['quatorze']));
    $modelo->setQuinze(addslashes($_POST['quinze']));
    $modelo->setDezesseis(addslashes($_POST['dezesseis']));
    $modelo->setDezessete(addslashes($_POST['dezessete']));
    $modelo->setDezoito(addslashes($_POST['dezoito']));
    $modelo->setDezenove(addslashes($_POST['dezenove']));
    $modelo->setVinte(addslashes($_POST['vinte']));

    if($modelo->cadastrar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Modelo cadastrado com sucesso';

        $log = new LogAlteracao();
        $log->setDescricao("Criou o modelo de avaliação ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao cadastrar o modelo';
    }

    header('Location: ../empresa/novoModeloAvaliacao.php');
    die();
} else if (isset($_GET['ativar'])) {

    $id = $_GET['id'];
    $modelo->setID($id);
    if($modelo->ativar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Modelo de avaliação ativado';

        $log = new LogAlteracao();
        $log->setDescricao("Ativou o modelo de avaliação ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao ativar seu modelo de avaliação';
    }

    header('Location: ../empresa/verModelosAvaliacao.php');
    die();
} else if (isset($_GET['desativar'])) {

    $id = $_GET['id'];
    $modelo->setID($id);
    if($modelo->desativar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Modelo de avaliação desativado';

        $log = new LogAlteracao();
        $log->setDescricao("Desativou o modelo de avaliação ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao desativar seu modelo de avaliação';
    }

    header('Location: ../empresa/verModelosAvaliacao.php');
    die();
} else if (isset($_GET['editar'])) {

    $modelo->setID($_POST['id']);
    $modelo->setTitulo(addslashes($_POST['titulo']));
    $modelo->setUm(addslashes($_POST['um']));
    $modelo->setDois(addslashes($_POST['dois']));
    $modelo->setTres(addslashes($_POST['tres']));
    $modelo->setQuatro(addslashes($_POST['quatro']));
    $modelo->setCinco(addslashes($_POST['cinco']));
    $modelo->setSeis(addslashes($_POST['seis']));
    $modelo->setSete(addslashes($_POST['sete']));
    $modelo->setOito(addslashes($_POST['oito']));
    $modelo->setNove(addslashes($_POST['nove']));
    $modelo->setDez(addslashes($_POST['dez']));
    $modelo->setOnze(addslashes($_POST['onze']));
    $modelo->setDoze(addslashes($_POST['doze']));
    $modelo->setTreze(addslashes($_POST['treze']));
    $modelo->setQuatorze(addslashes($_POST['quatorze']));
    $modelo->setQuinze(addslashes($_POST['quinze']));
    $modelo->setDezesseis(addslashes($_POST['dezesseis']));
    $modelo->setDezessete(addslashes($_POST['dezessete']));
    $modelo->setDezoito(addslashes($_POST['dezoito']));
    $modelo->setDezenove(addslashes($_POST['dezenove']));
    $modelo->setVinte(addslashes($_POST['vinte']));

    if($modelo->atualizar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Modelo atualizado com sucesso';

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou o modelo de avaliação ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao atualizar o modelo';
    }

    header('Location: ../empresa/verModelosAvaliacao.php');
    die();
} else if (isset($_GET['atribuirColaboradores'])) {

    $id = $_POST['id'];

    $modelo->setID($id);

    foreach($_POST['colaboradores'] as $c) {
        $modelo->atribuirColaborador($_SESSION['empresa']['database'], $c);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Atribuiu colaboradores ao modelo de avaliação ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores atribuídos com sucesso';
    header('Location: ../empresa/verModeloAvaliacao.php?id='.$id);
} else if (isset($_GET['retirarColaboradores'])) {

    $id = $_POST['id'];

    $modelo->setID($id);

    foreach($_POST['colaboradores'] as $c) {
        $modelo->retirarColaborador($_SESSION['empresa']['database'], $c);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Retirou colaboradores ao modelo de avaliação ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores retirados com sucesso';
    header('Location: ../empresa/verModeloAvaliacao.php?id='.$id);
}

?>