<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if($_SESSION['user']['permissao'] != 'GESTOR-1' && $_SESSION['user']['permissao'] != 'GESTOR-2') {
        include('../include/acessoNegado.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);

    $select = "SELECT set_id as id FROM tbl_setor WHERE set_ativo = 0 ORDER BY set_nome ASC";

    $query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todos os setores</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-sm-1">
            <img src="img/factory.png" width="60">
        </div>
        <div class="col-sm-11">
            <h2 class="high-text">Todos os <i><span class="destaque-text">setores</span></i> desativados de <?php echo $_SESSION['empresa']['nome']; ?></h2>
        </div>
    </div>

    <hr class="hr-divide">

    <div class="row">

    <?php
    $contador = 0;
       while($fetch = mysqli_fetch_assoc($query)) {
           $setor = new Setor();

           $setor->setID($fetch['id']);
           $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

           ?>
            <div class="col-sm-2">
                <div class="card" style="width: 15rem;">
                    <div class="card-body">
                        <h5 class="high-text"><?php echo $setor->getNome(); ?></h5>
                        <p class="card-text"><?php echo $setor->getLocal(); ?></p>
                        <a href="perfilSetor.php?id=<?php echo $setor->getID(); ?>"><button class="button button1" style="font-size: 0.7em;">Ver</button></a>
                    </div>
                </div>
			</div>
             <?php
            $contador += 1;
            if($contador == 6) {
                echo '</div>
                <div class="row">';
            }
       }
       if(mysqli_num_rows($query) == 0) {
        ?>
         <div class="col-sm-2 offset-sm-3">
             <img src="img/enterprise.png" width="110">
         </div>
         <div class="col-sm-4" style="margin-top: 2em;">
             <h4 class="text">Sem setores desativados por enquanto.</h4>
         </div>
        <?php
        }
    ?>
</div>
</body>
</html>