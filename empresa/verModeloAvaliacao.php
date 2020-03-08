<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once '../classes/class_modelo_avaliacao.php';
    require_once '../classes/class_colaborador.php';
    require_once '../classes/class_gestor.php';
    require_once '../classes/class_conexao_empresa.php';
    require_once '../classes/class_queryHelper.php';

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $modelo = new ModeloAvaliacao();
    $modelo->setID($_GET['id']);
    $modelo = $modelo->retornarModeloAvaliacao($_SESSION['empresa']['database']);

    $situacao = '';
    switch((int)$modelo->getAtivo()) {
        case 1:
            $situacao = '<span style="color: green">Ativo</span>'; break;
        case 0:
            $situacao = '<span style="color: red">Inativo</span>'; break;
    }

    $gestor = new Gestor();
    $gestor->setCpf($modelo->getCpfCriador());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $colaborador = new Colaborador();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $modelo->getTitulo(); ?></title>    
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
            <li class="breadcrumb-item"><a href="verModelosAvaliacao.php">Modelos de Avaliação</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $modelo->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?php echo $modelo->getTitulo(); ?></h2>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="high-text">Criado por <?php echo $gestor->getNomeCompleto(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Situação: <?php echo $situacao; ?></h6>
        </div>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal" value="Gerenciar atribuições">
        </div>
        <div class="col-sm">
            <a href="novoModeloAvaliacao.php?editar=true&id=<?php echo $modelo->getID(); ?>"><input type="button" class="button button1" value="Editar modelo"></a>
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

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Colaboradores atribuídos a este modelo</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <?php
    $select = "SELECT 
                DISTINCT t2.col_nome_completo as nome, 
                DATE_FORMAT(data_add, '%d/%m/%Y') as data 
               FROM tbl_colaborador_modelo_avaliacao t1 
                INNER JOIN tbl_colaborador t2 
                ON t2.col_cpf = t1.col_cpf AND t2.col_ativo = 1
               WHERE t1.modelo_id = ".$modelo->getID();
    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h6 class="text">Não há colaboradores atribuídos</h6>
            </div>
        </div>
        <?php
    } else {
        while($f = mysqli_fetch_assoc($query)) {
            ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <h6 class="text"><?php echo $f['nome']; ?> - Desde <?php echo $f['data']; ?></h6>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <div class="row" style="text-align: center; margin-top: 2em;">
        <div class="col-sm">
            <h4 class="text">Competências deste modelo</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 1:</b> <?php echo $modelo->getUm(); ?></div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 2:</b> <?php echo $modelo->getDois(); ?></div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 3:</b> <?php echo $modelo->getTres(); ?></div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 4:</b> <?php echo $modelo->getQuatro(); ?></div>
    </div>
    <?php if($modelo->getCinco() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 5:</b> <?php echo $modelo->getCinco(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getSeis() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 6:</b> <?php echo $modelo->getSeis(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getSete() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 7:</b> <?php echo $modelo->getSete(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getOito() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 8:</b> <?php echo $modelo->getOito(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getNove() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 9:</b> <?php echo $modelo->getNove(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDez() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 10:</b> <?php echo $modelo->getDez(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getOnze() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 11:</b> <?php echo $modelo->getOnze(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDoze() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 12:</b> <?php echo $modelo->getDoze(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getTreze() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 13:</b> <?php echo $modelo->getTreze(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getQuatorze() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 14:</b> <?php echo $modelo->getQuatorze(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getQuinze() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 15:</b> <?php echo $modelo->getQuinze(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDezesseis() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 16:</b> <?php echo $modelo->getDezesseis(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDezessete() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 17:</b> <?php echo $modelo->getDezessete(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDezoito() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 18:</b> <?php echo $modelo->getDezoito(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getDezenove() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 19:</b> <?php echo $modelo->getDezenove(); ?></div>
    </div>
    <?php } ?>
    <?php if($modelo->getVinte() != "") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm"><b>Competência 20:</b> <?php echo $modelo->getVinte(); ?></div>
    </div>
    <?php } ?>

    
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
                <h3 class="high-text">Atribuir colaboradores a <?php echo $modelo->getTitulo(); ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Selecione os colaboradores para atribuir</h5>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <form action="../database/modeloAvaliacao.php?atribuirColaboradores=true" method="POST">
                <div style="height:7em;; overflow:auto;">          
                    <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="hidden" name="id" id="id" value="<?php echo $modelo->getID(); ?>">
                <input type="submit" value="Atribuir" class="button button1">
                </form>
            </div>
        </div>

        <div class="row" style="margin-top: 2em;">
            <div class="col-sm">
                <h5 class="text">Selecione os colaboradores para retirar</h5>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <form action="../database/modeloAvaliacao.php?retirarColaboradores=true" method="POST">
                <div style="height:7em;; overflow:auto;">          
                    <?php $modelo->popularSelectAtribuidosMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="hidden" name="id" id="id" value="<?php echo $modelo->getID(); ?>">
                <input type="submit" value="Retirar" class="button button1">
                </form>
            </div>
        </div>
        
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>