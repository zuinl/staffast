<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');
    include('../classes/class_pdi.php');

    $colaborador = new Colaborador();
    $gestor = new Gestor();

    $pdi = new PDI();

    if(isset($_GET['editar'])) {
        $pdi->setID($_GET['id']);
        $title = 'Editar';
        $action = 'editar';
        $button = 'Salvar alterações';
    } else {
        $title = 'Novo';
        $action = 'novo';
        $button = 'Salvar e ver meu PDI';
    }

    $pdi = $pdi->retornarPDI($_SESSION['empresa']['database']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?> PDI</title>    
    <script>
        function preencherExemplo() {
            var exemplo = document.getElementById('exemplo');
            var btn = document.getElementById('btnExemplo');

            if(exemplo.style.display == 'none') {
                exemplo.style.display = 'block';
                btn.value = 'Ocultar exemplo';
                exemplo.focus();
            } else {
                exemplo.style.display = 'none';
                btn.value = 'Me mostre um exemplo';
                document.getElementById('titulo').focus();
            }
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
            <li class="breadcrumb-item"><a href="PDIs.php">Planos de Desenvolvimento Individual (PDIs)</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?> PDI</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-6 offset-sm-2">
            <h4 class="high-text"><?php echo $title; ?> <span class="destaque-text">Plano de Desenvolvimento Individual (PDI)</span></h4>
        </div>
        <div class="col-sm-2">
            <input type="button" class="button button3" id="btnExemplo" value="Me mostre um exemplo" onclick="preencherExemplo();">
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
        <form method="POST" action="../database/pdi.php?<?php echo $action; ?>=true" id="form">
            <label for="titulo" class="text">Título / Objetivo *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $pdi->getTitulo(); ?>" class="all-input" maxlength="60" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Prazo *</label>
            <input type="date" name="prazo" id="prazo" value="<?php echo $pdi->getPrazo_format(); ?>"  class="all-input" required>
        </div>   
    </div>

    <div id="direcionados">

        <div class="row" style="margin-top: 1em;">
            <?php if(!isset($_GET['editar'])) { ?>
            <div class="col-sm">
                <label class="text">Este Plano de Desenvolvimento Individual é para...</label>
                <select name="dono" id="dono" class="all-input" required>
                    <?php if($_SESSION['user']['permissao'] == 'COLABORADOR') { ?>
                        <option value="<?php echo $_SESSION['user']['cpf'] ?>" selected disabled><?php echo $_SESSION['user']['nome_completo'] ?></option>
                    <?php } else { ?>
                        <option value="">- Selecione -</option>
                        <option value="" disabled>--- COLABORADORES</option>
                        <?php
                        $colaborador->popularSelect($_SESSION['empresa']['database']);
                        ?>
                        <option value="" disabled>--- GESTORES</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                        ?>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm">
                <label class="text">... e o gestor que orientará é...</label>
                <select name="orientador" id="orientador" value="<?php echo $pdi->getCpfGestor(); ?>" class="all-input" required>
                    <?php if($_SESSION['user']['permissao'] == 'COLABORADOR') { ?>
                        <option value="Nenhum" selected disabled>Nenhum</option>
                    <?php } else { ?>
                        <option value="Nenhum" selected>Nenhum</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                    } ?>
                </select>
                <small class="text">Deixando "nenhum" selecionado, o PDI será administrado apenas pelo funcionário 
                dono do mesmo.</small>
            </div>
            <?php } ?>
        </div>

    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <?php if(isset($_GET['editar'])) { ?> <input type="hidden" name="pdi_id" value="<?php echo $pdi->getID(); ?>"> <?php } ?>
            <input type="submit" value="<?php echo $button; ?>" id="btnSalvar" class="button button1">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" id="btnLimpar" class="button button1">
        </div>
    </div>
    </form>

    <div id="exemplo" style="display: none;">

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Objetivo do PDI: Abrir um escritório de arquitetura</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h4 class="high-text">Competências a desenvolver (3)</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <ul>
                    <li>Investimento financeiro</li>
                        <ul>
                            Metas a cumprir para desenvolver a competência
                            <li>Possuir R$10.000,00 disponíveis para investimento</li>
                            <li>Encontrar um investidor</li>
                        </ul>
                    <li>Empreendedorismo</li>
                        <ul>
                            Metas a cumprir para desenvolver a competência
                            <li>Frequentar 5 palestras/cursos sobre empreendedorismo</li>
                            <li>Ler 1 livro sobre empreendedorismo</li>
                            <li>Fazer 1 visita ao SEBRAE para orientações</li>
                        </ul>
                    <li>Administração de empresa</li>
                        <ul>
                            Metas a cumprir para desenvolver a competência
                            <li>Fazer 1 curso rápido sobre administração de empresa</li>
                        </ul>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>



</html>