<?php

    include('../../include/auth.php');
    require_once('../../classes/class_log_alteracao.php');
    require_once('../../classes/class_key_result.php');

    $id = $_GET['id'];

    $krs = new KeyResult();
    $krs->setID($id);
    $krs->setTitulo(addslashes($_GET['titulo']));
    $krs->setTipo($_GET['tipo']);
    
    $goal = str_replace('.', '', $_GET['goal']);
    $goal = str_replace(',', '.', $goal);
    $krs->setGoal($goal);

    $current = str_replace('.', '', $_GET['current']);
    $current = str_replace(',', '.', $current);
    $krs->setCurrent($current);

    if($krs->atualizar($_SESSION['empresa']['database'])) {
        $log = new LogAlteracao();
        $log->setDescricao("Alterou KRS ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        echo '<div class="col-sm"> Key Result atualizado - <a href="#" onclick="location.reload();">Atualizar a página p/ ver alterações</a> </div>';
    } else {
        echo '<div class="col-sm"> Houve um erro ao atualizar </div>';
    }

?>