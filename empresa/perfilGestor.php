<?php
    set_time_limit(0);
    include('../include/auth.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_usuario.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_feedback.php');
    require_once('../classes/class_reuniao.php');
    require_once('../classes/class_mensagem.php');
    require_once('../classes/class_documento.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if(!isset($_GET['id'])) {
        header('Location: gestores.php');
        die();
    }

    $cpf = base64_decode($_GET['id']);

    $gestor = new Gestor();
    $gestor->setCpf($cpf);
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
    
        if($gestor->getAtivo() == "Ativo") {
            $ativo = 'Gestor ativo';
            $btnAtivo = true;
        } else {
            $ativo = 'Gestor desativado';
            $btnAtivo = false;
        }
    
    $usuario = new Usuario();
    $usuario->setID($gestor->getIDUser());
    $usuario = $usuario->retornarUsuario();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    if($gestor->isImportado($_SESSION['empresa']['database'])) {
        $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE col_cpf = '$cpf'";
        $query = $helper->select($select, 1);
        $autoavaliacoes = mysqli_num_rows($query);

        $select = "SELECT ava_id as id FROM tbl_avaliacao WHERE col_cpf = '$cpf'";
        $query = $helper->select($select, 1);
        $avaliacoes_receb = mysqli_num_rows($query);
    }

    $select = "SELECT ava_id as id FROM tbl_avaliacao WHERE ges_cpf = '$cpf'";
    $query = $helper->select($select, 1);
    $avaliacoes = mysqli_num_rows($query);

    //COLETANDO MÉDIAS DAS COMPETÊNCIAS QUE O GESTOR AVALIA

    $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
    ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
    ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
    ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
    ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
    ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
    ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
    ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
    ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
    ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
    FROM tbl_avaliacao WHERE ges_cpf = '$cpf'";
    $fetch = $helper->select($select, 2);

    $avgs = array();
    if($fetch['um']) {
        $avgs[1] = $fetch['um'];
        $avgs[2] = $fetch['dois'];
        $avgs[3] = $fetch['tres'];
        $avgs[4] = $fetch['quatro'];
        $avgs[5] = $fetch['cinco'];
        $avgs[6] = $fetch['seis'];
        $avgs[7] = $fetch['sete'];
        $avgs[8] = $fetch['oito'];
        $avgs[9] = $fetch['nove'];
        $avgs[10] = $fetch['dez'];
        $avgs[11] = $fetch['onze'];
        $avgs[12] = $fetch['doze'];
        $avgs[13] = $fetch['treze'];
        $avgs[14] = $fetch['quatorze'];
        $avgs[15] = $fetch['quinze'];
        $avgs[16] = $fetch['dezesseis'];
        $avgs[17] = $fetch['dezessete'];
        $avgs[18] = $fetch['dezoito'];
        $avgs[19] = $fetch['dezenove'];
        $avgs[20] = $fetch['vinte'];
    } else {
        $avgs[1] = 0;
        $avgs[2] = 0;
        $avgs[3] = 0;
        $avgs[4] = 0;
        $avgs[5] = 0;
        $avgs[6] = 0;
        $avgs[7] = 0;
        $avgs[8] = 0;
        $avgs[9] = 0;
        $avgs[10] = 0;
        $avgs[11] = 0;
        $avgs[12] = 0;
        $avgs[13] = 0;
        $avgs[14] = 0;
        $avgs[15] = 0;
        $avgs[16] = 0;
        $avgs[17] = 0;
        $avgs[18] = 0;
        $avgs[19] = 0;
        $avgs[20] = 0;
    }

    //

    $foto = $gestor->getFoto() != '' ? 'img/fotos/'.$gestor->getFoto() : 'img/fotos/person.png';

    include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $gestor->getNomeCompleto(); ?></title>
    <script>
        function desativar(cpf) {
            var confirma = confirm("Deseja mesmo desativar este(a) gestor(a)? Isso fará o cadastro dele(a) ficar oculto e o(a) mesmo(a) não conseguirá mais acessar o Staffast. Você poderá reativá-lo(a), se quiser.\n Se o(a) gestor(a) estiver cadastrado(a) como colaborador(a) também, esse cadastro permanece ativo");
            if(!confirma) return;
            else window.location.href = "../database/gestor.php?desativa=true&id="+cpf;
        }

        function reativar(cpf) {
            var confirma = confirm("Deseja mesmo reativar este(a) gestor(a)? Isso fará o cadastro dele(a) ficar visível novamente e o(a) mesmo(a) voltará a acessar o Staffast. Você poderá desativá-lo(a), se quiser.");
            if(!confirma) return;
            else window.location.href = "../database/gestor.php?reativa=true&id="+cpf;
        }
    </script> 
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Competência', 'Média de nota que o gestor avalia'],
          ['<?php echo $_SESSION['empresa']['compet_um'] ?>',  <?php echo $avgs[1]; ?>],
          ['<?php echo $_SESSION['empresa']['compet_dois'] ?>',  <?php echo $avgs[2]; ?>],
          ['<?php echo $_SESSION['empresa']['compet_tres'] ?>',  <?php echo $avgs[3]; ?>],
          ['<?php echo $_SESSION['empresa']['compet_quatro'] ?>',  <?php echo $avgs[4]; ?>],
          <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_cinco'] ?>',  <?php echo $avgs[5]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_seis'] ?>',  <?php echo $avgs[6]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_sete'] ?>',  <?php echo $avgs[7]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_oito'] ?>',  <?php echo $avgs[8]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_nove'] ?>',  <?php echo $avgs[9]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_dez'] ?>',  <?php echo $avgs[10]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_onze'] ?>',  <?php echo $avgs[11]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_doze'] ?>',  <?php echo $avgs[12]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_treze'] ?>',  <?php echo $avgs[13]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_quatorze'] ?>',  <?php echo $avgs[14]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_quinze'] ?>',  <?php echo $avgs[15]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_dezesseis'] ?>',  <?php echo $avgs[16]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_dezessete'] ?>',  <?php echo $avgs[17]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_dezoito'] ?>',  <?php echo $avgs[18]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_dezenove'] ?>',  <?php echo $avgs[19]; ?>],
          <?php } ?>
          <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
            ['<?php echo $_SESSION['empresa']['compet_vinte'] ?>',  <?php echo $avgs[20]; ?>],
          <?php } ?>
        ]);

        var options = {
          title: 'Média das avaliações que o gestor realizou',
          curveType: 'function',
          colors: ['#13A330'],
          legend: { position: 'bottom' },
          vAxis: {
            viewWindow: {
                min: 0,
                max: 5.5
            },
            ticks: [0, 1, 2, 3, 4, 5]
          }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('grafico'));

        chart.draw(data, options);
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
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="gestores.php">Gestores</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $gestor->getNomeCompleto(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><img src="<?php echo $foto ?>" width="70" class="img-perfil"> <?php echo $gestor->getNomeCompleto(); ?></h2>
            <h4 class="text"><?php echo $gestor->getCargo(); ?></h4>
            <h6 class="text"><?php if($gestor->getIDInterno() != "") echo 'ID interno: '.$gestor->getIDInterno(); ?></h6>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h6 class="text"><?php echo $ativo; ?></h6>
            <?php if($_SESSION['user']['permissao'] == "GESTOR-1" && !$gestor->isImportado($_SESSION['empresa']['database'])) { ?>
                <a class="text" href="../database/gestor.php?importar=true&id=<?php echo base64_encode($gestor->getCpf()); ?>">Duplicar como colaborador </a><a href="#" onclick="alert('Isso cria um novo cadastro de colaborador usando as mesmas informações deste gestor. Esta ação é útil para funcionários que são gestores mas precisam ser avaliados por outros gestores, por exemplo.')"><span><img src="img/help.png" width="17"></span></a>
            <?php } else if ($gestor->isImportado($_SESSION['empresa']['database'])) { ?>
                <span class="text">Gestor importado - <a href="perfilColaborador.php?id=<?php echo base64_encode($gestor->getCpf()) ?>" target="_blank">Ver</a> </span><a href="#" onclick="alert('Quando um gestor é importado, significa que ele também possui cadastro como colaborador com os mesmos dados do cadastro de gestor')"><span><img src="img/help.png" width="17"></span></a>
            <?php } ?>
        </div>
        <?php if($_SESSION['user']['cpf'] == $gestor->getCpf() || $_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm">
            <input type="button" class="button button2" data-toggle="modal" data-target="#modal" value="Ver dados de <?php echo $gestor->getPrimeiroNome(); ?>"></a>
        </div>
        <div class="col-sm">
            <a href="novoGestor.php?editar=<?php echo base64_encode($gestor->getCpf()); ?>"><input type="button" class="button button2" value="Editar cadastro"></a>
        </div>
        <?php } ?>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') {
            if($btnAtivo == true) { ?>
                <div class="col-sm">
                    <input type="button" class="button button3" value="Desativar <?php echo $gestor->getPrimeiroNome(); ?>" onclick="desativar('<?php echo base64_encode($gestor->getCpf()); ?>');">
                </div>
            <?php }
            if($btnAtivo == false) { ?>
                <div class="col-sm">
                    <input type="button" class="button button3" value="Reativar <?php echo $gestor->getPrimeiroNome(); ?>" onclick="reativar('<?php echo base64_encode($gestor->getCpf()); ?>');">
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <hr class="hr-divide">


    <!-- NAV DE NAVEGAÇÃO ENTRE ABAS -->
    <ul class="nav nav-tabs" style="margin-bottom: 2.5em; margin-top: 1.5em;">
        <li class="nav-item">
            <a class="nav-link active" id="nav-setores" href="#">Setores</a>
        </li>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-metas" href="#">Metas</a>
            </li>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
            <?php if($gestor->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-docs" href="#">Documentos</a>
            </li>
            <?php } ?>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-reunioes" href="#">Reuniões</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-eventos" href="#">Eventos</a>
            </li>
        <?php } ?>
        <?php if($gestor->getCpf() == $_SESSION['user']['cpf']) { ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-mensagens" href="#">Mensagens</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-feedbacks" href="#">Feedbacks</a>
        </li>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
            <?php if($gestor->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-pdis" href="#">PDIs</a>
            </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <!-- FIM DA NAV DE NAVEGAÇÃO ENTRE ABAS -->

    <!-- DIV SETORES -->
    <div class="row" style="margin-top: 1.5em; display: block;" id="div-setores">
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text">Setores que <?php echo $gestor->getPrimeiroNome() ?> participa</h4>

                <hr class="hr-divide-super-light">

                <?php
                $select = "SELECT DISTINCT t2.set_id as id, DATE_FORMAT(t1.data_add, '%d/%m/%Y') as data, 
                t2.set_nome as setor
                 FROM tbl_setor_funcionario t1 INNER JOIN 
                tbl_setor t2 ON t2.set_id = t1.set_id WHERE t1.ges_cpf = '$cpf' 
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
            <h4 class="text">Metas que <?php echo $gestor->getPrimeiroNome() ?> participa</h4>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT DISTINCT t1.okr_id as id, 
                t2.okr_titulo as titulo FROM tbl_okr_gestor t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id 
                WHERE t1.ges_cpf = '$cpf'";
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
    
    <?php if($gestor->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
    <!-- DIV DOCS -->
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-docs" >    
        <div class="col-sm">
            <h4 class="text">Últimos 5 documentos de <?php echo $gestor->getPrimeiroNome() ?></h4>
            <small class="text">Apenas <?php echo $gestor->getPrimeiroNome() ?> e os gestores administrativos 
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
            <h4 class="text">Próximas reuniões que <?php echo $gestor->getPrimeiroNome() ?> participará</h4>

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

            <h4 class="text">Últimas 5 reuniões que <?php echo $gestor->getPrimeiroNome() ?> participou</h4>

            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
            DATE_FORMAT(t2.reu_data, '%d/%m/%Y') as data, DATE_FORMAT(t2.reu_hora, '%H:%i') as hora
            FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
            ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND (t2.reu_data < '$hoje' OR reu_concluida = 1) ORDER BY t2.reu_data DESC";
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
            <h4 class="text">Próximos eventos que <?php echo $gestor->getPrimeiroNome() ?> está incluído</h4>

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

            <h4 class="text">Próximos eventos que <?php echo $gestor->getPrimeiroNome() ?> confirmou presença</h4>

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
            
            <h4 class="text">Últimos 5 eventos que <?php echo $gestor->getPrimeiroNome() ?> foi incluído</h4>

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

    <?php if($gestor->getCpf() == $_SESSION['user']['cpf'] || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>
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
        <?php if($gestor->getCpf() == $_SESSION['user']['cpf']) { ?>
            <div class="col-sm">
                <h4 class="text">Mensagens recebidas por <?php echo $gestor->getPrimeiroNome() ?></h4>
                <small class="text">Apenas <?php echo $gestor->getPrimeiroNome() ?> consegue visualizar suas mensagens.</small>
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
    <?php if($gestor->getCpf() == $_SESSION['user']['cpf']) { ?>
    <div class="row" style="margin-top: 1.5em; display: none; text-align: center;" id="div-feedbacks" >
        <div class="col-sm">
            <h4 class="text">Últimos 10 <i>feedbacks</i> recebidos por <?php echo $gestor->getPrimeiroNome() ?></h4>
            <small class="text">Apenas <?php echo $gestor->getPrimeiroNome() ?> consegue visualizar seus <i>feedbacks</i>.</small>
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

            <h4 class="text">Últimos 10 <i>feedbacks</i> enviados por <?php echo $gestor->getPrimeiroNome() ?></h4>
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

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
        <?php if($_SESSION['user']['cpf'] == $gestor->getCpf() || $_SESSION['user']['permissao'] == "GESTOR-1") { ?>

            <hr class="hr-divide">

            <div class="row">
                <div class="col-sm" style="text-align: center;">
                    <h3 class="text">Avaliações feitas por <?php echo $gestor->getPrimeiroNome(); ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="col-sm">
                        <div id="grafico" style="width: 100%; height: 410px;"></div>
                    </div>
                </div>
            </div>

        <?php } ?>
    <?php } ?>

</div>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $gestor->getNomeCompleto(); ?></h5>
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
                <p class="text"><?php echo $gestor->getNomeCompleto(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>R.G</b></label>
                <p class="text"><?php echo $gestor->getRg(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>C.P.F</b></label>
                <p class="text"><?php echo $gestor->getCpfFormatado(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>C.N.H - Categeria</b></label>
                <p class="text"><?php echo $gestor->getCnh().' - '.$gestor->getTipoCnh(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Telefone</b></label>
                <p class="text"><?php echo $gestor->getTelefone(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Data de nascimento</b></label>
                <p class="text"><?php echo $gestor->getDataNascimento(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Sexo</b></label>
                <p class="text"><?php echo $gestor->getSexo(true); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Endereço</b></label>
                <p class="text"><?php echo $gestor->getEndereco().', Nº '.$gestor->getNumero().' - '.$gestor->getBairro().', '.$gestor->getCidade().' - CEP '.$gestor->getCep(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Filhos</b></label>
                <p class="text"><?php echo $gestor->getFilhos(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Estado civil</b></label>
                <p class="text"><?php echo $gestor->getEstadoCivil(); ?></p>
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
                <p class="text"><?php echo $gestor->getApresentacao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Titulação</b></label>
                <p class="text"><?php echo $gestor->getFormacao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Cargo</b></label>
                <p class="text"><?php echo $gestor->getCargo(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>LinkedIn</b></label>
                <p class="text"><?php echo $gestor->getLinkedin(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Telefone Profissional - Ramal</b></label>
                <p class="text"><?php echo $gestor->getTelefoneProfissional().' - Ramal: '.$gestor->getRamal(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>N.I.S</b></label>
                <p class="text"><?php echo $gestor->getNis(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>C.T.P.S</b></label>
                <p class="text"><?php echo $gestor->getCtps(); ?></p>
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
                <p class="text"><?php echo $gestor->getPlanoMedico(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Cartão SUS</b></label>
                <p class="text"><?php echo $gestor->getCartaoSus(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Tipo Sanguíneo</b></label>
                <p class="text"><?php echo $gestor->getTipoSanguineo(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Medicamentos contínuos</b></label>
                <p class="text"><?php echo $gestor->getMedicamentos(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Alergias</b></label>
                <p class="text"><?php echo $gestor->getAlergias(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Deficiência</b></label>
                <p class="text"><?php echo $gestor->getDeficiencia(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Diabético</b></label>
                <p class="text"><?php if($gestor->getDiabetico() == 1 || $gestor->getDiabetico() == '1') { echo 'Sim'; } else { echo 'Não'; } ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Hipertenso</b></label>
                <p class="text"><?php if($gestor->getHipertenso() == 1 || $gestor->getHipertenso() == '1') { echo 'Sim'; } else { echo 'Não'; } ?></p>
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
                <p class="text"><?php echo $gestor->getDataCadastro(); ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Última alteração</b></label>
                <p class="text"><?php echo $gestor->getDataAlteracao(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>É colaborador também?</b></label>
                <p class="text"><?php if($gestor->isImportado($_SESSION['empresa']['database'])) { echo 'Sim'; } else { echo 'Não'; } ?></p>
            </div>
            <div class="col-sm">
                <label class="text"><b>Situação</b></label>
                <p class="text"><?php echo $gestor->getAtivo(); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text"><b>Permissão</b></label>
                <p class="text"><?php if($gestor->getTipo() == 1 || $gestor->getTipo() == '1') { echo 'Permissão Administrativa - Total'; } else { echo 'Permissão Funcional - Avaliações'; } ?></p>
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