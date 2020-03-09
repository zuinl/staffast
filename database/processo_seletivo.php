<?php

include('../include/auth.php');
include('../src/functions.php');
require_once("../classes/class_processo_seletivo.php");
require_once("../classes/class_pergunta.php");
require_once('../classes/class_log_alteracao.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $_SESSION['msg'] = "";

    if(isset($_GET['novo'])) { //ENTENDE-SE QUE TERÁ PERGUNTAS, POIS CHEGOU ATÉ AQUI

        $ps = new ProcessoSeletivo();
        $ps->setTitulo(addslashes($_POST['titulo']));
        $ps->setDescricao(addslashes($_POST['descricao']));
        $ps->setDataEncerramento($_POST['encerramento'].' 23:59:59');
        $ps->setVagas($_POST['vagas']);
        $ps->setCpfGestor($_SESSION['user']['cpf']);

        $ps->cadastrar($_SESSION['empresa']['database'], $_SESSION['empresa']['emp_id']);

        $log = new LogAlteracao();
        $log->setDescricao("Cadastrou processo seletivo ".$_POST['titulo']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $id_pergunta = $ps->retornarUltimo($_SESSION['empresa']['database']);

        for($i = 1; $i <= $_POST['perguntas']; $i++) {

            $id_titulo = "titulo_".$i;
            $id_descricao = "descricao_".$i;
            $id_alter_um = "alter_um_".$i;
            $id_alter_dois = "alter_dois_".$i;
            $id_alter_tres = "alter_tres_".$i;
            $id_alter_quatro = "alter_quatro_".$i;
            $id_compet_um = "compet_um_".$i;
            $id_compet_dois = "compet_dois_".$i;
            $id_compet_tres = "compet_tres_".$i;
            $id_compet_quatro = "compet_quatro_".$i;

            $pergunta = new Pergunta();
            $pergunta->setTitulo($_POST[$id_titulo]);
            $pergunta->setDescricao($_POST[$id_descricao]);
            $pergunta->setOpcUm($_POST[$id_alter_um]);
            $pergunta->setOpcDois($_POST[$id_alter_dois]);
            $pergunta->setOpcTres($_POST[$id_alter_tres]);
            $pergunta->setOpcQuatro($_POST[$id_alter_quatro]);
            $pergunta->setCompetUm($_POST[$id_compet_um]);
            $pergunta->setCompetDois($_POST[$id_compet_dois]);
            $pergunta->setCompetTres($_POST[$id_compet_tres]);
            $pergunta->setCompetQuatro($_POST[$id_compet_quatro]);
            $pergunta->setSelID($id_pergunta);

            $pergunta->cadastrar($_SESSION['empresa']['database']);

            $_SESSION['msg'] = "Processo seletivo com ".$i." perguntas cadastradas";

            header('Location: ../empresa/processosSeletivos.php');

        }

    } else if (isset($_GET['encerrar'])) {

        $ps = new ProcessoSeletivo();
        $ps->setID($_GET['ps']);

        $ps->fechar($_SESSION['empresa']['database']);

        $log = new LogAlteracao();
        $log->setDescricao("Encerrou processo seletivo ".$_GET['ps']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = "Processo seletivo encerrado com sucesso";

            header('Location: ../empresa/processosSeletivos.php');

    } else if (isset($_GET['reabrir'])) {

        $ps = new ProcessoSeletivo();
        $ps->setID($_GET['ps']);

        $ps->reabrir($_SESSION['empresa']['database']);

        $log = new LogAlteracao();
        $log->setDescricao("Reabriu processo seletivo ".$_GET['ps']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = "Processo seletivo reaberto com sucesso";

        header('Location: ../empresa/processosSeletivosEncerrados.php');

    } else if (isset($_GET['editar'])) {

        $ps = new ProcessoSeletivo();
        $ps->setID($_POST['ps']);
        $ps->setTitulo(addslashes($_POST['titulo']));
        $ps->setDescricao(addslashes($_POST['descricao']));
        $ps->setVagas($_POST['vagas']);
        $ps->setDataEncerramento($_POST['encerramento'].' 23:59:59');
        
        if($ps->atualizar($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = "Processo seletivo atualizado com sucesso";
        } else {
            $_SESSION['msg'] = "Algo errado aconteceu ao atualizar o processo seletivo";
        }

        $log = new LogAlteracao();
        $log->setDescricao("Editou processo seletivo ".$_POST['ps']);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        header('Location: ../empresa/processosSeletivos.php');

    }
?>