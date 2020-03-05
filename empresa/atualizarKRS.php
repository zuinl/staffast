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
        $meta = 'Meta: '.$krs->getGoal();
        $andamento = 'Andamento: '.$krs->getCurrent();
    } 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Atualizar Key Result</title>
</head>
<body>
<?php
    //include('../include/navbar.php');
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
    <div class="row">
        <div class="col-sm">
            <h1 class="high-text">Atualizar Key Result <?php echo $krs->getTitulo(); ?> da meta OKR <?php echo $okr->getTitulo(); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h2 class="text"><?php echo $meta; ?></h2>
        </div>
        <div class="col-sm">
            <h2 class="text"><?php echo $andamento; ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <form action="atualizarKRS.php?atualizar=true&okr=<?php echo $okr->getID() ?>&krs=<?php echo $krs->getID() ?>" method="POST">
            <input type="text" name="valor" class="all-input" placeholder="Insira no formato inteiro (Ex: 150) ou monetário (Ex: 1.500,00)">
            <h5 class="text">LEMBRE-SE: este valor será <b>somado</b> ao valor do andamento atual. Por exemplo, se seu andamento atual é R$100,00 e você inserir R$120,00, o novo andamento constará como R$220,00.</h5>
        </div>
        <div class="col-sm">
            <input type="submit" class="button button3" value="Atualizar">
            </form>
        </div>
    </div>
    
</div>
</body>
</html>