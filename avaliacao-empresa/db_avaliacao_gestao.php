<?php
require_once("../classes/class_avaliacao_gestao.php");
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_queryHelper.php");
include("../src/meta.php");

if(!isset($_POST)) die("Erro");

$conexao = new ConexaoPadrao();
$conn = $conexao->conecta();
$helper = new QueryHelper($conn);

if(isset($_GET['salvar'])) {


    $setor = $_POST['setor'];
    $gestor = $_POST['gestor'];
    $usu_id = $_POST['usu_id'];
    $codigo = $_POST['codigo'];

    $compet_um = $_POST['compet_um'];
    $compet_um_obs = addslashes($_POST['compet_um_obs']);
    $compet_dois = $_POST['compet_dois'];
    $compet_dois_obs = addslashes($_POST['compet_dois_obs']);
    $compet_tres = $_POST['compet_tres'];
    $compet_tres_obs = addslashes($_POST['compet_tres_obs']);
    $compet_quatro = $_POST['compet_quatro'];
    $compet_quatro_obs = addslashes($_POST['compet_quatro_obs']);

    if(isset($_POST['compet_cinco'])) {
        $compet_cinco = $_POST['compet_cinco'];
        $compet_cinco_obs = addslashes($_POST['compet_cinco_obs']);
    } else {
        $compet_cinco = 0;
        $compet_cinco_obs = "";
    }

    if(isset($_POST['compet_seis'])) {
        $compet_seis = $_POST['compet_seis'];
        $compet_seis_obs = addslashes($_POST['compet_seis_obs']);
    } else {
        $compet_seis = 0;
        $compet_seis_obs = "";
    }

    if(isset($_POST['compet_sete'])) {
        $compet_sete = $_POST['compet_sete'];
        $compet_sete_obs = addslashes($_POST['compet_sete_obs']);
    } else {
        $compet_sete = 0;
        $compet_sete_obs = "";
    }

    if(isset($_POST['compet_oito'])) {
        $compet_oito = $_POST['compet_oito'];
        $compet_oito_obs = addslashes($_POST['compet_oito_obs']);
    } else {
        $compet_oito = 0;
        $compet_oito_obs = "";
    }

    if(isset($_POST['compet_nove'])) {
        $compet_nove = $_POST['compet_nove'];
        $compet_nove_obs = addslashes($_POST['compet_nove_obs']);
    } else {
        $compet_nove = 0;
        $compet_nove_obs = "";
    }

    if(isset($_POST['compet_dez'])) {
        $compet_dez = $_POST['compet_dez'];
        $compet_dez_obs = addslashes($_POST['compet_dez_obs']);
    } else {
        $compet_dez = 0;
        $compet_dez_obs = "";
    }

    $avaliacao = new AvaliacaoGestao();
    $avaliacao->setCpfGestor($gestor);
    $avaliacao->setSetorID($setor);
    $avaliacao->setUserID($usu_id);
    $avaliacao->setSessaoUm($compet_um);
    $avaliacao->setSessaoDois($compet_dois);
    $avaliacao->setSessaoTres($compet_tres);
    $avaliacao->setSessaoQuatro($compet_quatro);
    $avaliacao->setSessaoCinco($compet_cinco);
    $avaliacao->setSessaoSeis($compet_seis);
    $avaliacao->setSessaoSete($compet_sete);
    $avaliacao->setSessaoOito($compet_oito);
    $avaliacao->setSessaoNove($compet_nove);
    $avaliacao->setSessaoDez($compet_dez);
    $avaliacao->setSessaoUmObs($compet_um_obs);
    $avaliacao->setSessaoDoisObs($compet_dois_obs);
    $avaliacao->setSessaoTresObs($compet_tres_obs);
    $avaliacao->setSessaoQuatroObs($compet_quatro_obs);
    $avaliacao->setSessaoCincoObs($compet_cinco_obs);
    $avaliacao->setSessaoSeisObs($compet_seis_obs);
    $avaliacao->setSessaoSeteObs($compet_sete_obs);
    $avaliacao->setSessaoOitoObs($compet_oito_obs);
    $avaliacao->setSessaoNoveObs($compet_nove_obs);
    $avaliacao->setSessaoDezObs($compet_dez_obs);
    $avaliacao->setCodigo($codigo);

    if($avaliacao->salvar($_POST['database'])) {

        $update = "UPDATE tbl_codigo_avaliacao_empresa SET cod_usado = cod_usado + 1 
        WHERE cod_string = '$codigo'";

        $helper->update($update);

        echo '<div class="container">
                <h1 class="high-text">Perfeito! A empresa agradece sua avaliação :D</h1>
                <a href="../"><button class="button button1">Voltar</button></a>
                <a href="index.php"><button class="button button3">Avaliar de novo</button></a>
            </div>';
    } else {
        echo '<div class="container">
                <h1 class="high-text">Ooops... parece que houve um erro :(</h1>
                <a href="../index.php"><button class="button button1">Voltar</button></a>
            </div>';
    }

}

?>