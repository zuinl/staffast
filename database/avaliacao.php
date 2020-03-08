<?php
include('../include/auth.php');
include('../src/functions.php');
include('../src/meta.php');
require_once('../classes/class_avaliacao.php');
require_once('../classes/class_email.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_colaborador.php');
require_once('../classes/class_log_alteracao.php');
require_once('../classes/class_mensagem.php');
require_once('../classes/class_conexao_empresa.php');
require_once('../classes/class_queryHelper.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include('../include/acessoNegado.php');
        die();
    }
    
$_SESSION['msg'] = "";

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    var_dump($_POST); die();

    $colaborador = $_POST['colaborador'];
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

    $avaliacao = new Avaliacao();
    $avaliacao->setCpfColaborador($colaborador);
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
    $avaliacao->setSessaoOnze($compet_onze);
    $avaliacao->setSessaoDoze($compet_doze);
    $avaliacao->setSessaoTreze($compet_treze);
    $avaliacao->setSessaoQuatorze($compet_quatorze);
    $avaliacao->setSessaoQuinze($compet_quinze);
    $avaliacao->setSessaoDezesseis($compet_dezesseis);
    $avaliacao->setSessaoDezessete($compet_dezessete);
    $avaliacao->setSessaoDezoito($compet_dezoito);
    $avaliacao->setSessaoDezenove($compet_dezenove);
    $avaliacao->setSessaoVinte($compet_vinte);
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
    $avaliacao->setSessaoOnzeObs($compet_onze_obs);
    $avaliacao->setSessaoDozeObs($compet_doze_obs);
    $avaliacao->setSessaoTrezeObs($compet_treze_obs);
    $avaliacao->setSessaoQuatorzeObs($compet_quatorze_obs);
    $avaliacao->setSessaoQuinzeObs($compet_quinze_obs);
    $avaliacao->setSessaoDezesseisObs($compet_dezesseis_obs);
    $avaliacao->setSessaoDezesseteObs($compet_dezessete_obs);
    $avaliacao->setSessaoDezoitoObs($compet_dezoito_obs);
    $avaliacao->setSessaoDezenoveObs($compet_dezenove_obs);
    $avaliacao->setSessaoVinteObs($compet_vinte_obs);
    $avaliacao->setCpfGestor($_SESSION['user']['cpf']);

    if($avaliacao->cadastrar($_SESSION['empresa']['database'])) {

        $cpf_colaborador = $colaborador;
        $colaborador = new Colaborador();
        $colaborador->setCpf($cpf_colaborador);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $usuario = $usuario->retornarUsuario();

        $email = new Email();
        $email->setAssunto("Nova avaliação");
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $empresa = $_SESSION['empresa']['nome'];
        $gestor = $_SESSION['user']['primeiro_nome'];
        $msg = '<h1 class="high-text">Nova avaliação no Staffast</h1>
            <h2 class="high-text">Olá, '.$colaborador->getPrimeiroNome().'! '.$gestor.' de '.$empresa.' acabou de criar uma avaliação para você no Staffast.</h2>
            <h3 class="text">Nao se preocupe, nós vamos te avisar quando ela estiver disponível para você visualizar.</h3>

            <h2 class="destaque-text">Por agora é só :D</h2>
            <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $mensagem = new Mensagem();
        $mensagem->setTitulo("Nova avaliação");
        $mensagem->setTexto($gestor." realizou uma avaliação para você. Ela ainda não está liberada para visualização, você será notificado quando isso acontecer.");
            $hoje = date('Y-m-d');
            $date = date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf_colaborador')";
        $helper->insert($insert);

        $log = new LogAlteracao();
        $log->setDescricao("Avaliou colaborador ".$colaborador->getNomeCompleto());
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Avaliação cadastrada com sucesso';
    } else {
        $_SESSION['msg'] = 'Algo errado aconteceu ao cadastrar avaliação';
    }

    header('Location: ../empresa/novaAvaliacao.php');
    die();

} else if (isset($_GET['liberar'])) {

    $cpf = base64_decode($_GET['id']);

    $ava = new Avaliacao();
    $ava->setCpfColaborador($cpf);

    if($ava->liberarAgora($_SESSION['empresa']['database'])) {

        $colaborador = new Colaborador();
        $colaborador->setCpf($cpf);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $usuario = $usuario->retornarUsuario();

        $email = new Email();
        $email->setAssunto("Avaliação liberada");
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $empresa = $_SESSION['empresa']['nome'];
        $gestor = $_SESSION['user']['primeiro_nome'];
        $msg = '<h1 class="high-text">Avaliação liberada :D</h1>
            <h2 class="high-text">Olá, '.$colaborador->getPrimeiroNome().'! '.$gestor.' de '.$empresa.' acabou de liberar a visualização das suas avaliações no Staffast.<br>
            Corre ver!</h2>
            <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
            <h2 class="destaque-text">Por agora é só :D</h2>
            <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $mensagem = new Mensagem();
        $mensagem->setTitulo("Nova liberação de avaliação");
        $mensagem->setTexto($gestor." liberou a visualização das suas avaliações para você. Vá em Avaliação > Painel de controle, encontre seu nome e clique em Visualizar Resultados ;)");
            $hoje = date('Y-m-d');
            $date = date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
        $helper->insert($insert);

        $log = new LogAlteracao();
        $log->setDescricao("Liberou visualização de avaliações para ".$cpf);
        $log->setIDUser($_SESSION['user']['usu_id']);

        $_SESSION['msg'] = 'Avaliações liberadas com sucesso';
    } else {
        $_SESSION['msg'] = 'Algo errado aconteceu ao liberar avaliações';
    }

    header('Location: ../empresa/painelAvaliacao.php');
    die();

}

?>