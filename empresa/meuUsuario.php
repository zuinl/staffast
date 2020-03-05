<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_usuario.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_email.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    $cpf = $_SESSION['user']['cpf'];

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);
    
    $usuario = new Usuario();
    $usuario->setID($_SESSION['user']['usu_id']);
    $usuario = $usuario->retornarUsuario();

    $select = "SELECT ges_cpf as cpf FROM tbl_gestor WHERE ges_cpf = '$cpf'";
    $query = $helper->select($select, 1);

    $isGestor = false;
    if(mysqli_num_rows($query) > 0) {
        $isGestor = true;

        $gestor = new Gestor();
        $gestor->setCpf($_SESSION['user']['cpf']);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
    } else {
        $isGestor = false;
    }


    $select = "SELECT col_cpf as cpf FROM tbl_colaborador WHERE col_cpf = '$cpf'";
    $query = $helper->select($select, 1);

    $isCol = false;
    if(mysqli_num_rows($query) > 0) {
        $isCol = true;

        $colaborador = new Colaborador();
        $colaborador->setCpf($_SESSION['user']['cpf']);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
    } else {
        $isCol = false;
    }

if(isset($_GET['senha'])) {

    $usu_id = $_SESSION['user']['usu_id'];
    $senhaAtual = $_POST['senhaAtual'];
    $novaSenha = $_POST['novaSenha'];
    $confereSenha = $_POST['confereSenha'];

    if($novaSenha != $confereSenha) {
        $_SESSION['msg'] = 'As senhas não conferem';
        header('Location: meuUsuario.php');
        die();
    } else {
        $usuario = new Usuario();
        $usuario->setID($usu_id);

        if(!$usuario->conferirSenhaAtual($senhaAtual)) {
            $_SESSION['msg'] = 'A senha atual não confere';
            header('Location: meuUsuario.php');
            die();
        } else {
            $usuario->setSenha($novaSenha);
            
            if($usuario->atualizarSenha()) {
                $usuario = $usuario->retornarUsuario();
                $email = new Email();
                $email->setEmailFrom(0);
                $email->setEmailTo($usuario->getEmail());
                $email->setAssunto("Sua senha foi alterada no Staffast");
                $msg = '<h1 class="high-text">Olá, você alterou sua senha?</h1>
                    <h2 class="high-text">Detectamos que sua senha foi alterada no portal do Staffast</h2>
                    <h3 class="text">Se foi você, não há nada que precise fazer. Mas se você não alterou sua senha, 
                    recupere-a agora para sua segurança.</h3>
                    <a href="https://sistemastaffast.com/staffast/recuperarSenha.php" target="blank_"><button class="button button3">Eu não mudei minha senha</button></a>
                    <h2 class="destaque-text">Por agora é só :D</h2>
                    <h5 class="text">Equipe do Staffast</h5>';
                $email->setMensagem($msg);
                $email->enviar();

                $_SESSION['msg'] = 'Senha atualizada';
            } else {
                $_SESSION['msg'] = 'Erro ao atualizar a senha';
            }
        }
    }  
    unset($_GET);
    header('Location: meuUsuario.php');
    die();
}

if(isset($_GET['email'])) {

    $usu_id = $_SESSION['user']['usu_id'];
    $novoEmail = $_POST['email'];

    $usuario = new Usuario();

    $usuario->setID($usu_id);
    $usuario = $usuario->retornarUsuario();
    $oldEmail = $usuario->getEmail();
    $usuario->setEmail($novoEmail);
    $email = new Email();
    $email->setEmailFrom(0);
    $email->setEmailTo($oldEmail);
    $email->setAssunto("Seu e-mail foi alterado no Staffast");
    $msg = '<h1 class="high-text">Olá, você alterou seu e-mail?</h1>
                <h2 class="high-text">Detectamos que seu e-mail de acesso foi alterada no portal do Staffast</h2>
                <h3 class="text">Se foi você, não há nada que precise fazer. Mas se você não alterou seu e-mail, 
                você vai precisar entrar em contato com o suporte para sua segurança.</h3>
                <h2 class="high-text">Novo e-mail: '.$novoEmail.'</h2>
                <a href="https://sistemastaffast.com/staffast/suporte/" target="blank_"><button class="button button3">Eu não mudei meu e-mail</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
    $email->setMensagem($msg);
    $email->enviar();

    $email->setEmailTo($novoEmail);
    $email->setAssunto("Novo e-mail cadastrado no Staffast");
    $msg = '<h1 class="high-text">Olá, você solicitou a migração do e-mail</h1>
                <h2 class="high-text">A partir de agora, este é seu e-mail no Staffast</h2>
                <h3 class="text">Use este e-mail e sua senha para acessar o Staffast</h3>
                <a href="https://sistemastaffast.com/staffast" target="blank_"><button class="button button3">Acessar Staffast</button></a>
                <h2 class="destaque-text">Por agora é só :D</h2>
                <h5 class="text">Equipe do Staffast</h5>';
    $email->setMensagem($msg);
    $email->enviar();

    if($usuario->atualizarEmail()) {
        $_SESSION['msg'] = 'E-mail atualizado';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar o e-mail. Talvez este e-mail já esteja cadastrado em outra empresa do Staffast. Se o problema persistir, contate o suporte';
        header('Location: meuUsuario.php');
        die();
    }
        unset($_GET);
        header('Location: meuUsuario.php');
        die();

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sua conta - Staffast</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php
    }
    ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Olá, <i><span class="destaque-text"><?php echo $_SESSION['user']['primeiro_nome']; ?></span></i></h2>
        </div>
    </div>

    <hr class="hr-divide">

