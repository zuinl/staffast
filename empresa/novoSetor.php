<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');

    $gestor = new Gestor();

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include('../include/acessoNegado.php');
        die();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo setor</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Cadastro de <span class="destaque-text">setor</span></h2>
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
        <form method="POST" action="../database/setor.php?novoSetor=true" id="form">
            <label for="nome" class="text">Nome *</label>
            <input type="text" name="nome" id="nome" class="all-input" maxlength="50" required="">
        </div>
        <div class="col-sm">
            <label for="local" class="text">Local</label>
            <input type="text" name="local" id="local" class="all-input" maxlength="80">
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="descricao" class="text">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="all-input" maxlength="150">
        </div>
        <div class="col-sm">
            <label for="gestores" class="text">Gestor(es)</label>
            <div class="div-checkboxes">
            <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h4 class="destaque-text">Agora defina as competências do setor</h4>
            <h6 class="text">Elas serão usadas nas avaliações do setor, então defina bem, ok? Você só poderá alterá-las depois através de solicitação ao suporte. Se você não preencher todas as 6 agora, poderá adicioná-las depois clicando em "Editar setor"</h6>
            <h6 class="text">Insira pelo menos 4 competências</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label class="text">Competência 1</label>
            <input type="text" name="c1" class="all-input" placeholder="Ex: Comunicação entre colaboradores" required>
        </div>
        <div class="col-sm">
            <label class="text">Competência 2</label>
            <input type="text" name="c2" class="all-input" placeholder="Ex: Organização da rotina" required>
        </div>
        <div class="col-sm">
            <label class="text">Competência 3</label>
            <input type="text" name="c3" class="all-input" placeholder="Ex: Atuação da gestão" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label class="text">Competência 4</label>
            <input type="text" name="c4" class="all-input" placeholder="Ex: Liberdado para autodidatismo" required>
        </div>
        <div class="col-sm">
            <label class="text">Competência 5</label>
            <input type="text" name="c5" class="all-input" placeholder="Ex: Transparência da gestão">
        </div>
        <div class="col-sm">
            <label class="text">Competência 6</label>
            <input type="text" name="c6" class="all-input" placeholder="Ex: Pressão no dia a dia">
        </div>
    </div>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="Cadastrar" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button2">
        </div>
    </div>
    </form>
</div>
</body>
</html>