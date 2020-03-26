<?php
    include('../include/auth.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if(!isset($_GET['id'])) {
        header('Location: setores.php');
        die();
    }

    $id = $_GET['id'];

    $setor = new Setor();
    $setor->setID($id);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    $gestor = new Gestor();
    $colaborador = new Colaborador();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT avs_id as id FROM tbl_avaliacao_setor WHERE set_id = '$id'";
    $query = $helper->select($select, 1);
    $num_avaliacoes = mysqli_num_rows($query);

    $select = "SELECT DISTINCT cpf FROM tbl_avaliacao_setor WHERE set_id = '$id'";
    $query = $helper->select($select, 1);
    $num_avaliadores = mysqli_num_rows($query);

    $select = "SELECT DISTINCT(ges_cpf) as qtd FROM tbl_setor_funcionario WHERE set_id = '$id' AND ges_cpf != '00000000000'";
    $query = $helper->select($select, 1);
    $num_gestores = mysqli_num_rows($query);

    $select = "SELECT DISTINCT(col_cpf) as qtd FROM tbl_setor_funcionario WHERE set_id = '$id' AND col_cpf != '00000000000'";
    $query = $helper->select($select, 1);
    $num_colaboradores = mysqli_num_rows($query);

    $date1=date_create($setor->getDataCadastroFormat());
    $date2=date_create(date('Y-m-d H:i:s'));
    $diff=date_diff($date2,$date1);

    $select = "SELECT ROUND(AVG(um), 1) AS um,
    ROUND(AVG(dois), 1) AS dois,
    ROUND(AVG(tres), 1) AS tres,
    ROUND(AVG(quatro), 1) AS quatro, 
    ROUND(AVG(cinco), 1) AS cinco,
    ROUND(AVG(seis), 1) AS seis
    FROM tbl_avaliacao_setor WHERE set_id = '$id'";
    $f = $helper->select($select, 2);

    if($f['um'] != NULL) {
        $avg_um = $f['um'];
        $avg_dois = $f['dois'];
        $avg_tres = $f['tres'];
        $avg_quatro = $f['quatro'];
        $avg_cinco = $f['cinco'];
        $avg_seis = $f['seis'];
    } else {
        $avg_um = 0.0;
        $avg_dois = 0.0;
        $avg_tres = 0.0;
        $avg_quatro = 0.0;
        $avg_cinco = 0.0;
        $avg_seis = 0.0;
    }

    if($setor->getSessaoSeis() != '') {
        $avg_geral = round(($avg_um + $avg_dois + $avg_tres + $avg_quatro + $avg_cinco + $avg_seis) / 6, 1);
    } else if ($setor->getSessaoCinco() != '') {
        $avg_geral = round(($avg_um + $avg_dois + $avg_tres + $avg_quatro + $avg_cinco) / 5, 1);
    } else {
        $avg_geral = round(($avg_um + $avg_dois + $avg_tres + $avg_quatro) / 4, 1);
    }
    
    if($num_avaliacoes > 0) {
        if($avg_geral <= 1) $media_exibe = '<h4 class="text" style="color: red;"> <b>'.number_format($avg_geral, 1).'</b> <img src="img/unhappy.png" width="30"></h4>';
        else if($avg_geral > 1 && $avg_geral <= 2) $media_exibe = '<h4 class="text" style="color: red;"> <b>'.number_format($avg_geral, 1).'</b> <img src="img/sad.png" width="30"></h4>';
        else if($avg_geral > 2 && $avg_geral <= 3.5) $media_exibe = '<h4 class="text" style="color: orange;"> <b>'.number_format($avg_geral, 1).'</b> <img src="img/confused.png" width="30"></h4>';
        else if($avg_geral > 3.5 && $avg_geral <= 4.5) $media_exibe = '<h4 class="text" style="color: orange;"> <b>'.number_format($avg_geral, 1).'</b> <img src="img/smiling.png" width="30"></h4>';
        else if($avg_geral > 4.5) $media_exibe = '<h4 class="text" style="color: green;"> <b>'.number_format($avg_geral, 1).'</b> <img src="img/happy.png" width="30"></h4>';
    } else {
        $media_exibe = '<h5 class="text">Não avaliado ainda.</h5>';
    }

    $select = "SELECT ROUND(AVG(um), 1) AS um,
    ROUND(AVG(dois), 1) AS dois,
    ROUND(AVG(tres), 1) AS tres,
    ROUND(AVG(quatro), 1) AS quatro, 
    ROUND(AVG(cinco), 1) AS cinco,
    ROUND(AVG(seis), 1) AS seis,
    COUNT(avs_id) as qtd
    FROM tbl_avaliacao_setor WHERE set_id = '$id' AND 
    avs_data_criacao >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $f = $helper->select($select, 2);

    if($f['um'] != NULL) {
        $avg_um_30 = $f['um'];
        $avg_dois_30 = $f['dois'];
        $avg_tres_30 = $f['tres'];
        $avg_quatro_30 = $f['quatro'];
        $avg_cinco_30 = $f['cinco'];
        $avg_seis_30 = $f['seis'];
        $qtd_30 = $f['qtd'];
    } else {
        $avg_um_30 = 0.0;
        $avg_dois_30 = 0.0;
        $avg_tres_30 = 0.0;
        $avg_quatro_30 = 0.0;
        $avg_cinco_30 = 0.0;
        $avg_seis_30 = 0.0;
        $qtd_30 = 0;
    }

    $select = "SELECT ROUND(AVG(um), 1) AS um,
    ROUND(AVG(dois), 1) AS dois,
    ROUND(AVG(tres), 1) AS tres,
    ROUND(AVG(quatro), 1) AS quatro, 
    ROUND(AVG(cinco), 1) AS cinco,
    ROUND(AVG(seis), 1) AS seis,
    COUNT(avs_id) as qtd
    FROM tbl_avaliacao_setor WHERE set_id = '$id' AND 
    avs_data_criacao >= DATE_SUB(NOW(), INTERVAL 90 DAY)";
    $f = $helper->select($select, 2);

    if($f['um'] != NULL) {
        $avg_um_90 = $f['um'];
        $avg_dois_90 = $f['dois'];
        $avg_tres_90 = $f['tres'];
        $avg_quatro_90 = $f['quatro'];
        $avg_cinco_90 = $f['cinco'];
        $avg_seis_90 = $f['seis'];
        $qtd_90 = $f['qtd'];
    } else {
        $avg_um_90 = 0.0;
        $avg_dois_90 = 0.0;
        $avg_tres_90 = 0.0;
        $avg_quatro_90 = 0.0;
        $avg_cinco_90 = 0.0;
        $avg_seis_90 = 0.0;
        $qtd_90 = 0;
    }

    $select = "SELECT ROUND(AVG(um), 1) AS um,
    ROUND(AVG(dois), 1) AS dois,
    ROUND(AVG(tres), 1) AS tres,
    ROUND(AVG(quatro), 1) AS quatro, 
    ROUND(AVG(cinco), 1) AS cinco,
    ROUND(AVG(seis), 1) AS seis,
    COUNT(avs_id) as qtd
    FROM tbl_avaliacao_setor WHERE set_id = '$id' AND 
    avs_data_criacao >= DATE_SUB(NOW(), INTERVAL 180 DAY)";
    $f = $helper->select($select, 2);

    if($f['um'] != NULL) {
        $avg_um_180 = $f['um'];
        $avg_dois_180 = $f['dois'];
        $avg_tres_180 = $f['tres'];
        $avg_quatro_180 = $f['quatro'];
        $avg_cinco_180 = $f['cinco'];
        $avg_seis_180 = $f['seis'];
        $qtd_180 = $f['qtd'];
    } else {
        $avg_um_180 = 0.0;
        $avg_dois_180 = 0.0;
        $avg_tres_180 = 0.0;
        $avg_quatro_180 = 0.0;
        $avg_cinco_180 = 0.0;
        $avg_seis_180 = 0.0;
        $qtd_180 = 0;
    }

    $select = "SELECT ROUND(AVG(um), 1) AS um,
    ROUND(AVG(dois), 1) AS dois,
    ROUND(AVG(tres), 1) AS tres,
    ROUND(AVG(quatro), 1) AS quatro, 
    ROUND(AVG(cinco), 1) AS cinco,
    ROUND(AVG(seis), 1) AS seis,
    COUNT(avs_id) as qtd
    FROM tbl_avaliacao_setor WHERE set_id = '$id' AND 
    avs_data_criacao >= DATE_SUB(NOW(), INTERVAL 365 DAY)";
    $f = $helper->select($select, 2);

    if($f['um'] != NULL) {
        $avg_um_365 = $f['um'];
        $avg_dois_365 = $f['dois'];
        $avg_tres_365 = $f['tres'];
        $avg_quatro_365 = $f['quatro'];
        $avg_cinco_365 = $f['cinco'];
        $avg_seis_365 = $f['seis'];
        $qtd_365 = $f['qtd'];
    } else {
        $avg_um_365 = 0.0;
        $avg_dois_365 = 0.0;
        $avg_tres_365 = 0.0;
        $avg_quatro_365 = 0.0;
        $avg_cinco_365 = 0.0;
        $avg_seis_365 = 0.0;
        $qtd_365 = 0;
    }

    include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $setor->getNome(); ?></title>
    <script>
        function mostraEdicao() {
            var div = document.getElementById('edicao');
            var btn = document.getElementById('btnEdita');

            if(div.style.display == 'none') {
                div.style.display = 'block';
                btn.value = 'Ocultar';
            } else {
                div.style.display = 'none';
                btn.value = 'Atualizar dados';
            }
        }

        function mostrarGerenciar() {
            var div = document.getElementById('gerenciamento');
            var btn = document.getElementById('btnGerenciar');

            if(div.style.display == 'none') {
                div.style.display = 'block';
                btn.value = 'Ocultar gerenciamento';
            } else {
                div.style.display = 'none';
                btn.value = 'Gerenciar funcionários';
            }
        }

        function verMeta(meta) {

            if(meta != "") {
                window.location.href = "verOKR.php?id="+meta;
            } else {
                alert("Selecione uma meta para visualizar");
                return;
            }
        }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média geral"],
        ['<?php echo $setor->getSessaoUm(); ?>', <?php echo $avg_um; ?>],
        ['<?php echo $setor->getSessaoDois(); ?>', <?php echo $avg_dois; ?>],
        ['<?php echo $setor->getSessaoTres(); ?>', <?php echo $avg_tres; ?>],
        ['<?php echo $setor->getSessaoQuatro(); ?>', <?php echo $avg_quatro; ?>]
        <?php if ($setor->getSessaoCinco() != "") { ?>,
        ['<?php echo $setor->getSessaoCinco(); ?>', <?php echo $avg_cinco; ?>]
        <?php } ?>
        <?php if ($setor->getSessaoSeis() != "") { ?>,
        ['<?php echo $setor->getSessaoSeis(); ?>', <?php echo $avg_seis; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média geral desde o início (<?php echo $num_avaliacoes ?> avaliações)",
        colors: ['#13A330'],
        legend: { position: "none" },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico"));
      chart.draw(view, options);
  }
  </script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média geral"],
        ['<?php echo $setor->getSessaoUm(); ?>', <?php echo $avg_um_30; ?>],
        ['<?php echo $setor->getSessaoDois(); ?>', <?php echo $avg_dois_30; ?>],
        ['<?php echo $setor->getSessaoTres(); ?>', <?php echo $avg_tres_30; ?>],
        ['<?php echo $setor->getSessaoQuatro(); ?>', <?php echo $avg_quatro_30; ?>]
        <?php if ($setor->getSessaoCinco() != "") { ?>,
        ['<?php echo $setor->getSessaoCinco(); ?>', <?php echo $avg_cinco_30; ?>]
        <?php } ?>
        <?php if ($setor->getSessaoSeis() != "") { ?>,
        ['<?php echo $setor->getSessaoSeis(); ?>', <?php echo $avg_seis_30; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média geral a curto prazo (últimos 30 dias - <?php echo $qtd_30; ?> avaliações)",
        colors: ['#13A330'],
        legend: { position: "none" },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico1"));
      chart.draw(view, options);
  }
  </script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média geral"],
        ['<?php echo $setor->getSessaoUm(); ?>', <?php echo $avg_um_90; ?>],
        ['<?php echo $setor->getSessaoDois(); ?>', <?php echo $avg_dois_90; ?>],
        ['<?php echo $setor->getSessaoTres(); ?>', <?php echo $avg_tres_90; ?>],
        ['<?php echo $setor->getSessaoQuatro(); ?>', <?php echo $avg_quatro_90; ?>]
        <?php if ($setor->getSessaoCinco() != "") { ?>,
        ['<?php echo $setor->getSessaoCinco(); ?>', <?php echo $avg_cinco_90; ?>]
        <?php } ?>
        <?php if ($setor->getSessaoSeis() != "") { ?>,
        ['<?php echo $setor->getSessaoSeis(); ?>', <?php echo $avg_seis_90; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média geral a médio prazo (últimos 90 dias - <?php echo $qtd_90; ?> avaliações)",
        colors: ['#13A330'],
        legend: { position: "none" },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico2"));
      chart.draw(view, options);
  }
  </script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média geral"],
        ['<?php echo $setor->getSessaoUm(); ?>', <?php echo $avg_um_180; ?>],
        ['<?php echo $setor->getSessaoDois(); ?>', <?php echo $avg_dois_180; ?>],
        ['<?php echo $setor->getSessaoTres(); ?>', <?php echo $avg_tres_180; ?>],
        ['<?php echo $setor->getSessaoQuatro(); ?>', <?php echo $avg_quatro_180; ?>]
        <?php if ($setor->getSessaoCinco() != "") { ?>,
        ['<?php echo $setor->getSessaoCinco(); ?>', <?php echo $avg_cinco_180; ?>]
        <?php } ?>
        <?php if ($setor->getSessaoSeis() != "") { ?>,
        ['<?php echo $setor->getSessaoSeis(); ?>', <?php echo $avg_seis_180; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média geral a médio longo prazo (últimos 180 dias - <?php echo $qtd_180; ?> avaliações)",
        colors: ['#13A330'],
        legend: { position: "none" },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico3"));
      chart.draw(view, options); 
  }
  </script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média geral"],
        ['<?php echo $setor->getSessaoUm(); ?>', <?php echo $avg_um_365; ?>],
        ['<?php echo $setor->getSessaoDois(); ?>', <?php echo $avg_dois_365; ?>],
        ['<?php echo $setor->getSessaoTres(); ?>', <?php echo $avg_tres_365; ?>],
        ['<?php echo $setor->getSessaoQuatro(); ?>', <?php echo $avg_quatro_365; ?>]
        <?php if ($setor->getSessaoCinco() != "") { ?>,
        ['<?php echo $setor->getSessaoCinco(); ?>', <?php echo $avg_cinco_365; ?>]
        <?php } ?>
        <?php if ($setor->getSessaoSeis() != "") { ?>,
        ['<?php echo $setor->getSessaoSeis(); ?>', <?php echo $avg_seis_365; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média geral a longo prazo (últimos 365 dias - <?php echo $qtd_365; ?> avaliações)",
        colors: ['#13A330'],
        legend: { position: "none" },
        vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico4"));
      chart.draw(view, options);
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
    <div class="row">
        <div class="col-sm" style="text-align: center;">
            <h2 class="high-text"><?php echo $setor->getNome(); ?></h2>
            <h6 class="text"><?php echo $setor->getLocal(); ?></h6>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <input type="button" class="button button2" data-toggle="modal" data-target="#modal" value="Informações de <?php echo $setor->getNome() ?>"></a>
        </div>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $setor->isGestorIn($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) { ?>
            <div class="col-sm">
                <input type="button" class="button button2" value="Gerenciar funcionários" id="btnGerenciar" onclick="mostrarGerenciar();">
            </div>
            <div class="col-sm">
                <input type="button" class="button button2" id="btnEdita" onclick="mostraEdicao();" value="Editar <?php echo $setor->getNome() ?>">
            </div> 
            <?php if($setor->getAtivo() == 1) { ?>
            <div class="col-sm">
                <a href="../database/setor.php?desativa=true&id=<?php echo $setor->getID(); ?>"><input type="button" class="button button3" value="Desativar setor"></a>
            </div>
            <?php } else if ($setor->getAtivo() == 0) { ?>
            <div class="col-sm">
                <a href="../database/setor.php?reativa=true&id=<?php echo $setor->getID(); ?>"><input type="button" class="button button3" value="Reativar setor"></a>
            </div>
            <?php } ?>
        <?php } ?>
    </div>

    <hr class="hr-divide">
</div>
<div class="container">

    <!-- ------------------------- FORM PARA EDIÇÃO DE DADOS --------------------- -->
    <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $setor->isGestorIn($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) { ?>
    <div id="edicao" style="display: none;">
    
            <div class="row">
                <div class="col-sm">
                    <form method="POST" action="../database/setor.php?atualiza=true" id="form">
                    <label for="nome" class="text">Nome *</label>
                    <input type="text" name="nome" id="nome" value="<?php echo $setor->getNome(); ?>" class="all-input" maxlength="50" required="">
                </div>
                <div class="col-sm">
                    <label for="local" class="text">Local</label>
                    <input type="text" name="local" id="local" value="<?php echo $setor->getLocal(); ?>" class="all-input" maxlength="80">
                </div>
                <div class="col-sm">
                    <label for="descricao" class="text">Descrição</label>
                    <textarea name="descricao" id="descricao" class="all-input" maxlength="150"><?php echo $setor->getDescricao(); ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label for="compet1" class="text">Competência 1</label>
                    <input type="text" name="compet1" id="compet1" value="<?php echo $setor->getSessaoUm(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoUm() != "") echo 'readOnly' ?>>
                </div>
                <div class="col-sm">
                    <label for="compet2" class="text">Competência 2</label>
                    <input type="text" name="compet2" id="compet2" value="<?php echo $setor->getSessaoDois(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoDois() != "") echo 'readOnly' ?>>
                </div>
                <div class="col-sm">
                    <label for="compet3" class="text">Competência 3</label>
                    <input type="text" name="compet3" id="compet3" value="<?php echo $setor->getSessaoTres(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoTres() != "") echo 'readOnly' ?>>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label for="compet4" class="text">Competência 4</label>
                    <input type="text" name="compet4" id="compet4" value="<?php echo $setor->getSessaoQuatro(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoQuatro() != "") echo 'readOnly' ?>>
                </div>
                <div class="col-sm">
                    <label for="compet5" class="text">Competência 5</label>
                    <input type="text" name="compet5" id="compet5" value="<?php echo $setor->getSessaoCinco(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoCinco() != "") echo 'readOnly' ?>>
                </div>
                <div class="col-sm">
                    <label for="compet6" class="text">Competência 6</label>
                    <input type="text" name="compet6" id="compet6" value="<?php echo $setor->getSessaoSeis(); ?>" class="all-input" maxlength="30" <?php if($setor->getSessaoSeis() != "") echo 'readOnly' ?>>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <small class="text">As competências não podem ser editadas, apenas adicionadas (caso ainda haja alguma das 6 disponíveis). Caso seja necessário alterar uma competência existe, entre em contato com o suporte, mas esteja ciente de que isso afeta todas as avaliações já realizadas para o setor.</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-2 offset-sm-5">
                    <input type="hidden" name="id" value="<?php echo $setor->getID(); ?>">
                    <input type="submit" class="button button1" value="Atualizar setor">
                </div>
                </form>
            </div>

            <hr class="hr-divide-light">
    </div>
    <?php } ?>

    <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $setor->isGestorIn($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) { ?>

        <div id="gerenciamento" style="display: none;">

            <div class="row">
                <div class="col-sm">
                    <h5 class="text">Adicionar colaboradores</h5>
                    <form action="../database/setor.php?addColaboradores=true" method="POST">
                    <div style="height:7em;; overflow:auto;">
                        <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Adicionar" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $setor->getID(); ?>">
                    </form>
                </div>
                <div class="col-sm">
                    <h5 class="text">Adicionar gestores</h5>
                    <form action="../database/setor.php?addGestores=true" method="POST">
                    <div style="height:7em;; overflow:auto;">
                        <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Adicionar" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $setor->getID(); ?>">
                    </form>
                </div>
                <div class="col-sm">
                    <h5 class="text">Descadastrar gestores</h5>
                    <form action="../database/setor.php?rmvGestores=true" method="POST">
                    <div style="height:7em;; overflow:auto;">
                        <?php $setor->popularSelectGestores($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Remover" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $setor->getID(); ?>">
                    </form>
                </div>
                <div class="col-sm">
                    <h5 class=text">Descadastrar colaboradores</h5>
                    <form action="../database/setor.php?rmvColaboradores=true" method="POST">
                    <div style="height:7em;; overflow:auto;">
                        <?php $setor->popularSelectColaboradores($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Remover" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $setor->getID(); ?>">
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <small class="text">Ao adicionar e/ou remover colaboradores/gestores, você automaticamente altera o vínculo entre eles. 
                    <br>Todos os gestores de um setor têm acesso a avaliações, metas, reuniões, eventos e outras informações sobre o setor e os colaboradores nele inseridos. Ao remover um gestor, o mesmo perde permissão de acesso às informações dos colaboradores do setor (a não ser que o mesmo esteja inserido em outro setor com os mesmos colaborades, desta forma o vínculo é mantido). 
                    <br>Ao adicionar um colaborador, você fornece aos gestores desse setor acesso às informações do colaborador (exceto documentos, feedbacks e mensagens)</small>
                </div>
            </div>

            <?php } ?>

            <hr class="hr-divide-light">
        </div>
</div>
<div class="container">
    
    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
        <div class="row">
            <div class="col-sm" style="text-align: center">
                <h3 class="text">Metas do setor</h3>
                <hr class="hr-divide-super-light">
                <?php 
                $select = "SELECT DISTINCT t2.okr_id as id, t2.okr_titulo as titulo FROM tbl_okr_setor t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE t2.okr_visivel = 1 AND t1.set_id = '$id' 
                ORDER BY t2.okr_titulo DESC";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) {
                    ?>
                        <span class="text">Nenhuma.
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <span class="text"><a href="verOKR.php?id=<?php echo $f['id'] ?>"><?php echo $f['titulo']; ?></a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
    <hr class="hr-divide">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Avaliações do setor</h3>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <br class="text"><b>Média geral do setor</b>
                <?php echo $media_exibe; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="grafico1" style="width: 100%; height: 410px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="grafico2" style="width: 100%; height: 410px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="grafico3" style="width: 100%; height: 410px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="grafico4" style="width: 100%; height: 410px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div id="grafico" style="width: 100%; height: 410px;"></div>
            </div>
        </div>
    <?php } ?>
</div>
</body>


<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $setor->getNome(); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <h4 class="text">Informações</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Data de criação</b>
                <br class="text"><?php echo $setor->getDataCadastro(); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Data de atualização</b>
                <br class="text"><?php echo $setor->getDataAlteracao(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Local do setor</b>
                <br class="text"><?php echo $setor->getLocal(); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Descrição do setor</b>
                <br class="text"><?php echo $setor->getDescricao(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Gestores responsáveis</b>
                <br class="text"><?php echo $setor->listarGestores($_SESSION['empresa']['database']); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Colaboradores</b>
                <br class="text"><?php echo $setor->listarColaboradores($_SESSION['empresa']['database']); ?>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h4 class="text">Competências</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Competência Um</b>
                <br class="text"><?php echo $setor->getSessaoUm(); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Competência Dois</b>
                <br class="text"><?php echo $setor->getSessaoDois(); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Competência Três</b>
                <br class="text"><?php echo $setor->getSessaoTres(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Competência Quatro</b>
                <br class="text"><?php echo $setor->getSessaoQuatro(); ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Competência Cinco</b>
                <br class="text"><?= $setor->getSessaoCinco() == "" ? "Não utilizada" : $setor->getSessaoCinco() ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Competência Seis</b>
                <br class="text"><?= $setor->getSessaoSeis() == "" ? "Não utilizada" : $setor->getSessaoSeis() ?>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h4 class="text">Números</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Avaliações recebidas</b>
                <br class="text"><?php echo $num_avaliacoes; ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Pessoas diferentes que avaliaram</b>
                <br class="text"><?php echo $num_avaliadores; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <br class="text"><b>Total de gestores</b>
                <br class="text"><?php echo $num_gestores; ?>
            </div>
            <div class="col-sm">
                <br class="text"><b>Total de colaboradores</b>
                <br class="text"><?php echo $num_colaboradores; ?>
            </div>
        </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


</html>