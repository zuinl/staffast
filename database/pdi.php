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
        $_POST['orientador'] = $_POST['orientador'];
        $pdi->setCpfGestor($orientador);
        $pdi->setCpf($_POST['dono']);
        $pdi->setPrazo($_POST['prazo'].' 23:59:59');

        $pdi->cadastrar($_SESSION['empresa']['database']);

        $log = new LogAlteracao();
        $log->setDescricao("Cadastrou PDI ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $id_pdi = $pdi->retornarUltimo($_SESSION['empresa']['database']);

        for($i = 1; $i <= $_POST['competencias']; $i++) {
            $competencia = $_POST['competencia_'.$i];
            $metas = $_POST['numMetas_'.$i];

            //cadastrar competencia
            $id_competencia = $pdi->cadastrarCompetencia($_SESSION['empresa']['database'], $id_pdi, $competencia);

            for($j = 1; $j <= $metas; $j++) {
                $meta = $_POST['competencia_'.$i.'&meta_'.$j];

                //cadastrar meta da competência
                $pdi->cadastrarMeta($_SESSION['empresa']['database'], $id_competencia, $meta);
            }
        }

        $_SESSION['msg'] = 'Plano de Desenvolvimento Individual (PDI) criado com sucesso!';
        header('Location: ../empresa/PDIs.php');

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
    }
?>