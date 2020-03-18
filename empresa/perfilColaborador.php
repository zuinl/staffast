<?php
    include('../include/auth.php');
    include('../src/functions.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_usuario.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_mensagem.php');
    require_once('../classes/class_documento.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if(!isset($_GET['id'])) {
        header('Location: colaboradores.php');
        die();
    }

    $cpf = base64_decode($_GET['id']);

    $colaborador = new Colaborador();
    $colaborador->setCpf($cpf);
    $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);
    
    $usuario = new Usuario();
    $usuario->setID($colaborador->getIDUser());
    $usuario = $usuario->retornarUsuario();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

        $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE col_cpf = '$cpf'";
        $query = $helper->select($select, 1);
        $autoavaliacoes = mysqli_num_rows($query);

        $select = "SELECT ava_id as id FROM tbl_avaliacao WHERE col_cpf = '$cpf' AND ava_data_liberacao <= NOW()";
        $query = $helper->select($select, 1);
        $avaliacoes_receb = mysqli_num_rows($query);

    $ava = new Avaliacao();
    $ava->setCpfColaborador($cpf);
    $medias = $ava->calcularMedias($_SESSION['empresa']['database']);
    $medias_30_ava = $ava->calcularMediasCurtoPrazo($_SESSION['empresa']['database']);
    $medias_90_ava = $ava->calcularMediasMedioPrazo($_SESSION['empresa']['database']);
    $medias_180_ava = $ava->calcularMediasCurtoMedioPrazo($_SESSION['empresa']['database']);
    $medias_365_ava = $ava->calcularMediasLongoPrazo($_SESSION['empresa']['database']);

    $competencias = numCompetencias();

    $mediaTotalAva = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $mediaTotalAva += $medias[$a];
    }
    $mediaTotalAva = round(($mediaTotalAva / $competencias), 1);

    if($avaliacoes_receb > 0) {
        if($mediaTotalAva < 1) $show_media_ava = '<h4 class="text" style="color: red;">'.number_format($mediaTotalAva, 1, ',', '').' <img src="img/unhappy.png" width="30"></h4>';
        else if($mediaTotalAva >= 1 && $mediaTotalAva < 2) $show_media_ava = '<h4 class="text" style="color: red;">'.number_format($mediaTotalAva, 1, ',', '').' <img src="img/sad.png" width="30"></h4>';
        else if($mediaTotalAva >= 2 && $mediaTotalAva < 3.5) $show_media_ava = '<h4 class="text" style="color: orange;">'.number_format($mediaTotalAva, 1, ',', '').' <img src="img/confused.png" width="30"></h4>';
        else if($mediaTotalAva >= 3.5 && $mediaTotalAva < 4.5) $show_media_ava = '<h4 class="text" style="color: green;">'.number_format($mediaTotalAva, 1, ',', '').' <img src="img/smiling.png" width="30"></h4>';
        else if($mediaTotalAva >= 4.5) $show_media_ava = '<h4 class="text" style="color: green;">'.number_format($mediaTotalAva, 1, ',', '').' <img src="img/happy.png" width="30"></h4>';
    } else {
        $show_media_ava = '<h4 class="text">Nunca avaliado.</h4>';
    }

    $media30Ava = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media30Ava += $medias_30_ava[$a];
    }
    $media30Ava = round(($media30Ava / $competencias), 1);

    $media90Ava = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media90Ava += $medias_90_ava[$a];
    }
    $media90Ava = round(($media90Ava / $competencias), 1);

    $media180Ava = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media180Ava += $medias_180_ava[$a];
    }
    $media180Ava = round(($media180Ava / $competencias), 1);

    $media365Ava = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media365Ava += $medias_365_ava[$a];
    }
    $media365Ava = round(($media365Ava / $competencias), 1);

    $ata = new Autoavaliacao();
    $ata->setCpfColaborador($cpf);
    $medias = $ata->calcularMedias($_SESSION['empresa']['database']);
    $medias_30_ata = $ata->calcularMediasCurtoPrazo($_SESSION['empresa']['database']);
    $medias_90_ata = $ata->calcularMediasMedioPrazo($_SESSION['empresa']['database']);
    $medias_180_ata = $ata->calcularMediasCurtoMedioPrazo($_SESSION['empresa']['database']);
    $medias_365_ata = $ata->calcularMediasLongoPrazo($_SESSION['empresa']['database']);

    $mediaTotalAta = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $mediaTotalAta += $medias[$a];
    }
    $mediaTotalAta = round(($mediaTotalAta / $competencias), 1);

    if($autoavaliacoes > 0) {
        if($mediaTotalAta < 1) $show_media_ata = '<h4 class="text" style="color: red;">'.number_format($mediaTotalAta, 1, ',', '').' <img src="img/unhappy.png" width="30"></h4>';
        else if($mediaTotalAta >= 1 && $mediaTotalAta < 2) $show_media_ata = '<h4 class="text" style="color: red;">'.number_format($mediaTotalAta, 1, ',', '').' <img src="img/sad.png" width="30"></h4>';
        else if($mediaTotalAta >= 2 && $mediaTotalAta < 3.5) $show_media_ata = '<h4 class="text" style="color: orange;">'.number_format($mediaTotalAta, 1, ',', '').' <img src="img/confused.png" width="30"></h4>';
        else if($mediaTotalAta >= 3.5 && $mediaTotalAta < 4.5) $show_media_ata = '<h4 class="text" style="color: green;">'.number_format($mediaTotalAta, 1, ',', '').' <img src="img/smiling.png" width="30"></h4>';
        else if($mediaTotalAta >= 4.5) $show_media_ata = '<h4 class="text" style="color: green;">'.number_format($mediaTotalAta, 1, ',', '').' <img src="img/happy.png" width="30"></h4>';
    } else {
        $show_media_ata = '<h4 class="text">Nunca autoavaliado.</h4>';
    }

    $media30Ata = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media30Ata += $medias_30_ata[$a];
    }
    $media30Ata = round(($media30Ata / $competencias), 1);

    $media90Ata = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media90Ata += $medias_90_ata[$a];
    }
    $media90Ata = round(($media90Ata / $competencias), 1);

    $media180Ata = 0.0;
    for($a = 1; $a <= $competencias; $a++) {
        $media180Ata += $medias_180_ata[$a];
    }
    $media180Ata = round(($media180Ata / $competencias), 1);

    $media365Ata = 0.0; 
    for($a = 1; $a <= $competencias; $a++) {
        $media365Ata += $medias_365_ata[$a];
    }
    $media365Ata = round(($media365Ata / $competencias), 1);

    $isAutorizado = false;
    if($_SESSION['user']['permissao'] == "GESTOR-1") {
        $isAutorizado = true;
    } else if ($_SESSION['user']['cpf'] == $cpf) {
        $isAutorizado = true;
    } else if($_SESSION['user']['permissao'] == "GESTOR-2" && $ava->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $cpf)) {
        $isAutorizado = true;
    } else {
        $isAutorizado = false;
    }

    $foto = $colaborador->getFoto() != '' ? 'img/fotos/'.$colaborador->getFoto() : 'img/fotos/person.png';

    include('../src/meta.php');
