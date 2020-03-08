<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');

    $colaborador = new Colaborador();
    $gestor = new Gestor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo PDI</title>    
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
            <li class="breadcrumb-item active" aria-current="page">Novo PDI</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-6 offset-sm-2">
            <h4 class="high-text">Novo <span class="destaque-text">Plano de Desenvolvimento Individual (PDI)</span></h4>
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
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
        <form method="POST" action="novasCompetenciasPDI.php" id="form">
            <label for="titulo" class="text">Título / Objetivo *</label>
            <input type="text" name="titulo" id="titulo" class="all-input" maxlength="60" required>
            <!-- <small class="text">Lembre-se: . Exemplos: "Atingir excelência em atendimento ao cliente", "Construir o time de vendas perfeito" ou "Atingir 100% de segurança dos dados dos clientes"</small> -->
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Prazo *</label>
            <input type="date" name="prazo" id="prazo" class="all-input" required>
        </div>   
        <div class="col-sm">
            <label class="text">Quantas competências você pretende desenvolver? *</label>
            <input type="number" name="competencias" id="competencias" class="all-input" required>
        </div>
    </div>

    <div id="direcionados">

        <div class="row" style="margin-top: 1em;">
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
                <select name="orientador" id="orientador" class="all-input" required>
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
        </div>

    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="Prosseguir" id="btnSalvar" class="button button1">
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