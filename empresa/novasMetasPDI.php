<?php
    include('../include/auth.php');
    include('../src/meta.php');

    if(!isset($_POST)) die("Erro");
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novas metas para as competências - PDI</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Criação de <span class="destaque-text">metas para as competências</span> para o PDI</h2>
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
            <li class="breadcrumb-item active" aria-current="page">Novas competências</li>
            <li class="breadcrumb-item active" aria-current="page">Novas metas para as competências para <?php echo $_POST['titulo'] ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->
</div>
<div class="container">
    <form action="../database/pdi.php?novo=true" method="POST">
<?php
for($i = 1; $i <= $_POST['competencias']; $i++) {
    $competencia = $_POST['competencia_'.$i];
    $metas = $_POST['numero_'.$i];

    ?>

    <table class="table-site">
        <thead>
            <tr>
                <th>Competência</th>
                <th>Meta</th>
            </tr>
        </thead>

    <?php

    for($j = 1; $j <= $metas; $j++) {
?>
    <tr>
        <td><b><?php echo $competencia; ?></b></td>
        <input name="numMetas_<?php echo $i; ?>" value="<?php echo $metas; ?>" type="hidden">
        <input name="competencia_<?php echo $i; ?>" value="<?php echo $competencia; ?>" type="hidden">
        <td><input name="competencia_<?php echo $i; ?>&meta_<?php echo $j; ?>" type="text" class="all-input" placeholder="Meta <?php echo $j; ?>" maxlength="120" required></td>        
    </tr>
<?php
    }
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
            <input type="submit" value="Salvar PDI" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1" onclick="">
        </div>
    </div>
</form>