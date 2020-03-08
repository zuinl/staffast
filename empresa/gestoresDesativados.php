<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if($_SESSION['user']['permissao'] != 'GESTOR-1') {
        include('../include/acessoNegado.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);

    $select = "SELECT ges_cpf as cpf FROM tbl_gestor WHERE ges_ativo = 0 ORDER BY ges_nome_completo ASC";

    $query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestores desativados</title>
    <script>
    function excluir(cpf) {
        var confirma = confirm("ATENÇÃO: a exclusão de um gestor é irreversível e todos os dados, avaliações e autoavaliações dele serão perdidas, inclusive os dados do cadastro dele como COLABORADOR, se houver. Lembre-se que, estando desativado, o gestor já não consegue acessar o sistema. \nQuer mesmo continuar e apagar tudo no Staffast vinculado a este CPF?");
        if(!confirma) return;
        else window.location.href = "../database/gestor.php?excluir=true&id="+cpf;
    }
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
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

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestores desativados</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/meeting.png" width="60">
        </div>
        <div class="col-sm-8">
            <h2 class="high-text">Todos os <i><span class="destaque-text">gestores desativados</span></i> de <?php echo $_SESSION['empresa']['nome']; ?></h2>
        </div>
        <?php if($_SESSION['empresa']['logotipo'] != "") { ?>
        <div class="col-sm-1">
            <img src="<?php echo $_SESSION['empresa']['logotipo']; ?>" width="100">
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
           $gestor = new Gestor();

           $gestor->setCpf($fetch['cpf']);
           $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
           $foto = $gestor->getFoto() != "" ? 'img/fotos/'.$gestor->getFoto() : 'img/fotos/person.png';
           ?>
            <div class="col-sm">
                <div class="card" style="width: 15rem; margin-bottom: 1.5em; text-align: center;">
                    <img src="<?php echo $foto; ?>" width="50" style="margin-left: 40%; margin-top: 0.7em; border-radius: 30px">
                    <div class="card-body">
                        <h5 class="high-text"><?php echo $gestor->getPrimeiroNome(); ?></h5>
                        <p class="card-text"><?php echo $gestor->getCargo(); ?></p>
                        <a href="perfilGestor.php?id=<?php echo base64_encode($gestor->getCpf()); ?>"><button class="button button1" style="font-size: 0.7em;">Ver</button></a>
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
         <div class="col-sm-4" style="margin-top: 2em;">
             <h4 class="text">Sem gestores desativados por enquanto.</h4>
         </div>
        <?php
    }
    ?>
</div>
</body>
</html>