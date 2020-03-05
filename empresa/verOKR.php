<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_key_result.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $id = $_GET['id'];

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        $cpf = $_SESSION['user']['cpf'];
        $select = "SELECT okr_id FROM tbl_okr_gestor WHERE ges_cpf = '$cpf' AND okr_id = '$id'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) {
            $select = "SELECT okr_id FROM tbl_okr_colaborador WHERE col_cpf = '$cpf' AND okr_id = '$id'";
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) {
                include('../include/acessoNegado.php');
                die();
            }
        }
    }

    $okr = new OKR();
    $okr->setID($id);
    $okr = $okr->retornarOKR($_SESSION['empresa']['database']);

    switch($okr->getVisivel()) {
        case 1: break; //Para todos
        case 2: //Apenas o criador
            if($_SESSION['user']['cpf'] != $okr->getCpfGestor()) {
                include('../include/acessoNegado.php'); 
                die();
            }
            break;
        case 3: //Apenas os gestores
            if($_SESSION['user']['permissao'] == "COLABORADOR") { 
                include('../include/acessoNegado.php'); 
                die();
            }
            break;
    }
    
    $gestor = new Gestor();
    $gestor->setCpf($okr->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $select = "SELECT krs_id as id FROM tbl_key_result WHERE okr_id = ".$okr->getID()." ORDER BY krs_id ASC";
    $query_krs = $helper->select($select, 1);
    $num_krs = mysqli_num_rows($query_krs);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $okr->getTitulo(); ?></title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text"><?php echo $okr->getTitulo(); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h5 class="text">Categoria: <?php echo $okr->getTipo(); ?></h5>
        </div>
        <div class="col-sm">
            <h6 class="text">Descrição: <?php echo $okr->getDescricao(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Prazo: <?php echo $okr->getPrazo(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Criada em <?php echo $okr->getDataCriacao(); ?> por <?php echo $gestor->getPrimeiroNome(); ?></h6>
        </div>
        <div class="col-sm">
            <input type="button" class="button button3" data-toggle="modal" data-target="#modal" value="Participantes">       
        </div>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">


    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Reuniões que contêm esta meta na pauta</h3>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
                CONCAT(DATE_FORMAT(t2.reu_data, '%d/%m/%Y'), ' às ', t2.reu_hora) as data,
                t2.reu_concluida as concluida
                FROM tbl_reuniao_okr t1 INNER JOIN tbl_reuniao t2 ON t2.reu_id = t1.reu_id WHERE t1.okr_id = '$id' 
                ORDER BY t2.reu_data DESC";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    ?>
                    <span class="text">Nenhuma.
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <span class="text"><a href="verReuniao.php?id=<?php echo $f['id'] ?>">
                        <?php echo $f['pauta'] ?></a> - <?php echo $f['data'] ?> (<?= $f['concluida'] == 1 ? "Concluída" : "Pendente" ?>)<br>
                        <?php   
                    }
                }
            ?>
        </div>
    </div>

    <hr class="hr-divide">


    <?php
    $contador = 0;
    while($f = mysqli_fetch_assoc($query_krs)) {
        $contador++;
        $krs = new KeyResult();
        $krs->setID($f['id']);
        $krs = $krs->retornarKeyResult($_SESSION['empresa']['database']);
        $id_grafico = 'grafico_krs_'.$krs->getID();

    

        if($okr->getTipo() == 'Orçamento') { 
            $descricao = 'Valor R$';
            $meta = 'Meta (R$)';
            $andamento = 'Andamento (R$)';
        }
        else if ($okr->getTipo() == 'Meta numérica') { 
            $descricao = 'Número atual';
            $meta = 'Meta';
            $andamento = 'Andamento';
        }
        else { 
            $descricao = 'Valor';
            $meta = 'Meta';
            $andamento = 'Andamento';
        }
        ?>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Metas", "<?php echo $descricao; ?>", { role: "style" } ],
                ['<?php echo $meta; ?>', <?php echo $krs->getGoal(); ?>, "#42adf5"],
                ['<?php echo $andamento; ?>', <?php echo $krs->getCurrent(); ?>, "orange"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                title: "<?php echo $krs->getTitulo(); ?>",
                width: 1200,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('<?php echo $id_grafico; ?>'));
            chart.draw(view, options);
        }
        </script>

        <div class="row">
            <div class="col-sm">
                <h5 class="text"><b>Key result <?php echo $contador; ?></b>: <span class="text"><?php echo $krs->getTitulo(); ?></span></h5>
            </div>
            <div class="col-sm">
                <h6 class="text"><b>Progresso:</b> <span style="font-size: 1.7em;"><?php echo $krs->porcentagem(); ?> %</span></h6>
            </div>
            <div class="col-sm">
                <h6 class="text"><b>Última atualização:</b> <?php echo $krs->getUltimaAtualizacao(); ?></h6>
            </div>
            <?php if($_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?>
            <div class="col-sm">
                <a href="atualizarKRS.php?id=<?php echo $krs->getID(); ?>&okr=<?php echo $okr->getID(); ?>"><button class="button button1">Atualizar</button></a>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="<?php echo $id_grafico; ?>" style="width: 100%; height: 410px;"></div>
            </div>
        </div>

        <hr class="hr-divide-light">
        <?php
    }

    ?>
    
</div>
</body>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Participantes desta meta</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <h4 class="text">Gestores</h4>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php
            $select = "SELECT DISTINCT t1.ges_cpf as cpf, t2.ges_nome_completo as nome 
            FROM tbl_okr_gestor t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
            WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.ges_nome_completo ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
            else {
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<p class="text">'.$f['nome'].'</p>';
                }
            }
        ?>

        <div class="row">
            <div class="col-sm">
                <h4 class="text">Colaboradores</h4>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php
            $select = "SELECT DISTINCT t1.col_cpf as cpf, t2.col_nome_completo as nome 
            FROM tbl_okr_colaborador t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf 
            WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.col_nome_completo ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
            else {
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<p class="text">'.$f['nome'].'</p>';
                }
            }
        ?>

        <div class="row">
            <div class="col-sm">
                <h4 class="text">Setores</h4>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php
            $select = "SELECT DISTINCT t1.set_id as id, t2.set_nome as nome 
            FROM tbl_okr_setor t1 INNER JOIN tbl_setor t2 ON t2.set_id = t1.set_id 
            WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.set_nome ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
            else {
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<p class="text">'.$f['nome'].'</p>';
                }
            }
        ?>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>