</div>
<div class="container">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Estes são seus dados de acesso ao Staffast</h3>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="text">Seu <i>e-mail</i></h5>
            <h6 class="text"><?php echo $usuario->getEmail();; ?></h6>
        </div>
        <div class="col-sm">
            <h5 class="text">Sua <i>senha</i></h5>
            <span class="text"><h6>Alterada pela última vez em: <i><?php echo $usuario->getUltimaAlteracao(); ?></i></h6></span>
        </div>
        <div class="col-sm">
            <h5 class="text">Sua <i>empresa</i></h5>
            <h6 class="text"><?php echo $_SESSION['empresa']['nome']; ?></h6>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="text">Atualizar <i>senha</i></h5>
        </div>
        <div class="col-sm">
            <form action="meuUsuario.php?senha=true" method="POST">
            <input type="password" name="senhaAtual" id="senhaAtual" class="all-input" placeholder="Insira senha atual">
        </div>
        <div class="col-sm">
            <input type="password" name="novaSenha" id="novaSenha" class="all-input" placeholder="Insira nova senha">
        </div>
        <div class="col-sm">
            <input type="password" name="confereSenha" id="confereSenha" class="all-input" placeholder="Repita nova senha">
        </div>
        <div class="col-sm">
            <input type="submit" class="button button1" value="Alterar senha">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm-3">
            <h5 class="text">Atualizar <i>e-mail</i></h5>
        </div>
        <div class="col-sm-3">
            <form action="meuUsuario.php?email=true" method="POST">
            <input type="email" name="email" id="email" class="all-input" placeholder="Insira novo e-mail">
        </div>
        <div class="col-sm-2">
            <input type="submit" class="button button1" value="Alterar senha">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <?php if($isGestor) { ?>
    <div class="row">
        <div class="col-sm">
            <h5 class="text">Você está cadastrado como <i>gestor</i> no Staffast</h5>
        </div>
        <div class="col-sm">
            <h5 class="text">Desde <?php echo $gestor->getdataCadastro(); ?></h5>
        </div>
        <div class="col-sm">
            <a href="perfilGestor.php?id=<?php echo base64_encode($gestor->getCpf()); ?>"><button class="button button3">Ver meu perfil</button></a>
        </div>
        <div class="col-sm">
            <a href="novoGestor.php?editar=<?php echo base64_encode($gestor->getCpf()); ?>"><button class="button button1">Editar meus dados</button></a>
        </div>
    </div>
    <?php }
    if ($isCol) { ?>
    <hr class="hr-divide-super-light">
    <div class="row">
        <div class="col-sm">
            <h5 class="text">Você está cadastrado como <i>colaborador</i> no Staffast</h5>
        </div>
        <div class="col-sm">
            <h5 class="text">Desde <?php echo $colaborador->getdataCadastro(); ?></h5>
        </div>
        <div class="col-sm">
            <a href="perfilColaborador.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button3">Ver meu perfil</button></a>
        </div>
        <div class="col-sm">
            <a href="novoColaborador.php?editar=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button1">Editar meus dados</button></a>
        </div>
    </div>
    <?php } ?>
    
</div>
</body>
</html>