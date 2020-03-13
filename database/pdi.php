<?php

include('../include/auth.php');
include('../src/functions.php');
require_once("../classes/class_pdi.php");
require_once('../classes/class_log_alteracao.php');
require_once('../classes/class_conexao_empresa.php');
require_once('../classes/class_queryHelper.php');

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conexao = $conexao->conecta();
$helper = new QueryHelper($conexao);

    $_SESSION['msg'] = "";

    if(isset($_GET['novo'])) { 

        $pdi = new PDI();
        $pdi->setTitulo(addslashes($_POST['titulo']));
        $pdi->setCpf($_POST['dono']);
        $pdi->setCpfGestor($_POST['orientador']);
        $pdi->setPrazo($_POST['prazo'].' 23:59:59');

        $pdi->cadastrar($_SESSION['empresa']['database']);

        $log = new LogAlteracao();
        $log->setDescricao("Cadastrou PDI ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $id_pdi = $pdi->retornarUltimo($_SESSION['empresa']['database']);

        $_SESSION['msg'] = 'Plano de Desenvolvimento Individual (PDI) criado com sucesso! <br>Agora você já pode adicionar competências e metas';
        header('Location: ../empresa/verPDI.php?id='.$id_pdi);

    } else if (isset($_GET['editar'])) {
        $pdi = new PDI();
        $pdi->setID($_POST['pdi_id']);
        $pdi->setTitulo(addslashes($_POST['titulo']));
        $pdi->setPrazo($_POST['prazo'].' 23:59:59');

        $pdi->atualizar($_SESSION['empresa']['database']);

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou PDI ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Plano de Desenvolvimento Individual (PDI) atualizado com sucesso!';
        header('Location: ../empresa/verPDI.php?id='.$_POST['pdi_id']);
    } else if (isset($_GET['atualizarTipo'])) {
        $tipo = $_POST['tipo'];
        $id = $_POST['id'];
        $status = $_POST['status'];
        $anotacao = addslashes($_POST['anotacao']);

        if($tipo == 'compet') {
            if($status == 1) { //se tiver tentando concluir a competência
                //Checar se existem metas não concluídas ou canceladas antes de atualizar
                $select = "SELECT id FROM tbl_pdi_competencia_meta WHERE cpt_id = $id AND status NOT IN (0,1)";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) > 0) {
                    $_SESSION['msg'] = 'Você não pode atualizar uma competência enquanto houverem metas dela que 
                    não estejam concluídas ou canceladas';
                    header('Location: ../empresa/verPDI.php?id='.$_GET['id_pdi']);
                    die();
                }
            }
            $update = "UPDATE tbl_pdi_competencia SET status = $status WHERE id = $id";
            $helper->update($update);
        } else if ($tipo == 'meta') {
            $update = "UPDATE tbl_pdi_competencia_meta SET status = $status WHERE id = $id";
            $helper->update($update);
        } else if ($tipo == 'pdi') {
            if($status == 1) { //se estiver tentando concluir o PDI
                //checar se há competências que não estejam canceladas ou concluídas
                $select = "SELECT id FROM tbl_pdi_competencia WHERE pdi_id = $id AND status NOT IN (0,1)";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) > 0) {
                    $_SESSION['msg'] = 'Você não pode atualizar um PDI enquanto houverem competências dele que 
                    não estejam concluídas ou canceladas';
                    header('Location: ../empresa/verPDI.php?id='.$_GET['id_pdi']);
                    die();
                }
            }
            $update = "UPDATE tbl_pdi SET pdi_status = $status WHERE pdi_id = $id";
            $helper->update($update);
        }

        //Inserindo anotação
        $insert = "INSERT INTO tbl_pdi_anotacao (pdi_id, anotacao) VALUES (".$_GET['id_pdi'].", '$anotacao')";
        $helper->insert($insert);

        $_SESSION['msg'] = 'Atualização realizada com sucesso';
        header('Location: ../empresa/verPDI.php?id='.$_GET['id_pdi']);
    } else if (isset($_GET['novaCompetencia'])) {
        $pdi_id = $_POST['pdi_id'];
        $competencia = addslashes($_POST['competencia']);

        $pdi = new PDI();

        if($pdi->cadastrarCompetencia($_SESSION['empresa']['database'], $pdi_id, $competencia) === false) {
            $_SESSION['msg'] = 'Houve um erro ao adicionar a competência';
        } else {
            $_SESSION['msg'] = 'Competência adicionada com sucesso';
        }

        header('Location: ../empresa/verPDI.php?id='.$pdi_id);
        die();
    } else if (isset($_GET['novaMeta'])) {
        $pdi_id = $_POST['pdi_id'];
        $compet_id = $_POST['new_compet_id'];
        $meta = addslashes($_POST['meta']);

        $pdi = new PDI();
        
        if($pdi->cadastrarMeta($_SESSION['empresa']['database'], $compet_id, $meta)) {
            $_SESSION['msg'] = 'Meta adicionada com sucesso';
        } else {
            $_SESSION['msg'] = 'Houve um erro ao adicionar a meta';
        }

        header('Location: ../empresa/verPDI.php?id='.$pdi_id);
        die();
    } else if (isset($_GET['arquivar'])) {
        $pdi_id = $_GET['id'];

        $pdi = new PDI();
        $pdi->setID($pdi_id);

        if($pdi->arquivar($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = 'Plano de Desenvolvimento Individual arquivado';
        } else {
            $_SESSION['msg'] = 'Houve um erro ao arquivar o PDI';
        }

        header('Location: ../empresa/verPDI.php?id='.$pdi_id);
        die();
    } else if (isset($_GET['desarquivar'])) {
        $pdi_id = $_GET['id'];

        $pdi = new PDI();
        $pdi->setID($pdi_id);

        if($pdi->desarquivar($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = 'Plano de Desenvolvimento Individual desarquivado';
        } else {
            $_SESSION['msg'] = 'Houve um erro ao desarquivar o PDI';
        }

        header('Location: ../empresa/verPDI.php?id='.$pdi_id);
        die();
    } else if (isset($_GET['tornarPublico'])) {
        $pdi_id = $_GET['id'];

        $pdi = new PDI();
        $pdi->setID($pdi_id);

        if($pdi->tornarPublico($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = 'Plano de Desenvolvimento Individual publicado para a empresa';
        } else {
            $_SESSION['msg'] = 'Houve um erro ao publicar o PDI';
        }

        header('Location: ../empresa/PDIs.php');
        die();
    } else if (isset($_GET['reverterPublico'])) {
        $pdi_id = $_GET['id'];

        $pdi = new PDI();
        $pdi->setID($pdi_id);

        if($pdi->reverterPublico($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = 'Plano de Desenvolvimento Individual foi retirado do mural público';
        } else {
            $_SESSION['msg'] = 'Houve um erro ao retirar o PDI do mural público';
        }

        header('Location: ../empresa/verPDI.php?id='.$pdi_id);
        die();
    }
?>