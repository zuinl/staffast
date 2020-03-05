<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once("../classes/class_processo_seletivo.php");

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $ps = new ProcessoSeletivo();

    $dataEncerramento = date_create(date("Y-m-d"));
    date_add($dataEncerramento,date_interval_create_from_date_string("7 days"));

    if(isset($_GET['editar'])) {
        $ps->setID($_GET['ps']);
        $ps = $ps->retornarProcessoSeletivo($_SESSION['empresa']['database']);
        if($ps->isEncerrado($_SESSION['empresa']['database'])) {
            include("../include/acessoNegado.php");
            die();
        }
        $dataEncerramento = date_create($ps->getDataEncerramento_format());
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($_GET['editar'])) { echo 'Edição de'; } else { echo 'Novo'; } ?> processo seletivo</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text"><?php if(isset($_GET['editar'])) { echo 'Edição'; } else { echo 'Criação'; } ?> de <span class="destaque-text">processo seletivo</span></h2>
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
        <div class="col-sm">
        <form method="POST" <?php if(isset($_GET['editar'])) { echo 'action="../database/processo_seletivo.php?editar=true"'; } else { echo 'action="novasPerguntas.php"'; } ?>  action="novasPerguntas.php" id="form">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $ps->getTitulo(); ?>" class="all-input" maxlength="80" required="">
        </div>
        <div class="col-sm">
            <label for="descricao" class="text">Descrição *</label>
            <textarea class="all-input" name="descricao" id="descricao" required maxlength="800"><?php echo $ps->getDescricao(); ?></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="vagas" class="text">Número de vagas disponíveis *</label>
            <input type="number" name="vagas" id="vagas" value="<?php echo $ps->getVagas(); ?>" class="all-input" required="">
        </div>
        <div class="col-sm">
            <label for="encerramento" class="text">Data de encerramento *</label>
            <input type="date" name="encerramento" id="encerramento" value="<?php echo date_format($dataEncerramento,"Y-m-d"); ?>" class="all-input">
        </div>
    </div>
    <?php if(!isset($_GET['editar'])) { ?>
    <div class="row">
        <div class="col-sm" id="perguntas_div">
            <label for="perguntas" class="text">Número de perguntas</label>
            <input type="number" name="perguntas" id="perguntas" max="10" min="0" class="all-input" required>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" name="sem_perguntas" value="1" onclick="document.getElementById('perguntas').value = 0"> Não adicionar perguntas
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h6 class="text">O Staffast já vai coletar as informações pessoais e o currículo do candidato. As perguntas que você criar são com a finalidade de avaliar as competências.</h5>
            <h6 class="text">Você só poderá selecionar o número de perguntas uma vez e não poderá alterá-las depois.</h6>
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <?php if(isset($_GET['editar'])) { ?> <input type="hidden" name="ps" value="<?php echo $ps->getID(); ?>"> <?php } ?>
            <input type="submit" <?php if(isset($_GET['editar'])) { echo 'value="Salvar alterações"'; } else { echo 'value="Continuar"'; } ?> class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form> 
</div>
</body>
</html>