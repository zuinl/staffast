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

    $numCompetencias = $_GET['numCompetencias'];
    $colaborador = $_GET['colaborador'];
    $compet_um = $_GET['compet_um'];
    $compet_um_obs = addslashes($_GET['compet_um_obs']);
    $compet_dois = $_GET['compet_dois'];
    $compet_dois_obs = addslashes($_GET['compet_dois_obs']);
    $compet_tres = $_GET['compet_tres'];
    $compet_tres_obs = addslashes($_GET['compet_tres_obs']);
    $compet_quatro = $_GET['compet_quatro'];
    $compet_quatro_obs = addslashes($_GET['compet_quatro_obs']);
    
    if($numCompetencias >= 5) {
        $compet_cinco = $_GET['compet_cinco'];
        $compet_cinco_obs = addslashes($_GET['compet_cinco_obs']);
    } else {
        $compet_cinco = 0;
        $compet_cinco_obs = "";
    }

    if($numCompetencias >= 6) {
        $compet_seis = $_GET['compet_seis'];
        $compet_seis_obs = addslashes($_GET['compet_seis_obs']);
    } else {
        $compet_seis = 0;
        $compet_seis_obs = "";
    }

    if($numCompetencias >= 7) {
        $compet_sete = $_GET['compet_sete'];
        $compet_sete_obs = addslashes($_GET['compet_sete_obs']);
    } else {
        $compet_sete = 0;
        $compet_sete_obs = "";
    }

    if($numCompetencias >= 8) {
        $compet_oito = $_GET['compet_oito'];
        $compet_oito_obs = addslashes($_GET['compet_oito_obs']);
    } else {
        $compet_oito = 0;
        $compet_oito_obs = "";
    }

    if($numCompetencias >= 9) {
        $compet_nove = $_GET['compet_nove'];
        $compet_nove_obs = addslashes($_GET['compet_nove_obs']);
    } else {
        $compet_nove = 0;
        $compet_nove_obs = "";
    }

    if($numCompetencias >= 10) {
        $compet_dez = $_GET['compet_dez'];
        $compet_dez_obs = addslashes($_GET['compet_dez_obs']);
    } else {
        $compet_dez = 0;
        $compet_dez_obs = "";
    }

    if($numCompetencias >= 11) {
        $compet_onze = $_GET['compet_onze'];
        $compet_onze_obs = addslashes($_GET['compet_onze_obs']);
    } else {
        $compet_onze = 0;
        $compet_onze_obs = "";
    }

    if($numCompetencias >= 12) {
        $compet_doze = $_GET['compet_doze'];
        $compet_doze_obs = addslashes($_GET['compet_doze_obs']);
    } else {
        $compet_doze = 0;
        $compet_doze_obs = "";
    }

    if($numCompetencias >= 13) {
        $compet_treze = $_GET['compet_treze'];
        $compet_treze_obs = addslashes($_GET['compet_treze_obs']);
    } else {
        $compet_treze = 0;
        $compet_treze_obs = "";
    }

    if($numCompetencias >= 14) {
        $compet_quatorze = $_GET['compet_quatorze'];
        $compet_quatorze_obs = addslashes($_GET['compet_quatorze_obs']);
    } else {
        $compet_quatorze = 0;
        $compet_quatorze_obs = "";
    }

    if($numCompetencias >= 15) {
        $compet_quinze = $_GET['compet_quinze'];
        $compet_quinze_obs = addslashes($_GET['compet_quinze_obs']);
    } else {
        $compet_quinze = 0;
        $compet_quinze_obs = "";
    }

    if($numCompetencias >= 16) {
        $compet_dezesseis = $_GET['compet_dezesseis'];
        $compet_dezesseis_obs = addslashes($_GET['compet_dezesseis_obs']);
    } else {
        $compet_dezesseis = 0;
        $compet_dezesseis_obs = "";
    }

    if($numCompetencias >= 17) {
        $compet_dezessete = $_GET['compet_dezessete'];
        $compet_dezessete_obs = addslashes($_GET['compet_dezessete_obs']);
    } else {
        $compet_dezessete = 0;
        $compet_dezessete_obs = "";
    }

    if($numCompetencias >= 18) {
        $compet_dezoito = $_GET['compet_dezoito'];
        $compet_dezoito_obs = addslashes($_GET['compet_dezoito_obs']);
    } else {
        $compet_dezoito = 0;
        $compet_dezoito_obs = "";
    }

    if($numCompetencias >= 19) {
        $compet_dezenove = $_GET['compet_dezenove'];
        $compet_dezenove_obs = addslashes($_GET['compet_dezenove_obs']);
    } else {
        $compet_dezenove = 0;
        $compet_dezenove_obs = "";
    }

    if($numCompetencias >= 20) {
        $compet_vinte = $_GET['compet_vinte'];
        $compet_vinte_obs = addslashes($_GET['compet_vinte_obs']);
    } else {
        $compet_vinte = 0;
        $compet_vinte_obs = "";
    }

    $modelo_id = (int)$_GET['modelo'];

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
    $avaliacao->setModeloID($modelo_id);

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

        $_SESSION['msg'] = 'Avaliação cadastrada com sucesso!';
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