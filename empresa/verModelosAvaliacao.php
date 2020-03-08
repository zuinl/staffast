<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_modelo_avaliacao.php');
    require_once('../classes/class_gestor.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    
    $select = "SELECT id, titulo, DATE_FORMAT(data_criacao, '%d/%m/%Y') 
    as criacao, cpf_criador as cpf, ativo FROM tbl_modelo_avaliacao ORDER BY titulo DESC";
    $query = $helper->select($select, 1);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modelos de Avaliação</title>
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
            <li class="breadcrumb-item active" aria-current="page">Modelos de Avaliação</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text">Modelos de Avaliação</h3>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm">
            <a href="novoModeloAvaliacao.php"><input type="button" class="button button1" value="Criar modelo"></a>
        </div>
        <?php } ?>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">

    <?php
    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm" style="margin-top: 2em;">
                <h4 class="text">Sem modelos por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Criador</th>
            <th>Situação</th>
            <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?><th>Editar</th><?php } ?>
            <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?><th>Desativar</th><?php } ?>
            <th>Ver</th>
        </tr>
        <?php
            while($f = mysqli_fetch_assoc($query)) {
                $gestor = new Gestor();
                $gestor->setCpf($f['cpf']);
                $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
                if((int)$f['ativo'] === 1) {
                    $situacao = 'Ativo';
                    $acao = 'desativar';
                    $button = 'Desativar';
                } else {
                    $situacao = 'Inativo';
                    $acao = 'ativar';
                    $button = 'Ativar';
                }
        ?>
        <tr>
            <td><b><?php echo $f['titulo']; ?></b></td>
            <td><?php echo $gestor->getNomeCompleto(); ?></td>
            <td><?php echo $situacao; ?></td>
            <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?><td><a href="novoModeloAvaliacao.php?editar=true&id=<?php echo $f['id']; ?>"><button class="button button2">Editar</button></a></td><?php } ?>            
            <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?><td><a href="../database/modeloAvaliacao.php?<?php echo $acao; ?>=true&id=<?php echo $f['id']; ?>"><button class="button button2"><?php echo $button; ?></button></a></td><?php } ?>
            <td><a href="verModeloAvaliacao.php?id=<?php echo $f['id']; ?>"><button class="button button3">Ver</button></a></td>            
    <?php } ?>   
    </table>
 <?php } ?>
</div>
</body>

</html>