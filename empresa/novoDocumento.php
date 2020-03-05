<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $colaborador = new Colaborador();
    $gestor = new Gestor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo documento</title>
    <script>
        function mostraAviso(tipo) {
            var aviso = document.getElementById('aviso');

            if(tipo == 'Holerite' && aviso.style.display == 'none') {
                aviso.style.display = 'block';
            }
        }
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Novo <span class="destaque-text">documento</span></h2>
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
        <div class="col-sm-3">
        <form method="POST" action="../database/documentos.php?novo=true" id="form" enctype="multipart/form-data">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" class="all-input" maxlength="50" required="">
        </div>
        <div class="col-sm-3">
            <label for="tipo" class="text">Tipo *</label>
            <select name="tipo" id="tipo" class="all-input" required onchange="mostraAviso(this.value);">
                <option value="Geral">Documento geral</option>
                <option value="Holerite">Holerite</option>
                <option value="Notificação">Notificação</option>
                <option value="Apresentação/Proposta">Apresentação / Proposta</option>
                <option value="Outro">Outro</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label for="donos" class="text">Visível para *</label>
            <select name="donos[]" id="donos[]" class="all-input" multiple required size="12">
                <option value="" disabled>---- GESTORES ----</option>
                <?php
                $gestor->popularSelect($_SESSION['empresa']['database'], true);
                ?>
                <option value="" disabled>---- COLABORADORES ----</option>
                <?php
                $colaborador->popularSelect($_SESSION['empresa']['database'], true);
                ?>
            </select>
            <small class="text">Mantenha <b>CTRL</b> pressionado para selecionar mais de um</small>
            <small class="text" id="aviso" style="display: none;">Lembre-se: holerites só podem ser atribuídos a um funcionário.</small>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <label for="documento" class="text">Documento *</label>
            <input type="file" name="documento" id="documento" required class="button button3">
        </div>
    </div>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="Enviar" class="button button2">
        </div>
    </div>
    </form>
</div>
</body>
</html>