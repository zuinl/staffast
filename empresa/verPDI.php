<?php
    require_once('../include/auth.php');
    require_once('../src/meta.php');
    require_once('../classes/class_pdi.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

        $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

    $pdi = new PDI();
    $pdi->setID($_GET['id']);
    $pdi = $pdi->retornarPDI($_SESSION['empresa']['database']);

    //Coletando anotações do PDI
    $select = "SELECT 
                    DATE_FORMAT(data, '%d/%m/%Y %H:%i') as data, 
                    anotacao 
                FROM tbl_pdi_anotacao 
                WHERE pdi_id = ".$_GET['id']." 
                ORDER BY data DESC";
    $query_anotacao = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PDI</title> 
    <script>
        function update(tipo, id) {
            document.getElementById('tipo').value = tipo;
            document.getElementById('id').value = id;
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
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
    ?>

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
            <li class="breadcrumb-item"><a href="PDIs.php">Planos de Desenvolvimento Individual - PDI</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $pdi->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?php echo $pdi->getTitulo(); ?></h2>
            <h5 class="text">Este PDI é para <?php echo $pdi->getDono(); ?></h5>
            <h6 class="text">Orientador: <?php echo $pdi->getOrientador(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Situação: <b><?php echo $pdi->traduzStatus($pdi->getStatus()); ?></b></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Prazo: <?php echo $pdi->getPrazo(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Criado em: <?php echo $pdi->getDataCriacao(); ?></h6>
        </div>
        <div class="col-sm">
            <?php if($_SESSION['user']['cpf'] == $pdi->getCpf() || $_SESSION['user']['cpf'] == $pdi->getCpfGestor()) { ?>
                <span><input type="button" data-toggle="modal" data-target="#modal" class="button button3" style="font-size: 0.7em;" onClick="update('pdi', '<?php echo $pdi->getID(); ?>')" value="Atualizar <?php echo $pdi->getTitulo(); ?>"></span>
            <?php } ?>
        </div>
    </div>
</div>

<hr class="hr-divide">

<div class="container">
    <div class="accordion" id="accordionExample">
        <?php 
        $select_compet = "SELECT id, descricao, status FROM tbl_pdi_competencia WHERE pdi_id = ".$pdi->getID()." ORDER BY id ASC";
        $query_compet = $helper->select($select_compet, 1);
        if(mysqli_num_rows($query_compet) > 0) {
            while($f_compet = mysqli_fetch_assoc($query_compet)) {
        ?>
            <div class="card">
                <div class="card-header" id="card_<?php echo $f_compet['id']; ?>" style="text-align: center;">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_<?php echo $f_compet['id']; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $f_compet['id']; ?>">
                    <b><?php echo $f_compet['descricao']; ?> - Situação: <?php echo $pdi->traduzStatus($f_compet['status']); ?></b> 
                    </button>
                </h2>
                <?php if($_SESSION['user']['cpf'] == $pdi->getCpf() || $_SESSION['user']['cpf'] == $pdi->getCpfGestor()) { ?>
                    <span><input type="button" data-toggle="modal" data-target="#modal" class="button button1" style="font-size: 0.7em;" onClick="update('compet', '<?php echo $f_compet['id']; ?>')" value="Atualizar <?php echo $f_compet['descricao']; ?>"></span>
                <?php } ?>
                </div>

                <div id="collapse_<?php echo $f_compet['id']; ?>" class="collapse" aria-labelledby="card_<?php echo $f_compet['id']; ?>" data-parent="#accordionExample">
                <div class="card-body">
                    <?php
                    $select_metas = "SELECT id, descricao, status FROM tbl_pdi_competencia_meta WHERE cpt_id = ".$f_compet['id']." ORDER BY descricao ASC";
                    $query_metas = $helper->select($select_metas, 1);
                    if(mysqli_num_rows($query_metas) > 0) {
                        while($f_metas = mysqli_fetch_assoc($query_metas)) {
                    ?>
                    <ul>
                        <li>
                            <?php echo $f_metas['descricao']; ?> 
                            <?php if($_SESSION['user']['cpf'] == $pdi->getCpf() || $_SESSION['user']['cpf'] == $pdi->getCpfGestor()) { ?>
                                <span><input type="button" data-toggle="modal" data-target="#modal" class="button button2" style="font-size: 0.6em;" onClick="update('meta', '<?php echo $f_metas['id']; ?>')" value="Atualizar <?php echo $f_metas['descricao']; ?>"></span>
                            <?php } ?>
                            <ul>
                                <li>Situação: <?php echo $pdi->traduzStatus($f_metas['status']); ?></li>
                            </ul>
                        </li>
                    </ul>
                    <?php
                        }
                    } else {
                        ?>
                        <ul>
                            <li>Sem metas para esta competência</li>
                        </ul>
                        <?php
                    }
                    ?>
                </div>
                </div>
            </div>
        <?php 
            }    
        }   
        ?>
        <div class="card">
            <div class="card-header" id="card_anotacao" style="text-align: center;">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_anotacao" aria-expanded="true" aria-controls="collapse_anotacao">
                    <b>Anotações deste PDI</b> 
                    </button>
                </h2>
            </div>
            <div id="collapse_anotacao" class="collapse" aria-labelledby="card_anotacao" data-parent="#accordionExample">
                <div class="card-body">
                    <ul>
                        <?php while($f = mysqli_fetch_assoc($query_anotacao)) { ?>
                            <li><b><?php echo $f["data"]; ?></b>: <?php echo $f["anotacao"]; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>


<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Atualizar status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align: center; padding-right: 2em;">

        <div class="row">
            <div class="col-sm">
                <form action="../database/pdi.php?atualizarTipo=true&id_pdi=<?php echo $pdi->getID(); ?>" method="POST">
                <label class="text">Selecione novo status</label>
                <select name="status" class="all-input" required>
                    <option value="2">Em andamento</option>
                    <option value="1">Concluído</option>
                    <option value="3">Pendente</option>
                    <option value="0">Cancelado</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <label class="text">Insira uma anotação</label>
                <textarea class="all-input" name="anotacao" id="anotacao" required minlength="30" maxlength="500" placeholder="Descreve sobre as ações, avanços ou observações relacionadas a esta nova atualização no seu PDI"></textarea>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <input type="hidden" name="tipo" id="tipo" value="">
                <input type="hidden" name="id" id="id" value="">
                <input type="submit" class="button button1" value="Atualizar">
                </form>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>