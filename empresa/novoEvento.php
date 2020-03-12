<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_setor.php');
    include('../classes/class_evento.php');
    include('../classes/class_colaborador.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $evento = new Evento();

    if(isset($_GET['editar'])) {
        $evento = new Evento();
        $evento->setID($_GET['id']);
        $evento = $evento->retornarEvento($_SESSION['empresa']['database']);
    }

    $colaborador = new Colaborador();
    $gestor = new Gestor();
    $setor = new Setor();
?>
<!DOCTYPE html>
<html>
<head> 
    <title><?= isset($_GET['editar']) ? 'Editar evento' : 'Novo evento'; ?></title>
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
            <li class="breadcrumb-item"><a href="eventos.php">Próximos eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= isset($_GET['editar']) ? 'Editar' : 'Novo'; ?> evento</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?= isset($_GET['editar']) ? 'Editar' : 'Novo'; ?> evento</h2>
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
        <form method="POST" action="<?= isset($_GET['editar']) ? '../database/evento.php?editar=true' : '../database/evento.php?novo=true'; ?>" id="form">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $evento->getTitulo(); ?>" class="all-input" maxlength="70" required>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="descricao" class="text">Descrição do evento *</label>
            <textarea name="descricao" id="descricao" class="all-input" maxlength="300" required><?php echo $evento->getDescricao(); ?></textarea>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" class="text">
            <label>Local / descrição do local</label>
            <input type="text" name="local" id="local" value="<?php echo $evento->getLocal(); ?>" class="all-input" maxlength="150">
            <input type="checkbox" name="isNaEmpresa" id="isNaEmpresa" value="1" <?php if($evento->getIsNaEmpresa() == 1) echo 'checked';?>> O evento será na empresa
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Data de início *</label>
            <input type="date" name="dataI" id="dataI" value="<?php echo $evento->getDataI_format(); ?>" class="all-input" onblur="document.getElementById('dataF').value = this.value" required>
        </div>  
        <div class="col-sm">
            <label class="text">Hora de início *</label>
            <input type="time" name="horaI" id="horaI" value="<?php echo $evento->getHoraI(); ?>" class="all-input" required>
        </div> 
        <div class="col-sm">
            <label class="text">Data final</label>
            <input type="date" name="dataF" id="dataF" value="<?php echo $evento->getDataF_format(); ?>" class="all-input">
        </div>  
        <div class="col-sm">
            <label class="text">Hora de final</label>
            <input type="time" name="horaF" id="horaF" value="<?php echo $evento->getHoraF(); ?>" class="all-input" required>
        </div> 
    </div>

    <?php if(!isset($_GET['editar'])) { ?>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm-4">
            <label for="colaboradores" class="text">Os seguintes colaboradores participarão...</label>
            <div class="div-checkboxes">
                <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm-4">
            <label for="gestores" class="text">... e os seguintes gestores...</label>
            <div class="div-checkboxes">
                <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm-4">
            <label for="setores" class="text">... e estes setores</label>
            <div class="div-checkboxes">
                <?php $setor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm-4">
            <input type="checkbox" name="todosCols" value="1" onclick="selectAllCols(this)"> Direcionar a todos os colaboradores
        </div>
        <div class="col-sm-4">
            <input type="checkbox" name="todosGes" value="1" onclick="selectAllGes(this)"> Direcionar a todos os gestores
        </div>
        <div class="col-sm-4">
            <input type="checkbox" name="todosSet" value="1" onclick="selectAllSets(this)"> Direcionar a todos os setores
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-light">

    <div class="row">
        <?php if(isset($_GET['editar'])) { ?>
            <input type="hidden" name="id" id="id" value="<?php echo $evento->getID(); ?>">
        <?php } ?>
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="<?= isset($_GET['editar']) ? 'Salvar alterações' : 'Criar' ?>" class="button button1">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>
</html>