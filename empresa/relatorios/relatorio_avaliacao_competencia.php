<?php

    include('../../include/auth.php');
    include('../../src/meta.php');
    require_once('../../classes/class_conexao_empresa.php');
    require_once('../../classes/class_queryHelper.php');
    require_once('../../classes/class_colaborador.php');
    require_once('../../classes/class_gestor.php');
    require_once('../../classes/class_avaliacao.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    
    $array_compet = $_POST['compet'];
    $competencias = explode("|", $array_compet);
    $competencia = $competencias[0]; //É NECESSÁRIO SELECIONAR 1 COMPETÊNCIA PARA ESTE RELATÓRIO
    $cpf = $_POST['colaborador']; //BLOQUEAR SELECT SE FOR PERMISSÃO COLABORADOR

    if($_POST['dataI'] == "" || $_POST['dataI'] == "0000-00-00") {
      $dataI = '2019-11-01 00:00:00';
    } else {
      $dataI = $_POST['dataI'].' 00:00:00';
    }

    if($_POST['dataF'] == "" || $_POST['dataF'] == "0000-00-00") {
      $dataF = date('Y-m-d H:i:s');
    } else {
      $dataF = $_POST['dataF'].' 23:59:59';
    }

    $condicao = " AND (ava_data_criacao >= '$dataI' AND ava_data_criacao <= '$dataF')";

    if(isset($_POST['gestor']) && $_POST['gestor'] != "") {
      $ges_cpf = $_POST['gestor'];
      $condicao .= " AND ges_cpf = '$ges_cpf'";
      $gestor = new Gestor();
      $gestor->setCpf($ges_cpf);
      $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
    }

    $colaborador = new Colaborador();
    $colaborador->setCpf($cpf);
    $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);

    //QUERY AVALIAÇÃO MAIS ANTIGA E MAIS RECENTE DA COMPETÊNCIA
    $select = "SELECT DATE_FORMAT(MAX(ava_data_criacao), '%d/%m/%Y %H:%i') as mais_recente,
    DATE_FORMAT(MIN(ava_data_criacao), '%d/%m/%Y %H:%i') as mais_antiga
    FROM tbl_avaliacao
    WHERE ava_data_liberacao <= NOW()".$condicao;
    $fetch = $helper->select($select, 2);

    $mais_recente = $fetch['mais_recente'];
    $mais_antiga = $fetch['mais_antiga'];

    //QUERY 5 MAIORES NOTAS DA COMPETÊNCIA
    $select = "SELECT ".$competencia." as compet,
    DATE_FORMAT(ava_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao WHERE col_cpf = '$cpf' AND ava_data_liberacao <= NOW()".$condicao." 
    ORDER BY ".$competencia." DESC LIMIT 5";
    $query_maiores = $helper->select($select, 1);
    $maiores = array(0, 0, 0, 0, 0);
    $datas_maiores = array('Sem dados', 'Sem dados', 'Sem dados', 'Sem dados', 'Sem dados');

    $posicao = 0;
    while($f = mysqli_fetch_assoc($query_maiores)) {
        $maiores[$posicao] = $f['compet'];
        $datas_maiores[$posicao] = $f['data'];
        $posicao++;
    }

    //QUERY 5 MENORES NOTAS DA COMPETENCIA
    $select = "SELECT ".$competencia." as compet,
    DATE_FORMAT(ava_data_criacao, '%d/%m/%Y') as data
    FROM tbl_avaliacao WHERE col_cpf = '$cpf' AND ava_data_liberacao <= NOW()
    ".$condicao." ORDER BY ".$competencia." ASC LIMIT 5";
    $query_menores = $helper->select($select, 1);
    $menores = array(0, 0, 0, 0, 0);
    $datas_menores = array('Sem dados', 'Sem dados', 'Sem dados', 'Sem dados', 'Sem dados');

    $posicao = 0;
    while($f = mysqli_fetch_assoc($query_menores)) {
        $menores[$posicao] = $f['compet'];
        $datas_menores[$posicao] = $f['data'];
        $posicao++;
    }

    //QUERY PARA USAR NO RELATÓRIO, COM O HISTÓRICO COMPLETO
    $select = "SELECT DATE_FORMAT(ava_data_criacao, '%d/%m/%Y %H:%i:%s') as data,
    ".$competencia." as compet, 
    ".$competencia."_obs as obs, ges_cpf as cpf
    FROM tbl_avaliacao WHERE col_cpf = '$cpf' AND ava_data_liberacao <= NOW()
    ".$condicao." ORDER BY ava_data_criacao DESC";
    $query = $helper->select($select, 1);
    $num_avaliacoes = mysqli_num_rows($query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Avaliação Por Competência</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Data", "Nota"],
        ['<?php echo $datas_maiores[0]; ?>', <?php echo $maiores[0]; ?>],
        ['<?php echo $datas_maiores[1]; ?>', <?php echo $maiores[1]; ?>],
        ['<?php echo $datas_maiores[2]; ?>', <?php echo $maiores[2]; ?>],
        ['<?php echo $datas_maiores[3]; ?>', <?php echo $maiores[3]; ?>],
        ['<?php echo $datas_maiores[4]; ?>', <?php echo $maiores[4]; ?>]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Melhores períodos de <?php echo $competencias[1]; ?>",
        width: 1200,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        colors: ['#13A330'],
        vAxis: {
          viewWindow: {
            min: 0,
            max: 5.5
          },
          ticks: [0, 1, 2, 3, 4, 5]
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico"));
      chart.draw(view, options);
  }
  </script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Data", "Nota"],
        ['<?php echo $datas_menores[0]; ?>', <?php echo $menores[0]; ?>],
        ['<?php echo $datas_menores[1]; ?>', <?php echo $menores[1]; ?>],
        ['<?php echo $datas_menores[2]; ?>', <?php echo $menores[2]; ?>],
        ['<?php echo $datas_menores[3]; ?>', <?php echo $menores[3]; ?>],
        ['<?php echo $datas_menores[4]; ?>', <?php echo $menores[4]; ?>]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Piores períodos de <?php echo $competencias[1]; ?>",
        width: 1200,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        colors: ['#13A330'],
        vAxis: {
          viewWindow: {
            min: 0,
            max: 5.5
          },
          ticks: [0, 1, 2, 3, 4, 5]
        }
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("grafico1"));
      chart.draw(view, options);
  }
  </script>
