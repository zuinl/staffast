<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");
require_once("../classes/class_mensagem.php");

if($_SESSION['user']['permissao'] != "GESTOR-1") {
    include('../include/acessoNegado.php');
    die();
}

$conexao = new ConexaoPadrao();
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

$conexao_emp = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn_emp = $conexao_emp->conecta();

$helper_emp = new QueryHelper($conn_emp);

if(isset($_GET['gerar'])) {

    $codigo = rand(000000, 999999);

    $select = "SELECT cod_string FROM tbl_codigo_avaliacao_empresa WHERE cod_string = '$codigo'";

    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) > 0) {
        while($num != 0) {
            $codigo = rand(100000, 999999);

            $select = "SELECT cod_string FROM tbl_codigo_avaliacao_empresa WHERE cod_string = '$codigo'";

            $query = $helper->select($select, 1);

            $num = mysqli_num_rows($query);
        }
    }
    
    $data = $_POST['validade']." 23:59:59";
    $insert = "INSERT INTO tbl_codigo_avaliacao_empresa (cod_string, cod_validade, emp_id) VALUES 
    ('$codigo', '$data', ".$_SESSION['empresa']['emp_id'].")";

    $helper->insert($insert);

    $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo código de avaliação de gestão");
        $mensagem->setTexto("Foi gerado um código para avaliação da gestão: ".$codigo."<br>Para avaliar sua empresa, acesse através de sistemastaffast.com/avaliacao-empresa/ e insira o código ;)");
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

    $select = "SELECT col_cpf FROM tbl_colaborador";
    $query = $helper_emp->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
        $cpf_msg = $f['col_cpf'];
        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf_msg')";
        $helper_emp->insert($insert);
    }

    $log = new LogAlteracao();
    $log->setDescricao("Gerou código de avaliação de gestão ".$codigo);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();


    $email = new Email();
    $email->setAssunto("Nova avaliação da gestão");

    $msg = '<h1 class="high-text">Nova avaliação de gestão</h1>
            <h2 class="high-text">Olá! Há uma nova avaliação de gestão liberada.</h2>
            <h3 class="text">Para avaliar sua empresa, basta usar o código <b>'.$codigo.'</b>.</h3>
            <a href="https://sistemastaffast.com/staffast/avaliacao-empresa/" target="blank_"><button class="button button3">Avaliar agora</button></a>
            <h2 class="destaque-text">Por agora é só :D</h2>
            <h5 class="text">Equipe do Staffast</h5>';
    $email->setMensagem($msg);
    $email->dispararTodos($_SESSION['empresa']['emp_id']);


    $_SESSION['msg'] = "O código <b>".$codigo."</b> foi gerado";

    header('Location: ../empresa/avaliacaoGestao.php');
    die();

} else if (isset($_GET['invalidar'])) {

    $codigo = $_GET['codigo'];

    $update = "UPDATE tbl_codigo_avaliacao_empresa SET cod_validade = NOW() WHERE 
    cod_string = '$codigo'";
    echo $update;

    $helper->update($update);

    $log = new LogAlteracao();
    $log->setDescricao("Invalidou código de avaliação de gestão ".$codigo);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = "O código ".$codigo." foi <b>invalidado</b>";

    header('Location: ../empresa/avaliacaoGestao.php');
    die();

}

?>