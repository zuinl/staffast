<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_mensagem.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    $titulo = addslashes($_POST['titulo']);
    $texto = addslashes($_POST['texto']);
    $data_expiracao = $_POST['data'];
    var_dump($data_expiracao);
    $colaboradores = $_POST['colaboradores'];
    $gestores = $_POST['gestores'];
    $setores = $_POST['setores'];

    $mensagem = new Mensagem();
    $mensagem->setTitulo($titulo);
    $mensagem->setTexto($texto);
    $mensagem->setDataExpiracao($data_expiracao.' 23:59:59');
    $mensagem->setCpf($_SESSION['user']['cpf']);

    $mensagem->cadastrar($_SESSION['empresa']['database']);

    $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

    if($_POST['todosCols'] == "1") {
        $select = "SELECT col_cpf FROM tbl_colaborador";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }
    } else {
        foreach ($colaboradores as $c) {
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$c')";
            $helper->insert($insert);
        }
    }


    if($_POST['todosGes'] == "1") {
        $select = "SELECT ges_cpf FROM tbl_gestor";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['ges_cpf'];
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }
    } else {
        foreach ($gestores as $g) {
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$g')";
            $helper->insert($insert);
        }
    }


    if($_POST['todosSet'] == "1") {
        $select = "SELECT col_cpf, ges_cpf FROM tbl_setor_funcionario";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $fetch['col_cpf'] == '00000000000' ? $cpf = $fetch['ges_cpf'] : $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }
    } else {
        foreach ($setores as $s) {
            $select = "SELECT col_cpf, ges_cpf FROM tbl_setor_funcionario WHERE set_id = ".$s;
            $query = $helper->select($select, 1);

            while($fetch = mysqli_fetch_assoc($query)) {
                $fetch['col_cpf'] == '00000000000' ? $cpf = $fetch['ges_cpf'] : $cpf = $fetch['col_cpf'];
                $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
                $helper->insert($insert);
            }
        }
    }

    $_SESSION['msg'] = "Mensagem criada com sucesso";
    header("Location: ../empresa/novaMensagem.php");
    die();

}


?>