</head>
<body style="margin-top: 0em;">
<div class="container">
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

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text">RELATÓRIO DE AVALIAÇÃO POR COMPETÊNCIA</h3>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="high-text"><?php echo $colaborador->getNomeCompleto(); ?></h4>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="high-text">Competência: <?php echo $competencias[1]; ?></h5>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="high-text">Avaliações encontradas: <?php echo $num_avaliacoes; ?></h5>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="high-text">Mais antiga: <?php echo $mais_antiga; ?></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">Mais recente: <?php echo $mais_recente; ?></h3>
        </div>
    </div>
      <?php if(isset($_POST['gestor']) && $_POST['gestor'] != "") { ?>
      <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="high-text">Gestor: <?php echo $gestor->getNomeCompleto(); ?></h3>
        </div>
      </div>
      <?php } ?>
    </div>

    <hr class="hr-divide-light">

    <?php 
    if($num_avaliacoes == 0) {
      echo '<h2 class="text">Não foram encontrados dados para os filtros selecionados</h2>'; 
      die();
    }  
    ?>

    <div class="row">
      <div class="col-sm">
        <div id="grafico" style="width: 100%; height: 410px;"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm">
        <div id="grafico1" style="width: 100%; height: 410px;"></div>
      </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center; margin-bottom: 1em;">
        <div class="col-sm">
          <h4 class="text">Observações das avaliações</h4>
        </div>
    </div>
    <?php
      $string = "";
      while($fetch = mysqli_fetch_assoc($query)) {
          $nota = $fetch['compet'];
          $data = $fetch['data'];
          $fetch['obs'] == "" ? $obs = "Nada observado" : $obs = $fetch['obs'];

          $gestor = new Gestor();
          $gestor->setCpf($fetch['cpf']);
          $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

          $string .= '
          <div class="row" style="text-align: center; margin-bottom: 1em;">
            <div class="col-sm">
              <br><span class="text"><b>'.$data.'</b> - Nota: '.$nota.' - Gestor: '.$gestor->getNomeCompleto().'
              <br>Observações realizadas: '.$obs.'</span>
            </div>
          </div>
          ';
      }

    echo $string;
    ?>
</div>

<script>
  window.print();
</script>