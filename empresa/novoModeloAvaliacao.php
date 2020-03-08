<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once '../classes/class_modelo_avaliacao.php';

    if($_SESSION['user']['permissao'] != 'GESTOR-1') {
        include('../include/acessoNegado.php');
        die();
    }

    $modelo = new ModeloAvaliacao();

    if(isset($_GET['editar'])) {
        $modelo->setID($_GET['id']);
        $modelo = $modelo->retornarModeloAvaliacao($_SESSION['empresa']['database']);
        $titulo = "Editar";
        $button = "Salvar alterações";
        $acao = "editar";
    } else {
        $titulo = "Novo";
        $button = "Salvar modelo";
        $acao = "novo";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo; ?> modelo de Avaliação</title>    
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="verModelosAvaliacao.php">Modelos de Avaliação</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?> Modelo de Avaliação</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?php echo $titulo; ?> Modelo de Avaliação</h2>
        </div>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
		</div>
        <?php
    }
    ?>
</div>
<div class="container">
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
        <form method="POST" action="../database/modeloAvaliacao.php?<?php echo $acao; ?>=true" id="form">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $modelo->getTitulo(); ?>" class="all-input" maxlength="60" required placeholder="Vendas, Marketing, Estágio, Atendimento...">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Insira as competências</h4>
            <small class="text">Insira ao menos as 4 primeiras competências</small>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 1 *</label>
            <input type="text" name="um" id="um" value="<?php echo $modelo->getUm(); ?>" class="all-input" maxlength="35" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 2 *</label>
            <input type="text" name="dois" id="dois" value="<?php echo $modelo->getDois(); ?>" class="all-input" maxlength="35" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 3 *</label>
            <input type="text" name="tres" id="tres" value="<?php echo $modelo->getTres(); ?>" class="all-input" maxlength="35" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 4 *</label>
            <input type="text" name="quatro" id="quatro" value="<?php echo $modelo->getQuatro(); ?>" class="all-input" maxlength="35" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 5</label>
            <input type="text" name="cinco" id="cinco" value="<?php echo $modelo->getCinco(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 6</label>
            <input type="text" name="seis" id="seis" value="<?php echo $modelo->getSeis(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 7</label>
            <input type="text" name="sete" id="sete" value="<?php echo $modelo->getSete(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 8</label>
            <input type="text" name="oito" id="oito" value="<?php echo $modelo->getOito(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 9</label>
            <input type="text" name="nove" id="nove" value="<?php echo $modelo->getNove(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 10</label>
            <input type="text" name="dez" id="dez" value="<?php echo $modelo->getDez(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 11</label>
            <input type="text" name="onze" id="onze" value="<?php echo $modelo->getOnze(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 12</label>
            <input type="text" name="doze" id="doze" value="<?php echo $modelo->getDoze(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 13</label>
            <input type="text" name="treze" id="treze" value="<?php echo $modelo->getTreze(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 14</label>
            <input type="text" name="quatorze" id="quatorze" value="<?php echo $modelo->getQuatorze(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 15</label>
            <input type="text" name="quinze" id="quinze" value="<?php echo $modelo->getQuinze(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 16</label>
            <input type="text" name="dezesseis" id="dezesseis" value="<?php echo $modelo->getDezesseis(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 17</label>
            <input type="text" name="dezessete" id="dezessete" value="<?php echo $modelo->getDezessete(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 18</label>
            <input type="text" name="dezoito" id="dezoito" value="<?php echo $modelo->getDezoito(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 19</label>
            <input type="text" name="dezenove" id="dezenove" value="<?php echo $modelo->getDezenove(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Competência 20</label>
            <input type="text" name="vinte" id="vinte" value="<?php echo $modelo->getVinte(); ?>" class="all-input" maxlength="35">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em; text-align: center;">
        <div class="col-sm">
            <small class="text">Os modelos de avaliação ficam disponíveis para todos os gestores utilizarem com os colaboradores.</small>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <?php if(isset($_GET['editar'])) { ?> <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"> <?php } ?>
            <input type="submit" value="<?php echo $button; ?>" id="btnSalvar" class="button button1">
        </div>
        <div class="col-sm">
            <input type="reset" value="Limpar" id="btnLimpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>

</html>