<?php
    include('../include/auth.php');
    include('../src/meta.php');

    if(!isset($_POST['competencias'])) die("Erro");
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novas competências - PDI</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Criação de <span class="destaque-text">competências</span> para o PDI</h2>
        </div>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm-6">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            </div>
		</div>
        <?php
    }
?>

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="PDIs.php">Planos de Desenvolvimento Individual (PDIs)</a></li>
            <li class="breadcrumb-item active" aria-current="page">Novo PDI</li>
            <li class="breadcrumb-item active" aria-current="page">Novas competências para <?php echo $_POST['titulo'] ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->
</div>
<div class="container">
<table class="table-site">
    <thead>
        <tr>
            <th>Nº competência</th>
            <th>Competência a ser desenvolvida</th>
            <th>Quantas metas a competência vai ter?</th>
        </tr>
    </thead>
    <form action="novasMetasPDI.php" method="POST">
<?php
for($i = 1; $i <= $_POST['competencias']; $i++) {
?>
    <tr>
        <td><b><?php echo $i; ?></b></td>
        <td><input name="competencia_<?php echo $i; ?>" type="text" class="all-input" placeholder="Competência <?php echo $i; ?>" maxlength="120" required></td>
        
        <td><input name="numero_<?php echo $i; ?>" type="number" class="all-input" required></td>
    </tr>
<?php
}
?>
</table>
<hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-3 offset-sm-3">
            <input type="hidden" name="titulo" value="<?php echo $_POST['titulo']; ?>">
            <input type="hidden" name="competencias" value="<?php echo $_POST['competencias']; ?>">
            <input type="hidden" name="prazo" value="<?php echo $_POST['prazo']; ?>">
            <input type="hidden" name="dono" value="<?php echo $_POST['dono']; ?>">
            <input type="hidden" name="orientador" value="<?php echo $_POST['orientador']; ?>">
            <input type="submit" value="Prosseguir para metas" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button2" onclick="">
        </div>
    </div>
</form>