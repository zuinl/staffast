<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');
    include('../classes/class_conexao_empresa.php');
    include('../classes/class_queryHelper.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" || $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

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

        function selectAll(source) {
            var tipo = document.getElementById('tipo').value;
            if(tipo == '') {
                alert('Por favor, selecione o tipo do documento primeiro');
                return false;
            }
            if(tipo == 'Holerite') {
                alert('Holerites não podem ser direcionados para mais de uma pessoa');
                return false;
            }
		    checkboxes = document.getElementsByName('donos[]');
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
            <li class="breadcrumb-item"><a href="documentos.php">Documentos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Novo documento</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Novo documento</h2>
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
        <form method="POST" action="../database/documentos.php?novo=true" id="form" enctype="multipart/form-data">
            <label for="titulo" class="text">Título *</label>
            <input type="text" name="titulo" id="titulo" class="all-input" maxlength="50" required="">
        </div>
    </div>    
    <div class="row">
        <div class="col-sm">
            <label for="tipo" class="text">Tipo *</label>
            <select name="tipo" id="tipo" class="all-input" required onchange="mostraAviso(this.value);">
                <option value="" selected disabled>- Selecione -</option>
                <option value="Geral">Documento geral</option>
                <option value="Holerite">Holerite</option>
                <option value="Notificação">Notificação</option>
                <option value="Apresentação/Proposta">Apresentação / Proposta</option>
                <option value="Outro">Outro</option>
            </select>
        </div>
        <div class="col-sm">
            <label for="donos" class="text">Visível para *</label><br>
            <div class="div-checkboxes">
                <h6 class="text">- Colaboradores</h6>
                <?php
                $select = "SELECT col_cpf as cpf, col_nome_completo as nome FROM tbl_colaborador 
                WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
                $query = $helper->select($select, 1);
                while($f = mysqli_fetch_assoc($query)) {
                    ?>
                    <input type="checkbox" id="donos[]" name="donos[]" value="<?php echo $f['cpf'];?>"> <?php echo $f['nome'];?><br>
                    <?php
                }
                ?>
                <h6 class="text">- Gestores</h6>
                <?php
                $select = "SELECT ges_cpf as cpf, ges_nome_completo as nome FROM tbl_gestor 
                WHERE ges_ativo = 1 ORDER BY ges_nome_completo ASC";
                $query = $helper->select($select, 1);
                while($f = mysqli_fetch_assoc($query)) {
                    ?>
                    <input type="checkbox" id="donos[]" name="donos[]" value="<?php echo $f['cpf'];?>"> <?php echo $f['nome'];?><br>
                    <?php
                }
                ?>
            </div>
            <br><input type="checkbox" name="todosCols" value="1" onclick="selectAll(this)"> Direcionar a todos
            <small class="text" id="aviso" style="display: none;">Lembre-se: holerites só podem ser atribuídos a um funcionário.</small>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <label for="documento" class="text">Documento *</label>
            <input type="file" name="documento" id="documento" required class="button button3">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="submit" value="Enviar" class="button button2">
        </div>
    </div>
    </form>
</div>
</body>
</html>