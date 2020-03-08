<?php
    include('../../include/auth.php');
    include('../../src/meta.php');
    require_once('../../classes/class_gestor.php');
    require_once('../../classes/class_setor.php');
    require_once('../../classes/class_avaliacao_setor.php');
    require_once('../../classes/class_queryHelper.php');
    require_once('../../classes/class_conexao_empresa.php');

    $setor = new Setor();
    $setor->setID($_GET['setor']);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] != "GESTOR-1" && !$setor->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) {
        include("../include/acessoNegado.php");
        die();
    }

    $gestor = new Gestor();

    $avaliacao = new AvaliacaoSetor();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT um, dois, tres, quatro, cinco, seis FROM tbl_setor_competencia 
    WHERE set_id = ".$setor->getID(); 
    $fetch = $helper->select($select, 2);

    $um = $fetch['um'];
    $dois = $fetch['dois'];
    $tres = $fetch['tres'];
    $quatro = $fetch['quatro'];
    $cinco = $fetch['cinco'];
    $seis = $fetch['seis'];

    $condicao = $_GET['condicao'];
    $filtros = $_GET['filtros'];

    $ava_um_obs = "";
    $ava_dois_obs = "";
    $ava_tres_obs = "";
    $ava_quatro_obs = "";
    $ava_cinco_obs = "";
    $ava_seis_obs = "";

    $ava_um = 0.0;
    $ava_dois = 0.0;
    $ava_tres = 0.0;
    $ava_quatro = 0.0;
    $ava_cinco = 0.0;
    $ava_seis = 0.0;

    $select = "SELECT ROUND(AVG(um), 1) as um, 
    ROUND(AVG(dois), 1) as dois, 
    ROUND(AVG(tres), 1) as tres, 
    ROUND(AVG(quatro), 1) as quatro, 
    ROUND(AVG(cinco), 1) as cinco, 
    ROUND(AVG(seis), 1) as seis,
    COUNT(avs_id) as tot
    FROM tbl_avaliacao_setor ".$condicao." ORDER BY avs_data_criacao DESC LIMIT 4";

    $query = $helper->select($select, 1);

    $num_resultados = 0;
    $fetch = $helper->select($select, 2);
    if($fetch['um'] != "") {
        $num_resultados = $fetch['tot'];
        $ava_um = (float)$fetch['um'];
        $ava_dois = (float)$fetch['dois'];
        $ava_tres = (float)$fetch['tres'];
        $ava_quatro = (float)$fetch['quatro'];
        $ava_cinco = (float)$fetch['cinco'];
        $ava_seis = (float)$fetch['seis'];
    }
    $filtros .= "<br>Nº de avaliações encontradas: ".$num_resultados;

    $select = "SELECT um_obs as um,
    dois_obs as dois,
    tres_obs as tres,
    quatro_obs as quatro,
    cinco_obs as cinco,
    seis_obs as seis,
    DATE_FORMAT(avs_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao_setor ".$condicao." ORDER BY avs_data_criacao DESC LIMIT 4";

    $query = $helper->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
      $f['um'] != "" ? $ava_um_obs .= '<b>'.$f['data'].'</b>: '.$f['um'].'<br>' : "";
      $f['dois'] != "" ? $ava_dois_obs .= '<b>'.$f['data'].'</b>: '.$f['dois'].'<br>' : "";
      $f['tres'] != "" ? $ava_tres_obs .= '<b>'.$f['data'].'</b>: '.$f['tres'].'<br>' : "";
      $f['quatro'] != "" ? $ava_quatro_obs .= '<b>'.$f['data'].'</b>: '.$f['quatro'].'<br>' : "";
      $f['cinco'] != "" ? $ava_cinco_obs .= '<b>'.$f['data'].'</b>: '.$f['cinco'].'<br>' : "";
      $f['seis'] != "" ? $ava_seis_obs .= '<b>'.$f['data'].'</b>: '.$f['seis'].'<br>' : "";
    }
    if($ava_um_obs == "") $ava_um_obs = "Nada encontrado.";
    if($ava_dois_obs == "") $ava_dois_obs = "Nada encontrado.";
    if($ava_tres_obs == "") $ava_tres_obs = "Nada encontrado.";
    if($ava_quatro_obs == "") $ava_quatro_obs = "Nada encontrado.";
    if($ava_cinco_obs == "") $ava_cinco_obs = "Nada encontrado.";
    if($ava_seis_obs == "") $ava_seis_obs = "Nada encontrado.";


    //Coletando evolução recente das competências
    $array_evolucao = array();

    $select = "SELECT um, dois, tres, quatro, cinco, seis
    FROM tbl_avaliacao_setor ORDER BY avs_data_criacao DESC LIMIT 30";

    $query = $helper->select($select, 1);

    $i = 1;
    if(mysqli_num_rows($query) > 0) {
      while($f = mysqli_fetch_assoc($query)) {
        $array_evolucao["um"][$i] = $f['um'];
        $array_evolucao["dois"][$i] = $f['dois'];
        $array_evolucao["tres"][$i] = $f['tres'];
        $array_evolucao["quatro"][$i] = $f['quatro'];
        $array_evolucao["cinco"][$i] = $f['cinco'];
        $array_evolucao["seis"][$i] = $f['seis'];
        
        $i++;
      }
    }

    for($i = $i; $i <= 30; $i++) {
      $array_evolucao["um"][$i] = 0;
      $array_evolucao["dois"][$i] = 0;
      $array_evolucao["tres"][$i] = 0;
      $array_evolucao["quatro"][$i] = 0;
      $array_evolucao["cinco"][$i] = 0;
      $array_evolucao["seis"][$i] = 0;
    }
