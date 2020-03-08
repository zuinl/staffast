<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);

    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] != 'COLABORADOR') $select = "SELECT col_cpf as cpf FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
    else $select = "SELECT col_cpf as cpf FROM tbl_colaborador WHERE col_cpf = '$cpf'";

    $query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todos os colaboradores</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="col-sm">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php
    }
    ?>

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Colaboradores</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/team.png" width="60">
        </div>
        <div class="col-sm-6">
            <h2 class="high-text">Todos os <i><span class="destaque-text">colaboradores</span></i> de <?php echo $_SESSION['empresa']['nome']; ?></h2>
        </div>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm-2">
            <a href="novoColaborador.php"><button class="button button1">Novo colaborador</button></a>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">

</div>

<div class="container">

    <div class="row">

    <?php
    $contador = 0;
       while($fetch = mysqli_fetch_assoc($query)) {
           $colaborador = new colaborador();

           $colaborador->setCpf($fetch['cpf']);
           $colaborador = $colaborador->retornarcolaborador($_SESSION['empresa']['database']);

           $foto = $colaborador->getFoto() != "" ? 'img/fotos/'.$colaborador->getFoto() : 'img/fotos/person.png';

           ?>
            <div class="col-sm">
                <div class="card" style="width: 15rem; margin-bottom: 1.5em; text-align: center;">
                    <img src="<?php echo $foto; ?>" width="50" style="margin-left: 40%; margin-top: 0.7em; border-radius: 30px">
                    <div class="card-body">
                        <h5 class="high-text"><?php echo $colaborador->getPrimeiroNome(); ?></h5>
                        <p class="card-text"><?php echo $colaborador->getCargo(); ?></p>
                        <a href="perfilColaborador.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button1" style="font-size: 0.7em;">Ver</button></a>
                    </div>
                </div>
			</div>
             <?php
            $contador += 1;
            if($contador == 4) {
                echo '</div>
                <div class="row">';
            }
       }
       if(mysqli_num_rows($query) == 0) {
        ?>
         <div class="col-sm-2 offset-sm-3">
             <img src="img/leadership.png" width="110">
         </div>
         <div class="col-sm-3" style="margin-top: 2em;">
             <h4 class="text">Sem colaboradores por enquanto.</h4>
         </div>
        <?php
        }
    ?>
</div>
</body>
</html>