?>
<!DOCTYPE html> 
<html>
<head>
    <title><?php echo $colaborador->getNomeCompleto(); ?></title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function(){
        $("#nav-setores").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-setores").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-setores").show();
        });
        $("#nav-metas").click(function(){
            $("#nav-setores").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-metas").addClass("active");

            $("#div-setores").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-metas").show();
        });
        $("#nav-docs").click(function(){
            $("#nav-setores").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-docs").addClass("active");

            $("#div-metas").hide();
            $("#div-setores").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-docs").show();
        });
        $("#nav-reunioes").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-reunioes").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-setores").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-reunioes").show();
        });
        $("#nav-eventos").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-eventos").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-setores").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-eventos").show();
        });
        $("#nav-mensagens").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-mensagens").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-setores").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-mensagens").show();
        });
        $("#nav-feedbacks").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-feedbacks").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-setores").hide();
            $("#div-pdis").hide();
            $("#div-ferias").hide();

            $("#div-feedbacks").show();
        });
        $("#nav-pdis").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-ferias").removeClass("active");

            $("#nav-pdis").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-setores").hide();
            $("#div-ferias").hide();

            $("#div-pdis").show();
        });
        $("#nav-ferias").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-setores").removeClass("active");

            $("#nav-ferias").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-setores").hide();

            $("#div-ferias").show();
        });
    });
    </script>
    <script>
        function desativar(cpf) {
            var confirma = confirm("Deseja mesmo desativar este(a) colaborador(a)? Isso fará o cadastro dele(a) ficar oculto e o(a) mesmo(a) não conseguirá mais acessar o Staffast. Você poderá reativá-lo(a), se quiser. Se o(a) colaborador(a) estiver cadastrado(a) como gestor também, esse cadastro permanecerá ativo");
            if(!confirma) return;
            else window.location.href = "../database/colaborador.php?desativa=true&id="+cpf;
        }

        function reativar(cpf) {
            var confirma = confirm("Deseja mesmo reativar este(a) colaborador(a)? Isso fará o cadastro dele(a) ficar visível novamente e o(a) mesmo(a) voltará a acessar o Staffast. Você poderá desativá-lo(a), se quiser.");
            if(!confirma) return;
            else window.location.href = "../database/colaborador.php?reativa=true&id="+cpf;
        }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Competência", "Média"],
        ['Média longo prazo (365 dias)', <?php echo $media365Ava; ?>],
        ['Média curto médio prazo (180 dias)', <?php echo $media180Ava; ?>],
        ['Média médio prazo (90 dias)', <?php echo $media90Ava; ?>],
        ['Média curto prazo (30 dias)', <?php echo $media30Ava; ?>]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Médias de avaliações",
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
        ["Competência", "Média"],
        ['Média longo prazo (365 dias)', <?php echo $media365Ata; ?>],
        ['Média curto médio prazo (180 dias)', <?php echo $media180Ata; ?>],
        ['Média médio prazo (90 dias)', <?php echo $media90Ata; ?>],
        ['Média curto prazo (30 dias)', <?php echo $media30Ata; ?>]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Médias de autoavaliações",
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
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item"><a href="colaboradores.php">Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $colaborador->getNomeCompleto(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA --> 

    <div class="row" style="text-align: center;"> 
        <div class="col-sm">
            <h2 class="high-text"> <img src="<?php echo $foto ?>" width="70" class="img-perfil"><?php echo $colaborador->getNomeCompleto(); ?></h2>
            <h4 class="text"><?php echo $colaborador->getCargo(); ?></h4>
            <h6 class="text"><?php if($colaborador->getIDInterno() != "") echo 'ID interno: '.$colaborador->getIDInterno(); ?></h6>
        </div>
    </div>

    <hr class="hr-divide">

