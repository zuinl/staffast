<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_modelo_avaliacao.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
      $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
      módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
      header('Location: home.php');
      die();
  }

    if(!isset($_GET['id'])) {
        header('Location: painelAvaliacao.php');
        die();
    }

    $id = $_GET['id'];
    $cpf_col = base64_decode($_GET['col']);

    $modelo = new ModeloAvaliacao();
    $modelo->setID($id);
    $modelo = $modelo->retornarModeloAvaliacao($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] == "COLABORADOR" && $id != $_SESSION['user']['cpf']) {
      include("../include/acessoNegado.php");
      die();
    }

    $gestor = new Gestor();
    $colaborador = new Colaborador();
        $colaborador->setCpf($cpf_col);
        $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
    $avaliacao = new Avaliacao();
        $avaliacao->setCpfColaborador($cpf_col);
        

        if($_SESSION['user']['permissao'] == "GESTOR-2") {
          if(!$avaliacao->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $cpf_col)) {
              include('../include/acessoNegado.php');
              die();
          }
      }

    //COLETANDO MÉDIAS

    $medias = $avaliacao->calcularMedias($_SESSION['empresa']['database'], $id);

    //

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    //COLETANDO DADOS DAS ÚLTIMAS 10 AVALIAÇÕES

    $ava_um = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dois = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_tres = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quatro = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_cinco = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_seis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_sete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_oito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_nove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dez = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_onze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_doze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_treze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quatorze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_quinze = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezesseis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezessete = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezoito = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_dezenove = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_vinte = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $ava_datas = array("Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados", "Sem dados");

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
    $ava_onze_obs = "";
    $ava_doze_obs = "";
    $ava_treze_obs = "";
    $ava_quatorze_obs = "";
    $ava_quinze_obs = "";
    $ava_dezesseis_obs = "";
    $ava_dezessete_obs = "";
    $ava_dezoito_obs = "";
    $ava_dezenove_obs = "";
    $ava_vinte_obs = "";

    $select = "SELECT ava_sessao_um as um, ava_sessao_dois as dois, ava_sessao_tres as tres, 
    ava_sessao_quatro as quatro, ava_sessao_cinco as cinco, ava_sessao_seis as seis,
    ava_sessao_sete as sete, ava_sessao_oito as oito, ava_sessao_nove as nove,
    ava_sessao_dez as dez, ava_sessao_onze as onze, ava_sessao_doze as doze, 
    ava_sessao_treze as treze, ava_sessao_quatorze as quatorze, ava_sessao_quinze as quinze,
    ava_sessao_dezesseis as dezesseis, ava_sessao_dezessete as dezessete, 
    ava_sessao_dezoito as dezoito, ava_sessao_dezenove as dezenove, ava_sessao_vinte as vinte,
    ava_sessao_um_obs as um_obs, ava_sessao_dois_obs as dois_obs,
    ava_sessao_tres_obs as tres_obs, ava_sessao_quatro_obs as quatro_obs,
    ava_sessao_cinco_obs as cinco_obs, ava_sessao_seis_obs as seis_obs,
    ava_sessao_sete_obs as sete_obs, ava_sessao_oito_obs as oito_obs,
    ava_sessao_nove_obs as nove_obs, ava_sessao_dez_obs as dez_obs,
    ava_sessao_onze_obs as onze_obs, ava_sessao_doze_obs as doze_obs,
    ava_sessao_treze_obs as treze_obs, ava_sessao_quatorze_obs as quatorze_obs,
    ava_sessao_quinze_obs as quinze_obs, ava_sessao_dezesseis_obs as dezesseis_obs,
    ava_sessao_dezessete_obs as dezessete_obs, ava_sessao_dezoito_obs as dezoito_obs,
    ava_sessao_dezenove_obs as dezenove_obs, ava_sessao_vinte_obs as vinte_obs,
    DATE_FORMAT(ava_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao WHERE col_cpf = '$cpf_col' AND ava_data_liberacao < NOW() 
    AND ava_modelo_id = $id ORDER BY ava_data_criacao DESC LIMIT 10";

    $query = mysqli_query($conn, $select);

    $i = 0;
    while($fetch = mysqli_fetch_assoc($query)) {
        $ava_um[$i] = $fetch['um'];
        $ava_dois[$i] = $fetch['dois'];
        $ava_tres[$i] = $fetch['tres'];
        $ava_quatro[$i] = $fetch['quatro'];
        $ava_cinco[$i] = $fetch['cinco'];
        $ava_seis[$i] = $fetch['seis'];
        $ava_sete[$i] = $fetch['sete'];
        $ava_oito[$i] = $fetch['oito'];
        $ava_nove[$i] = $fetch['nove'];
        $ava_dez[$i] = $fetch['dez'];
        $ava_onze[$i] = $fetch['onze'];
        $ava_doze[$i] = $fetch['doze'];
        $ava_treze[$i] = $fetch['treze'];
        $ava_quatorze[$i] = $fetch['quatorze'];
        $ava_quinze[$i] = $fetch['quinze'];
        $ava_dezesseis[$i] = $fetch['dezesseis'];
        $ava_dezessete[$i] = $fetch['dezessete'];
        $ava_dezoito[$i] = $fetch['dezoito'];
        $ava_dezenove[$i] = $fetch['dezenove'];
        $ava_vinte[$i] = $fetch['vinte'];
        $ava_datas[$i] = $fetch['data'];
        $i++; 

        $fetch['um_obs'] != "" ? $ava_um_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['um_obs'] : "";
        $fetch['dois_obs'] != "" ? $ava_dois_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dois_obs'] : "";
        $fetch['tres_obs'] != "" ? $ava_tres_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['tres_obs'] : "";
        $fetch['quatro_obs'] != "" ? $ava_quatro_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatro_obs'] : "";
        $fetch['cinco_obs'] != "" ? $ava_cinco_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['cinco_obs'] : "";
        $fetch['seis_obs'] != "" ? $ava_seis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['seis_obs'] : "";
        $fetch['sete_obs'] != "" ? $ava_sete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['sete_obs'] : "";
        $fetch['oito_obs'] != "" ? $ava_oito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['oito_obs'] : "";
        $fetch['nove_obs'] != "" ? $ava_nove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['nove_obs'] : "";
        $fetch['dez_obs'] != "" ? $ava_dez_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dez_obs'] : "";
        $fetch['onze_obs'] != "" ? $ava_onze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['onze_obs'] : "";
        $fetch['doze_obs'] != "" ? $ava_doze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['doze_obs'] : "";
        $fetch['treze_obs'] != "" ? $ava_treze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['treze_obs'] : "";
        $fetch['quatorze_obs'] != "" ? $ava_quatorze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quatorze_obs'] : "";
        $fetch['quinze_obs'] != "" ? $ava_quinze_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['quinze_obs'] : "";
        $fetch['dezesseis_obs'] != "" ? $ava_dezesseis_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezesseis_obs'] : "";
        $fetch['dezessete_obs'] != "" ? $ava_dezessete_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezessete_obs'] : "";
        $fetch['dezoito_obs'] != "" ? $ava_dezoito_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezoito_obs'] : "";
        $fetch['dezenove_obs'] != "" ? $ava_dezenove_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['dezenove_obs'] : "";
        $fetch['vinte_obs'] != "" ? $ava_vinte_obs .= "<br><b>".$fetch['data'].": </b>".$fetch['vinte_obs'] : "";
    }

    if($ava_um_obs == "") $ava_um_obs = "<br>Nada encontrado.";
    if($ava_dois_obs == "") $ava_dois_obs = "<br>Nada encontrado.";
    if($ava_tres_obs == "") $ava_tres_obs = "<br>Nada encontrado.";
    if($ava_quatro_obs == "") $ava_quatro_obs = "<br>Nada encontrado.";
    if($ava_cinco_obs == "") $ava_cinco_obs = "<br>Nada encontrado.";
    if($ava_seis_obs == "") $ava_seis_obs = "<br>Nada encontrado.";
    if($ava_sete_obs == "") $ava_sete_obs = "<br>Nada encontrado.";
    if($ava_oito_obs == "") $ava_oito_obs = "<br>Nada encontrado.";
    if($ava_nove_obs == "") $ava_nove_obs = "<br>Nada encontrado.";
    if($ava_dez_obs == "") $ava_dez_obs = "<br>Nada encontrado.";
    if($ava_onze_obs == "") $ava_onze_obs = "<br>Nada encontrado.";
    if($ava_doze_obs == "") $ava_doze_obs = "<br>Nada encontrado.";
    if($ava_treze_obs == "") $ava_treze_obs = "<br>Nada encontrado.";
    if($ava_quatorze_obs == "") $ava_quatorze_obs = "<br>Nada encontrado.";
    if($ava_quinze_obs == "") $ava_quinze_obs = "<br>Nada encontrado.";
    if($ava_dezesseis_obs == "") $ava_dezesseis_obs = "<br>Nada encontrado.";
    if($ava_dezessete_obs == "") $ava_dezessete_obs = "<br>Nada encontrado.";
    if($ava_dezoito_obs == "") $ava_dezoito_obs = "<br>Nada encontrado.";
    if($ava_dezenove_obs == "") $ava_dezenove_obs = "<br>Nada encontrado.";
    if($ava_vinte_obs == "") $ava_vinte_obs = "<br>Nada encontrado.";

    //

    $select = "SELECT COUNT(ava_id) as total FROM tbl_avaliacao WHERE col_cpf = '$cpf_col' AND ava_modelo_id = $id 
    AND ava_data_liberacao <= NOW()";
    $f = $helper->select($select, 2);
    $total_avaliacoes = $f['total'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Resultados de avaliações</title>
    <script>
        function verAvaliacaoModelo(id_col) {
          var id = document.getElementById("avaliacao_modelo").value;

          if(id == 0 || id == "0") {
            alert("Selecione uma avaliação com modelo para visualizar");
            return true;
          }

          window.location.href = "verAvaliacaoModelo.php?id="+id+"&col="+id_col;
        }
      </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getUm(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_um[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getUm(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDois(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dois[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDois(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart1'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getTres(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_tres[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getTres(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart2'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getQuatro(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quatro[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getQuatro(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart3'));

        chart.draw(data, options);
      }
    </script>

    <?php if($modelo->getCinco() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getCinco(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_cinco[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getCinco(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart4'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getSeis() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getSeis(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_seis[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getSeis(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart5'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getSete() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getSete(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_sete[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getSete(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart6'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getOito() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getOito(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_oito[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getOito(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart7'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getNove() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getNove(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_nove[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getNove(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart8'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDez() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDez(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dez[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDez(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart9'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getOnze() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getOnze(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_onze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getOnze(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart10'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDoze() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDoze(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_doze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDoze(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart11'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getTreze() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getTreze(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_treze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getTreze(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart12'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getQuatorze() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getQuatorze(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quatorze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getQuatorze(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart13'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getQuinze() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getQuinze(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_quinze[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getQuinze(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart14'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDezesseis() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDezesseis(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezesseis[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDezesseis(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart15'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDezessete() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDezessete(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezessete[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDezessete(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart16'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDezoito() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDezoito(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezoito[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDezoito(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart17'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getDezenove() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getDezenove(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_dezenove[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getDezenove(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart18'));

        chart.draw(data, options);
      }
    </script>
    <?php } ?>

    <?php if($modelo->getVinte() != "") { ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $modelo->getVinte(); ?>', 'Nota do Gestor'],
          <?php for ($a = sizeof($ava_datas) - 1; $a >= 0; $a--) { ?>
          ['<?php echo $ava_datas[$a] ?>',  <?php echo $ava_vinte[$a]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: '<?php echo $modelo->getVinte(); ?> ',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#13A330', '#1386A3'],
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.8
            },
            ticks: [0, 1, 2, 3, 4, 5] // display labels every 25
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart19'));

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
        <li class="breadcrumb-item"><a href="./">Início</a></li>
        <li class="breadcrumb-item"><a href="painelAvaliacao.php">Painel de Avaliações</a></li>
        <li class="breadcrumb-item" aria-current="page"><a href="resultados.php?id=<?php echo base64_encode($cpf_col); ?>">Relatório de resultados</a></li>
        <li class="breadcrumb-item active" aria-current="page">Relatório de resultados de <?php echo $colaborador->getNomeCompleto(); ?> - Modelo <?php echo $modelo->getTitulo(); ?></li>

    </ol>
</nav>
<!-- FIM DA NAV DE CAMINHO DE TELA -->
</div> 
<div class="container">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h3 class="high-text">Relatório de resultados</h3>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <a href="printable/resultadosModelo.php?col=<?php echo base64_encode($colaborador->getCpf()); ?>&id=<?php echo $id; ?>" target="_blank"><button class="button button3">Imprimir este relatório</button></a>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <small class="text">Apenas as avaliações realizadas com o modelo <?php echo $modelo->getTitulo() ?> estão sendo contabilizadas nos dados deste relatório.</small>
      </div>
    </div>

    <hr class="hr-divide">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h5 class="text">Informações deste relatório</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <br class="text"><b>Colaborador</b>
            <br class="text"><a href="perfilColaborador.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>" target="blank_"><?php echo $colaborador->getNomeCompleto(); ?></a>
        </div> 
        <div class="col-sm">
            <br class="text"><b>Modelo de avaliação</b>
            <br class="text"><?php echo $modelo->getTitulo(); ?>
        </div> 
        <div class="col-sm">
            <br class="text"><b>Quantidade de avaliações com este modelo</b>
            <br class="text"><?php echo $total_avaliacoes; ?>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
          <h5 class="text">Médias de todo o período</h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <?php echo $modelo->getUm(); ?>: <?php echo number_format($medias[1], 1, ',', ''); ?>
        <br><?php echo $modelo->getTres(); ?>: <?php echo number_format($medias[3], 1, ',', ''); ?>
        <?php if($modelo->getCinco() != "") { ?><?php echo '<br>'.$modelo->getCinco(); ?>: <?php echo number_format($medias[5], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getSete() != "") { ?><?php echo '<br>'.$modelo->getSete(); ?>: <?php echo number_format($medias[7], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getNove() != "") { ?><?php echo '<br>'.$modelo->getNove(); ?>: <?php echo number_format($medias[9], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getOnze() != "") { ?><?php echo '<br>'.$modelo->getOnze(); ?>: <?php echo number_format($medias[11], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getTreze() != "") { ?><?php echo '<br>'.$modelo->getTreze(); ?>: <?php echo number_format($medias[13], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getQuinze() != "") { ?><?php echo '<br>'.$modelo->getQuinze(); ?>: <?php echo number_format($medias[15], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDezessete() != "") { ?><?php echo '<br>'.$modelo->getDezessete(); ?>: <?php echo number_format($medias[17], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDezenove() != "") { ?><?php echo '<br>'.$modelo->getDezenove(); ?>: <?php echo number_format($medias[19], 1, ',', ''); ?> <?php } ?>
    </div>
    <div class="col-sm">
        <?php echo $modelo->getDois(); ?>: <?php echo number_format($medias[2], 1, ',', ''); ?>
        <br><?php echo $modelo->getQuatro(); ?>: <?php echo number_format($medias[4], 1, ',', ''); ?>
        <?php if($modelo->getSeis() != "") { ?><?php echo '<br>'.$modelo->getSeis(); ?>: <?php echo number_format($medias[6], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getOito() != "") { ?><?php echo '<br>'.$modelo->getOito(); ?>: <?php echo number_format($medias[8], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDez() != "") { ?><?php echo '<br>'.$modelo->getDez(); ?>: <?php echo number_format($medias[10], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDoze() != "") { ?><?php echo '<br>'.$modelo->getDoze(); ?>: <?php echo number_format($medias[12], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getQuatorze() != "") { ?><?php echo '<br>'.$modelo->getQuatorze(); ?>: <?php echo number_format($medias[14], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDezesseis() != "") { ?><?php echo '<br>'.$modelo->getDezesseis(); ?>: <?php echo number_format($medias[16], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getDezoito() != "") { ?><?php echo '<br>'.$modelo->getDezoito(); ?>: <?php echo number_format($medias[18], 1, ',', ''); ?> <?php } ?>
        <?php if($modelo->getVinte() != "") { ?><?php echo '<br>'.$modelo->getVinte(); ?>: <?php echo number_format($medias[20], 1, ',', ''); ?> <?php } ?>
    </div>
  </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h5 class="text">Visualizar avaliações feitas com o modelo <?php echo $modelo->getTitulo(); ?></h5>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <label class="text">Selecione uma avaliaçao</label>
        <select class="all-input" name="avaliacao_modelo" id="avaliacao_modelo">
            <option selected value="0">-- Selecione --</option>
            <?php
            $cpf = $colaborador->getCpf();
            $select = "SELECT t1.ava_id as id, 
            CONCAT(DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y'), ' - Avaliado por ', t2.ges_primeiro_nome) as avaliacao,
            t3.titulo as titulo 
            FROM tbl_avaliacao t1 
            INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
            INNER JOIN tbl_modelo_avaliacao t3 ON t3.id = t1.ava_modelo_id
            WHERE t1.col_cpf = '$cpf_col' AND t1.ava_data_liberacao <= NOW() 
            AND t1.ava_modelo_id = $id ORDER BY t1.ava_data_criacao DESC";
            $query = $helper->select($select, 1);
            while($fetch = mysqli_fetch_assoc($query)) {
              echo '<option value="'.$fetch['id'].'">'.$fetch['avaliacao'].' - Modelo: '.$fetch['titulo'].'</option>';
            }
            ?>
        </select>
        <small class="text">Aqui aparecem as avaliações já liberadas</small>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <input type="button" id="btnAvaliacaoModelo" value="Ver resultados da avaliação com modelo" class="button button2" onclick="verAvaliacaoModelo('<?php echo base64_encode($colaborador->getCpf()); ?>');">
      </div>
    </div>

    <hr class="hr-divide">

    <div class="row">
        <div class="col-sm" style="text-align: center;">
            <h3 class="text">Histórico recente de <?php echo $colaborador->getPrimeiroNome(); ?></h3>
        </div>  
    </div>

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getUm(); ?></b><?php echo $ava_um_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart1" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDois(); ?></b><?php echo $ava_dois_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart2" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getTres(); ?></b><?php echo $ava_tres_obs; ?>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart3" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getQuatro(); ?></b><?php echo $ava_quatro_obs; ?>
      </div>
    </div>

    <?php if($modelo->getCinco() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart4" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getCinco(); ?></b><?php echo $ava_cinco_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getSeis() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart5" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getSeis(); ?></b><?php echo $ava_seis_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getSete() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart6" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getSete(); ?></b><?php echo $ava_sete_obs; ?>
      </div>
    </div>
    <?php } ?>


    <?php if($modelo->getOito() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart7" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getOito(); ?></b><?php echo $ava_oito_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getNove() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart8" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getNove(); ?></b><?php echo $ava_nove_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDez() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart9" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDez(); ?></b><?php echo $ava_dez_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getOnze() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart10" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getOnze(); ?></b><?php echo $ava_onze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDoze() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart11" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDoze(); ?></b><?php echo $ava_doze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getTreze() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart12" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getTreze(); ?></b><?php echo $ava_treze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getQuatorze() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart13" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getQuatorze(); ?></b><?php echo $ava_quatorze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getQuinze() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart14" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getQuinze(); ?></b><?php echo $ava_quinze_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDezesseis() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart15" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDezesseis(); ?></b><?php echo $ava_dezesseis_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDezessete() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart16" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDezessete(); ?></b><?php echo $ava_dezessete_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDezoito() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart17" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDezoito(); ?></b><?php echo $ava_dezoito_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getDezenove() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart18" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getDezenove(); ?></b><?php echo $ava_dezenove_obs; ?>
      </div>
    </div>
    <?php } ?>

    <?php if($modelo->getVinte() != '') { ?>
    <hr class="hr-divide-super-light">

    <div class="row">
      <div class="col-sm">
          <div id="curve_chart19" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="row" style="text-align: center;">
      <div class="col-sm">
        <h6 class="text">Observações do(s) gestor(es)</h6>
        <b class="text"><?php echo $modelo->getVinte(); ?></b><?php echo $ava_vinte_obs; ?>
      </div>
    </div>
    <?php } ?>

</div>
</body>
</html>