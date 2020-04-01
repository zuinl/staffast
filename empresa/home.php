<?php
    include('../include/auth.php');

    //SE A EMPRESA FOR DO PLANO PONTO, MANDAR DIRETO PARA O PONTO
    if($_SESSION['empresa']['plano'] == "PONTO") {
        header('Location: historicoPontos.php');
        die();
    }

    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_conexao_padrao.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_mensagem.php');
    require_once('../classes/class_documento.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_feedback.php');
    require_once('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);
    $autoavaliacao = new Autoavaliacao();
    $autoavaliacao->setCpfColaborador($_SESSION['user']['cpf']);
    $avaliacao = new Avaliacao();
    $avaliacao->setCpfColaborador($_SESSION['user']['cpf']);

    $select_ms = "SELECT DISTINCT a.men_id as men_id FROM tbl_mensagem_funcionario a INNER JOIN tbl_mensagem b ON b.men_id = a.men_id
    WHERE a.cpf = "."'".$_SESSION['user']['cpf']."' AND b.men_data_expiracao >= NOW() ORDER BY a.men_id DESC";
    $query_ms = $helper->select($select_ms, 1);
    $num_mensagens = mysqli_num_rows($query_ms);

    $avisos = 0;

    $cpf = $_SESSION['user']['cpf'];
    $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE col_cpf = '$cpf' AND ata_preenchida = 0";
    $query = $helper->select($select, 1);
    $autoavaliacoes_liberadas = mysqli_num_rows($query);
    if($autoavaliacoes_liberadas > 0) $avisos++;
    
    $select = "SELECT ava_id as id FROM tbl_avaliacao WHERE col_cpf = '$cpf' AND ava_data_liberacao <= NOW() AND ava_visualizada = 0";
    $query = $helper->select($select, 1);
    $avaliacoes_nao_visualizadas = mysqli_num_rows($query);
    if($avaliacoes_nao_visualizadas > 0) $avisos++;

    $conexao_p = new ConexaoPadrao();
    $conexao_p = $conexao_p->conecta();

    $helper_p = new QueryHelper($conexao_p);

    $select = "SELECT cod_string as codigo FROM tbl_codigo_avaliacao_empresa WHERE emp_id = ".$_SESSION['empresa']['emp_id']." AND cod_validade > NOW()";
    $query_codigo = $helper_p->select($select, 1);
    $codigos_empresa = mysqli_num_rows($query_codigo);
    if($codigos_empresa > 0) $avisos++;

    if($avisos <= 1) $notificacoesAvisos = $avisos.' aviso';
    else $notificacoesAvisos = $avisos.' avisos';

    if($num_mensagens <= 1) $notificacoesMensagens = $num_mensagens.' mensagem';
    else $notificacoesMensagens = $num_mensagens.' mensagens';

    date_default_timezone_set('America/Sao_Paulo');
    $hora = date('H:i');
    
    if ($hora >= "05:00" && $hora <= "11:59") {
        $greetings = "Bom dia :D";
        $img_src = "heart.png";
    } else if ($hora >= "12:00" && $hora <= "17:59") {
        $greetings = "Boa tarde ;)";
        $img_src = "cloud.png";
    } else if ($hora >= "18:00" && $hora <= "04:59") {
        $greetings = "Boa noite zzZzZ";
        $img_src = "extra.png";
    } else {
        $greetings = "Bem-vindo de volta";
        $img_src = "cloud.png";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - Staffast</title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function(){
        $("#nav-geral").click(function(){
            $("#nav-metas").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-geral").addClass("active");

            $("#div-setores").hide();
            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();

            $("#div-geral").show();
        });
        $("#nav-metas").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-metas").addClass("active");

            $("#div-setores").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-metas").show();
        });
        $("#nav-docs").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-docs").addClass("active");

            $("#div-metas").hide();
            $("#div-setores").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-docs").show();
        });
        $("#nav-reunioes").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-reunioes").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-setores").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-reunioes").show();
        });
        $("#nav-eventos").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-eventos").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-setores").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-eventos").show();
        });
        $("#nav-mensagens").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-mensagens").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-setores").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-mensagens").show();
        });
        $("#nav-feedbacks").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-feedbacks").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-setores").hide();
            $("#div-pdis").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-feedbacks").show();
        });
        $("#nav-pdis").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-setores").removeClass("active");
            $("#nav-avaliacoes").removeClass("active");

            $("#nav-pdis").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-setores").hide();
            $("#div-avaliacoes").hide();
            $("#div-geral").hide();

            $("#div-pdis").show();
        });
        $("#nav-avaliacoes").click(function(){
            $("#nav-geral").removeClass("active");
            $("#nav-metas").removeClass("active");
            $("#nav-docs").removeClass("active");
            $("#nav-reunioes").removeClass("active");
            $("#nav-eventos").removeClass("active");
            $("#nav-mensagens").removeClass("active");
            $("#nav-feedbacks").removeClass("active");
            $("#nav-pdis").removeClass("active");
            $("#nav-setores").removeClass("active");

            $("#nav-avaliacoes").addClass("active");

            $("#div-metas").hide();
            $("#div-docs").hide();
            $("#div-reunioes").hide();
            $("#div-eventos").hide();
            $("#div-mensagens").hide();
            $("#div-feedbacks").hide();
            $("#div-pdis").hide();
            $("#div-setores").hide();
            $("#div-geral").hide();

            $("#div-avaliacoes").show();
        });
    });
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <div class="alert alert-info alert-dismissible fade show" role="alert">
            O Staffast Beta chegou. O que isso quer dizer? O Staffast está em sua melhor versão desde que começou a ser desenvolvido. 
            Mas como tudo sempre pode ser melhorado, nós queremos e precisamos te ouvir. Se encontrar qualquer problema ou tiver qualquer 
            sugestão, por favor, <a href="../suporte/" target="_blank">entre em contato conosco</a> ;)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
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

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Olá, <i><span class="destaque-text"><?php echo $_SESSION['user']['primeiro_nome']; ?></span></i></h3>
        </div>
        <div class="col-sm">
            <a data-toggle="modal" data-target="#modal-avisos" href="#"><img src="img/question.png" width="60"></a>
            <span class="text"><?php echo $notificacoesAvisos; ?></span>
        </div>
        <div class="col-sm">
            <a data-toggle="modal" data-target="#modal-mensagens" href="#"><img src="img/email.png" width="60"></a>
            <span class="text"><?php echo $notificacoesMensagens; ?></span>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="verRanking.php"><img src="img/gold.png" width="40"></a>
            <span class="text" style="font-size: 0.9em;"><i>Ranking</i></span>
        </div>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
        <div class="col-sm">
            <a href="painelAvaliacao.php"><img src="img/checklist.png" width="40"></a>
            <span class="text" style="font-size: 0.9em;">Avaliações</span>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">

    <!-- NAV DE NAVEGAÇÃO ENTRE ABAS -->
    <ul class="nav nav-tabs" style="margin-bottom: 2.5em; margin-top: 1.5em;">
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
            <li class="nav-item">
                <a class="nav-link active" id="nav-geral" href="#"><b>Geral</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-metas" href="#">Metas</a>
            </li>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-docs" href="#">Documentos</a>
            </li>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-reunioes" href="#">Reuniões</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav-eventos" href="#">Eventos</a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link" id="nav-mensagens" href="#">Mensagens</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-feedbacks" href="#">Feedbacks</a>
        </li>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-pdis" href="#">PDIs</a>
            </li>
        <?php } ?>
        <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
            <li class="nav-item">
                <a class="nav-link" id="nav-avaliacoes" href="#">Avaliações</a>
            </li>
        <?php } ?>
    </ul>
    <!-- FIM DA NAV DE NAVEGAÇÃO ENTRE ABAS -->
