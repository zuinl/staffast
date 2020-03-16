<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_okr.php');
    include('../classes/class_evento.php');
    include('../classes/class_reuniao.php');
    include('../classes/class_colaborador.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $reuniao = new Reuniao();

    if(isset($_GET['editar'])) {
        $reuniao->setID($_GET['id']);
        $reuniao = $reuniao->retornarReuniao($_SESSION['empresa']['database']);
    }

    $colaborador = new Colaborador();
    $gestor = new Gestor();
    $metas = new OKR();
    $eventos = new Evento();
?>
<!DOCTYPE html>
<html>
<head> 
    <title><?= isset($_GET['editar']) ? 'Editar reunião' : 'Nova reunião'; ?></title>
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
        function selectAllMetas(source) {
		    checkboxes = document.getElementsByName('metas[]');
		    for(var i in checkboxes)
			checkboxes[i].checked = source.checked;
	    }
        function selectAllEventos(source) {
		    checkboxes = document.getElementsByName('eventos[]');
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
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= isset($_GET['editar']) ? 'Editar' : 'Nova'; ?> reunião</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?= isset($_GET['editar']) ? 'Editar' : 'Nova'; ?> reunião</h2>
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
        <form method="POST" action="<?= isset($_GET['editar']) ? '../database/reuniao.php?editar=true' : '../database/reuniao.php?nova=true'; ?>" id="form">
            <label for="pauta" class="text">Pauta *</label>
            <input type="text" name="pauta" id="pauta" value="<?php echo $reuniao->getPauta(); ?>" class="all-input" maxlength="100" required>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="descricao" class="text">Descrição da reunião *</label>
            <textarea name="descricao" id="descricao" class="all-input" maxlength="300" required><?php echo $reuniao->getDescricao(); ?></textarea>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="objetivo" class="text">Objetivo da reunião *</label>
            <textarea name="objetivo" id="objetivo" class="all-input" maxlength="300" required><?php echo $reuniao->getObjetivo(); ?></textarea>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="local" class="text">Local</label>
            <input type="text" name="local" id="local" value="<?php echo $reuniao->getLocal(); ?>" class="all-input" maxlength="150">
        </div>
    </div>
    <?php if(isset($_GET['editar'])) { ?>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" class="text">
            <input type="checkbox" name="atingido" id="atingido" value="1" <?php if($reuniao->getAtingido() == 1) echo 'checked'; ?>> Objetivo atingido
        </div>
    </div>
    <?php } ?>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Data *</label>
            <input type="date" name="data" id="data" value="<?php echo $reuniao->getData_format(); ?>" class="all-input" required>
        </div>  
        <div class="col-sm">
            <label class="text">Hora *</label>
            <input type="time" name="hora" id="hora" value="<?php echo $reuniao->getHora(); ?>" class="all-input" required>
        </div> 
    </div>

    <?php if(!isset($_GET['editar'])) { ?>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm-3">
            <label for="colaboradores" class="text">Os seguintes colaboradores participarão...</label>
            <div class="div-checkboxes">
                <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm-3">
            <label for="gestores" class="text">... e os seguintes gestores...</label>
            <div class="div-checkboxes">
                <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm-3">
            <label for="metas" class="text">... e as seguintes metas...</label>
            <div class="div-checkboxes">
                <?php $metas->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <div class="col-sm-3">
            <label for="eventos" class="text">... e os seguintes eventos...</label>
            <div class="div-checkboxes">
                <?php $eventos->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>

    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm-3">
            <input type="checkbox" name="todosCols" value="1" onclick="selectAllCols(this)"> Direcionar a todos os colaboradores
        </div>
        <div class="col-sm-3">
            <input type="checkbox" name="todosGes" value="1" onclick="selectAllGes(this)"> Direcionar a todos os gestores
        </div>
        <div class="col-sm-3">
            <input type="checkbox" name="todasMetas" value="1" onclick="selectAllMetas(this)"> Direcionar a todas as metas
        </div>
        <div class="col-sm-3">
            <input type="checkbox" name="todosEventos" value="1" onclick="selectAllEventos(this)"> Direcionar a todos os eventos
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-light">

    <div class="row">
        <?php if(isset($_GET['editar'])) { ?>
            <input type="hidden" name="id" id="id" value="<?php echo $reuniao->getID(); ?>">
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