</div>

<div class="container">

    <div class="row">
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $colaborador->getCpf() == $_SESSION['user']['cpf']) { ?>
            <div class="col-sm">
                <a href="novoColaborador.php?editar=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button2">Editar dados</button></a>
            </div>
            <div class="col-sm">
                <input type="button" value="Ver dados de <?php echo $colaborador->getPrimeiroNome(); ?>" class="button button2" data-toggle="modal" data-target="#modal">
            </div>
        <?php } ?>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
            <?php if($colaborador->getAtivo() == 1) { ?>
                <div class="col-sm">
                    <input type="button" value="Desativar colaborador" onclick="desativar('<?php echo base64_encode($colaborador->getCpf()); ?>');" class="button button3">
                </div>
            <?php } else { ?>
                <div class="col-sm">
                    <input type="button" value="Reativar colaborador" onclick="reativar('<?php echo base64_encode($colaborador->getCpf()); ?>');" class="button button3">
                </div>
            <?php } ?> 
        <?php } ?> 
    </div>

    <hr class="hr-divide-super-light">

    <!-- NAV DE NAVEGAÇÃO ENTRE ABAS -->
    <ul class="nav nav-tabs" style="margin-bottom: 2.5em; margin-top: 1.5em;">
        <li class="nav-item">
            <a class="nav-link active" id="nav-setores" href="#">Setores</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-metas" href="#">Metas</a>
        </li>
        <?php if($colaborador->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-docs" href="#">Documentos</a>
        </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-reunioes" href="#">Reuniões</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-eventos" href="#">Eventos</a>
        </li>
        <?php if($colaborador->getCpf() == $_SESSION['user']['cpf']) { ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-mensagens" href="#">Mensagens</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-feedbacks" href="#">Feedbacks</a>
        </li>
        <?php } ?>
        <?php if($colaborador->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-pdis" href="#">PDIs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-ferias" href="#">Férias</a>
        </li>
        <?php } ?>
    </ul>
    <!-- FIM DA NAV DE NAVEGAÇÃO ENTRE ABAS -->

    <!-- DIV SETORES -->
    <div class="row" style="margin-top: 1.5em; display: block;" id="div-setores">
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text">Setores que <?php echo $colaborador->getPrimeiroNome() ?> participa</h4>

                <hr class="hr-divide-super-light">

                <?php
                $select = "SELECT DISTINCT t2.set_id as id, t2.set_nome as setor FROM tbl_setor_funcionario t1 INNER JOIN 
                    tbl_setor t2 ON t2.set_id = t1.set_id WHERE t1.col_cpf = '$cpf' AND t2.set_ativo = 1 
                    ORDER BY t2.set_nome DESC";
                $query_set = $helper->select($select, 1);
                if(mysqli_num_rows($query_set) == 0) {
                    ?>
                    <span class="text">Nenhum<br>
                    <?php
                } else { 
                    while($f = mysqli_fetch_assoc($query_set)) {
                    ?>
                    <span class="text"><?php echo $f['setor'];  ?> - <a href="perfilSetor.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
            <?php  } ?>
        <?php  } ?>
            </div>
        </div>
    </div>
    <!-- FIM DIV SETORES -->

    <!-- DIV METAS -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-metas" >
        <div class="col-sm">
            <h4 class="text">Metas que <?php echo $colaborador->getPrimeiroNome() ?> participa</h4>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT DISTINCT t2.okr_id as id, t2.okr_titulo as titulo 
                FROM tbl_okr_colaborador t1 INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id 
                WHERE t1.col_cpf = '$cpf' AND t2.okr_visivel = 1 
                ORDER BY t2.okr_titulo ASC";
                $query_m = $helper->select($select, 1);
                if(mysqli_num_rows($query_m) == 0) {
                    ?>
                    <span class="text">Não há metas<br>
                    <?php
                } else {
                    while($f =  mysqli_fetch_assoc($query_m)) {
                        ?>
                        <span class="text"><?php echo $f['titulo'] ?> - <a href="verOKR.php?id=<?php echo $f['id'] ?>" target="_blank">Ver</a></span><br>
                        <?php
                    }
                }
                ?>
        </div>
    </div>
    <!-- FIM DIV METAS -->
    
    <?php if($colaborador->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
    <!-- DIV DOCS -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-docs" >    
        <div class="col-sm">
            <h4 class="text">Últimos 5 documentos de <?php echo $colaborador->getPrimeiroNome() ?></h4>
            <small class="text">Apenas <?php echo $colaborador->getPrimeiroNome() ?> e os gestores administrativos 
            têm acesso a esses documentos.</small>
            <br><a href="documentos.php" href="_blank"><button class="button button2" style="text-size: 0.5em;">Ver todos os documentos</button></a>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT t2.doc_id as id FROM tbl_documento_dono t1 INNER JOIN tbl_documento t2 
                ON t2.doc_id = t1.doc_id WHERE t1.cpf = '$cpf' ORDER BY t2.doc_data_upload DESC LIMIT 5";
                $query_doc = $helper->select($select, 1);
                if(mysqli_num_rows($query_doc) == 0) {
                    ?>
                    <span class="text">Não há documentos<br>
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query_doc)) {
                        $doc = new Documento();
                        $doc->setID($f['id']);
                        $doc = $doc->retornarDocumento($_SESSION['empresa']['database']);
                        ?>
                            <span class="text" style="margin-top: 1.3em;"><?php echo $doc->getTitulo() ?> - <a href="documentos/download.php?arquivo=<?php echo $doc->getID(); ?>" target="blank_">Baixar</a></span>
                            <script src="https://apis.google.com/js/platform.js" async defer></script>
                            <div class="g-savetodrive"
                            data-src="documentos/<?php echo $doc->getCaminhoArquivo(); ?>"
                            data-filename="<?php echo $doc->getCaminhoArquivo(); ?>"
                            data-sitename="Staffast">
                            </div><br>
                        <?php
                    }
                }
            ?> 
        </div>
    </div>
    <?php } ?>
    <!-- FIM DIV DOCS -->

    <!-- DIV REUNIÕES -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-reunioes" > 
        <div class="col-sm">
            <h4 class="text">Próximas reuniões que <?php echo $colaborador->getPrimeiroNome() ?> participará</h4>

            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
            DATE_FORMAT(t2.reu_data, '%d/%m/%Y') as data, DATE_FORMAT(t2.reu_hora, '%H:%i') as hora
            FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
            ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND t2.reu_data >= '$hoje' AND reu_concluida = 0 ORDER BY t2.reu_data ASC";
            $query_reu = $helper->select($select, 1);
            if(mysqli_num_rows($query_reu) == 0) {
                ?>
                <span class="text">Não há reuniões<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_reu)) {
                    ?>
                        <span class="text"><b><?php echo $f['data'].' - '.$f['hora'] ?>: </b><?php echo $f['pauta'] ?> - <a href="verReuniao.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
                    <?php
                }
            }
        ?>
            <hr class="hr-divide-super-light">

            <h4 class="text">Últimas 5 reuniões que <?php echo $colaborador->getPrimeiroNome() ?> participou</h4>

            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
            DATE_FORMAT(t2.reu_data, '%d/%m/%Y') as data, DATE_FORMAT(t2.reu_hora, '%H:%i') as hora
            FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
            ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND t2.reu_data < '$hoje' AND reu_concluida = 1 ORDER BY t2.reu_data DESC";
            $query_reu = $helper->select($select, 1);
            if(mysqli_num_rows($query_reu) == 0) {
                ?>
                <span class="text">Não há reuniões<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_reu)) {
                    ?>
                        <span class="text"><b><?php echo $f['data'].' - '.$f['hora'] ?>: </b><?php echo $f['pauta'] ?> - <a href="verReuniao.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- FIM DIV REUNIÕES -->

    <!-- DIV EVENTOS -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-eventos" > 
        <div class="col-sm">
            <h4 class="text">Próximos eventos que <?php echo $colaborador->getPrimeiroNome() ?> está incluído</h4>

            <?php
            $select = "SELECT DISTINCT t1.eve_id as id, t2.eve_titulo as titulo FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
            ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_data_inicial >= NOW() AND eve_status = 1 ORDER BY t2.eve_data_inicial ASC";
            $query_eve = $helper->select($select, 1);
            if(mysqli_num_rows($query_eve) == 0) {
                ?>
                <span class="text">Não há eventos<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_eve)) {
                    ?>
                        <span class="text"><?php echo $f['titulo'] ?> - <a href="verEvento.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
                    <?php
                }
            }
        ?>
            <hr class="hr-divide-super-light">

            <h4 class="text">Próimos eventos que <?php echo $colaborador->getPrimeiroNome() ?> confirmou presença</h4>

            <?php
            $select = "SELECT DISTINCT t1.eve_id as id, t2.eve_titulo as titulo FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
            ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_data_inicial >= NOW() AND eve_status = 1 AND t1.confirmado = 1 ORDER BY t2.eve_data_inicial ASC";
            $query_eve = $helper->select($select, 1);
            if(mysqli_num_rows($query_eve) == 0) {
                ?>
                <span class="text">Não há eventos<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_eve)) {
                    ?>
                        <span class="text"><?php echo $f['titulo'] ?> - <a href="verEvento.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
                    <?php
                }
            }
            ?>
            <hr class="hr-divide-super-light">
            
            <h4 class="text">Últimos 5 eventos que <?php echo $colaborador->getPrimeiroNome() ?> foi incluído</h4>

            <?php
            $select = "SELECT DISTINCT t1.eve_id as id, t2.eve_titulo as titulo FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
            ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_data_inicial <= NOW() AND eve_status = 1 ORDER BY t2.eve_data_inicial DESC";
            $query_eve = $helper->select($select, 1);
            if(mysqli_num_rows($query_eve) == 0) {
                ?>
                <span class="text">Não há eventos<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_eve)) {
                    ?>
                        <span class="text"><?php echo $f['titulo'] ?> - <a href="verEvento.php?id=<?php echo $f['id']; ?>" target="_blank">Ver</a></span><br>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- FIM DIV EVENTOS -->

    <?php if($colaborador->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
    <!-- DIV PLANOS DE DESENVOLVIMENTO INDIVIDUAL -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-pdis" >    
        <div class="col-sm">
            <h4 class="text">Planos de Desenvolvimento Individual</h4>

            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT 
                pdi_titulo as titulo,
                pdi_id as id 
                FROM tbl_pdi WHERE ges_cpf = '$cpf' OR pdi_cpf = '$cpf'";
                $query_pdi = $helper->select($select, 1);
                if(mysqli_num_rows($query_pdi) == 0) {
                    ?>
                    <span class="text">Não há PDIs<br>
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query_pdi)) {
                        ?>
                            <span class="text"><?php echo $f['titulo'] ?> - <a href="verPDI.php?id=<?php echo $f['id']; ?>">Ver</a></span><br>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <?php } ?>
    <!-- FIM DIV PLANOS DE DESENVOLVIMENTO INDIVIDUAL -->

    <!-- DIV MENSAGENS -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-mensagens" >   
        <?php if($colaborador->getCpf() == $_SESSION['user']['cpf']) { ?>
            <div class="col-sm">
                <h4 class="text">Mensagens recebidas por <?php echo $colaborador->getPrimeiroNome() ?></h4>
                <small class="text">Apenas <?php echo $colaborador->getPrimeiroNome() ?> consegue visualizar suas mensagens.</small>
                <br><a href="novaMensagem.php" href="_blank"><button class="button button2" style="text-size: 0.5em;">Enviar mensagem</button></a>

                <hr class="hr-divide-super-light">

                <?php
                $select = "SELECT DISTINCT t1.men_id as id FROM tbl_mensagem_funcionario t1 
                INNER JOIN tbl_mensagem t2 
                    ON t2.men_id = t1.men_id AND t2.men_data_expiracao >= NOW() 
                WHERE t1.cpf = '$cpf' ORDER BY t1.men_id DESC LIMIT 10";
                $query_men = $helper->select($select, 1);
                if(mysqli_num_rows($query_men) == 0) {
                    ?>
                    <span class="text">Não há mensagens<br>
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query_men)) {
                        $mensagem = new Mensagem();
                        $mensagem->setID($f['id']);
                        $mensagem = $mensagem->retornarMensagem($_SESSION['empresa']['database']);
                        ?>
                            <span class="text"><b><?php echo $mensagem->getTitulo() ?> (<?php echo $mensagem->getDataCriacao() ?>):</b>  <?php echo $mensagem->getTexto() ?></span><br>
                        <?php
                    }
                }
            ?>
            </div>
        <?php } ?>
    </div>
    <!-- FIM DIV MENSAGENS -->

    <!-- DIV FEEDBACKS -->   
    <?php if($colaborador->getCpf() == $_SESSION['user']['cpf']) { ?>
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-feedbacks" >
        <div class="col-sm">
            <h4 class="text">Últimos 10 <i>feedbacks</i> recebidos por <?php echo $colaborador->getPrimeiroNome() ?></h4>
            <small class="text">Apenas <?php echo $colaborador->getPrimeiroNome() ?> consegue visualizar seus <i>feedbacks</i>.</small>
            <br><a href="novoFeedback.php" href="_blank"><button class="button button2" style="text-size: 0.5em;">Ir para <i>feedbacks</i></button></a><br>

            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT fee_texto as feedback, DATE_FORMAT(fee_criacao, '%d/%m/%Y') as data 
            FROM tbl_feedback WHERE fee_cpf = '$cpf' ORDER BY fee_criacao DESC LIMIT 10";
            $query_fee = $helper->select($select, 1);
            if(mysqli_num_rows($query_fee) == 0) {
                ?>
                <span class="text">Não há feedbacks<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_fee)) {
                    ?>
                        <span class="text"><?php echo $f['data'] ?>: <?php echo $f['feedback'] ?></span><br>
                    <?php
                }
            }
        ?>
             <hr class="hr-divide-super-light">

            <h4 class="text">Últimos 10 <i>feedbacks</i> enviados por <?php echo $colaborador->getPrimeiroNome() ?></h4>
            <a href="novoFeedback.php" href="_blank"><button class="button button2" style="text-size: 0.5em;">Enviar <i>feedback</i></button></a><br>

            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT t1.fee_texto as feedback, DATE_FORMAT(t1.fee_criacao, '%d/%m/%Y') as data,
            CASE
                WHEN t2.col_nome_completo IS NOT NULL THEN t2.col_nome_completo
                ELSE t3.ges_nome_completo
            END as destinatario  
            FROM tbl_feedback t1
            LEFT JOIN tbl_colaborador t2 ON t2.col_cpf = t1.fee_cpf
            LEFT JOIN tbl_gestor t3 ON t3.ges_cpf = t1.fee_cpf
             WHERE t1.col_cpf = '$cpf' OR t1.ges_cpf = '$cpf' ORDER BY fee_criacao DESC LIMIT 10";
            $query_fee = $helper->select($select, 1);
            if(mysqli_num_rows($query_fee) == 0) {
                ?>
                <span class="text">Não há feedbacks<br>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query_fee)) {
                    ?>
                        <span class="text"><?php echo $f['data'] ?> para <b><?php echo $f['destinatario'] ?></b>: <?php echo $f['feedback'] ?></span><br>
                    <?php
                }
            }
        ?>
        </div>
    </div>
    <?php } ?>
    <!-- FIM DIV FEEDBACKS -->

    <!-- DIV FÉRIAS -->
    <?php if($colaborador->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-ferias" >
        <div class="col-sm">
            <h4 class="text">Férias</h4>

            <hr class="hr-divide-super-light">

            <span class="text">Não há férias agendadas</span><br>
        </div>
    <?php } ?>
    </div>
    <!-- FIM DIV FÉRIAS -->

    <hr class="hr-divide">

    <!-- DIV GRÁFICOS -->
        <?php if($isAutorizado) { ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <div class="col-sm">
                        <h3 class="text">Avaliações</h3>
                        <span style="font-size: 0.8em;"><a href="resultados.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>" target="_blank"><button class="button button2">Ver detalhes</button></a></span>
                        <br><small class="text">Os dados abaixo não incluem as avaliações realizadas utilizando modelos de avaliação. Para ver esses resultados, clique em "Ver detalhes" e encontre o modelo desejado</small>
                    </div>
                </div>
            </div>

            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <label class="text"><b>Média geral das avaliações</b></label>
                    <br><span class="text">Avaliado <b><?php echo $avaliacoes_receb; ?></b> vezes
                    <?php echo $show_media_ava; ?>
                </div>
                <div class="col-sm">
                    <label class="text"><b>Média geral das autoavaliações</b></label>
                    <br><span class="text">Autoavaliado <b><?php echo $autoavaliacoes; ?></b> vezes
                    <?php echo $show_media_ata; ?>
                </div>
            </div>

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
        <?php } ?>
    <!-- FIM DIV GRÁFICOS -->

