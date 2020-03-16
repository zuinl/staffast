<?php
include('../include/auth.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_mensagem.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");
require_once("../classes/class_email.php");
require_once("../classes/class_reuniao.php");
require_once("../classes/class_usuario.php");
require_once("../classes/class_gestor.php");
require_once("../classes/class_colaborador.php");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
$conn = $conexao->conecta();

$helper = new QueryHelper($conn);

if(isset($_GET['nova'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $pauta = addslashes($_POST['pauta']);
    $descricao = addslashes($_POST['descricao']);
    $objetivo = addslashes($_POST['objetivo']);
    $local = addslashes($_POST['local']);
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    
    $colaboradores = $_POST['colaboradores'];
    $gestores = $_POST['gestores'];
    $eventos = $_POST['eventos'];
    $metas = $_POST['metas'];

    $reu = new Reuniao();
    $reu->setPauta($pauta);
    $reu->setDescricao($descricao);
    $reu->setLocal($local);
    $reu->setObjetivo($objetivo);
    $reu->setData($data);
    $reu->setHora($hora);
    $reu->setCpfGestor($_SESSION['user']['cpf']);
    $reu->salvar($_SESSION['empresa']['database']);

    $reu_id = $reu->retornarUltima($_SESSION['empresa']['database']);

    $ges_cpf = $_SESSION['user']['cpf'];
    $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, gestor) VALUES ('$reu_id', '$ges_cpf', 1)";
    $helper->insert($insert);

    if($_POST['todosCols'] == "1") {
        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, colaborador) VALUES ('$reu_id', '$cpf', 1)";
            $helper->insert($insert);
        }
    } else {
        foreach ($colaboradores as $c) {
            $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, colaborador) VALUES ('$reu_id', '$c', 1)";
            $helper->insert($insert);
        }
    }


    if($_POST['todosGes'] == "1") {
        $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_ativo = 1";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $cpf = $fetch['col_cpf'];
            $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, gestor) VALUES ('$reu_id', '$cpf', 1)";
            $helper->insert($insert);
        }
    } else {
        foreach ($gestores as $g) {
            $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, gestor) VALUES ('$reu_id', '$g', 1)";
            $helper->insert($insert);
        }
    }

    if($_POST['todasMetas'] == "1") {
        $select = "SELECT okr_id as id FROM tbl_okr WHERE okr_concluida = 0";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $id = $fetch['id'];
            $insert = "INSERT INTO tbl_reuniao_okr (reu_id, okr_id) VALUES ('$reu_id', '$id')";
            $helper->insert($insert);
        }
    } else {
        foreach ($metas as $m) {
            $insert = "INSERT INTO tbl_reuniao_okr (reu_id, okr_id) VALUES ('$reu_id', '$m')";
            $helper->insert($insert);
        }
    }

    if($_POST['todosEventos'] == "1") {
        $select = "SELECT eve_id as id FROM tbl_evento WHERE eve_data_final >= NOW()";
        $query = $helper->select($select, 1);

        while($fetch = mysqli_fetch_assoc($query)) {
            $id = $fetch['id'];
            $insert = "INSERT INTO tbl_reuniao_evento (reu_id, eve_id) VALUES ('$reu_id', '$id')";
            $helper->insert($insert);
        }
    } else {
        foreach ($eventos as $e) {
            $insert = "INSERT INTO tbl_reuniao_evento (reu_id, eve_id) VALUES ('$reu_id', '$e')";
            $helper->insert($insert);
        }
    }

    $log = new LogAlteracao();
        $log->setDescricao("Criou a reunião ".$pauta." - ID ".$reu_id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $select = "SELECT DISTINCT(cpf) as cpf FROM tbl_reuniao_integrante WHERE reu_id = '$reu_id'";
    $query = $helper->select($select, 1);

    //ENVIAR MENSAGEM E E-MAIL
    while($f = mysqli_fetch_assoc($query)) { 
        $cpf = $f['cpf'];
        $mensagem = new Mensagem();
        $mensagem->setTitulo("Nova reunião");
        $mensagem->setTexto("Uma nova reunião foi criado e você foi adicionado como integrante. Cheque em Reuniões > Suas próximas reuniões");
        $mensagem->setDataExpiracao(date_format($data,"Y-m-d").' 23:59:59');
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
        $email->setAssunto("Nova reunião no Staffast");
        $empresa = $_SESSION['empresa']['nome'];
        $msg = '<h1 class="high-text">Uma nova reunião foi criada</h1>
                <h2 class="high-text">Olá! Uma nova reunião foi criada para a empresa '.$empresa.' e você foi adicionado como integrante.</h2>
                <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
        $email->setMensagem($msg);
        $email->enviar();
    }

    $_SESSION['msg'] = "Reunião criada com sucesso";
    header("Location: ../empresa/reunioes.php");
    die();

} else if (isset($_GET['editar'])) {

    $reu_id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($reu_id);
    $reu = $reu->retornarReuniao($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $reu->getCpfGestor() != $_SESSION['user']['cpf']) {
        include("../include/acessoNegado.php");
        die();
    }

    $data = $_POST['data'];
    $hora = $_POST['hora'];

    //Verificando data e hora da reunião com as alterações
    $notificar = false;
    if($hora != $reu->getHora() || $data != $reu->getData_format()) {
        $notificar = true;
    }

    $pauta = addslashes($_POST['pauta']);
    $local = addslashes($_POST['local']);
    $descricao = addslashes($_POST['descricao']);
    $objetivo = addslashes($_POST['objetivo']);
    $atingido = isset($_POST['atingido']) ? 1 : 0;

    $reu->setPauta($pauta);
    $reu->setDescricao($descricao);
    $reu->setLocal($local);
    $reu->setObjetivo($objetivo);
    $reu->setData($data);
    $reu->setHora($hora);
    $reu->setAtingido($atingido);
    if($reu->atualizar($_SESSION['empresa']['database'])) {
        if($notificar) {
            $reu = $reu->retornarReuniao($_SESSION['empresa']['database']);
            $msg = '<h1 class="high-text">Uma reunião que você é integrante sofreu alterações</h1>
                    <h2 class="high-text">Olá. A reunião '.$reu->getPauta().' na empresa '.$_SESSION['empresa']['nome'].', a qual você 
                    está inserido como integrante, sofreu alterações.</h2>
                    <h3 class="text">Data: '.$reu->getData().'</h3>
                    <h3 class="text">Hora: '.$reu->getHora().'</h3>
                    <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acessar para ver</button></a>
                    <h2 class="destaque-text">Por agora é só :D</h2>
                    <h5 class="text">Equipe do Staffast</h5>';
            $reu->notificarIntegrantes($_SESSION['empresa']['database'], 'Sua reunião foi alterada', $msg);

            $log = new LogAlteracao();
            $log->setDescricao("Editou a reunião ".$reu_id);
            $log->setIDUser($_SESSION['user']['usu_id']);
            $log->salvar();

            $_SESSION['msg'] = "Reunião atualizada com sucesso";
        }
    } else {
        $_SESSION['msg'] = "Houve um erro ao atualizar sua reunião";
    }

    header("Location: ../empresa/verReuniao.php?id=".$reu_id);
    die();

} else if (isset($_GET['confirmar'])) {

    $id = $_GET['id'];
    $cpf = $_SESSION['user']['cpf'];

    $update = "UPDATE tbl_reuniao_integrante SET confirmado = 1 WHERE reu_id = '$id' 
    AND cpf = '$cpf'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Confirmou presença em reunião - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Presença confirmada!";
    header("Location: ../empresa/reunioes.php");
    die();

} else if (isset($_GET['desconfirmar'])) {

    $id = $_GET['id'];
    $cpf = $_SESSION['user']['cpf'];

    $update = "UPDATE tbl_reuniao_integrante SET confirmado = 0 WHERE reu_id = '$id' 
    AND cpf = '$cpf'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Desconfirmou presença em reunião - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Presença desconfirmada!";
    header("Location: ../empresa/reunioes.php");
    die();

} else if (isset($_GET['concluir'])) {

    $id = $_GET['id'];
    $ata = addslashes($_POST['ata']);
    if($ata == "") $ata = "Não informada";
    $atingido = 0;

    if(isset($_POST['atingido']) && $_POST['atingido'] == "1") {
        $atingido = 1;
    }

    $update = "UPDATE tbl_reuniao SET reu_concluida = 1, 
    reu_objetivo_atingido = '$atingido',
    reu_ata = '$ata' WHERE reu_id = '$id'";
    $helper->update($update);

    $log = new LogAlteracao();
        $log->setDescricao("Concluiu reunião - ID ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = "Reunião concluída!";
    header("Location: ../empresa/verReuniao.php?id=".$id);
    die();

} else if (isset($_GET['addColaboradores'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $col) {
        $reu->adicionarColaborador($_SESSION['empresa']['database'], $col);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou colaboradores à reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores adicionados';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['addGestores'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $gestores = $_POST['gestores'];

    foreach($gestores as $ges) {
        $reu->adicionarGestor($_SESSION['empresa']['database'], $ges);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou gestores à reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores adicionados';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['rmvGestores'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $gestores = $_POST['gestores'];

    foreach($gestores as $ges) {
        $reu->removerGestor($_SESSION['empresa']['database'], $ges);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu gestores da reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Gestores removidos';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['rmvColaboradores'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $colaboradores = $_POST['colaboradores'];

    foreach($colaboradores as $col) {
        $reu->removerColaborador($_SESSION['empresa']['database'], $col);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu colaboradores da reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Colaboradores removidos';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['rmvMetas'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $metas = $_POST['metas'];

    foreach($metas as $m) {
        $reu->removerMeta($_SESSION['empresa']['database'], $m);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu metas da reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Metas removidas';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['addMetas'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $metas = $_POST['metas'];

    foreach($metas as $m) {
        $reu->adicionarMeta($_SESSION['empresa']['database'], $m);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou metas à reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Metas adicionadas';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['rmvEventos'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $eventos = $_POST['eventos'];

    foreach($eventos as $e) {
        $reu->removerEvento($_SESSION['empresa']['database'], $e);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Removeu eventos da reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Eventos removidos';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

} else if (isset($_GET['addEventos'])) {

    $id = $_POST['id'];

    $reu = new Reuniao();
    $reu->setID($id);

    $eventos = $_POST['eventos'];

    foreach($eventos as $e) {
        $reu->adicionarEvento($_SESSION['empresa']['database'], $e);
    }

    $log = new LogAlteracao();
        $log->setDescricao("Adicionou eventos à reunião ".$id);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

    $_SESSION['msg'] = 'Eventos adicionados';

    header('Location: ../empresa/verReuniao.php?id='.$id);
    die();

}


?>