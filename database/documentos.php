<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");
require_once("../classes/class_usuario.php");
require_once("../classes/class_documento.php");
require_once("../classes/class_mensagem.php");

if($_SESSION['user']['permissao'] != "GESTOR-1") {
    include('../include/acessoNegado.php');
    die();
}

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

$conexao_p = new ConexaoPadrao();
$conn_p = $conexao_p->conecta();

$helper_p = new QueryHelper($conn_p);

if(isset($_GET['novo'])) {

    $titulo = addslashes($_POST['titulo']);
    $tipo = addslashes($_POST['tipo']);
    $donos = $_POST['donos'];

    if($tipo == "Holerite" && sizeof($donos) > 1) {
        $_SESSION['msg'] = "Holerites só podem ter um dono";
        header('Location: ../empresa/novoDocumento.php');
        die();
    }

    $documento = $_FILES['documento'];

    if($_FILES["documento"]["error"] == 0){
        $arqNome = "";
        $nome_dir = "../empresa/documentos/".date('Y-m-d')."/";
        $documento = $_FILES['documento'];
        $diretorio = $nome_dir;

            if(!file_exists($diretorio)){
                mkdir($diretorio);
            }
        $arqNome = $diretorio.$documento['name'];
        move_uploaded_file($documento['tmp_name'], $arqNome);
        $diretorio = date('Y-m-d')."/".$documento['name'];
    } else {
        $_SESSION['msg'] = "Houve um erro no upload do documento";
        header('Location: ../empresa/novoDocumento.php');
        die();
    }

    $documento = new Documento();
    $documento->setTitulo($titulo);
    $documento->setTipo($tipo);
    $documento->setCaminhoArquivo($diretorio);
    $documento->setCpfGestor($_SESSION['user']['cpf']);
    $documento->cadastrar($_SESSION['empresa']['database']);

    $doc_id = $documento->retornarUltimo($_SESSION['empresa']['database']);

    $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo documento para você");
        $mensagem->setTexto("Um novo documento foi enviado e direcionado a você, cheque na página de Documentos, clicando em Mais > Documentos ou no seu perfil, clicando em Mais > Minha Conta e encontre seu perfil no fim da página ;)");
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

    foreach ($donos as $d) {
        $insert = "INSERT INTO tbl_documento_dono (doc_id, cpf) VALUES ('$doc_id', '$d')";
        $helper->insert($insert);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$d')";
        $helper->insert($insert);   
        
        $select = "SELECT usu_id FROM tbl_gestor WHERE ges_cpf = '$d'";
        $query = $helper->select($select, 1);
        if(mysqli_num_rows($query) > 0) {
            $f = mysqli_fetch_assoc($query);
            $usu_id = $f['usu_id'];
        } else {
            $select = "SELECT usu_id FROM tbl_colaborador WHERE col_cpf = '$d'";
            $f = $helper->select($select, 2);
            $usu_id = $f['usu_id'];
        }
        $usuario = new Usuario();
        $usuario->setID($usu_id);
        $usuario = $usuario->retornarUsuario();
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Novo documento para você");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Um novo documento foi direcionado para você</h1>
                <h2 class="high-text">Olá! Um documento foi direcionado a você em '.$empresa.'.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }


    $log = new LogAlteracao();
    $log->setDescricao("Realizou o upload do documento ID ".$doc_id);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();


    $_SESSION['msg'] = "Upload realizado com sucesso";

    header('Location: ../empresa/novoDocumento.php');
    die();

}

?>