</div>

<div class="container">

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
    <!-- DIV GERAL  -->
    <div class="row" id="div-geral" style="text-align: center;">
        <div class="col-sm">
            <img src="img/<?php echo $img_src; ?>" width="100">
            <span class="text" style="margin-left: 1.5em; font-size: 2em;"><?php echo $greetings; ?></span>

            <hr class="hr-divide-super-light">

            <!-- <small class="text"><?php //echo $_SESSION['user']['primeiro_nome'] ?>, você está acessando o Staffast fora do seu horário de trabalho. 
            Nós não recomendamos que funcionários acessem o sistema fora de seu horário de trabalho, pois é parte da missão do Staffast contribuir com 
            uma boa e organizada gestão da sua empresa. O seu acesso foi registrado no Relatório de Acessos Extras</small> -->

            <h4 class="text">Suas atividades para hoje</h4>
            
            <h5 class="text"><b>Reuniões</b></h5>
            <?php
            $hoje = date('Y-m-d');
            $select = "SELECT DISTINCT t2.reu_pauta as pauta, t2.reu_id as id,
            CONCAT('Às ', DATE_FORMAT(t2.reu_hora, '%H:%i')) as hora
             FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
            ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND t2.reu_data = '$hoje' AND reu_concluida = 0 ORDER BY t2.reu_data ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) {
                ?>
                <h6 class="text">Sem reuniões hoje.</h6>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {
                    ?>
                    <h6 class="text"><?php echo $f['hora'] ?>: <?php echo $f['pauta'] ?> - <a href="verReuniao.php?id=<?php echo $f['id']; ?>">Ver</a></h6>
                    <?php
                }
            }
            ?>

            <!-- <h5 class="text">Avaliações</h5> -->

        </div>
    </div>
    <!-- FIM DIV GERAL  -->
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
    <!-- DIV METAS -->
    <div class="row" id="div-metas" style="display: none">
        <div class="col-sm" style="margin-top: 0.7em; text-align: center;">
            <h4 class="text"><img src="img/pie-chart.png" width="80"> Metas que você participa <a href="metas.php"><button class="button button2" style="font-size: 0.5em;">Ver todas</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                $select = "SELECT DISTINCT t2.okr_titulo as titulo, t2.okr_id as id FROM tbl_okr_gestor t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE (t2.okr_visivel != 2 OR (t2.okr_visivel = 2 AND t2.ges_cpf = '$cpf')) AND t1.ges_cpf = '$cpf' 
                ORDER BY t2.okr_data_criacao DESC";
                $query = $helper->select($select, 1);
                $metas = mysqli_num_rows($query);
                
                $select = "SELECT DISTINCT t2.okr_titulo as titulo, t2.okr_id as id FROM tbl_okr_colaborador t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE t2.okr_visivel = 1 AND t1.col_cpf = '$cpf' 
                ORDER BY t2.okr_data_criacao DESC";
                $query1 = $helper->select($select, 1);
                $metas1 = mysqli_num_rows($query1);

                    if($_SESSION['user']['permissao'] != "COLABORADOR") {
                        ?>
                        <br class="text"><b>Participando como gestor</b>
                        <?php
                        if($metas == 0) echo '<p class="text">Não há metas.</p>';
                        while($f = mysqli_fetch_assoc($query)) {
                            ?>
                            <br class="text"><?php echo $f['titulo']; ?> - <a href="verOKR.php?id=<?php echo $f['id']; ?>">Ver meta</a>
                            <?php
                        }
                    }
                    ?>
                    <br><br class="text"><b>Participando como colaborador</b>
                    <?php
                    if($metas1 == 0) echo '<p class="text">Não há metas.</p>';
                    while($f = mysqli_fetch_assoc($query1)) {
                        ?>
                        <br class="text"><?php echo $f['titulo']; ?> - <a href="verOKR.php?id=<?php echo $f['id']; ?>">Ver meta</a>
                        <?php
                    }
            ?>
        </div>
    </div>
    <!-- FIM DIV METAS -->
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
    <div class="row" id="div-avaliacoes" style="display: none; text-align: center">
        <div class="col-sm">
            <h4 class="text"><img src="img/checklist.png" width="70"> Últimas avaliações liberadas <a href="resultados.php?id=<?php echo base64_encode($cpf); ?>"><button class="button button2" style="font-size: 0.4em;">Ver painel</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT t1.ava_id as id, t2.ges_primeiro_nome as nome, 
            DATE_FORMAT (t1.ava_data_liberacao, '%d/%m/%Y') as liberacao
            FROM tbl_avaliacao t1 
            INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf WHERE t1.col_cpf = '$cpf' AND 
            t1.ava_data_liberacao <= NOW() ORDER BY t1.ava_data_liberacao DESC LIMIT 3";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há avaliações liberadas.</p>';
            else {
                while($f = mysqli_fetch_assoc($query)) {
                    ?>
                    <br class="text"><b><?php echo $f['liberacao']; ?>: </b>feita por <?php echo $f['nome'] ?> - <a href="verAvaliacao.php?id=<?php echo $f['id'] ?>&col=<?php echo base64_encode($cpf); ?>">Ver</a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <!-- FIM DIV AVALIAÇÕES -->
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO" || $_SESSION['empresa']['plano'] == "AVALIACAO") { ?>
    <!-- DIV DOCUMENTOS -->
    <div class="row" id="div-docs" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/file.png" width="70"> Seus documentos recentes <a href="documentos.php"><button class="button button2" style="font-size: 0.5em;">Ver todos</button></a></h4>
            <small class="text">Apenas você e os gestores administrativos da sua empresa podem ver seus documentos.</small>
            <hr class="hr-divide-super-light">
            <?php 
            $select = "SELECT t2.doc_id as id 
            FROM tbl_documento_dono t1 INNER JOIN tbl_documento t2 ON t2.doc_id = t1.doc_id
            WHERE t1.cpf = '$cpf' ORDER BY t2.doc_data_upload DESC LIMIT 5";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há documentos.</p>';
            else {
                while($f = mysqli_fetch_assoc($query)) {
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
    <!-- FIM DIV DOCUMENTOS -->
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
    <!-- DIV EVENTOS -->
    <div class="row" id="div-eventos" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/calendar.png" width="70"> Próximos eventos <a href="eventos.php"><button class="button button2" style="font-size: 0.5em;">Ver eventos</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                if($_SESSION['user']['permissao'] == 'GESTOR-1') {
                    $select = "SELECT DISTINCT eve_id as id, eve_titulo as titulo, DATE_FORMAT(eve_data_inicial, '%d/%m/%Y') as inicio FROM tbl_evento WHERE eve_data_inicial >= NOW() AND eve_status = 1 ORDER BY eve_data_inicial ASC";
                } else {
                    $select = "SELECT DISTINCT t1.eve_id as id, t2.eve_titulo as titulo, DATE_FORMAT(t2.eve_data_inicial, '%d/%m/%Y') as inicio FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
                    ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_data_inicial >= NOW() AND eve_status = 1 ORDER BY t2.eve_data_inicial ASC";
                }
                
                $query = $helper->select($select, 1);  
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há eventos próximos.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <br class="text"><?php echo $f['titulo']." - ".$f['inicio']; ?>  - <a href="verEvento.php?id=<?php echo $f['id']; ?>">Ver</a>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <!-- FIM DIV EVENTOS  -->
    <?php } ?>

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
    <!-- DIV REUNIOES  -->
    <div class="row" id="div-reunioes" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/round-table.png" width="70"> Próximas reuniões <a href="reunioes.php"><button class="button button2" style="font-size: 0.5em;">Ver reuniões</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                if($_SESSION['user']['permissao'] == 'GESTOR-1') {
                    $select = "SELECT DISTINCT reu_pauta as pauta, reu_id as id,
                    CONCAT(DATE_FORMAT(reu_data, '%d/%m/%Y'), ' às ', DATE_FORMAT(reu_hora, '%H:%i')) as data
                     FROM tbl_reuniao WHERE reu_data >= NOW() AND reu_concluida = 0 ORDER BY reu_data ASC";
                } else {
                    $select = "SELECT DISTINCT t2.reu_pauta as pauta, t2.reu_id as id,
                    CONCAT(DATE_FORMAT(t2.reu_data, '%d/%m/%Y'), ' às ', DATE_FORMAT(t2.reu_hora, '%H:%i')) as data
                     FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
                    ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND t2.reu_data >= NOW() AND reu_concluida = 0 ORDER BY t2.reu_data ASC";
                }
                
                $query = $helper->select($select, 1);  
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há reuniões próximas.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <br class="text"><b><?php echo $f['data']; ?></b>: <?php echo $f['pauta']; ?> - <a href="verReuniao.php?id=<?php echo $f['id']; ?>">Ver</a></span>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <!-- FIM DIV REUNIÕES  -->
    <?php } ?>

    <!-- DIV FEEDBACKS  -->
    <div class="row" id="div-feedbacks" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/feedback.png" width="70"> Últimos 5 <i>feedbacks</i> recebidos <a href="novoFeedback.php"><button class="button button2" style="font-size: 0.5em;">Enviar um <i>feedback</i></button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                $select = "SELECT DISTINCT fee_id as id FROM tbl_feedback WHERE fee_cpf = '$cpf' ORDER BY fee_criacao DESC LIMIT 5";
                
                $query = $helper->select($select, 1);  
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há feedbacks.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        $feedback = new Feedback();
                        $feedback->setID($f['id']);
                        $feedback = $feedback->retornarFeedback($_SESSION['empresa']['database']);
                        ?>
                        <br class="text"><?php echo '<b>De '.$feedback->getRemetente().':</b> '.$feedback->getTexto(); ?>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <!-- FIM DIV FEEDBACKS  -->

    <!-- DIV MENSAGENS  -->
    <div class="row" id="div-mensagens" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/email.png" width="70"> Mensagens <a href="novaMensagem.php"><button class="button button2" style="font-size: 0.5em;">Enviar uma mensagem</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                $select = "SELECT DISTINCT t1.men_id as id FROM tbl_mensagem_funcionario t1 
                INNER JOIN tbl_mensagem t2 
                    ON t2.men_id = t1.men_id AND t2.men_data_expiracao >= NOW() 
                WHERE t1.cpf = '$cpf' ORDER BY t1.men_id DESC LIMIT 10";
                
                $query = $helper->select($select, 1);  
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há mensagens</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        $mensagem = new Mensagem();
                        $mensagem->setID($f['id']);
                        $mensagem = $mensagem->retornarMensagem($_SESSION['empresa']['database']);
                        ?>
                        <br class="text"><?php echo "<b>".$mensagem->getTitulo()."</b>: ".$mensagem->getTexto(); ?>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <!-- FIM DIV MENSAGENS  -->

    <?php if($_SESSION['empresa']['plano'] == "REVOLUCAO") { ?>
    <!-- DIV PDIS  -->
    <div class="row" id="div-pdis" style="display: none; text-align: center;">
        <div class="col-sm">
            <h4 class="text"><img src="img/pdi.png" width="70"> Planos de Desenvolvimento Individual <a href="PDIs.php"><button class="button button2" style="font-size: 0.5em;">Ver PDIs</button></a></h4>
            <hr class="hr-divide-super-light">
            <?php
                $select = "SELECT 
                    pdi_titulo as titulo,
                    pdi_id as id 
                    FROM tbl_pdi WHERE ges_cpf = '$cpf' OR pdi_cpf = '$cpf'";
                $query = $helper->select($select, 1);  
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Não há PDIs criados.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <br class="text"><?php echo $f['titulo']; ?> - <a href="verPDI.php?id=<?php echo $f['id']; ?>">Ver</a></span>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <!-- FIM DIV PDIS  -->
    <?php } ?>
