<?php
    date_default_timezone_set('America/Sao_Paulo');
    include('../include/auth.php');
    include('../src/meta.php');
    require_once '../classes/class_modelo_avaliacao.php';
    require_once '../classes/class_gestor.php';
    require_once '../classes/class_colaborador.php';

    if($_SESSION['user']['permissao'] != 'GESTOR-1' && $_SESSION['user']['permissao'] != 'GESTOR-2') {
        include('../include/acessoNegado.php');
        die();
    }

    $modelo = new ModeloAvaliacao();
    $gestor = new Gestor();
    $colaborador = new Colaborador();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Agendamento de Avaliação</title>    
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
            <li class="breadcrumb-item"><a href="agendamentosAvaliacao.php">Agendamentos de avaliações</a></li>
            <li class="breadcrumb-item active" aria-current="page">Novo agendamento de avaliação</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Novo agendamento de avaliação</h2>
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
        <form method="POST" action="../database/agendamento.php?novo=true" id="form">
            <label for="titulo" class="text">Data *</label>
            <input type="date" name="data" id="data" class="all-input" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="col-sm">
            <label for="hora" class="text">Hora *</label>
            <input type="time" name="hora" id="hora" class="all-input" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="gestor" class="text">Gestor que avaliará *</label>
            <select name="gestor" id="gestor" required class="all-input">
                <option value="" disabled selected>- Selecione -</option>
                <?php 
                if($_SESSION['user']['permissao'] == 'GESTOR-1') {
                    $gestor->popularSelect($_SESSION['empresa']['database']); 
                } else {
                    ?>
                    <option value="<?php echo $_SESSION['user']['cpf'] ?>">Você - <?php echo $_SESSION['user']['nome_completo'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="col-sm">
            <label for="colaborador" class="text">Colaborador que será avaliado *</label>
            <select name="colaborador" id="colaborador" required class="all-input">
                <option value="" disabled selected>- Selecione -</option>
                <?php 
                    $colaborador->popularSelect($_SESSION['empresa']['database']); 
                ?>
            </select>
        </div>
    </div>

    <div class="row" style="margin-top: 2em;">
        <div class="col-sm">
            <input type="checkbox" name="lembrar" id="lembrar" value="1"> <label class="text">Enviar e-mail com lembrete ao gestor</label>
        </div>

        <div class="col-sm">
            <input type="checkbox" name="liberar" id="liberar" value="1"> <label class="text">Liberar autoavaliação automaticamente</label>
        </div>
    </div>

    <div class="row">
        <div class="col-sm">
            <small class="text">Por padrão, os agendamentos aparecerão como aviso e tela inicial do Staffast para o gestor e colaborador selecionado</small>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="submit" value="Agendar" id="btnSalvar" class="button button1">
        </div>
        <div class="col-sm">
            <input type="reset" value="Limpar" id="btnLimpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>

</html>