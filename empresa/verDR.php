<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_conexao_empresa.php');
    include('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>DR</title>    
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text">FUNCIONÁRIO vs. FUNCIONÁRIO</h3>
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
</div>
<div class="container">
    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">CARGO</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">CARGO</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">SETORES INSERIDOS</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">SETORES INSERIDOS</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">MÉDIA GERAL</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">MÉDIA GERAL</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">ÚLTIMAS 3 AVALIAÇÕES (gráficos de média geral e links para visualização)</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">ÚLTIMAS 3 AVALIAÇÕES</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">ÚLTIMAS 3 AUTOAVALIAÇÕES (gráficos de média geral e links para visualização)</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">ÚLTIMAS 3 AUTOAVALIAÇÕES</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">ÚLTIMAS 3 REUNIÕES</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">ÚLTIMAS 3 REUNIÕES</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">PRÓXIMAS REUNIÕES</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">PRÓXIMAS REUNIÕES</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">METAS PARTICIPANTES</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">METAS PARTICIPANTES</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <h5 class="text">PLANOS DE DESENVOLVIMENTO INDIVIDUAL</h5>
        </div>

        <div class="col-sm" style="text-align: left;">
            <h5 class="text">PLANOS DE DESENVOLVIMENTO INDIVIDUAL</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="text-align: right;">
            <label class="text">Comentários de FUNCIONÁRIO</label>
            <textarea name="comentarios_1" id="comentarios_1" class="all-input" maxlength="300"></textarea>
        </div>

        <div class="col-sm" style="text-align: left;">
            <label class="text">Comentários de FUNCIONÁRIO</label>
            <textarea name="comentarios_2" id="comentarios_2" class="all-input" maxlength="300"></textarea>
        </div>
    </div>
</div>
</body>
</html>