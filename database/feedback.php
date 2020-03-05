<?php
include('../include/auth.php');
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_feedback.php");
require_once("../classes/class_mensagem.php");
require_once("../classes/class_usuario.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

if(isset($_GET['novo'])) {

    $texto = addslashes($_POST['feedback']);
    $dono = $_POST['dono'];

    $feedback = new Feedback();
    $feedback->setTexto($texto);
    $feedback->setFee_cpf($dono);

    if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == 'GESTOR-2') {
        $feedback->setGes_cpf($_SESSION['user']['cpf']);
    } else {
        $feedback->setCol_cpf($_SESSION['user']['cpf']);
    }

    $feedback->cadastrar($_SESSION['empresa']['database']);
    $fee_id = $feedback->retornarUltimo($_SESSION['empresa']['database']);
    $feedback->setID($fee_id);
    $feedback = $feedback->retornarFeedback($_SESSION['empresa']['database']);

    $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo feedback pra você!");
        $mensagem->setTexto("Oi! Você recebeu um novo feedback de ".$feedback->getRemetente().": ".$feedback->getTexto());
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("2 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

    $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$dono')";
    $helper->insert($insert);

    $select = "SELECT usu_id FROM tbl_gestor WHERE ges_cpf = '$dono'";
    $query = $helper->select($select, 1);
    if(mysqli_num_rows($query) > 0) {
        $f = mysqli_fetch_assoc($query);
        $usu_id = $f['usu_id'];
    } else {
        $select = "SELECT usu_id FROM tbl_colaborador WHERE col_cpf = '$dono'";
        $f = $helper->select($select, 2);
        $usu_id = $f['usu_id'];
    }
        $usuario = new Usuario();
        $usuario->setID($usu_id);
        $usuario = $usuario->retornarUsuario();
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Novo feedback para você");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Um novo feedback foi enviado para você</h1>
                <h2 class="high-text">Olá! Um feedback foi direcionado a você em '.$empresa.'.</h2>
                <h4 class="high-text">'.$feedback->getRemetente().': '.$feedback->getTexto().'</h4>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $log = new LogAlteracao();
        $log->setDescricao("Enviou um feedback para ".$dono);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Feedback criado com sucesso";
    header("Location: ../empresa/novoFeedback.php");
    die();

}


?>