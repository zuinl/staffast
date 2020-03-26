<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
      $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
      módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
      header('Location: home.php');
      die();
  }

    if(!isset($_GET['id'])) {
        header('Location: painelAvaliacao.php');
        die();
    }

    $id = base64_decode($_GET['id']);

    if($_SESSION['user']['permissao'] == "COLABORADOR" && $id != $_SESSION['user']['cpf']) {
      include("../include/acessoNegado.php");
      die();
    }

    $gestor = new Gestor();
    $colaborador = new Colaborador();
        $colaborador->setCpf($id);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
    $avaliacao = new Avaliacao();
        $avaliacao->setCpfColaborador($id);
    $autoavaliacao = new Autoavaliacao();
        $autoavaliacao->setCpfColaborador($id);

        if($_SESSION['user']['permissao'] == "GESTOR-2") {
          if(!$avaliacao->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $id)) {
              include('../include/acessoNegado.php');
              die();
          }
      }

    //COLETANDO MÉDIAS

    $medias = $avaliacao->calcularMedias($_SESSION['empresa']['database']);

    //

    $aviso = '<ul>';

    if(!$avaliacao->isLiberada($_SESSION['empresa']['database'])) {
        $aviso .= '<li>Não há avaliação feitas por gestores que estejam liberadas para visualização</li>';
    } else {
        $aviso .= '<li>A última avaliação liberada para visualização é de '.$avaliacao->retornarUltimaComGestor($_SESSION['empresa']['database']).'</li>';
    }

    if(!$autoavaliacao->checarPreenchida($_SESSION['empresa']['database'])) {
        $aviso .= '<li>Não há autoavaliações que foram preenchidas pelo colaborador</li>';
    } else {
        $datas_recentes = $autoavaliacao->retornaUltima($_SESSION['empresa']['database']);
        $aviso .= '<li>A última autoavaliação preenchida foi em: '.$datas_recentes["preenchida"].'</li>';
    }

    $aviso .= '<li>Você só verá os resultados de avaliações feitas por gestores que já foram liberadas</li>';

    if($_SESSION['user']['permissao'] == 'GESTOR') {
       $aviso .= '<li>Você só verá autoavaliações que foram preenchidas pelo colaborador</li>';
    } else if ($_SESSION['user']['permissao'] == 'COLABORADOR' && $autoavaliacao->checarLiberada($_SESSION['empresa']['database'])) {
        $aviso .= '<li>Há uma autoavaliação liberada que você não preencheu</li>';
    }

    $aviso .= '</ul>';

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    //COLETANDO DADOS DAS ÚLTIMAS 4 AVALIAÇÕES

    $ava_um = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dois = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_tres = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quatro = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_cinco = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_seis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_sete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_oito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_nove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dez = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_onze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_doze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_treze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quatorze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quinze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezesseis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezessete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezoito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezenove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_vinte = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_datas = array("Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados");

    $ava_um_obs = "";
    $ava_dois_obs = "";
    $ava_tres_obs = "";
    $ava_quatro_obs = "";
    $ava_cinco_obs = "";
    $ava_seis_obs = "";
    $ava_sete_obs = "";
    $ava_oito_obs = "";
    $ava_nove_obs = "";
    $ava_dez_obs = "";
    $ava_onze_obs = "";
    $ava_doze_obs = "";
    $ava_treze_obs = "";
    $ava_quatorze_obs = "";
    $ava_quinze_obs = "";
    $ava_dezesseis_obs = "";
    $ava_dezessete_obs = "";
    $ava_dezoito_obs = "";
    $ava_dezenove_obs = "";
    $ava_vinte_obs = "";

    $select = "SELECT ava_sessao_um as um, ava_sessao_dois as dois, ava_sessao_tres as tres, 
    ava_sessao_quatro as quatro, ava_sessao_cinco as cinco, ava_sessao_seis as seis,
    ava_sessao_sete as sete, ava_sessao_oito as oito, ava_sessao_nove as nove,
    ava_sessao_dez as dez, ava_sessao_onze as onze, ava_sessao_doze as doze, 
    ava_sessao_treze as treze, ava_sessao_quatorze as quatorze, ava_sessao_quinze as quinze,
    ava_sessao_dezesseis as dezesseis, ava_sessao_dezessete as dezessete, 
    ava_sessao_dezoito as dezoito, ava_sessao_dezenove as dezenove, ava_sessao_vinte as vinte,
    ava_sessao_um_obs as um_obs, ava_sessao_dois_obs as dois_obs,
    ava_sessao_tres_obs as tres_obs, ava_sessao_quatro_obs as quatro_obs,
    ava_sessao_cinco_obs as cinco_obs, ava_sessao_seis_obs as seis_obs,
    ava_sessao_sete_obs as sete_obs, ava_sessao_oito_obs as oito_obs,
    ava_sessao_nove_obs as nove_obs, ava_sessao_dez_obs as dez_obs,
    ava_sessao_onze_obs as onze_obs, ava_sessao_doze_obs as doze_obs,
    ava_sessao_treze_obs as treze_obs, ava_sessao_quatorze_obs as quatorze_obs,
    ava_sessao_quinze_obs as quinze_obs, ava_sessao_dezesseis_obs as dezesseis_obs,
    ava_sessao_dezessete_obs as dezessete_obs, ava_sessao_dezoito_obs as dezoito_obs,
    ava_sessao_dezenove_obs as dezenove_obs, ava_sessao_vinte_obs as vinte_obs,
    DATE_FORMAT(ava_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao WHERE col_cpf = '$id' AND ava_data_liberacao < NOW() 
    AND ava_modelo_id = 0 ORDER BY ava_data_criacao DESC LIMIT 10";

    $query = mysqli_query($conn, $select);

    $i = 0;
    while($fetch = mysqli_fetch_assoc($query)) {
        $ava_um[$i] = $fetch['um'];
        $ava_dois[$i] = $fetch['dois'];
        $ava_tres[$i] = $fetch['tres'];
        $ava_quatro[$i] = $fetch['quatro'];
        $ava_cinco[$i] = $fetch['cinco'];
        $ava_seis[$i] = $fetch['seis'];
        $ava_sete[$i] = $fetch['sete'];
        $ava_oito[$i] = $fetch['oito'];
        $ava_nove[$i] = $fetch['nove'];
        $ava_dez[$i] = $fetch['dez'];
        $ava_onze[$i] = $fetch['onze'];
        $ava_doze[$i] = $fetch['doze'];
        $ava_treze[$i] = $fetch['treze'];
        $ava_quatorze[$i] = $fetch['quatorze'];
        $ava_quinze[$i] = $fetch['quinze'];
        $ava_dezesseis[$i] = $fetch['dezesseis'];
        $ava_dezessete[$i] = $fetch['dezessete'];
        $ava_dezoito[$i] = $fetch['dezoito'];
        $ava_dezenove[$i] = $fetch['dezenove'];
        $ava_vinte[$i] = $fetch['vinte'];
        $ava_datas[$i] = $fetch['data'];
        $i++; 

        $fetch['um_obs'] != "" ? $ava_um_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['um_obs'] : "";
        $fetch['dois_obs'] != "" ? $ava_dois_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dois_obs'] : "";
        $fetch['tres_obs'] != "" ? $ava_tres_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['tres_obs'] : "";
        $fetch['quatro_obs'] != "" ? $ava_quatro_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatro_obs'] : "";
        $fetch['cinco_obs'] != "" ? $ava_cinco_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['cinco_obs'] : "";
        $fetch['seis_obs'] != "" ? $ava_seis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['seis_obs'] : "";
        $fetch['sete_obs'] != "" ? $ava_sete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['sete_obs'] : "";
        $fetch['oito_obs'] != "" ? $ava_oito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['oito_obs'] : "";
        $fetch['nove_obs'] != "" ? $ava_nove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['nove_obs'] : "";
        $fetch['dez_obs'] != "" ? $ava_dez_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dez_obs'] : "";
        $fetch['onze_obs'] != "" ? $ava_onze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['onze_obs'] : "";
        $fetch['doze_obs'] != "" ? $ava_doze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['doze_obs'] : "";
        $fetch['treze_obs'] != "" ? $ava_treze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['treze_obs'] : "";
        $fetch['quatorze_obs'] != "" ? $ava_quatorze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatorze_obs'] : "";
        $fetch['quinze_obs'] != "" ? $ava_quinze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quinze_obs'] : "";
        $fetch['dezesseis_obs'] != "" ? $ava_dezesseis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezesseis_obs'] : "";
        $fetch['dezessete_obs'] != "" ? $ava_dezessete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezessete_obs'] : "";
        $fetch['dezoito_obs'] != "" ? $ava_dezoito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezoito_obs'] : "";
        $fetch['dezenove_obs'] != "" ? $ava_dezenove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezenove_obs'] : "";
        $fetch['vinte_obs'] != "" ? $ava_vinte_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['vinte_obs'] : "";
    }

    if($ava_um_obs == "") $ava_um_obs = "<br>Nada encontrado.";
    if($ava_dois_obs == "") $ava_dois_obs = "<br>Nada encontrado.";
    if($ava_tres_obs == "") $ava_tres_obs = "<br>Nada encontrado.";
    if($ava_quatro_obs == "") $ava_quatro_obs = "<br>Nada encontrado.";
    if($ava_cinco_obs == "") $ava_cinco_obs = "<br>Nada encontrado.";
    if($ava_seis_obs == "") $ava_seis_obs = "<br>Nada encontrado.";
    if($ava_sete_obs == "") $ava_sete_obs = "<br>Nada encontrado.";
    if($ava_oito_obs == "") $ava_oito_obs = "<br>Nada encontrado.";
    if($ava_nove_obs == "") $ava_nove_obs = "<br>Nada encontrado.";
    if($ava_dez_obs == "") $ava_dez_obs = "<br>Nada encontrado.";
    if($ava_onze_obs == "") $ava_onze_obs = "<br>Nada encontrado.";
    if($ava_doze_obs == "") $ava_doze_obs = "<br>Nada encontrado.";
    if($ava_treze_obs == "") $ava_treze_obs = "<br>Nada encontrado.";
    if($ava_quatorze_obs == "") $ava_quatorze_obs = "<br>Nada encontrado.";
    if($ava_quinze_obs == "") $ava_quinze_obs = "<br>Nada encontrado.";
    if($ava_dezesseis_obs == "") $ava_dezesseis_obs = "<br>Nada encontrado.";
    if($ava_dezessete_obs == "") $ava_dezessete_obs = "<br>Nada encontrado.";
    if($ava_dezoito_obs == "") $ava_dezoito_obs = "<br>Nada encontrado.";
    if($ava_dezenove_obs == "") $ava_dezenove_obs = "<br>Nada encontrado.";
    if($ava_vinte_obs == "") $ava_vinte_obs = "<br>Nada encontrado.";

    //

    //COLETANDO DADOS DAS ÚLTIMAS 4 AUTOAVALIAÇÕES

    $ata_um = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dois = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_tres = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_quatro = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_cinco = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_seis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_sete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_oito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_nove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dez = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_onze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_doze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_treze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_quatorze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_quinze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dezesseis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dezessete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dezoito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_dezenove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_vinte = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ata_datas = array("Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados");

    $ata_um_obs = "";
    $ata_dois_obs = "";
    $ata_tres_obs = "";
    $ata_quatro_obs = "";
    $ata_cinco_obs = "";
    $ata_seis_obs = "";
    $ata_sete_obs = "";
    $ata_oito_obs = "";
    $ata_nove_obs = "";
    $ata_dez_obs = "";
    $ata_onze_obs = "";
    $ata_doze_obs = "";
    $ata_treze_obs = "";
    $ata_quatorze_obs = "";
    $ata_quinze_obs = "";
    $ata_dezesseis_obs = "";
    $ata_dezessete_obs = "";
    $ata_dezoito_obs = "";
    $ata_dezenove_obs = "";
    $ata_vinte_obs = "";

    $select = "SELECT ata_sessao_um as um, ata_sessao_dois as dois, ata_sessao_tres as tres, 
    ata_sessao_quatro as quatro, ata_sessao_cinco as cinco, ata_sessao_seis as seis,
    ata_sessao_sete as sete, ata_sessao_oito as oito, ata_sessao_nove as nove,
    ata_sessao_dez as dez, ata_sessao_onze as onze, ata_sessao_doze as doze, 
    ata_sessao_treze as treze, ata_sessao_quatorze as quatorze, ata_sessao_quinze as quinze,
    ata_sessao_dezesseis as dezesseis, ata_sessao_dezessete as dezessete, 
    ata_sessao_dezoito as dezoito, ata_sessao_dezenove as dezenove, ata_sessao_vinte as vinte,
    ata_sessao_um_obs as um_obs, ata_sessao_dois_obs as dois_obs,
    ata_sessao_tres_obs as tres_obs, ata_sessao_quatro_obs as quatro_obs,
    ata_sessao_cinco_obs as cinco_obs, ata_sessao_seis_obs as seis_obs,
    ata_sessao_sete_obs as sete_obs, ata_sessao_oito_obs as oito_obs,
    ata_sessao_nove_obs as nove_obs, ata_sessao_dez_obs as dez_obs,
    ata_sessao_onze_obs as onze_obs, ata_sessao_doze_obs as doze_obs,
    ata_sessao_treze_obs as treze_obs, ata_sessao_quatorze_obs as quatorze_obs,
    ata_sessao_quinze_obs as quinze_obs, ata_sessao_dezesseis_obs as dezesseis_obs,
    ata_sessao_dezessete_obs as dezessete_obs, ata_sessao_dezoito_obs as dezoito_obs,
    ata_sessao_dezenove_obs as dezenove_obs, ata_sessao_vinte_obs as vinte_obs,
    DATE_FORMAT(ata_data_preenchida, '%d/%m/%Y') as data
    FROM tbl_autoavaliacao WHERE col_cpf = '$id' AND ata_preenchida = 1 
    ORDER BY ata_data_criacao DESC LIMIT 10";

    $query = mysqli_query($conn, $select);

    $i = 0;
    while($fetch = mysqli_fetch_assoc($query)) {
        $ata_um[$i] = $fetch['um'];
        $ata_dois[$i] = $fetch['dois'];
        $ata_tres[$i] = $fetch['tres'];
        $ata_quatro[$i] = $fetch['quatro'];
        $ata_cinco[$i] = $fetch['cinco'];
        $ata_seis[$i] = $fetch['seis'];
        $ata_sete[$i] = $fetch['sete'];
        $ata_oito[$i] = $fetch['oito'];
        $ata_nove[$i] = $fetch['nove'];
        $ata_dez[$i] = $fetch['dez'];
        $ata_onze[$i] = $fetch['onze'];
        $ata_doze[$i] = $fetch['doze'];
        $ata_treze[$i] = $fetch['treze'];
        $ata_quatorze[$i] = $fetch['quatorze'];
        $ata_quinze[$i] = $fetch['quinze'];
        $ata_dezesseis[$i] = $fetch['dezesseis'];
        $ata_dezessete[$i] = $fetch['dezessete'];
        $ata_dezoito[$i] = $fetch['dezoito'];
        $ata_dezenove[$i] = $fetch['dezenove'];
        $ata_vinte[$i] = $fetch['vinte'];
        $ata_datas[$i] = $fetch['data'];
        $i++;

        $fetch['um_obs'] != "" ? $ata_um_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['um_obs'] : "";
        $fetch['dois_obs'] != "" ? $ata_dois_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dois_obs'] : "";
        $fetch['tres_obs'] != "" ? $ata_tres_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['tres_obs'] : "";
        $fetch['quatro_obs'] != "" ? $ata_quatro_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatro_obs'] : "";
        $fetch['cinco_obs'] != "" ? $ata_cinco_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['cinco_obs'] : "";
        $fetch['seis_obs'] != "" ? $ata_seis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['seis_obs'] : "";
        $fetch['sete_obs'] != "" ? $ata_sete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['sete_obs'] : "";
        $fetch['oito_obs'] != "" ? $ata_oito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['oito_obs'] : "";
        $fetch['nove_obs'] != "" ? $ata_nove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['nove_obs'] : "";
        $fetch['dez_obs'] != "" ? $ata_dez_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dez_obs'] : "";
        $fetch['onze_obs'] != "" ? $ata_onze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['onze_obs'] : "";
        $fetch['doze_obs'] != "" ? $ata_doze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['doze_obs'] : "";
        $fetch['treze_obs'] != "" ? $ata_treze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['treze_obs'] : "";
        $fetch['quatorze_obs'] != "" ? $ata_quatorze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatorze_obs'] : "";
        $fetch['quinze_obs'] != "" ? $ata_quinze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quinze_obs'] : "";
        $fetch['dezesseis_obs'] != "" ? $ata_dezesseis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezesseis_obs'] : "";
        $fetch['dezessete_obs'] != "" ? $ata_dezessete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezessete_obs'] : "";
        $fetch['dezoito_obs'] != "" ? $ata_dezoito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezoito_obs'] : "";
        $fetch['dezenove_obs'] != "" ? $ata_dezenove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezenove_obs'] : "";
        $fetch['vinte_obs'] != "" ? $ata_vinte_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['vinte_obs'] : "";
    }

    if($ata_um_obs == "") $ata_um_obs = "<br>Nada encontrado.";
    if($ata_dois_obs == "") $ata_dois_obs = "<br>Nada encontrado.";
    if($ata_tres_obs == "") $ata_tres_obs = "<br>Nada encontrado.";
    if($ata_quatro_obs == "") $ata_quatro_obs = "<br>Nada encontrado.";
    if($ata_cinco_obs == "") $ata_cinco_obs = "<br>Nada encontrado.";
    if($ata_seis_obs == "") $ata_seis_obs = "<br>Nada encontrado.";
    if($ata_sete_obs == "") $ata_sete_obs = "<br>Nada encontrado.";
    if($ata_oito_obs == "") $ata_oito_obs = "<br>Nada encontrado.";
    if($ata_nove_obs == "") $ata_nove_obs = "<br>Nada encontrado.";
    if($ata_dez_obs == "") $ata_dez_obs = "<br>Nada encontrado.";
    if($ata_onze_obs == "") $ata_onze_obs = "<br>Nada encontrado.";
    if($ata_doze_obs == "") $ata_doze_obs = "<br>Nada encontrado.";
    if($ata_treze_obs == "") $ata_treze_obs = "<br>Nada encontrado.";
    if($ata_quatorze_obs == "") $ata_quatorze_obs = "<br>Nada encontrado.";
    if($ata_quinze_obs == "") $ata_quinze_obs = "<br>Nada encontrado.";
    if($ata_dezesseis_obs == "") $ata_dezesseis_obs = "<br>Nada encontrado.";
    if($ata_dezessete_obs == "") $ata_dezessete_obs = "<br>Nada encontrado.";
    if($ata_dezoito_obs == "") $ata_dezoito_obs = "<br>Nada encontrado.";
    if($ata_dezenove_obs == "") $ata_dezenove_obs = "<br>Nada encontrado.";
    if($ata_vinte_obs == "") $ata_vinte_obs = "<br>Nada encontrado.";

    //

?>
<!DOCTYPE html>
<html>
<head>
    <title>Resultados de avaliações</title>
    <script>
        function verAvaliacao(id_col) {
          var id = document.getElementById("avaliacao").value;

          if(id == 0 || id == "0") {
            alert("Selecione uma avaliação para visualizar");
            return true;
          }

          window.location.href = "verAvaliacao.php?id="+id+"&col="+id_col;
        }
        function verAutoavaliacao(id_col) {
          var id = document.getElementById("autoavaliacao").value;

          if(id == 0 || id == "0") {
            alert("Selecione uma autoavaliação para visualizar");
            return true;
          }

          window.location.href = "verAutoavaliacao.php?id="+id+"&col="+id_col;
        }
        function verAvaliacaoModelo(id_col) {
          var id = document.getElementById("avaliacao_modelo").value;

          if(id == 0 || id == "0") {
            alert("Selecione uma avaliação com modelo para visualizar");
            return true;
          }

          window.location.href = "verAvaliacaoModelo.php?id="+id+"&col="+id_col;
        }
        function verResultadoModelo(id_col) {
          var id = document.getElementById("resultado_modelo").value;

          if(id == 0 || id == "0") {
            alert("Selecione um modelo para visualizar resultados");
            return true;
          }

          window.location.href = "resultadosModelo.php?id="+id+"&col="+id_col;
        }
        function verAvaAta(id_col) {
          var id_ava = document.getElementById("avaliacao").value;
          var id_ata = document.getElementById("autoavaliacao").value;

          if(id_ava == 0 || id_ava == "0" || id_ata == 0 || id_ata == "0") {
            alert("Selecione uma autoavaliação e uma avaliação para visualizar");
            return true;
          }

          window.location.href = "verAva_Ata.php?id_ava="+id_ava+"&id_ata="+id_ata+"&col="+id_col;
        }
      </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_um']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_um[$a]; ?>,      <?php echo $ata_um[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_um']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dois']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dois[$a]; ?>,      <?php echo $ata_dois[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dois']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart1'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_tres']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_tres[$a]; ?>,      <?php echo $ata_tres[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_tres']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart2'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_quatro']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quatro[$a]; ?>,      <?php echo $ata_quatro[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_quatro']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart3'));

        chart.draw(data, options);
      }
    </script>

    <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_cinco']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_cinco[$a]; ?>,      <?php echo $ata_cinco[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_cinco']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart4'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_seis']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_seis[$a]; ?>,      <?php echo $ata_seis[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_seis']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart5'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_sete']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_sete[$a]; ?>,      <?php echo $ata_sete[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_sete']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart6'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_oito']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_oito[$a]; ?>,      <?php echo $ata_oito[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_oito']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart7'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_nove']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_nove[$a]; ?>,      <?php echo $ata_nove[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_nove']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart8'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dez']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dez[$a]; ?>,      <?php echo $ata_dez[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dez']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart9'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_onze']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_onze[$a]; ?>,      <?php echo $ata_onze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_onze']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart10'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_doze']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_doze[$a]; ?>,      <?php echo $ata_doze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_doze']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart11'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_treze']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_treze[$a]; ?>,      <?php echo $ata_treze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_treze']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart12'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_quatorze']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quatorze[$a]; ?>,      <?php echo $ata_quatorze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_quatorze']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart13'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_quinze']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quinze[$a]; ?>,      <?php echo $ata_quinze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_quinze']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart14'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezesseis[$a]; ?>,      <?php echo $ata_dezesseis[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dezesseis']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart15'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dezessete']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezessete[$a]; ?>,      <?php echo $ata_dezessete[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dezessete']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart16'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dezoito']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezoito[$a]; ?>,      <?php echo $ata_dezoito[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dezoito']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart17'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_dezenove']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezenove[$a]; ?>,      <?php echo $ata_dezenove[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_dezenove']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart18'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['compet_vinte']; ?>', 'Gestor', 'Colaborador'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_vinte[$a]; ?>,      <?php echo $ata_vinte[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION['empresa']['compet_vinte']; ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart19'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item"><a href="painelAvaliacao.php">Painel de Avaliações</a></li>
            <li class="breadcrumb-item active" aria-current="page">Relatório de resultados de <?php echo $colaborador->getNomeCompleto(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->
</div> 
<div class="container">
    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h3 class="high-text">Relatório de resultados</h3>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <a href="printable/resultados.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>" target="_blank"><button class="button button3">Imprimir este relatório</button></a>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <small class="text">Caso <?php echo $colaborador->getPrimeiroNome(); ?> tenha sido avaliado(a) usando algum modelo de avaliação personalizado, os resultados não estão embutidos neste relatório.</small>
      </div>
    </div>

    <hr class="hr-divide">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h5 class="text">Informações do colaborador</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <br class="text"><b>Colaborador</b>
            <br class="text"><a href="perfilColaborador.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>" target="blank_"><?php echo $colaborador->getNomeCompleto(); ?></a>
        </div> 
        <div class="col-sm">
            <br class="text"><b>Avaliações liberadas</b>
            <br class="text"><?php echo $avaliacao->quantidadeAvaliacoesLiberadas($_SESSION['empresa']['database']); ?>
        </div>  
        <div class="col-sm">
            <br class="text"><b>Autoavaliações preenchidas</b>
            <br class="text"><?php echo $autoavaliacao->quantidadeAutoavaliacoesPreenchidas($_SESSION['empresa']['database']); ?>
        </div>    
    </div>
    <hr class="hr-divide-super-light">
    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h5 class="text">Médias de todo o período (avaliações)</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <?php echo $_SESSION['empresa']['compet_um']; ?>: <?php echo number_format($medias[1], 1, ',', ''); ?>
        <br><?php echo $_SESSION['empresa']['compet_tres']; ?>: <?php echo number_format($medias[3], 1, ',', ''); ?>
        <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_cinco']; ?>: <?php echo number_format($medias[5], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_sete'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_sete']; ?>: <?php echo number_format($medias[7], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_nove'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_nove']; ?>: <?php echo number_format($medias[9], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_onze'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_onze']; ?>: <?php echo number_format($medias[11], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_treze'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_treze']; ?>: <?php echo number_format($medias[13], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_quinze']; ?>: <?php echo number_format($medias[15], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_dezessete']; ?>: <?php echo number_format($medias[17], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_dezenove']; ?>: <?php echo number_format($medias[19], 1, ',', ''); ?> <?php } ?>
    </div>
    <div class="col-sm">
        <?php echo $_SESSION['empresa']['compet_dois']; ?>: <?php echo number_format($medias[2], 1, ',', ''); ?>
        <br><?php echo $_SESSION['empresa']['compet_quatro']; ?>: <?php echo number_format($medias[4], 1, ',', ''); ?>
        <?php if($_SESSION['empresa']['compet_seis'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_seis']; ?>: <?php echo number_format($medias[6], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_oito'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_oito']; ?>: <?php echo number_format($medias[8], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_dez'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_dez']; ?>: <?php echo number_format($medias[10], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_doze'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_doze']; ?>: <?php echo number_format($medias[12], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_quatorze']; ?>: <?php echo number_format($medias[14], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_dezesseis']; ?>: <?php echo number_format($medias[16], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_dezoito']; ?>: <?php echo number_format($medias[18], 1, ',', ''); ?> <?php } ?>
        <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?><?php echo '<br>'.$_SESSION['empresa']['compet_vinte']; ?>: <?php echo number_format($medias[20], 1, ',', ''); ?> <?php } ?>
    </div>
  </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h5 class="text">Visualizar avaliações</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <label class="text">Selecione uma avaliaçao</label>
        <select class="all-input" name="avaliacao" id="avaliacao">
            <option selected value="0">-- Selecione --</option>
            <?php
            $cpf = $colaborador->getCpf();
            $select = "SELECT t1.ava_id as id, 
                        t1.ava_visualizada as visualizada,
                        CONCAT(DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y'), ' - Avaliado por ', t2.ges_primeiro_nome) as avaliacao 
                      FROM tbl_avaliacao t1 
                        INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
                      WHERE t1.col_cpf = '$cpf' 
                        AND t1.ava_data_liberacao <= NOW() 
                        AND ava_modelo_id = 0 
                      ORDER BY t1.ava_data_criacao DESC";
            $query = $helper->select($select, 1);
            while($fetch = mysqli_fetch_assoc($query)) {
              echo '<option value="'.$fetch['id'].'">'.$fetch['avaliacao'];
              if($fetch['visualizada'] == 0) echo ' * ';
              echo '</option>';
            }
            ?>
        </select>
        <small class="text">Aqui aparecem as avaliações já liberadas</small>
        <small class="text">As avaliações que ainda não visualizadas pelo colaborador aparecem com *</small>
      </div>
      <div class="col-sm" style="margin-top: 2em;">
          <h3 class="text">&</h3>
      </div>
      <div class="col-sm">
        <label class="text">Selecione uma autoavaliaçao</label>
        <select class="all-input" name="autoavaliacao" id="autoavaliacao">
            <option selected value="0">-- Selecione --</option>
            <?php
            $cpf = $colaborador->getCpf();
            $select = "SELECT ata_id as id, 
            DATE_FORMAT(ata_data_criacao, '%d/%m/%Y') as data 
            FROM tbl_autoavaliacao WHERE col_cpf = '$cpf' AND ata_preenchida = 1 
            ORDER BY ata_data_criacao DESC";
            $query = $helper->select($select, 1);
            while($fetch = mysqli_fetch_assoc($query)) {
              echo '<option value="'.$fetch['id'].'">'.$fetch['data'].'</option>';
            }
            ?>
        </select>
        <small class="text">Aqui aparecem as autoavaliações liberadas <b>e</b> preenchidas</small>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <input type="button" id="btnAvaliacao" value="Ver" class="button button2" onclick="verAvaliacao('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
      <div class="col-sm">
        <input type="button" id="btnAvaliacaovsAutoavaliacao" value="Avaliação vs. Autoavaliação" class="button button3" onclick="verAvaAta('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
      <div class="col-sm">
        <input type="button" id="btnAutoavaliacao" value="Ver" class="button button2" onclick="verAutoavaliacao('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h5 class="text">Visualizar avaliações feitas com modelos</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <label class="text">Selecione uma avaliaçao com modelo</label>
        <select class="all-input" name="avaliacao_modelo" id="avaliacao_modelo">
            <option selected value="0">-- Selecione --</option>
            <?php
            $cpf = $colaborador->getCpf();
            $select = "SELECT t1.ava_id as id, 
                        t1.ava_visualizada as visualizada,
                        CONCAT(DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y'), ' - Avaliado por ', t2.ges_primeiro_nome) as avaliacao,
                        t3.titulo as titulo 
                      FROM tbl_avaliacao t1 
                        INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
                        INNER JOIN tbl_modelo_avaliacao t3 ON t3.id = t1.ava_modelo_id
                      WHERE t1.col_cpf = '$cpf' 
                        AND t1.ava_data_liberacao <= NOW() 
                        AND t1.ava_modelo_id != 0 
                      ORDER BY t1.ava_data_criacao DESC";
            $query = $helper->select($select, 1);
            while($fetch = mysqli_fetch_assoc($query)) {
              echo '<option value="'.$fetch['id'].'">'.$fetch['avaliacao'];
              if($fetch['visualizada'] == 0) echo ' * ';
              echo ' - Modelo: '.$fetch['titulo'].'</option>';
            }
            ?>
        </select>
        <small class="text">Aqui aparecem as avaliações já liberadas</small>
        <small class="text">As avaliações que ainda não visualizadas pelo colaborador aparecem com *</small>
      </div>
      <div class="col-sm">
        <label class="text">Selecione um modelo</label>
        <select class="all-input" name="resultado_modelo" id="resultado_modelo">
            <option selected value="0">-- Selecione --</option>
            <?php
            $cpf = $colaborador->getCpf();
            $select = "SELECT DISTINCT t1.modelo_id as id, t2.titulo as titulo FROM tbl_colaborador_modelo_avaliacao t1 
            INNER JOIN tbl_modelo_avaliacao t2 ON t2.id = t1.modelo_id WHERE t1.col_cpf = '$cpf' ORDER BY t2.titulo ASC";
            $query = $helper->select($select, 1);
            while($fetch = mysqli_fetch_assoc($query)) {
              echo '<option value="'.$fetch['id'].'">'.$fetch['titulo'].'</option>';
            }
            ?>
        </select>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <input type="button" id="btnAvaliacaoModelo" value="Ver resultados da avaliação com modelo" class="button button2" onclick="verAvaliacaoModelo('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
      <div class="col-sm">
        <input type="button" id="btnModelo" value="Ver resultados do modelo" class="button button2" onclick="verResultadoModelo('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Avisos</h4>
        </div>  
    </div>
    <div class="row" style="text-align: left;">
        <div class="col-sm">
            <div class="text"><?php echo $aviso; ?></div>
        </div>  
    </div>

    <hr class="hr-divide">

    <div class="row">
        <div class="col-sm" style="text-align: center;">
            <h3 class="text">Histórico recente de <?php echo $colaborador->getPrimeiroNome(); ?></h3>
        </div>  
    </div>

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_um']; ?></b><?php echo $ata_um_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_um']; ?></b><?php echo $ava_um_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart1" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dois']; ?></b><?php echo $ata_dois_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dois']; ?></b><?php echo $ava_dois_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart2" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_tres']; ?></b><?php echo $ata_tres_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_tres']; ?></b><?php echo $ava_tres_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart3" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quatro']; ?></b><?php echo $ata_quatro_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quatro']; ?></b><?php echo $ava_quatro_obs; ?>
      </div>
    </div>

    <?php if($_SESSION['empresa']['compet_cinco'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart4" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_cinco']; ?></b><?php echo $ata_cinco_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_cinco']; ?></b><?php echo $ava_cinco_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_seis'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart5" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_seis']; ?></b><?php echo $ata_seis_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_seis']; ?></b><?php echo $ava_seis_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_sete'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart6" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_sete']; ?></b><?php echo $ata_sete_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_sete']; ?></b><?php echo $ava_sete_obs; ?>
      </div>
    </div>
    <?php } ?>


    <?php if($_SESSION['empresa']['compet_oito'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart7" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_oito']; ?></b><?php echo $ata_oito_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_oito']; ?></b><?php echo $ava_oito_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_nove'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart8" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_nove']; ?></b><?php echo $ata_nove_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_nove']; ?></b><?php echo $ava_nove_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_dez'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart9" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dez']; ?></b><?php echo $ata_dez_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dez']; ?></b><?php echo $ava_dez_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_onze'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart10" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_onze']; ?></b><?php echo $ata_onze_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_onze']; ?></b><?php echo $ava_onze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_doze'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart11" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_doze']; ?></b><?php echo $ata_doze_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_doze']; ?></b><?php echo $ava_doze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_treze'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart12" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_treze']; ?></b><?php echo $ata_treze_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_treze']; ?></b><?php echo $ava_treze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_quatorze'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart13" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></b><?php echo $ata_quatorze_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></b><?php echo $ava_quatorze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_quinze'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart14" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quinze']; ?></b><?php echo $ata_quinze_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_quinze']; ?></b><?php echo $ava_quinze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_dezesseis'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart15" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></b><?php echo $ata_dezesseis_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></b><?php echo $ava_dezesseis_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_dezessete'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart16" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></b><?php echo $ata_dezessete_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></b><?php echo $ava_dezessete_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_dezoito'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart17" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></b><?php echo $ata_dezoito_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></b><?php echo $ava_dezoito_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_dezenove'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart18" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></b><?php echo $ata_dezenove_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></b><?php echo $ava_dezenove_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['compet_vinte'] != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart19" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">COLABORADOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_vinte']; ?></b><?php echo $ata_vinte_obs; ?>
      </div>
      <div class="col-sm">
        <h6 class="text">GESTOR</h6>
        <b class="text"><?php echo $_SESSION['empresa']['compet_vinte']; ?></b><?php echo $ava_vinte_obs; ?>
      </div>
    </div>
    <?php } ?>

</div>
</body>
</html>