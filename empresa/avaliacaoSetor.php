<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_setor.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $cpf = $_SESSION['user']['cpf'];
    if($_SESSION['user']['permissao'] == 'GESTOR-1') { 
        $select = "SELECT DISTINCT set_id as id, set_nome as nome FROM tbl_setor 
        ORDER BY set_nome ASC";
    } else {
        $select = "SELECT DISTINCT t1.set_id as id, t2.set_nome as nome FROM tbl_setor_funcionario t1 
        INNER JOIN tbl_setor t2 ON t2.set_id = t1.set_id 
        WHERE t2.col_cpf = '$cpf' OR t2.ges_cpf = '$cpf'  
        ORDER BY t2.set_nome ASC";
    }

    $query = $helper->select($select, 1);

    $setor = new Setor();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Avaliação de setores</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid" style="text-align: center;">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Avaliações de Setores</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

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

    <div class="row">
        <div class="col-sm">
            <h2 class="high-text">Avaliações de Setores</h2>
        </div>
    </div>

<hr class="hr-divide">
</div>

<div class="container">

    <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
    <div class="row">
        <div class="col-sm">
            <form action="avaliacoesSetor.php" method="POST">
            <label class="text"><b>Quero ver resultados de...</b></label>
            <input type="date" name="dataI" class="all-input">
            <small class="text">Deixe em branco para ver todo o período</small>
        </div>
        <div class="col-sm">
            <label class="text"><b>... até esta data</b></label>
            <input type="date" name="dataF" class="all-input">
            <small class="text">Deixe em branco para ver todo o período</small>
        </div>
        <div class="col-sm">
            <label class="text"><b>... do seguinte setor</b></label>
            <select class="all-input" name="setor" id="setor" required>
                <option value="">-- Selecione --</option>
                <?php echo $setor->popularSelect($_SESSION['empresa']['database'], $_SESSION['user']['permissao'], $_SESSION['user']['cpf']); ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="submit" class="button button2" value="Ver avaliações"></a>
            </form>
        </div>
    </div>
    <?php } ?>

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
    
    <table class="table-site">
        <tr>
            <th>Setor</th>
            <?php if ($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
                <th>Liberar avaliações (por 7 dias)</th>
            <?php } ?> 
            <th>Avaliar</th>
        </tr>
        <?php
            while($fetch = mysqli_fetch_assoc($query)) {
                $setor = new Setor();
                $setor->setID($fetch['id']);
                $setor = $setor->retornarSetor($_SESSION['empresa']['database']);
            ?>
            <tr>
                <td><a href="perfilSetor.php?id=<?php echo $setor->getID(); ?>" target="blank_"><?php echo $setor->getNome(); ?></a></td>

                <?php
                if($_SESSION['user']['permissao'] == "GESTOR-1" && $setor->isAvaliacaoLiberada($_SESSION['empresa']['database'])) {
                    ?>
                    <td><h6 class="text">Avaliações atualmente liberadas</h6></td>
                    <?php
                } else if ($_SESSION['user']['permissao'] == "GESTOR-1" && !$setor->isAvaliacaoLiberada($_SESSION['empresa']['database'])) {
                    ?>
                    <td>
                        <a href="../database/setor.php?liberarAvaliacao=true&id=<?php echo $setor->getID(); ?>"><input type="button" class="button button2" value="Liberar"></a>
                    </td>
                <?php } ?>

                <?php
                if($setor->isAvaliacaoLiberada($_SESSION['empresa']['database'])) {
                    ?>
                    <td><a href="novaAvaliacaoSetor.php?id=<?php echo $setor->getID(); ?>"><button class="button button1">Avaliar</button></a></td>
                    <?php
                } else {
                    ?>
                    <td><h6 class="text">Não disponível</h6></td>
                    <?php
                }
                ?>
            </tr>
            <?php
            } ?> 
    </table>
</div>
</body>
</html>