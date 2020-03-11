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

    $okr = new OKR();
    $okr->setID($okr_id);

    if($_POST['todosCols'] == "1") {
        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $okr->adicionarColaborador($_SESSION['empresa']['database'], $cpf);
        }
    } else {
        foreach ($colaboradores as $c) {
            $okr->adicionarColaborador($_SESSION['empresa']['database'], $c);
        }
    }


    if($_POST['todosGes'] == "1") {
        $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['ges_cpf'];
            $okr->adicionarGestor($_SESSION['empresa']['database'], $cpf);
        }
    } else {
        foreach ($gestores as $g) {
            $okr->adicionarGestor($_SESSION['empresa']['database'], $g);
        }
        $okr->adicionarGestor($_SESSION['empresa']['database'], $_SESSION['user']['cpf']);
    }


    if($_POST['todosSet'] == "1") {
        $select = "SELECT set_id as id FROM tbl_setor";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $set_id = $fetch['id'];
            $okr->adicionarSetor($_SESSION['empresa']['database'], $set_id);
        }
    } else {
        foreach ($setores as $s) {
            $okr->adicionarSetor($_SESSION['empresa']['database'], $s);
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
    header("Location: ../empresa/metas.php");
    die();

} else if (isset($_GET['editar'])) {
    $okr_id = $_POST['okr_id'];

    $okr = new OKR();
    $okr->setID($okr_id);
    $okr->setTitulo(addslashes($_POST['titulo']));
    $okr->setDescricao(addslashes($_POST['descricao']));
    $okr->setTipo($_POST['tipo']);
    $okr->setPrazo($_POST['prazo'].' 23:59:59');

    switch($_POST['visivel']) {
        case 'Todos': $okr->setVisivel(1); break;
        case 'Apenas eu': $okr->setVisivel(2); break;
        case 'Apenas os gestores': $okr->setVisivel(3); break;
        default: $okr->setVisivel(1);
    }

    if($okr->atualizar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Meta OKR atualizada com sucesso';

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou OKR ".$okr_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao tentar atualizar sua meta OKR';
    }

    header('Location: ../empresa/verOKR.php?id='.$okr_id);
    die();
} else if (isset($_GET['addColaboradores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $c) {
        $okr->adicionarColaborador($_SESSION['empresa']['database'], $c);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou colaboradores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores adicionados';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['addGestores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $gestores = $_POST['gestores'];

    foreach($gestores as $g) {
        $okr->adicionarGestor($_SESSION['empresa']['database'], $g);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou gestores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores adicionados';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['rmvGestores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $gestores = $_POST['gestores'];

    foreach($gestores as $g) {
        $okr->removerGestor($_SESSION['empresa']['database'], $g);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu gestores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores removidos';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['rmvColaboradores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $c) {
        $okr->removerColaborador($_SESSION['empresa']['database'], $c);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu colaboradores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores removidos';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['rmvSetores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $setores = $_POST['setores'];

    foreach($setores as $s) {
        $okr->removerSetor($_SESSION['empresa']['database'], $s);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu setores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Setores removidos';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['addSetores'])) {
    $okr = new OKR();
    $okr->setID($_POST['id']);

    $setores = $_POST['setores'];

    foreach($setores as $s) {
        $okr->adicionarSetor($_SESSION['empresa']['database'], $s);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou setores a OKR ".$_POST['id']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Setores adicionados';
    header('Location: ../empresa/verOKR.php?id='.$_POST['id']);
    die();
} else if (isset($_GET['excluirKRS'])) {
    $krs_id = $_POST['krs_excluir_id'];
    $okr_id = $_GET['okr_id'];

    $krs = new KeyResult();
    $krs->setID($krs_id);

    if ($krs->excluir($_SESSION['empresa']['database'])) {
        $log = new LogAlteracao();
        $log->setDescricao("Excluiu a Key Result ".$krs_id." da OKR ".$okr_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Key Result excluída';
    } else {
        $_SESSION['msg'] = 'Houve um erro ao tentar exlcuir a Key Result';
    }

    header('Location: ../empresa/verOKR.php?id='.$okr_id);
    die();
} else if (isset($_GET['adicionarKRS'])) {
    $okr_id = $_POST['okr_id'];

    $titulo = addslashes($_POST['titulo_new_krs']);
    $tipo = $_POST['tipo_new_krs'];

    $goal = str_replace('.', '', $_POST['goal_new_krs']);
    $goal = str_replace(',', '.', $goal);
    
    $krs = new KeyResult();
    $krs->setTitulo($titulo);
    $krs->setTipo($tipo);
    $krs->setGoal($goal);
    $krs->setIDOKR($okr_id);

    if($krs->salvar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Key Result adicionada';

        $log = new LogAlteracao();
        $log->setDescricao("Adicionou Key Result ".$titulo." a OKR ".$okr_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();
    } else {
        $_SESSION['msg'] = 'Houve um erro ao adicionar a Key Result';
    }

    header('Location: ../empresa/verOKR.php?id='.$okr_id);
    die();
} else if (isset($_GET['adicionarAnotacao'])) {
    $krs_id = $_POST['krs_id'];
    $okr_id = $_GET['okr_id'];
    $anotacao = addslashes($_POST['anotacao']);
    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] == 'COLABORADOR') {
        $insert = "INSERT INTO tbl_krs_anotacao (krs_id, anotacao, col_cpf) VALUES ($krs_id, '$anotacao', '$cpf')";
    } else {
        $insert = "INSERT INTO tbl_krs_anotacao (krs_id, anotacao, ges_cpf) VALUES ($krs_id, '$anotacao', '$cpf')";
    }

    if($helper->insert($insert)) {
        $_SESSION['msg'] = 'Anotação adicionada com sucesso';
    } else {
        $_SESSION['msg'] = 'Houve um erro ao tentar adicionar a anotação';
    }

    header('Location: ../empresa/verOKR.php?id='.$okr_id);
    die();
    
} else if (isset($_GET['arquivar'])) {
    $okr = new OKR();
    $okr->setID($_GET['id']);

    if($okr->arquivar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Meta arquivada';
    } else {
        $_SESSION['msg'] = 'Houve um erro ao arquivar a meta';
    }

    header('Location: ../empresa/metasArquivadas.php');
    die();
} else if (isset($_GET['desarquivar'])) {
    $okr = new OKR();
    $okr->setID($_GET['id']);

    if($okr->desarquivar($_SESSION['empresa']['database'])) {
        $_SESSION['msg'] = 'Meta desarquivada';
    } else {
        $_SESSION['msg'] = 'Houve um erro ao desarquivar a meta';
    }

    header('Location: ../empresa/verOKR.php?id='.$_GET['id']);
    die();
}


?>