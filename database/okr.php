<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
//require_once("../classes/class_mensagem.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
// require_once("../classes/class_email.php");
require_once("../classes/class_okr.php");
require_once("../classes/class_key_result.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    $objetivo = addslashes($_POST['titulo']);
    $descricao = addslashes($_POST['descricao']);
    $tipo = $_POST['tipo'];
    switch($_POST['visivel']) {
        case 'Todos': $visivel = 1; break;
        case 'Apenas eu': $visivel = 2; break;
        case 'Apenas os gestores': $visivel = 3; break;
        default: $visivel = 1; break;
    }
    // switch($_POST['isGoal']) {
    //     case 1: $goal = 1;
    //     case 2: $goal = 2;
    //     default: $goal = 2;
    // }
    // if($goal = 1) {
    //     $meta = $_POST['goal'];
    //     $meta = str_replace('.', '', $meta);
    //     $meta = str_replace(',', '.', $meta);
    // } else {
    //     $meta = $_POST['goal'];
    // }
    $prazo = $_POST['prazo'].' 23:59:59';
    $colaboradores = $_POST['colaboradores'];
    $gestores = $_POST['gestores'];
    $setores = $_POST['setores'];

    $okr = new OKR();
    $okr->setTitulo($objetivo);
    $okr->setDescricao($descricao);
    $okr->setTipo($tipo);
    $okr->setVisivel($visivel);
    //if($goal == 1) {
        $okr->setGoalMoney(0);
        $okr->setGoalNumber(0);
    // } else {
    //     $okr->setGoalMoney(0);
    //     $okr->setGoalNumber(0);
    // }
    $okr->setPrazo($prazo);
    $okr->setCpfGestor($_SESSION['user']['cpf']);
    $okr->salvar($_SESSION['empresa']['database']);

    $okr_id = $okr->retornarUltima($_SESSION['empresa']['database']);

    if($_POST['todosCols'] == "1") {
        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_okr_colaborador (okr_id, col_cpf) VALUES ('$okr_id', '$cpf')";
            $helper->insert($insert);
        }
    } else {
        foreach ($colaboradores as $c) {
            $insert = "INSERT INTO tbl_okr_colaborador (okr_id, col_cpf) VALUES ('$okr_id', '$c')";
            $helper->insert($insert);
        }
    }


    if($_POST['todosGes'] == "1") {
        $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['ges_cpf'];
            $insert = "INSERT INTO tbl_okr_gestor (okr_id, ges_cpf) VALUES ('$okr_id', '$cpf')";
            $helper->insert($insert);
        }
    } else {
        foreach ($gestores as $g) {
            $insert = "INSERT INTO tbl_okr_gestor (okr_id, ges_cpf) VALUES ('$okr_id', '$g')";
            $helper->insert($insert);
        }
    }


    if($_POST['todosSet'] == "1") {
        $select = "SELECT set_id as id FROM tbl_setor";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $set_id = $fetch['id'];
            $insert = "INSERT INTO tbl_okr_setor (okr_id, set_id) VALUES ('$okr_id', '$set_id')";
            $helper->insert($insert);
        }
    } else {
        foreach ($setores as $s) {
            
            $insert = "INSERT INTO tbl_okr_setor (okr_id, set_id) VALUES ('$okr_id', '$s')";
            $helper->insert($insert);

        }
    }

    $krs = new KeyResult();
    $titulo = addslashes($_POST['tituloOKR1']);
    $krs->setTitulo($titulo);
    $krs->setTipo("Padrão");
    $meta = $_POST['metaOKR1'];
        $meta = str_replace('.', '', $meta);
        $meta = str_replace(',', '.', $meta);
    $krs->setGoal($meta);
    $krs->setIDOKR($okr_id);

    $krs->salvar($_SESSION['empresa']['database']);

    if($_POST['tituloOKR2'] != "" && $_POST['metaOKR2'] != "") {
        $krs = new KeyResult();
        $titulo = addslashes($_POST['tituloOKR2']);
        $krs->setTitulo($titulo);
        $krs->setTipo("Padrão");
        $meta = $_POST['metaOKR2'];
            $meta = str_replace('.', '', $meta);
            $meta = str_replace(',', '.', $meta);
        $krs->setGoal($meta);
        $krs->setIDOKR($okr_id);

        $krs->salvar($_SESSION['empresa']['database']);
    }

    if($_POST['tituloOKR3'] != "" && $_POST['metaOKR3'] != "") {
        $krs = new KeyResult();
        $titulo = addslashes($_POST['tituloOKR3']);
        $krs->setTitulo($titulo);
        $krs->setTipo("Padrão");
        $meta = $_POST['metaOKR3'];
            $meta = str_replace('.', '', $meta);
            $meta = str_replace(',', '.', $meta);
        $krs->setGoal($meta);
        $krs->setIDOKR($okr_id);

        $krs->salvar($_SESSION['empresa']['database']);
    }

    if($_POST['tituloOKR4'] != "" && $_POST['metaOKR4'] != "") {
        $krs = new KeyResult();
        $titulo = addslashes($_POST['tituloOKR4']);
        $krs->setTitulo($titulo);
        $krs->setTipo("Padrão");
        $meta = $_POST['metaOKR4'];
            $meta = str_replace('.', '', $meta);
            $meta = str_replace(',', '.', $meta);
        $krs->setGoal($meta);
        $krs->setIDOKR($okr_id);

        $krs->salvar($_SESSION['empresa']['database']);
    }

    if($_POST['tituloOKR5'] != "" && $_POST['metaOKR5'] != "") {
        $krs = new KeyResult();
        $titulo = addslashes($_POST['tituloOKR5']);
        $krs->setTitulo($titulo);
        $krs->setTipo("Padrão");
        $meta = $_POST['metaOKR5'];
            $meta = str_replace('.', '', $meta);
            $meta = str_replace(',', '.', $meta);
        $krs->setGoal($meta);
        $krs->setIDOKR($okr_id);

        $krs->salvar($_SESSION['empresa']['database']);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Criou a meta OKR ".$objetivo);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Meta OKR e Key Results criados com sucesso";
    header("Location: ../empresa/novaOKR.php");
    die();

}


?>