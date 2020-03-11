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
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item"><a href="reunioes.php">Reuniões</a></li>
            <li class="breadcrumb-item"><a href="reunioes.php">Reunião - <?php echo $reu->getPauta(); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Concluir reunião</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
    ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text">Atualizar reunião: <?php echo $reu->getPauta(); ?></h3>
        </div>
    </div>

    <hr class="hr-divide">
</div>

<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <h6 class="text"><b>Objetivo da reunião:</b> <?php echo $reu->getObjetivo(); ?></h6>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <form action="../database/reuniao.php?concluir=true&id=<?php echo $reu->getID() ?>" method="POST">
            <input type="checkbox" name="atingido" value="1"> O objetivo foi atingido
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