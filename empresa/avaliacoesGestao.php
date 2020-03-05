<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_avaliacao_gestao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if(!isset($_GET['codigo']) && !isset($_GET['semfiltro']) && !isset($_GET['gestor']) && !isset($_GET['setor'])) {
      header('Location: avaliacaoGestao.php');
      die();
    }

    $gestor = new Gestor();
    $setor = new Setor();

    $avaliacao = new AvaliacaoGestao();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $condicao = "";
    $filtros = "";

    if(isset($_GET['codigo'])) {
      $codigo = $_GET['codigo'];
      $condicao .= "WHERE cod_string = '$codigo'";
      $filtros = 'Código '.$codigo.' - Todos os gestores - Todo o período';
    } else {  
      if(!isset($_GET['semfiltro']) && $_POST['gestor'] == "all" && $_POST['setor'] == "all") {
        $condicao .= "WHERE avg_data_criacao >= '".$_POST['dataI']."' AND avg_data_criacao <= '".$_POST['dataF']."'";
        $dataI =date_create($_POST['dataI']);
        $dataF =date_create($_POST['dataF']);
        $filtros .= date_format($dataI,"d/m/Y").' a '.date_format($dataF,"d/m/Y");
      } else if (!isset($_GET['semfiltro']) && $_POST['gestor'] != "all" && $_POST['setor'] != "all") {
        $condicao .= "WHERE avg_data_criacao >= '".$_POST['dataI']."' AND avg_data_criacao <= '".
        $_POST['dataF']."' AND ges_cpf = '".$_POST['gestor']."' AND set_id = ".
        $_POST['setor'];
        $dataI = date_create($_POST['dataI']);
        $dataF = date_create($_POST['dataF']);
        $gestor->setCpf($_POST['gestor']);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $setor->setID($_POST['setor']);
        $setor = $setor->retornarSetor($_SESSION['empresa']['database']);
        $filtros .= date_format($dataI,"d/m/Y").' a '.date_format($dataF,"d/m/Y").
        "<br>Gestor: ".$gestor->getNomeCompleto()."<br>Setor: ".$setor->getNome();
      } else if (!isset($_GET['semfiltro']) && $_POST['gestor'] != "all" && $_POST['setor'] == "all") {
        $condicao .= "WHERE avg_data_criacao >= '".$_POST['dataI']."' AND avg_data_criacao <= '".
        $_POST['dataF']."' AND ges_cpf = '".$_POST['gestor']."'";
        $dataI = date_create($_POST['dataI']);
        $dataF = date_create($_POST['dataF']);
        $gestor->setCpf($_POST['gestor']);
        $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $filtros .= date_format($dataI,"d/m/Y").' a '.date_format($dataF,"d/m/Y").
        "<br>Gestor: ".$gestor->getNomeCompleto();
      } else if (!isset($_GET['semfiltro']) && $_POST['gestor'] == "all" && $_POST['setor'] != "all") {
        $condicao .= "WHERE avg_data_criacao >= '".$_POST['dataI']."' AND avg_data_criacao <= '".
        $_POST['dataF']."' AND set_id = ".$_POST['setor'];
        $dataI = date_create($_POST['dataI']);
        $dataF = date_create($_POST['dataF']);
        $setor->setID($_POST['setor']);
        $setor = $setor->retornarSetor($_SESSION['empresa']['database']);
        $filtros .= date_format($dataI,"d/m/Y").' a '.date_format($dataF,"d/m/Y").
        "<br>Setor: ".$setor->getNome();
      } else {
        $filtros = "Todo o período";
      }
    }

    $ava_um_obs = "";
    $ava_dois_obs = "";
    $ava_tres_obs = "";
    $ava_quatro_obs = "";
    $ava_cinco_obs = "";
    $ava_seis_obs = "";
    $ava_sete_obs = "";
    $ava_oito_obs = "";
    $ava_nove_obs = "";
    $ava_dez_obs = "";

    $ava_um = 0.0;
    $ava_dois = 0.0;
    $ava_tres = 0.0;
    $ava_quatro = 0.0;
    $ava_cinco = 0.0;
    $ava_seis = 0.0;
    $ava_sete = 0.0;
    $ava_oito = 0.0;
    $ava_nove = 0.0;
    $ava_dez = 0.0;

    $select = "SELECT ROUND(AVG(avg_sessao_um), 1) as um, 
    ROUND(AVG(avg_sessao_dois), 1) as dois, 
    ROUND(AVG(avg_sessao_tres), 1) as tres, 
    ROUND(AVG(avg_sessao_quatro), 1) as quatro, 
    ROUND(AVG(avg_sessao_cinco), 1) as cinco, 
    ROUND(AVG(avg_sessao_seis), 1) as seis,
    ROUND(AVG(avg_sessao_sete), 1) as sete, 
    ROUND(AVG(avg_sessao_oito), 1) as oito, 
    ROUND(AVG(avg_sessao_nove), 1) as nove,
    ROUND(AVG(avg_sessao_dez), 1) as dez, 
    COUNT(avg_id) as tot
    FROM tbl_avaliacao_gestao ".$condicao." ORDER BY avg_data_criacao DESC";

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
        $ava_sete = (float)$fetch['sete'];
        $ava_oito = (float)$fetch['oito'];
        $ava_nove = (float)$fetch['nove'];
        $ava_dez = (float)$fetch['dez'];
    }
    $filtros .= "<br>Nº de avaliações encontradas: ".$num_resultados;

    $select = "SELECT avg_sessao_um_obs as um,
    avg_sessao_dois_obs as dois,
    avg_sessao_tres_obs as tres,
    avg_sessao_quatro_obs as quatro,
    avg_sessao_cinco_obs as cinco,
    avg_sessao_seis_obs as seis,
    avg_sessao_sete_obs as sete,
    avg_sessao_oito_obs as oito,
    avg_sessao_nove_obs as nove,
    avg_sessao_dez_obs as dez,
    DATE_FORMAT(avg_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao_gestao ".$condicao." ORDER BY avg_data_criacao DESC";

    $query = $helper->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
      $f['um'] != "" ? $ava_um_obs .= '<b>'.$f['data'].'</b>: '.$f['um'].'<br>' : "";
      $f['dois'] != "" ? $ava_dois_obs .= '<b>'.$f['data'].'</b>: '.$f['dois'].'<br>' : "";
      $f['tres'] != "" ? $ava_tres_obs .= '<b>'.$f['data'].'</b>: '.$f['tres'].'<br>' : "";
      $f['quatro'] != "" ? $ava_quatro_obs .= '<b>'.$f['data'].'</b>: '.$f['quatro'].'<br>' : "";
      $f['cinco'] != "" ? $ava_cinco_obs .= '<b>'.$f['data'].'</b>: '.$f['cinco'].'<br>' : "";
      $f['seis'] != "" ? $ava_seis_obs .= '<b>'.$f['data'].'</b>: '.$f['seis'].'<br>' : "";
      $f['sete'] != "" ? $ava_sete_obs .= '<b>'.$f['data'].'</b>: '.$f['sete'].'<br>' : "";
      $f['oito'] != "" ? $ava_oito_obs .= '<b>'.$f['data'].'</b>: '.$f['oito'].'<br>' : "";
      $f['nove'] != "" ? $ava_nove_obs .= '<b>'.$f['data'].'</b>: '.$f['nove'].'<br>' : "";
      $f['dez'] != "" ? $ava_dez_obs .= '<b>'.$f['data'].'</b>: '.$f['dez'].'<br>' : "";
    }
    if($ava_um_obs == "") $ava_um_obs = "Nada encontrado.";
    if($ava_dois_obs == "") $ava_dois_obs = "Nada encontrado.";
    if($ava_tres_obs == "") $ava_tres_obs = "Nada encontrado.";
    if($ava_quatro_obs == "") $ava_quatro_obs = "Nada encontrado.";
    if($ava_cinco_obs == "") $ava_cinco_obs = "Nada encontrado.";
    if($ava_seis_obs == "") $ava_seis_obs = "Nada encontrado.";
    if($ava_sete_obs == "") $ava_sete_obs = "Nada encontrado.";
    if($ava_oito_obs == "") $ava_oito_obs = "Nada encontrado.";
    if($ava_nove_obs == "") $ava_nove_obs = "Nada encontrado.";
    if($ava_dez_obs == "") $ava_dez_obs = "Nada encontrado.";


    //Coletando evolução recente das competências
    $array_evolucao = array();

    $select = "SELECT avg_sessao_um as um, 
    avg_sessao_dois as dois, 
    avg_sessao_tres as tres, 
    avg_sessao_quatro as quatro, 
    avg_sessao_cinco as cinco, 
    avg_sessao_seis as seis,
    avg_sessao_sete as sete, 
    avg_sessao_oito as oito, 
    avg_sessao_nove as nove,
    avg_sessao_dez as dez
    FROM tbl_avaliacao_gestao ORDER BY avg_data_criacao DESC LIMIT 30";

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
        $array_evolucao["sete"][$i] = $f['sete'];
        $array_evolucao["oito"][$i] = $f['oito'];
        $array_evolucao["nove"][$i] = $f['nove'];
        $array_evolucao["dez"][$i] = $f['dez'];
        
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
      $array_evolucao["sete"][$i] = 0;
      $array_evolucao["oito"][$i] = 0;
      $array_evolucao["nove"][$i] = 0;
      $array_evolucao["dez"][$i] = 0;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resultados de avaliações da gestão</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Nota"],
        ['<?php echo $_SESSION['empresa']['avg_sessao_um']; ?>', <?php echo $ava_um; ?>],
        ['<?php echo $_SESSION['empresa']['avg_sessao_dois']; ?>', <?php echo $ava_dois; ?>],
        ['<?php echo $_SESSION['empresa']['avg_sessao_tres']; ?>', <?php echo $ava_tres; ?>],
        ['<?php echo $_SESSION['empresa']['avg_sessao_quatro']; ?>', <?php echo $ava_quatro; ?>]
        <?php if ($_SESSION['empresa']['avg_sessao_cinco'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_cinco']; ?>', <?php echo $ava_cinco; ?>]
        <?php } ?>
        <?php if ($_SESSION['empresa']['avg_sessao_seis'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_seis']; ?>', <?php echo $ava_seis; ?>]
        <?php } ?>
        <?php if ($_SESSION['empresa']['avg_sessao_sete'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_sete']; ?>', <?php echo $ava_sete; ?>]
        <?php } ?>
        <?php if ($_SESSION['empresa']['avg_sessao_oito'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_oito']; ?>', <?php echo $ava_oito; ?>]
        <?php } ?>
        <?php if ($_SESSION['empresa']['avg_sessao_nove'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_nove']; ?>', <?php echo $ava_nove; ?>]
        <?php } ?>
        <?php if ($_SESSION['empresa']['avg_sessao_dez'] != "") { ?>,
        ['<?php echo $_SESSION['empresa']['avg_sessao_dez']; ?>', <?php echo $ava_dez; ?>]
        <?php } ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Média das competências da empresa dentro do período",
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
          ['<?php echo $_SESSION['empresa']['avg_sessao_um']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["um"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_um']; ?> - Últimas 30 avaliações',
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
          ['<?php echo $_SESSION['empresa']['avg_sessao_dois']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["dois"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_dois']; ?> - Últimas 30 avaliações',
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
          ['<?php echo $_SESSION['empresa']['avg_sessao_tres']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["tres"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_tres']; ?> - Últimas 30 avaliações',
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
          ['<?php echo $_SESSION['empresa']['avg_sessao_quatro']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["quatro"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_quatro']; ?> - Últimas 30 avaliações',
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

  <?php if($_SESSION['empresa']['avg_sessao_cinco'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA CINCO -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_cinco']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["cinco"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_cinco']; ?> - Últimas 30 avaliações',
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


    <?php if($_SESSION['empresa']['avg_sessao_seis'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA SEIS -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_seis']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["seis"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_seis']; ?> - Últimas 30 avaliações',
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


    <?php if($_SESSION['empresa']['avg_sessao_sete'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA SETE -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_sete']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["sete"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_sete']; ?> - Últimas 30 avaliações',
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

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_sete'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['avg_sessao_oito'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA OITO -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_oito']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["oito"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_oito']; ?> - Últimas 30 avaliações',
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

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_oito'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['avg_sessao_nove'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA NOVE -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_nove']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["nove"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_nove']; ?> - Últimas 30 avaliações',
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

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_nove'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>


    <?php if($_SESSION['empresa']['avg_sessao_dez'] != "") { ?>
  <!-- GRÁFICO DA COMPETÊNCIA DEZ -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $_SESSION['empresa']['avg_sessao_dez']; ?>', 'Nota'],
          <?php for($i = 30; $i > 0; $i--) { ?>
          ['',  <?php echo $array_evolucao["dez"][$i]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Evolução de <?php echo $_SESSION['empresa']['avg_sessao_dez']; ?> - Últimas 30 avaliações',
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

        var chart = new google.visualization.LineChart(document.getElementById('evolucao_dez'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>
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
            <li class="breadcrumb-item"><a href="avaliacaoGestao.php">Avaliação da Gestão</a></li>
            <li class="breadcrumb-item active" aria-current="page">Resultados das Avaliações da Gestão</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Avaliações da gestão</h2>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Filtros utilizados: <?php echo $filtros; ?></h6>
        </div>  
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="printable/avaliacoesGestao.php?condicao=<?php echo $condicao ?>&filtros=<?php echo $filtros ?>" target="_blank"><button class="button button3">Imprimir relatório</button></a>
        </div>  
    </div>

    <hr class="hr-divide">

</div>

<div class="container">

    <div id="grafico" style="width: 100%; height: 410px;"></div>

    <?php if($_SESSION['user']['permissao'] != "COLABORADOR") { ?>
      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h3 class="text"><i>Feedbacks</i> dos colaboradores</h3>
        </div>
      </div>

      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_um']; ?></h4>
          
          <p class="text">
            <?php echo $ava_um_obs; ?>
          </p>
        </div>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_dois']; ?></h4>
          
          <p class="text">
            <?php echo $ava_dois_obs; ?>
          </p>
        </div>
      </div>
      <div class="row" style="text-align: center;">
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_tres']; ?></h4>
          
          <p class="text">
            <?php echo $ava_tres_obs; ?>
          </p>
        </div>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_quatro']; ?></h4>
          
          <p class="text">
            <?php echo $ava_quatro_obs; ?>
          </p>
        </div>
      </div>
      <div class="row" style="text-align: center;">
        <?php if($_SESSION['empresa']['avg_sessao_cinco'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_cinco']; ?></h4>
          
          <p class="text">
            <?php echo $ava_cinco_obs; ?>
          </p>
        </div>
        <?php } ?>
        <?php if($_SESSION['empresa']['avg_sessao_seis'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_seis']; ?></h4>
          
          <p class="text">
            <?php echo $ava_seis_obs; ?>
          </p>
        </div>
        <?php } ?>
      </div>
      <div class="row" style="text-align: center;">
        <?php if($_SESSION['empresa']['avg_sessao_sete'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_sete']; ?></h4>
          
          <p class="text">
            <?php echo $ava_sete_obs; ?>
          </p>
        </div>
        <?php } ?>
        <?php if($_SESSION['empresa']['avg_sessao_oito'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_oito']; ?></h4>
          
          <p class="text">
            <?php echo $ava_oito_obs; ?>
          </p>
        </div>
        <?php } ?>
      </div>
      <div class="row" style="text-align: center;">
        <?php if($_SESSION['empresa']['avg_sessao_nove'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_nove']; ?></h4>
          
          <p class="text">
            <?php echo $ava_nove_obs; ?>
          </p>
        </div>
        <?php } ?>
        <?php if($_SESSION['empresa']['avg_sessao_dez'] != "") { ?>
        <div class="col-sm">
          <h4 class="text"><?php echo $_SESSION['empresa']['avg_sessao_dez']; ?></h4>
          
          <p class="text">
            <?php echo $ava_dez_obs; ?>
          </p>
        </div>
        <?php } ?>
      </div>
    <?php } ?>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
       <div class="col-sm">
          <h3 class="text">Evolução de cada competência de <?php echo $_SESSION['empresa']['nome']; ?></h3>
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

    <?php if($_SESSION['empresa']['avg_sessao_cinco'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_cinco" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['avg_sessao_seis'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_seis" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['avg_sessao_sete'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_sete" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['avg_sessao_oito'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_oito" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['avg_sessao_nove'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_nove" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>

    <?php if($_SESSION['empresa']['avg_sessao_dez'] != "") { ?>
      <div class="row">
       <div class="col-sm">
          <div id="evolucao_dez" style="width: 100%; height: 410px;"></div>
       </div>
      </div>
    <?php } ?>
</div>
</body>
</html>