<?php
include('../include/auth.php');
include('../src/functions.php');
include('../src/meta.php');
require_once('../classes/class_avaliacao_setor.php');
require_once('../classes/class_email.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_gestor.php');
require_once('../classes/class_log_alteracao.php');
require_once('../classes/class_mensagem.php');
require_once('../classes/class_conexao_empresa.php');
require_once('../classes/class_queryHelper.php');
    
$_SESSION['msg'] = "";

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    $setor = $_POST['setor'];
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

    $avaliacao = new AvaliacaoSetor();
    $avaliacao->setCpf($_SESSION['user']['cpf']);
    $avaliacao->setSessaoUm($compet_um);
    $avaliacao->setSessaoDois($compet_dois);
    $avaliacao->setSessaoTres($compet_tres);
    $avaliacao->setSessaoQuatro($compet_quatro);
    $avaliacao->setSessaoCinco($compet_cinco);
    $avaliacao->setSessaoSeis($compet_seis);
    $avaliacao->setSessaoUmObs($compet_um_obs);
    $avaliacao->setSessaoDoisObs($compet_dois_obs);
    $avaliacao->setSessaoTresObs($compet_tres_obs);
    $avaliacao->setSessaoQuatroObs($compet_quatro_obs);
    $avaliacao->setSessaoCincoObs($compet_cinco_obs);
    $avaliacao->setSessaoSeisObs($compet_seis_obs);
    $avaliacao->setIDSetor($setor);

    if($avaliacao->salvar($_SESSION['empresa']['database'])) {

        $select = "SELECT DISTINCT ges_cpf as cpf FROM tbl_setor_funcionario WHERE set_id = '$setor'";
        $query = $helper->select($select, 1);
        while($f = mysqli_fetch_assoc($query)) {
            $gestor = new Gestor();
            $gestor->setCpf($f['cpf']);
            $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

            $usuario = new Usuario();
            $usuario->setID($gestor->getIDUser());
            $usuario = $usuario->retornarUsuario();

            $email = new Email();
            $email->setAssunto("Nova avaliação do setor");
            $email->setEmailTo($usuario->getEmail());
            $email->setEmailFrom(0);
            $empresa = $_SESSION['empresa']['nome'];
            $msg = '<h1 class="high-text">Nova avaliação do seu setor</h1>
                <h2 class="high-text">Olá, '.$gestor->getPrimeiroNome().'! Um colaborador acabou de avaliar um setor que você é gestor em '.$empresa.'.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar o Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
            $email->setMensagem($msg);
            $email->enviar();

            $mensagem = new Mensagem();
            $mensagem->setTitulo("Nova avaliação do setor");
            $mensagem->setTexto(addslashes("Um dos setores que você é gestor foi avaliado. Cheque em Avaliações > Avaliação de setores e filtro pelo seu setor para encontrar os resultados ;)"));
                $hoje = date('Y-m-d');
                $date = date_create($hoje);
                date_add($date,date_interval_create_from_date_string("7 days"));
            $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
            $mensagem->setCpf($_SESSION['user']['cpf']);

            $mensagem->cadastrar($_SESSION['empresa']['database']);

            $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);
            $cpf = $gestor->getCpf();
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }

        $log = new LogAlteracao();
        $log->setDescricao("Avaliou setor ".$setor);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Avaliação cadastrada com sucesso';
    } else {
        $_SESSION['msg'] = 'Algo errado aconteceu ao cadastrar avaliação';
    }

    header('Location: ../empresa/avaliacaoSetor.php');
    die();

}

?>