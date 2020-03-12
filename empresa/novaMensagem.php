<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_setor.php');
    include('../classes/class_colaborador.php');

    $colaborador = new Colaborador();
    $gestor = new Gestor();
    $setor = new Setor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova mensagem</title>
    <script>
        function selectAllGes(source) {
		    checkboxes = document.getElementsByName('gestores[]');
		    for(var i in checkboxes)
			checkboxes[i].checked = source.checked;
	    }
        function selectAllCols(source) {
		    checkboxes = document.getElementsByName('colaboradores[]');
		    for(var i in checkboxes)
			checkboxes[i].checked = source.checked;
	    }
        function selectAllSets(source) {
		    checkboxes = document.getElementsByName('setores[]');
		    for(var i in checkboxes)
			checkboxes[i].checked = source.checked;
	    }
    </script>
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
            <li class="breadcrumb-item active" aria-current="page">Nova mensagem</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Nova mensagem</h2>
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
    <div class="row">
        <div class="col-sm">
        <form method="POST" action="../database/mensagem.php?nova=true" id="form">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" class="all-input" maxlength="50" required="">
        </div>
        <div class="col-sm">
            <label for="data" class="text">Exibida até *</label>
            <input type="date" name="data" id="data" class="all-input" required="">
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="texto" class="text">Texto *</label>
            <textarea name="texto" id="texto" class="all-input" maxlength="1200" required=""></textarea>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <label for="colaboradores" class="text">Visível para os seguintes colaboradores</label>
            <div class="div-checkboxes">
                <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm">
            <label for="gestores" class="text">Visível para os seguintes gestores</label>
            <div class="div-checkboxes">
                <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <div class="col-sm">
            <label for="setores" class="text">Visível para os seguintes setores</label>
            <div class="div-checkboxes">
                <?php $setor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="col-sm">
            <input type="checkbox" name="todosCols" value="1" onclick="selectAllCols(this)"> Direcionar a todos os colaboradores
        </div>
        <div class="col-sm">
            <input type="checkbox" name="todosGes" value="1" onclick="selectAllGes(this)"> Direcionar a todos os gestores
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <div class="col-sm">
            <input type="checkbox" name="todosSet" value="1" onclick="selectAllSets(this)"> Direcionar a todos os setores
        </div>
        <?php } ?>
    </div>

    <div class="row" style="text-align: center; margin-top: 1em;">
        <div class="col-sm">
            <h6 class="text">As mensagens serão exibidas para todos os direcionados na tela inicial</h6>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="submit" value="Cadastrar" class="button button2">
        </div>
        <div class="col-sm">
            <input type="reset" value="Limpar" class="button button2">
        </div>
    </div>
    </form>
</div>
</body>
</html>