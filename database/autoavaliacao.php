<?php
include('../include/auth.php');
include('../src/functions.php');
require_once('../classes/class_autoavaliacao.php');
require_once('../classes/class_log_alteracao.php');
require_once('../classes/class_email.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_colaborador.php');
require_once('../classes/class_mensagem.php');
require_once('../classes/class_conexao_empresa.php');
require_once('../classes/class_queryHelper.php');
    
$_SESSION['msg'] = "";

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    $colaborador = $_SESSION['user']['cpf'];
    $compet_um = $_POST['compet_um'];
    $compet_um_obs = addslashes($_POST['compet_um_obs']);
    $compet_dois = $_POST['compet_dois'];
    $compet_dois_obs = addslashes($_POST['compet_dois_obs']);
    $compet_tres = $_POST['compet_tres'];
    $compet_tres_obs = addslashes($_POST['compet_tres_obs']);
    $compet_quatro = $_POST['compet_quatro'];
    $compet_quatro_obs = addslashes($_POST['compet_quatro_obs']);

    if($_SESSION['empresa']['compet_cinco'] != "") {
        $compet_cinco = $_POST['compet_cinco'];
        $compet_cinco_obs = addslashes($_POST['compet_cinco_obs']);
    } else {
        $compet_cinco = 0;
        $compet_cinco_obs = "";
    }

    if($_SESSION['empresa']['compet_seis'] != "") {
        $compet_seis = $_POST['compet_seis'];
        $compet_seis_obs = addslashes($_POST['compet_seis_obs']);
    } else {
        $compet_seis = 0;
        $compet_seis_obs = "";
    }

    if($_SESSION['empresa']['compet_sete'] != "") {
        $compet_sete = $_POST['compet_sete'];
        $compet_sete_obs = addslashes($_POST['compet_sete_obs']);
    } else {
        $compet_sete = 0;
        $compet_sete_obs = "";
    }

    if($_SESSION['empresa']['compet_oito'] != "") {
        $compet_oito = $_POST['compet_oito'];
        $compet_oito_obs = addslashes($_POST['compet_oito_obs']);
    } else {
        $compet_oito = 0;
        $compet_oito_obs = "";
    }

    if($_SESSION['empresa']['compet_nove'] != "") {
        $compet_nove = $_POST['compet_nove'];
        $compet_nove_obs = addslashes($_POST['compet_nove_obs']);
    } else {
        $compet_nove = 0;
        $compet_nove_obs = "";
    }

    if($_SESSION['empresa']['compet_dez'] != "") {
        $compet_dez = $_POST['compet_dez'];
        $compet_dez_obs = addslashes($_POST['compet_dez_obs']);
    } else {
        $compet_dez = 0;
        $compet_dez_obs = "";
    }

    if($_SESSION['empresa']['compet_onze'] != "") {
        $compet_onze = $_POST['compet_onze'];
        $compet_onze_obs = addslashes($_POST['compet_onze_obs']);
    } else {
        $compet_onze = 0;
        $compet_onze_obs = "";
    }

    if($_SESSION['empresa']['compet_doze'] != "") {
        $compet_doze = $_POST['compet_doze'];
        $compet_doze_obs = addslashes($_POST['compet_doze_obs']);
    } else {
        $compet_doze = 0;
        $compet_doze_obs = "";
    }

    if($_SESSION['empresa']['compet_treze'] != "") {
        $compet_treze = $_POST['compet_treze'];
        $compet_treze_obs = addslashes($_POST['compet_treze_obs']);
    } else {
        $compet_treze = 0;
        $compet_treze_obs = "";
    }

    if($_SESSION['empresa']['compet_quatorze'] != "") {
        $compet_quatorze = $_POST['compet_quatorze'];
        $compet_quatorze_obs = addslashes($_POST['compet_quatorze_obs']);
    } else {
        $compet_quatorze = 0;
        $compet_quatorze_obs = "";
    }

    if($_SESSION['empresa']['compet_quinze'] != "") {
        $compet_quinze = $_POST['compet_quinze'];
        $compet_quinze_obs = addslashes($_POST['compet_quinze_obs']);
    } else {
        $compet_quinze = 0;
        $compet_quinze_obs = "";
    }

    if($_SESSION['empresa']['compet_dezesseis'] != "") {
        $compet_dezesseis = $_POST['compet_dezesseis'];
        $compet_dezesseis_obs = addslashes($_POST['compet_dezesseis_obs']);
    } else {
        $compet_dezesseis = 0;
        $compet_dezesseis_obs = "";
    }

    if($_SESSION['empresa']['compet_dezessete'] != "") {
        $compet_dezessete = $_POST['compet_dezessete'];
        $compet_dezessete_obs = addslashes($_POST['compet_dezessete_obs']);
    } else {
        $compet_dezessete = 0;
        $compet_dezessete_obs = "";
    }

    if($_SESSION['empresa']['compet_dezoito'] != "") {
        $compet_dezoito = $_POST['compet_dezoito'];
        $compet_dezoito_obs = addslashes($_POST['compet_dezoito_obs']);
    } else {
        $compet_dezoito = 0;
        $compet_dezoito_obs = "";
    }

    if($_SESSION['empresa']['compet_dezenove'] != "") {
        $compet_dezenove = $_POST['compet_dezenove'];
        $compet_dezenove_obs = addslashes($_POST['compet_dezenove_obs']);
    } else {
        $compet_dezenove = 0;
        $compet_dezenove_obs = "";
    }

    if($_SESSION['empresa']['compet_vinte'] != "") {
        $compet_vinte = $_POST['compet_vinte'];
        $compet_vinte_obs = addslashes($_POST['compet_vinte_obs']);
    } else {
        $compet_vinte = 0;
        $compet_vinte_obs = "";
    }
    
    $ata_id = $_POST['ata_id'];

    $autoavaliacao = new Autoavaliacao();
    $autoavaliacao->setID($ata_id);
    $autoavaliacao->setSessaoUm($compet_um);
    $autoavaliacao->setSessaoDois($compet_dois);
    $autoavaliacao->setSessaoTres($compet_tres);
    $autoavaliacao->setSessaoQuatro($compet_quatro);
    $autoavaliacao->setSessaoCinco($compet_cinco);
    $autoavaliacao->setSessaoSeis($compet_seis);
    $autoavaliacao->setSessaoSete($compet_sete);
    $autoavaliacao->setSessaoOito($compet_oito);
    $autoavaliacao->setSessaoNove($compet_nove);
    $autoavaliacao->setSessaoDez($compet_dez);
    $autoavaliacao->setSessaoOnze($compet_onze);
    $autoavaliacao->setSessaoDoze($compet_doze);
    $autoavaliacao->setSessaoTreze($compet_treze);
    $autoavaliacao->setSessaoQuatorze($compet_quatorze);
    $autoavaliacao->setSessaoQuinze($compet_quinze);
    $autoavaliacao->setSessaoDezesseis($compet_dezesseis);
    $autoavaliacao->setSessaoDezessete($compet_dezessete);
    $autoavaliacao->setSessaoDezoito($compet_dezoito);
    $autoavaliacao->setSessaoDezenove($compet_dezenove);
    $autoavaliacao->setSessaoVinte($compet_vinte);
    $autoavaliacao->setSessaoUmObs($compet_um_obs);
    $autoavaliacao->setSessaoDoisObs($compet_dois_obs);
    $autoavaliacao->setSessaoTresObs($compet_tres_obs);
    $autoavaliacao->setSessaoQuatroObs($compet_quatro_obs);
    $autoavaliacao->setSessaoCincoObs($compet_cinco_obs);
    $autoavaliacao->setSessaoSeisObs($compet_seis_obs);
    $autoavaliacao->setSessaoSeteObs($compet_sete_obs);
    $autoavaliacao->setSessaoOitoObs($compet_oito_obs);
    $autoavaliacao->setSessaoNoveObs($compet_nove_obs);
    $autoavaliacao->setSessaoDezObs($compet_dez_obs);
    $autoavaliacao->setSessaoOnzeObs($compet_onze_obs);
    $autoavaliacao->setSessaoDozeObs($compet_doze_obs);
    $autoavaliacao->setSessaoTrezeObs($compet_treze_obs);
    $autoavaliacao->setSessaoQuatorzeObs($compet_quatorze_obs);
    $autoavaliacao->setSessaoQuinzeObs($compet_quinze_obs);
    $autoavaliacao->setSessaoDezesseisObs($compet_dezesseis_obs);
    $autoavaliacao->setSessaoDezesseteObs($compet_dezessete_obs);
    $autoavaliacao->setSessaoDezoitoObs($compet_dezoito_obs);
    $autoavaliacao->setSessaoDezenoveObs($compet_dezenove_obs);
    $autoavaliacao->setSessaoVinteObs($compet_vinte_obs);

    if($autoavaliacao->preencher($_SESSION['empresa']['database'], $ata_id)) {

        $log = new LogAlteracao();
        $log->setDescricao("Se autoavaliou");
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Sua autoavaliação foi salva com sucesso.';
        header('Location: ../empresa/painelAvaliacao.php');
        die();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao salvar sua autoavaliação.';
        header('Location: ../empresa/painelAvaliacao.php');
        die();
    }

} else if (isset($_GET['liberar'])) {

    $cpf = base64_decode($_GET['id']);
    $ata = new Autoavaliacao();
    $ata->setCpfColaborador($cpf);

    if($ata->liberarAgora($_SESSION['empresa']['database'])) {

        $log = new LogAlteracao();
        $log->setDescricao("Liberou autoavaliação para colaborador ".$cpf);
        $log->setIDUser($_SESSION['user']['usu_id']);

        $colaborador = new Colaborador();
        $colaborador->setCpf($cpf);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $usuario = $usuario->retornarUsuario();

        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Autoavaliação liberada");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Oi, '.$colaborador->getPrimeiroNome().'</h1>
                <h2 class="high-text">Uma nova autoavaliação foi liberada para você preencher em '.$empresa.'.<br>
                Acesse o Staffast e faça agora mesmo ;D</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar sistema</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $mensagem = new Mensagem();
        $mensagem->setTitulo("Nova autoavaliação liberada");
        $mensagem->setTexto("Você tem uma nova autoavaliação liberada para preenchimento. Vá em Avaliações > Fazer autoavaliação");
            $hoje = date('Y-m-d');
            $date = date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
        $helper->insert($insert);

        $_SESSION['msg'] = 'Autoavaliação liberada com sucesso';
    } else {
        $_SESSION['msg'] = 'Erro ao liberar';
    }

    header('Location: ../empresa/painelAvaliacao.php');
    die();
        
}

?>