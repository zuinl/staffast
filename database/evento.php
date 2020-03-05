<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_mensagem.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");
require_once("../classes/class_evento.php");
require_once("../classes/class_usuario.php");
require_once("../classes/class_gestor.php");
require_once("../classes/class_colaborador.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

if(isset($_GET['novo'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $titulo = addslashes($_POST['titulo']);
    $descricao = addslashes($_POST['descricao']);
    $local = addslashes($_POST['local']);
    $dataI = $_POST['dataI'];
    $horaI = $_POST['horaI'];
    $dataF = $_POST['dataF'];
    $horaF = $_POST['horaF'];
    if(isset($_POST['isNaEmpresa'])) {
        $isNaEmpresa = 1;
    } else {
        $isNaEmpresa = 0;
    }
    $colaboradores = $_POST['colaboradores'];
    $gestores = $_POST['gestores'];
    $setores = $_POST['setores'];

    $eve = new Evento();
    $eve->setTitulo($titulo);
    $eve->setDescricao($descricao);
    $eve->setLocal($local);
    $eve->setIsNaEmpresa($isNaEmpresa);
    $eve->setDataI($dataI);
    $eve->setHoraI($horaI);
    $eve->setDataF($dataF);
    $eve->setHoraF($horaF);
    $eve->setCpfGestor($_SESSION['user']['cpf']);
    $eve->salvar($_SESSION['empresa']['database']);

    $eve_id = $eve->retornarUltimo($_SESSION['empresa']['database']);

    $ges_cpf = $_SESSION['user']['cpf'];
    $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$eve_id', '$ges_cpf', 1)";
    $helper->insert($insert);

    if($_POST['todosCols'] == "1") {
        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, colaborador) VALUES ('$eve_id', '$cpf', 1)";
            $helper->insert($insert);
        }
    } else {
        foreach ($colaboradores as $c) {
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, colaborador) VALUES ('$eve_id', '$c', 1)";
            $helper->insert($insert);
        }
    }


    if($_POST['todosGes'] == "1") {
        $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$eve_id', '$cpf', 1)";
            $helper->insert($insert);
        }
    } else {
        foreach ($gestores as $g) {
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$eve_id', '$g', 1)";
            $helper->insert($insert);
        }
    }


    if($_POST['todosSet'] == "1") {
        $select = "SELECT DISTINCT(col_cpf) as cpf FROM tbl_setor_funcionario WHERE col_cpf != '00000000000'";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['cpf'];
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, colaborador) VALUES ('$eve_id', '$cpf', 1)";
            $helper->insert($insert);
        }

        $select = "SELECT DISTINCT(ges_cpf) as cpf FROM tbl_setor_funcionario WHERE ges_cpf != '00000000000'";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['cpf'];
            $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$eve_id', '$cpf', 1)";
            $helper->insert($insert);
        }
    } else {
        foreach ($setores as $s) {
            
            $select = "SELECT DISTINCT(col_cpf) as cpf FROM tbl_setor_funcionario WHERE col_cpf != '00000000000' AND set_id = '$s'";
            $query = $helper->select($select, 1);

            while($fetch = mysqli_fetch_assoc($query)) {
                $cpf = $fetch['cpf'];
                $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, colaborador) VALUES ('$eve_id', '$cpf', 1)";
                $helper->insert($insert);
            }

            $select = "SELECT DISTINCT(ges_cpf) as cpf FROM tbl_setor_funcionario WHERE ges_cpf != '00000000000' AND set_id = '$s'";
            $query = $helper->select($select, 1);

            while($fetch = mysqli_fetch_assoc($query)) {
                $cpf = $fetch['cpf'];
                $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$eve_id', '$cpf', 1)";
                $helper->insert($insert);
            }

        }
    }

    $log = new LogAlteracao();
        $log->setDescricao("Criou o evento ".$titulo." - ID ".$eve_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $select = "SELECT DISTINCT(cpf) as cpf FROM tbl_evento_participante WHERE eve_id = '$eve_id'";
    $query = $helper->select($select, 1);

    //ENVIAR MENSAGEM E E-MAIL
    while($f = mysqli_fetch_assoc($query)) { 
        $cpf = $f['cpf'];
        $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo evento");
        $mensagem->setTexto("Um evento foi criado e você foi adicionado como participante! Cheque em Mais > Eventos");
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("7 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
        $helper->insert($insert);  
        
        $select = "SELECT usu_id FROM tbl_colaborador WHERE col_cpf = '$cpf'";
        $query = $helper->select($select, 1);
        if(mysqli_num_rows($query) > 0) {
            $fetch = mysqli_fetch_assoc($query);
            $usu_id = $fetch['usu_id'];
        } else {
            $select = "SELECT usu_id FROM tbl_gestor WHERE ges_cpf = '$cpf'";
            $query = $helper->select($select, 1);
            $fetch = mysqli_fetch_assoc($query);
            $usu_id = $fetch['usu_id'];
        }
        
        $usuario = new Usuario();
        $usuario->setID($usu_id);
        $usuario = $usuario->retornarUsuario();
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Novo evento no Staffast");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Um novo evento foi criado</h1>
                <h2 class="high-text">Olá! Um novo evento foi criado para a empresa '.$empresa.' e você foi adicionado como participante.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $_SESSION['msg'] = "Evento criado com sucesso";
    header("Location: ../empresa/novoEvento.php");
    die();

} else if (isset($_GET['editar'])) {

    $eve_id = $_POST['id'];

    $eve = new Evento();
    $eve->setID($eve_id);
    $eve = $eve->retornarEvento($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $evento->getCpfGestor() != $_SESSION['user']['cpf']) {
        include("../include/acessoNegado.php");
        die();
    }

    $titulo = addslashes($_POST['titulo']);
    $descricao = addslashes($_POST['descricao']);
    $local = addslashes($_POST['local']);
    $dataI = $_POST['dataI'];
    $horaI = $_POST['horaI'];
    $dataF = $_POST['dataF'];
    $horaF = $_POST['horaF'];
    if(isset($_POST['isNaEmpresa'])) {
        $isNaEmpresa = 1;
    } else {
        $isNaEmpresa = 0;
    }

    $eve->setTitulo($titulo);
    $eve->setDescricao($descricao);
    $eve->setLocal($local);
    $eve->setIsNaEmpresa($isNaEmpresa);
    $eve->setDataI($dataI);
    $eve->setHoraI($horaI);
    $eve->setDataF($dataF);
    $eve->setHoraF($horaF);
    $eve->atualizar($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
        $log->setDescricao("Editou o evento ".$eve_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Evento atualizado com sucesso";
    header("Location: ../empresa/eventos.php");
    die();

} else if (isset($_GET['confirmar'])) {

    $id = $_GET['id'];
    $cpf = $_SESSION['user']['cpf'];

    $update = "UPDATE tbl_evento_participante SET confirmado = 1 WHERE eve_id = '$id' 
    AND cpf = '$cpf'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Confirmou presença em evento - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Presença confirmada!";
    header("Location: ../empresa/eventos.php");
    die();

} else if (isset($_GET['desconfirmar'])) {

    $id = $_GET['id'];
    $cpf = $_SESSION['user']['cpf'];

    $update = "UPDATE tbl_evento_participante SET confirmado = 0 WHERE eve_id = '$id' 
    AND cpf = '$cpf'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Desconfirmou presença em evento - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Presença desconfirmada!";
    header("Location: ../empresa/eventos.php");
    die();

} else if (isset($_GET['cancelar'])) {

    $id = $_GET['id'];

    $update = "UPDATE tbl_evento SET eve_status = 0 WHERE eve_id = '$id'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Cancelou evento - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Evento cancelado!";
    header("Location: ../empresa/eventos.php");
    die();

} else if (isset($_GET['addColaboradores'])) {

    $id = $_POST['id'];

    $evento = new Evento();
    $evento->setID($id);
    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $col) {
        $evento->adicionarColaborador($_SESSION['empresa']['database'], $col);

        $colaborador = new Colaborador();
        $colaborador->setCpf($col);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($colaborador->getIDUser());
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Novo evento");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Um novo evento foi criado</h1>
                <h2 class="high-text">Olá! Um novo evento foi criado para a empresa '.$empresa.' e você foi adicionado como participante.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo evento");
        $mensagem->setTexto("Um evento foi criado e você foi adicionado como participante! Cheque em Mais > Eventos");
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("3 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$col')";
        $helper->insert($insert); 
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou colaboradores ao evento ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores adicionados. Eles vão receber uma notificação no sistema.';

    header('Location: ../empresa/verEvento.php?id='.$id);
    die();

} else if (isset($_GET['addGestores'])) {

    $id = $_POST['id'];

    $evento = new Evento();
    $evento->setID($id);
    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

    $gestores = $_POST['gestores'];

    foreach($gestores as $ges) {
        $evento->adicionarGestor($_SESSION['empresa']['database'], $ges);

        $gestor = new Gestor();
        $gestor->setCpf($ges);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $usuario = new Usuario();
        $usuario->setID($gestor->getIDUser());
        $email = new Email();
        $email->setEmailTo($usuario->getEmail());
        $email->setEmailFrom(0);
        $email->setAssunto("Novo evento");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Um novo evento foi criado</h1>
                <h2 class="high-text">Olá! Um novo evento foi criado para a empresa '.$empresa.' e você foi adicionado como participante.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();

        $mensagem = new Mensagem();
        $mensagem->setTitulo("Novo evento");
        $mensagem->setTexto("Um evento foi criado e você foi adicionado como participante! Cheque em Mais > Eventos");
            $hoje = date('Y-m-d');
            $date=date_create($hoje);
            date_add($date,date_interval_create_from_date_string("3 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);

        $mensagem->cadastrar($_SESSION['empresa']['database']);

        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$ges')";
        $helper->insert($insert); 
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou gestores ao evento ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores adicionados. Eles vão receber uma notificação no sistema.';

    header('Location: ../empresa/verEvento.php?id='.$id);
    die();

} else if (isset($_GET['rmvGestores'])) {

    $id = $_POST['id'];

    $evento = new Evento();
    $evento->setID($id);
    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

    $gestores = $_POST['gestores'];

    foreach($gestores as $ges) {
        $evento->removerGestor($_SESSION['empresa']['database'], $ges);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu gestores do evento ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores removidos';

    header('Location: ../empresa/verEvento.php?id='.$id);
    die();

} else if (isset($_GET['rmvColaboradores'])) {

    $id = $_POST['id'];

    $evento = new Evento();
    $evento->setID($id);
    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $col) {
        $evento->removerColaborador($_SESSION['empresa']['database'], $col);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu colaboradores do evento ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores removidos';

    header('Location: ../empresa/verEvento.php?id='.$id);
    die();

}


?>