</div>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $colaborador->getNomeCompleto(); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align: center">
        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Informações pessoais</h3>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Nome completo</b></label>
                <p class="text"><?php echo $colaborador->getNomeCompleto(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>R.G</b></label>
                <p class="text"><?php echo $colaborador->getRg(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>C.P.F</b></label>
                <p class="text"><?php echo $colaborador->getCpfFormatado(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>C.N.H - Categeria</b></label>
                <p class="text"><?php echo $colaborador->getCnh().' - '.$colaborador->getTipoCnh(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Telefone</b></label>
                <p class="text"><?php echo $colaborador->getTelefone(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Data de nascimento</b></label>
                <p class="text"><?php echo $colaborador->getDataNascimento(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Sexo</b></label>
                <p class="text"><?php echo $colaborador->getSexo(true); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Endereço</b></label>
                <p class="text"><?php echo $colaborador->getEndereco().', Nº '.$colaborador->getNumero().' - '.$colaborador->getBairro().', '.$colaborador->getCidade().' - CEP '.$colaborador->getCep(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Filhos</b></label>
                <p class="text"><?php echo $colaborador->getFilhos(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Estado civil</b></label>
                <p class="text"><?php echo $colaborador->getEstadoCivil(); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Informações profissionais</h3>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Apresentação</b></label>
                <p class="text"><?php echo $colaborador->getApresentacao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Titulação</b></label>
                <p class="text"><?php echo $colaborador->getFormacao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Cargo</b></label>
                <p class="text"><?php echo $colaborador->getCargo(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>N.I.S</b></label>
                <p class="text"><?php echo $colaborador->getNis(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>C.T.P.S</b></label>
                <p class="text"><?php echo $colaborador->getCtps(); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Informações de saúde</h3>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Plano médico</b></label>
                <p class="text"><?php echo $colaborador->getPlanoMedico(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Cartão SUS</b></label>
                <p class="text"><?php echo $colaborador->getCartaoSus(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Tipo Sanguíneo</b></label>
                <p class="text"><?php echo $colaborador->getTipoSanguineo(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Medicamentos contínuos</b></label>
                <p class="text"><?php echo $colaborador->getMedicamentos(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Alergias</b></label>
                <p class="text"><?php echo $colaborador->getAlergias(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Deficiência</b></label>
                <p class="text"><?php echo $colaborador->getDeficiencia(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Diabético</b></label>
                <p class="text"><?php if($colaborador->getDiabetico() == 1 || $colaborador->getDiabetico() == '1') { echo 'Sim'; } else { echo 'Não'; } ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Hipertenso</b></label>
                <p class="text"><?php if($colaborador->getHipertenso() == 1 || $colaborador->getHipertenso() == '1') { echo 'Sim'; } else { echo 'Não'; } ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Informações de cadastro</h3>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Data do cadastro</b></label>
                <p class="text"><?php echo $colaborador->getDataCadastro(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Última alteração</b></label>
                <p class="text"><?php echo $colaborador->getDataAlteracao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Situação</b></label>
                <p class="text"><?php if($colaborador->getAtivo() == 1) echo 'Ativo'; else 'Inativo'; ?></p>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>