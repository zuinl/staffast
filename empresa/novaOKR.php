<?php
    require_once('../include/auth.php');
    require_once('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_okr.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $okr = new OKR();
    $colaborador = new Colaborador();
    $gestor = new Gestor();
    $setor = new Setor();

    if(isset($_GET['editar'])) {
        $okr->setID($_GET['id']);
        $okr = $okr->retornarOKR($_SESSION['empresa']['database']);

        $title = 'Editar';
        $action = 'editar';
        $button = 'Salvar alterações';
    } else {
        $title = 'Nova';
        $action = 'nova';
        $button = 'Salvar minha OKR';
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?> meta OKR</title>    
    <script>
        function isMoney(money = true) {
            document.getElementById("numberGoal").value = 0;
            document.getElementById("moneyGoal").disabled = false;
            document.getElementById("numberGoal").disabled = true; 
        }
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
        function preencherExemplo() {
            var titulo = document.getElementById("titulo");
            var descricao = document.getElementById("descricao");
            var tipo = document.getElementById("tipo");
            var visivel = document.getElementById("visivel");
            var prazo = document.getElementById("prazo");
            var tituloOKR1 = document.getElementById("tituloOKR1");
            var tipoOKR1 = document.getElementById("tipoOKR1");
            var metaOKR1 = document.getElementById("metaOKR1");
            var tituloOKR2 = document.getElementById("tituloOKR2");
            var tipoOKR2 = document.getElementById("tipoOKR2");
            var metaOKR2 = document.getElementById("metaOKR2");
            var tituloOKR3 = document.getElementById("tituloOKR3");
            var tipoOKR3 = document.getElementById("tipoOKR3");
            var metaOKR3 = document.getElementById("metaOKR3");
            var tituloOKR4 = document.getElementById("tituloOKR4");
            var tipoOKR4 = document.getElementById("tipoOKR4");
            var metaOKR4 = document.getElementById("metaOKR4");
            var tituloOKR5 = document.getElementById("tituloOKR5");
            var tipoOKR5 = document.getElementById("tipoOKR5");
            var metaOKR5 = document.getElementById("metaOKR5");
            var btn = document.getElementById("btnExemplo");
            var btnSalvar = document.getElementById("btnSalvar");
            var btnLimpar = document.getElementById("btnLimpar");
            var direcionados = document.getElementById("direcionados");

            if(titulo.value != "Criar minha OKR - Exemplo") {
                titulo.value = "Criar minha OKR - Exemplo";
                titulo.disabled = true;
                descricao.value = "Criar uma meta OKR ajudará minha empresa a organizar quais objetivos precisam ser alcançados para concluir uma meta";
                descricao.disabled = true;
                tipo.value = "Vendas";
                tipo.disabled = true;
                visivel.value = "Todos";
                visivel.disabled = true;
                prazo.value = "2050-12-31";
                prazo.disabled = true;
                tituloOKR1.value = "Vender R$200.000,00";
                tituloOKR1.disabled = true;
                tipoOKR1.value = "Orçamento";
                tipoOKR1.disabled = true;
                metaOKR1.value = "200000,00";
                metaOKR1.disabled = true;
                tituloOKR2.value = "Contratar mais 3 vendedores";
                tituloOKR2.disabled = true;
                tipoOKR2.value = "Meta numérica";
                tipoOKR2.disabled = true;
                metaOKR2.value = "3";
                metaOKR2.disabled = true;
                tituloOKR3.value = "Capacitar 2 gerentes de vendas";
                tituloOKR3.disabled = true;
                tipoOKR3.value = "Meta numérica";
                tipoOKR3.disabled = true;
                metaOKR3.value = "2";
                metaOKR3.disabled = true;
                tituloOKR4.disabled = true;
                tipoOKR4.disabled = true;
                metaOKR4.disabled = true;
                tituloOKR5.disabled = true;
                tipoOKR5.disabled = true;
                metaOKR5.disabled = true;
                btn.value = "Limpar exemplo";
                btnSalvar.disabled = true;
                btnLimpar.disabled = true;
                direcionados.style.display = 'none';
            } else {
                titulo.value = "";
                titulo.disabled = false;
                descricao.value = "";
                descricao.disabled = false;
                tipo.value = "";
                tipo.disabled = false;
                visivel.value = "Todos";
                visivel.disabled = false;
                prazo.value = "";
                prazo.disabled = false;
                tituloOKR1.value = "";
                tituloOKR1.disabled = false;
                tipoOKR1.value = "";
                tipoOKR1.disabled = false;
                metaOKR1.value = "";
                metaOKR1.disabled = false;
                tituloOKR2.value = "";
                tituloOKR2.disabled = false;
                tipoOKR2.value = "";
                tipoOKR2.disabled = false;
                metaOKR2.value = "";
                metaOKR2.disabled = false;
                tituloOKR3.value = "";
                tituloOKR3.disabled = false;
                tipoOKR3.value = "";
                tipoOKR3.disabled = false;
                metaOKR3.value = "";
                metaOKR3.disabled = false;
                tituloOKR4.disabled = false;
                tipoOKR4.disabled = false;
                metaOKR4.disabled = false;
                tituloOKR5.disabled = false;
                tipoOKR5.disabled = false;
                metaOKR5.disabled = false;
                btn.value = "Me mostre um exemplo";
                btnSalvar.disabled = false;
                btnLimpar.disabled = false;
                direcionados.style.display = 'block';
            }
        }
    </script>
</head>
<body>
<?php
    require_once('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="metas.php">Metas OKR</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?> meta OKR <?php echo $okr->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?php echo $title; ?> OKR</h2>
        </div>
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal" value="Como criar minha OKR?">
        </div>
        <div class="col-sm">
            <input type="button" class="button button3" id="btnExemplo" value="Me mostre um exemplo" onclick="preencherExemplo();">
        </div>
        <?php } ?>
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
        <form method="POST" action="../database/okr.php?<?php echo $action; ?>=true" id="form">
            <label for="titulo" class="text">Objetivo *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $okr->getTitulo(); ?>" class="all-input" maxlength="60" required>
            <small class="text">Lembre-se: o objetivo de uma meta OKR deve ser único e direto. Exemplos: "Atingir excelência em atendimento ao cliente", "Construir o time de vendas perfeito" ou "Atingir 100% de segurança dos dados dos clientes"</small>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="descricao" class="text">Descrição do objetivo *</label>
            <input type="text" name="descricao" id="descricao" value="<?php echo $okr->getDescricao(); ?>" class="all-input" maxlength="500" required>
            <small class="text">Assim como o objetivo, a descrição precisa ser curta, citando, por exemplo, a principal vantagem que o objetivo trará</small>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="tipo" class="text">Categoria *</label>
            <select name="tipo" id="tipo" class="all-input" required>
                <option value="">-- Selecione --</option>
                <option value="Atendimento ao cliente" <?php if($okr->getTipo() == 'Atendimento ao cliente') echo 'selected'; ?>>Atendimento ao cliente</option>
                <option value="Orçamento" <?php if($okr->getTipo() == 'Orçamento') echo 'selected'; ?>>Orçamento</option>
                <option value="Diminuição de perdas" <?php if($okr->getTipo() == 'Diminuição de perdas') echo 'selected'; ?>>Diminuição de perdas</option>
                <option value="Aumento de equipe" <?php if($okr->getTipo() == 'Aumento de equipe') echo 'selected'; ?>>Aumento de equipe</option>
                <option value="Redução de equipe" <?php if($okr->getTipo() == 'Redução de equipe') echo 'selected'; ?>>Redução de equipe</option>
                <option value="Capacitação de equipe" <?php if($okr->getTipo() == 'Capacitação de equipe') echo 'selected'; ?>>Capacitação de equipe</option>
                <option value="Vendas" <?php if($okr->getTipo() == 'Vendas') echo 'selected'; ?>>Vendas</option>
                <option value="Outros" <?php if($okr->getTipo() == 'Outros') echo 'selected'; ?>>Outros</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="text">Quem verá <b>detalhes</b> desta meta?</label>
            <select name="visivel" id="visivel" class="all-input" required>
                <option value="Todos" selected>Todos os integrantes verão</option>
                <option value="Apenas eu" <?php if($okr->getVisivel() == 2) echo 'selected'; ?>>Apenas eu</option>
                <option value="Apenas os gestores" <?php if($okr->getVisivel() == 3) echo 'selected'; ?>>Apenas os gestores</option>
            </select>
            <small class="text">Os não autorizados ainda a verão na lista de "Metas OKR", apenas</small>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Prazo *</label>
            <input type="date" name="prazo" id="prazo" value="<?php echo $okr->getPrazo_format(); ?>" class="all-input">
        </div>   
    </div>

    <?php if(!isset($_GET['editar'])) { ?>
    <div id="direcionados">

        <div class="row" style="margin-top: 1em;">
            <div class="col-sm-4">
                <label for="colaboradores" class="text">Os seguintes colaboradores participarão...</label>
                <div style="height:14em;; overflow:auto;">
                    <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
            </div>

            <div class="col-sm-4">
                <label for="gestores" class="text">... e os seguintes gestores...</label>
                <div style="height:14em;; overflow:auto;">
                    <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
            </div>

            <div class="col-sm-4">
                <label for="setores" class="text">... e estes setores</label>
                <div style="height:14em;; overflow:auto;">
                    <?php $setor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
            </div>
        </div>

        <div class="row">
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

    </div>
    <?php } ?>


    <?php if(!isset($_GET['editar'])) { ?>
    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h4 class="high-text"><i>Key result 1</i></h4>
        </div>
        <div class="col-sm">
            <label class="tituloOKR1">Título</label>
            <input type="text" name="tituloOKR1" id="tituloOKR1" class="all-input" maxlength="120" placeholder="Ex: Economizar R$500,00 em perdas" required>
        </div>
        <div class="col-sm">
            <label class="tipoOKR1">Tipo</label>
            <select name="tipoOKR1" id="tipoOKR1" class="all-input" required>
                <option value="">-- Selecione --</option>
                <option value="Orçamento">Orçamento (dinheiro)</option>
                <option value="Meta numérica">Meta numérica (número inteiro)</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="metaOKR1">Meta</label>
            <input type="text" name="metaOKR1" id="metaOKR1" class="all-input" placeholder="Ex: 500,00" required>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h4 class="high-text"><i>Key result 2</i></h4>
        </div>
        <div class="col-sm">
            <label class="tituloOKR2">Título</label>
            <input type="text" name="tituloOKR2" id="tituloOKR2" class="all-input" placeholder="Ex: Contratar 3 novos vendedores" maxlength="120">
        </div>
        <div class="col-sm">
            <label class="tipoOKR2">Tipo</label>
            <select name="tipoOKR2" id="tipoOKR2" class="all-input">
                <option value="">-- Selecione --</option>
                <option value="Orçamento">Orçamento (dinheiro)</option>
                <option value="Meta numérica">Meta numérica (número inteiro)</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="metaOKR2">Meta</label>
            <input type="text" name="metaOKR2" id="metaOKR2" class="all-input" placeholder="Ex: 3">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h4 class="high-text"><i>Key result 3</i></h4>
        </div>
        <div class="col-sm">
            <label class="tituloOKR3">Título</label>
            <input type="text" name="tituloOKR3" id="tituloOKR3" class="all-input" maxlength="120" placeholder="Ex: Derrubar 20% das reclamações">
        </div>
        <div class="col-sm">
            <label class="tipoOKR3">Tipo</label>
            <select name="tipoOKR3" id="tipoOKR3" class="all-input">
                <option value="">-- Selecione --</option>
                <option value="Orçamento">Orçamento (dinheiro)</option>
                <option value="Meta numérica">Meta numérica (número inteiro)</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="metaOKR3">Meta</label>
            <input type="text" name="metaOKR3" id="metaOKR3" class="all-input" placeholder="Ex: 20 (máximo de reclamações)">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h4 class="high-text"><i>Key result 4</i></h4>
        </div>
        <div class="col-sm">
            <label class="tituloOKR4">Título</label>
            <input type="text" name="tituloOKR4" id="tituloOKR4" class="all-input" placeholder="Ex: Faturar R$50.000,00" maxlength="120">
        </div>
        <div class="col-sm">
            <label class="tipoOKR4">Tipo</label>
            <select name="tipoOKR4" id="tipoOKR4" class="all-input">
                <option value="">-- Selecione --</option>
                <option value="Orçamento">Orçamento (dinheiro)</option>
                <option value="Meta numérica">Meta numérica (número inteiro)</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="metaOKR4">Meta</label>
            <input type="text" name="metaOKR4" id="metaOKR4" class="all-input" placeholder="Ex: 50.000,00">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <h4 class="high-text"><i>Key result 5</i></h4>
        </div>
        <div class="col-sm">
            <label class="tituloOKR5">Título</label>
            <input type="text" name="tituloOKR5" id="tituloOKR5" class="all-input" placeholder="Ex: Efetivar 2 estagiários" maxlength="120">
        </div>
        <div class="col-sm">
            <label class="tipoOKR5">Tipo</label>
            <select name="tipoOKR5" id="tipoOKR5" class="all-input">
                <option value="">-- Selecione --</option>
                <option value="Orçamento">Orçamento (dinheiro)</option>
                <option value="Meta numérica">Meta numérica (número inteiro)</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="metaOKR5">Meta</label>
            <input type="text" name="metaOKR5" id="metaOKR5" class="all-input" placeholder="Ex: 2">
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
        <?php if(isset($_GET['editar'])) { ?> <input type="hidden" name="okr_id" value="<?php echo $okr->getID(); ?>"> <?php } ?>
            <input type="submit" value="<?php echo $button; ?>" id="btnSalvar" class="button button1">
        </div>
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm">
            <input type="reset" value="Limpar" id="btnLimpar" class="button button1">
        </div>
        <?php } ?>
    </div>
    </form>
</div>
</body>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Como criar uma OKR?</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Objetivo</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">O objetivo ideal da uma meta OKR deve ser algo claro e direto, mas <b>inspirador</b>. 
                Evite citar números ou valores (deixe isto para os <i>Key Results</i>), um objetivo geralmente é <b>qualitativo</b>.
                Exemplos: Construir o time de vendas perfeito, Alcançar excelência em atendimento ao cliente, Dobrar nosso número de clientes</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Descrição do objetivo</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Enquanto o objetivo deve ser resumido a uma frase, a descrição pode ser um pouco mais longa. Nela você pode citar a razão pela qual sua empresa deve atingir este objetivo, 
                ou a principal vantagem. </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Participantes</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Você pode selecionar os colaboradores, gestores e setores envolvidos e/ou afetados pela sua meta OKR. Porém, eles só conseguirão visualizar a OKR se você selecionar "Todos verão" ou "gestores" no campo "Quem verá esta meta?". </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text"><i>Key Result</i></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Os <i>Key Result</i>, do inglês "Objetivos chave", são os mais importantes de uma meta OKR! Você pode inserir de 1 a 5, mas as melhores OKRs tem pelo menos três Key Results. 
                Você precisa inserir um título (exemplos: "Contratar 2 novos vendedores", "Reduzir em 20% o número de reclamações de clientes", "Economizar R$800,00 com avarias"). As Key Results <b>precisam ter um valor quantitativo</b>, 
                pois desta forma será possível medir o avanço das mesmas. As Key Results são, em essência, os resultados que a empresa precisa atingir para concluir o objetivo da OKR.</p>
            </div>
        </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>