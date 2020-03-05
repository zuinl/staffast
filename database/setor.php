<?php

include('../include/auth.php');
include('../src/functions.php');
require_once('../classes/class_setor.php');
require_once('../classes/class_colaborador.php');
require_once('../classes/class_gestor.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_email.php');
require_once('../classes/class_log_alteracao.php');


    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include('../include/acessoNegado.php');
        die();
    }
    
$_SESSION['msg'] = "";

if(isset($_GET['novoSetor'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1"){
        include('../include/acessoNegado.php');
        die();
    }

    $nome = addslashes($_POST['nome']);
    $local = addslashes($_POST['local']);
    $descricao = addslashes($_POST['descricao']);
    $gestores = $_POST['gestores'];
    $sessaoUm = addslashes($_POST['c1']);
    $sessaoDois = addslashes($_POST['c2']);
    $sessaoTres = addslashes($_POST['c3']);
    $sessaoQuatro = addslashes($_POST['c4']);
    $sessaoCinco = addslashes($_POST['c5']);
    $sessaoSeis = addslashes($_POST['c6']);

    $setor = new Setor();
    $setor->setNome($nome);
    $setor->setLocal($local);
    $setor->setDescricao($descricao);
    $setor->setSessaoUm($sessaoUm);
    $setor->setSessaoDois($sessaoDois);
    $setor->setSessaoTres($sessaoTres);
    $setor->setSessaoQuatro($sessaoQuatro);
    $setor->setSessaoCinco($sessaoCinco);
    $setor->setSessaoSeis($sessaoSeis);

    if($setor->cadastrar($_SESSION['empresa']['database'])) {

        $log = new LogAlteracao();
        $log->setDescricao("Cadastrou setor ".$nome);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $set_id = $setor->retornarUltimoSetor($_SESSION['empresa']['database']);

        foreach($gestores as $gestor) {
            $setor->inserirGestor($set_id, $gestor, $_SESSION['empresa']['database']);
        }

        $_SESSION['msg'] = 'Setor cadastrado com sucesso';
    } else {
        $_SESSION['msg'] = 'Erro ao cadastrar o setor';
    }

    header('Location: ../empresa/setores.php');
        
} else if (isset($_GET['atualiza'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1"){
        include('../include/acessoNegado.php');
        die();
    }

    $id = $_POST['id'];
    $nome = addslashes($_POST['nome']);
    $local = addslashes($_POST['local']);
    $descricao = addslashes($_POST['descricao']);
    $compet1 = addslashes($_POST['compet1']);
    $compet2 = addslashes($_POST['compet2']);
    $compet3 = addslashes($_POST['compet3']);
    $compet4 = addslashes($_POST['compet4']);
    $compet5 = addslashes($_POST['compet5']);
    $compet6 = addslashes($_POST['compet6']);

    $setor = new Setor();
    $setor->setID($id);
    $setor->setNome($nome);
    $setor->setLocal($local);
    $setor->setDescricao($descricao);
    $setor->setSessaoUm($compet1);
    $setor->setSessaoDois($compet2);
    $setor->setSessaoTres($compet3);
    $setor->setSessaoQuatro($compet4);
    $setor->setSessaoCinco($compet5);
    $setor->setSessaoSeis($compet6);

    if($setor->atualizar($_SESSION['empresa']['database'])) {

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou setor ".$nome);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Setor atualizado com sucesso';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar o setor';
    }

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['desativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1"){
        include('../include/acessoNegado.php');
        die();
    }

    $id = $_GET['id'];

    $setor = new Setor();
    $setor->setID($id);

    $setor->desativar($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Desativou setor ".$id);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Setor desativado com sucesso';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['reativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1"){
        include('../include/acessoNegado.php');
        die();
    }

    $id = $_GET['id'];

    $setor = new Setor();
    $setor->setID($id);

    $setor->reativar($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Reativou setor ".$id);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Setor reativado com sucesso';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['addColaboradores'])) {

    $id = $_POST['id'];

    $setor = new Setor();
    $setor->setID($id);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']); 

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $col) {
        $setor->adicionarColaborador($_SESSION['empresa']['database'], $col);

        $colaborador = new Colaborador();
        $colaborador->setCpf($col);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Você foi adicionado a um setor");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Olá, '.$colaborador->getPrimeiroNome().'</h1>
                <h2 class="high-text">Você foi adicionado como colaborador a um novo setor em '.$empresa.'.</h2>
                <h3 class="text">Setor incluído: <b>'.$setor->getNome().'</b>.</h3>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou colaboradores ao setor ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores adicionados';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['addGestores'])) {

    $id = $_POST['id'];

    $setor = new Setor();
    $setor->setID($id);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    $gestores = $_POST['gestores'];

    foreach($gestores as $ges) {
        $setor->adicionarGestor($_SESSION['empresa']['database'], $ges);

        $gestor = new Gestor();
        $gestor->setCpf($ges);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($gestor->getIDUser());
        $email = new Email();
        $email->setAssunto("Você foi adicionado a um setor");
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Olá, '.$gestor->getPrimeiroNome().'</h1>
                <h2 class="high-text">Você foi adicionado como gestor a um novo setor em '.$empresa.'.</h2>
                <h3 class="text">Setor incluído: <b>'.$setor->getNome().'</b>.</h3>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou gestores ao setor ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores adicionados';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['rmvGestores'])) {

    $id = $_POST['id'];

    $setor = new Setor();
    $setor->setID($id);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    $gestores = $_POST['gestoresrmv'];

    foreach($gestores as $ges) {
        $setor->removerGestor($_SESSION['empresa']['database'], $ges);

        $gestor = new Gestor();
        $gestor->setCpf($ges);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($gestor->getIDUser());
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Você foi removido de um setor");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Olá, '.$gestor->getPrimeiroNome().'</h1>
                <h2 class="high-text">Você foi removido como gestor de um setor em '.$empresa.'.</h2>
                <h3 class="text">Setor removido: <b>'.$setor->getNome().'</b>.</h3>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu gestores do setor ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores removidos';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['rmvColaboradores'])) {

    $id = $_POST['id'];

    $setor = new Setor();
    $setor->setID($id);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    $colaboradores = $_POST['colaboradoresrmv'];

    foreach($colaboradores as $col) {
        $setor->removerColaborador($_SESSION['empresa']['database'], $col);

        $colaborador = new Colaborador();
        $colaborador->setCpf($col);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Você foi removido de um setor");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Olá, '.$colaborador->getPrimeiroNome().'</h1>
                <h2 class="high-text">Você foi removido como colaborador de um novo setor em '.$empresa.'.</h2>
                <h3 class="text">Setor removido: <b>'.$setor->getNome().'</b>.</h3>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu colaboradores do setor ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores removidos';

    header('Location: ../empresa/perfilSetor.php?id='.$id);
    die();

} else if (isset($_GET['liberarAvaliacao'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1"){
        include('../include/acessoNegado.php');
        die();
    }

    $id = $_GET['id'];

    $setor = new Setor();
    $setor->setID($id);

    if($setor->liberarAvaliacao($_SESSION['empresa']['database'])){
        $_SESSION['msg'] = 'Avaliação liberada para o setor';
    } else {
        $_SESSION['msg'] = 'Houve algum erro :[';
    }

    header('Location: ../empresa/avaliacaoSetor.php');
    die();

}

?>