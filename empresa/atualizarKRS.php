<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_key_result.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();
    $helper = new QueryHelper($conexao);

    $okr = new OKR();
    $okr->setID($_GET['okr']);
    $okr = $okr->retornarOKR($_SESSION['empresa']['database']);

    if($_SESSION['user']['cpf'] != $okr->getCpfGestor()) {
        include('../include/acessoNegado.php');
        die();
    }

    if(isset($_GET['atualizar'])) {
        $valor = $_POST['valor'];
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        
        $krs = new KeyResult();
        $krs->setID($_GET['krs']);
        $krs->setCurrent($valor);
        if($krs->upgrade($_SESSION['empresa']['database'])) {
            $_SESSION['msg'] = 'Key Result atualizada com sucesso';
            header('Location: verOKR.php?id='.$_GET['okr']);
            die();
        } else {
            $_SESSION['msg'] = 'Ocorreu um erro ao atualizar a Key Result';
            header('Location: verOKR.php?id='.$_GET['okr']);
            die();
        }
    }

    $krs = new KeyResult();
    $krs->setID($_GET['id']);
    $krs = $krs->retornarKeyResult($_SESSION['empresa']['database']);

    if($krs->getTipo() == 'Orçamento') {
        $meta = 'Meta (R$): '.number_format($krs->getGoal(), 2, ",", ".");
        $andamento = 'Andamento (R$): '.number_format($krs->getCurrent(), 2, ",", ".");
    } else {
        $meta = 'Meta: '.number_format($krs->getGoal(), 0, '', '');
        $andamento = 'Andamento: '.number_format($krs->getCurrent(), 0, '', '');
    } 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Atualizar Key Result</title>
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
            <li class="breadcrumb-item"><a href="metas.php">Metas OKR</a></li>
            <li class="breadcrumb-item"><a href="verOKR.php?id=<?php echo $okr->getID(); ?>">Meta OKR - <?php echo $okr->getTitulo(); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Atualizar <i>Key Result</i></li>
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
            <h4 class="high-text">Atualizar Key Result - <?php echo $krs->getTitulo(); ?> da meta OKR <?php echo $okr->getTitulo(); ?></h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">
</div>
<div class="container">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="text"><?php echo $meta; ?></h2>
        </div>
        <div class="col-sm">
            <h2 class="text"><?php echo $andamento; ?></h2>
        </div>
    </div>
    <div class="row" style="text-align: center; margin-top: 2em;">
        <div class="col-sm">
            <form action="atualizarKRS.php?atualizar=true&okr=<?php echo $okr->getID() ?>&krs=<?php echo $krs->getID() ?>" method="POST">
            <input type="text" name="valor" class="all-input" placeholder="Insira no formato inteiro (Ex: 150) ou monetário (Ex: 1.500,00)" required>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="submit" class="button button3" value="Atualizar o andamento do objetivo">
            </form>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <small class="text">LEMBRE-SE: este valor será <b>somado</b> ao valor do andamento atual. Por exemplo, se seu andamento atual é R$100,00 e você inserir R$120,00, o novo andamento constará como R$220,00.</small>
        </div>
    </div>
    
</div>
</body>
</html>