</div>

<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-avisos" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><img src="img/question.png" width="60"> Avisos (<?php echo $avisos; ?>)</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
            <?php if($autoavaliacoes_liberadas > 0) { ?>
                <div class="row">
                    <div class="col-sm">
                        <p class="text">Você tem autoavaliação liberada
                        <a href="novaAutoavaliacao.php"><button class="button button1" style="font-size: 0.7em;">Autoavalie-se</button></a>
                        </p>
                    </div>
                </div>
                <?php } ?>
            <?php if($avaliacoes_nao_visualizadas > 0) { ?>
                <div class="row">
                    <div class="col-sm">
                        <p class="text">Há avaliações que você não visualizou
                        <a href="resultados.php?id=<?php echo base64_encode($_SESSION['user']['cpf']); ?>"><button class="button button1" style="font-size: 0.7em;">Ver resultados</button></a>
                        <br><span style="font-size: 0.7em;">Uma avaliação só é considerada visualizada quando você a visualiza individualmente no 
                        Painel de Controle de Avaliações. O mesmo vale para avaliações realizadas usando modelos.</span>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <?php if($codigos_empresa > 0) {
                $f = mysqli_fetch_assoc($query_codigo);
                ?>
                <div class="row">
                    <div class="col-sm">
                        <p class="text">A avaliação da sua empresa está liberada! Use o código: <?php echo $f['codigo']; ?>
                        <a href="../avaliacao-empresa/" target="blank_"><button class="button button1" style="font-size: 0.7em;">Avalie agora</button></a>
                        </p>
                    </div>
                </div>
            <?php } ?> 

        <div class="modal-footer">
            <small class="text">Fique tranquilo(a), se você já está ciente dos avisos, eles desaparecerão em alguns dias.</small>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>



<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-mensagens" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><img src="img/question.png" width="60"> Mensagens (<?php echo $num_mensagens; ?>)</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <?php if(mysqli_num_rows($query_ms) == 0) { ?>
                <h6 class="text">Não há mensagens</h6>
                <?php } else { ?>
                <div id="mensagens">
                <?php
                while($f = mysqli_fetch_assoc($query_ms)) {
                    $mensagem = new Mensagem();
                    $mensagem->setID($f['men_id']);
                    $mensagem = $mensagem->retornarMensagem($_SESSION['empresa']['database']);
                    ?>
                    <h6 class="text"><b><?php echo $mensagem->getTitulo() ?> - De: <?php echo $mensagem->getRemetente(); ?></b></h6>
                    <p class="text"><?php echo $mensagem->getTexto(); ?>
                    <br><small class="text"><?php echo $mensagem->getDataCriacao(); ?></small><br>
            <?php }
            } ?>
        </div>  

        <div class="modal-footer">
            <small class="text">Fique tranquilo(a), se você já visualizou as mensagens, elas desaparecerão em alguns dias.</small>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>

</body>
</html>