<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_reuniao.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();
    $helper = new QueryHelper($conexao);

    $reu = new Reuniao();
    $reu->setID($_GET['id']);
    $reu = $reu->retornarReuniao($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['cpf'] != $reu->getCpfGestor()) {
        include('../include/acessoNegado.php');
        die();
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Atualizar Reunião</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container">
    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-sm">
            <h2 class="high-text">Atualizar Reunião <?php echo $reu->getPauta(); ?></h2>
        </div>
    </div>
    <hr class="hr-divide">
    <div class="row">
        <div class="col-sm">
            <h4 class="text">Objetivo da reunião: <?php echo $reu->getObjetivo(); ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <form action="../database/reuniao.php?concluir=true&id=<?php echo $reu->getID() ?>" method="POST">
            <input type="checkbox" name="atingido" value="1"> O objetivo foi atingido?
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label class="text">Ata de encerramento</label>
            <textarea class="all-input" name="ata" id="ata" rows="6" maxlength="1000" required></textarea>
            <small class="text">A ata será visível para todos os integrantes</small>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <input type="submit" class="button button3" value="Concluir reunião">
            </form>
        </div>
    </div>
    
</div>
</body>
</html>