?>
<!DOCTYPE html> 
<html>
<head>
    <title>Resultados de avaliações do setor</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Nota"],
        ['<?php echo $um; ?>', <?php echo $ava_um; ?>],
        ['<?php echo $dois; ?>', <?php echo $ava_dois; ?>],
        ['<?php echo $tres; ?>', <?php echo $ava_tres; ?>],
        ['<?php echo $quatro; ?>', <?php echo $ava_quatro; ?>]
        <?php if ($cinco != "") { ?>,
        ['<?php echo $cinco; ?>', <?php echo $ava_cinco; ?>]
        <?php } ?>
        <?php if ($seis != "") { ?>,
        ['<?php echo $seis; ?>', <?php echo $ava_seis; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média das competências do setor dentro do período",
        colors: ['#13A330'],
        width: 1200,
        height: 400,
        bar: {groupWidth: "95%"},
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

  <!-- GRÁFICO DA COMPETÊNCIA UM -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $um; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["um"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $um; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_um'));

        chart.draw(data, options);
      }
    </script>

    <!-- GRÁFICO DA COMPETÊNCIA DOIS -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $dois; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["dois"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $dois; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_dois'));

        chart.draw(data, options);
      }
    </script>

     <!-- GRÁFICO DA COMPETÊNCIA TRÊS -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $tres; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["tres"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $tres; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_tres'));

        chart.draw(data, options);
      }
    </script>

       <!-- GRÁFICO DA COMPETÊNCIA QUATRO -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $quatro; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["quatro"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $quatro; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_quatro'));

        chart.draw(data, options);
      }
    </script>

  <?php if($cinco != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA CINCO -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $cinco; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["cinco"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $cinco; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_cinco'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($seis != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA CINCO -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $seis; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["seis"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $seis; ?> - Últimas 30 avaliações',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_seis'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">

    <div class="row">
        <div class="col-sm-2">
            <img src="../../img/logo_staffast.png" width="110">
        </div>
        <?php if($_SESSION['empresa']['logotipo'] != '') { ?>
        <div class="col-sm-2 offset-sm-8">
            <img src="../img/logos/<?php echo $_SESSION['empresa']['logotipo'] ?>" width="110">
        </div>  
        <?php } ?>
    </div>

      <div class="row">
        <div class="col-sm">
            <h2 class="high-text">Avaliações do setor <?php echo $setor->getNome(); ?></h2>
        </div>
      </div>
      <div class="row">
        <div class="col-sm">
            <h6 class="text">Filtros utilizados: <?php echo $filtros; ?></h6>
        </div>  
      </div>

    <hr class="hr-divide">

    <div id="grafico" style="width: 100%; height: 410px;"></div>

    <?php if($_SESSION['user']['permissao'] != "COLABORADOR") { ?>
      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h3 class="text"><i>Feedbacks</i> dos colaboradores</h3>
        </div>
      </div>

      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h4 class="text"><?php echo $um; ?></h4>
          
          <p class="text">
            <?php echo $ava_um_obs; ?>
          </p>
        </div>
        <div class="col-sm">
          <h4 class="text"><?php echo $dois; ?></h4>
          
          <p class="text">
            <?php echo $ava_dois_obs; ?>
          </p>
        </div>
      </div>
      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h4 class="text"><?php echo $tres; ?></h4>
          
          <p class="text">
            <?php echo $ava_tres_obs; ?>
          </p>
        </div>
        <div class="col-sm">
          <h4 class="text"><?php echo $quatro; ?></h4>
          
          <p class="text">
            <?php echo $ava_quatro_obs; ?>
          </p>
        </div>
      </div>
      <div class="row" style="text-align: center;">
        <?php if($cinco != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $cinco; ?></h4>
          
          <p class="text">
            <?php echo $ava_cinco_obs; ?>
          </p>
        </div>
        <?php } ?>
        <?php if($seis != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $seis; ?></h4>
          
          <p class="text">
            <?php echo $ava_seis_obs; ?>
          </p>
        </div>
        <?php } ?>
      </div>
    <?php } ?>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
       <div class="col-sm">
          <h3 class="text">Evolução de cada competência de <?php echo $setor->getNome(); ?></h3>
          <small class="text">Os filtros não são aplicáveis para os gráficos abaixo, que exibem todo o período</small>
       </div>
    </div>

    <div class="row">
       <div class="col-sm">
          <div id="evolucao_um" style="width: 100%; height: 410px;"></div>
       </div>
    </div>

    <div class="row">
       <div class="col-sm">
          <div id="evolucao_dois" style="width: 100%; height: 410px;"></div>
       </div>
    </div>

    <div class="row">
       <div class="col-sm">
          <div id="evolucao_tres" style="width: 100%; height: 410px;"></div>
       </div>
    </div>

    <div class="row">
       <div class="col-sm">
          <div id="evolucao_quatro" style="width: 100%; height: 410px;"></div>
       </div>
    </div>

    <?php if($cinco != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_cinco" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($seis != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_seis" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

</div>
</body>
</html>

<script>

    window.print();

</script>