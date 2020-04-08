<?php
session_start();
include('src/meta.php');

//if(isset($_SESSION['user'])) header('Location: empresa/home.php');

if(isset($_POST) && isset($_GET['enviarMensagem']) && $_GET['enviarMensagem'] == "true") {
    $nome = addslashes($_POST['nome']);
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $empresa = $_POST['empresa'];
    $mensagem = addslashes($_POST['mensagem']);

    require_once 'classes/class_email.php';

    $mail = new Email();
    $mail->setEmailTo('contato@sistemastaffast.com');
    $mail->setEmailFrom('contato@sistemastaffast.com');
    $mail->setAssunto('Contato - Staffast');
    $mail->setMensagem(
        "<h1>Novo contato - Página Inicial do Staffast</h1>
        <h2>Nome: $nome</h2>
        <h2>E-mail: $email</h2>
        <h2>Telefone: $telefone</h2>
        <h2>Empresa: $empresa</h2>
        <h2>Mensagem: $mensagem</h2>"
    );
    if($mail->enviar()) {
        $msg = 'Mensagem enviada';
    } else {
        $msg = 'Houve um erro ao enviar a mensagem';
    }

    unset($_GET);
} else if(isset($_POST) && isset($_GET['assinarNewsletter']) && $_GET['assinarNewsletter'] == "true") {
    $email = $_POST['email'];

    require_once 'classes/class_conexao_padrao.php';
    require_once 'classes/class_queryHelper.php';

    $conexao = new ConexaoPadrao();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $insert = "INSERT INTO tbl_newsletter_assinantes (email) VALUES ('$email')";
    $helper->insert($insert);

    $msg = "Prontinho! Você receberá mensagens do Staffast no e-mail ".$email;

    unset($_GET);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bem-vindo(a) - Staffast</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>  
    <script type="text/javascript">
        $('#telefone').mask('(00) 00000-0000');
    </script>
    <script>
        function revolucionar() {
            var mensagem = document.getElementById('mensagem');
            mensagem.value = "Quero usar o Staffast na minha empresa! Como eu faço?";
            mensagem.focus();
        }
    </script>
    <meta property="og:url"           content="https://sistemastaffast.com/staffast/" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Staffast" />
    <meta property="og:description"   content="Nossa missão é fazer você cumprir a sua" />
    <meta property="og:image"         content="https://sistemastaffast.com/staffast/img/graphic.png" />
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="img/logo_staffast.png" width="180">

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">

            <li class="nav-item" id="nav-2">
                <a class="nav-link" href="#fale-conosco" onClick="revolucionar();">Revolucione sua empresa</a>
            </li>

            <li class="nav-item" id="nav-3">
                <a class="nav-link" href="#solucoes">O que o Staffast faz?</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#usuarios">Quem vai usar?</a>
            </li>

            <!-- <li class="nav-item" id="nav-4">
                <a class="nav-link" href="#4" id="link-4">Marcação de Ponto</a>
            </li>

            <li class="nav-item" id="nav-5">
                <a class="nav-link" href="#5" id="link-5">... e tudo o resto</a>
            </li> -->

            <li class="nav-item" id="nav-6" style="margin-left: 1em;">
                <a class="nav-link" href="#fale-conosco">Fale com a gente</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="ajuda.php">Encontre ajuda</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="login.php" style="color: #13a378;"><b>ENTRAR NO STAFFAST</b></a>
            </li>
        </ul>
    </div>
</nav>

<body>

    <?php
    if(isset($msg)) {
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $msg; unset($msg); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="container-fluid">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <img src="empresa/img/demo.png" width="70" style="margin-right: 2em;">
                <a href="#fale-conosco" onClick="revolucionar();"><button class="button button3">SOLICITE UMA DEMONSTRAÇÃO</button></a>
            </div>

            <div class="col-sm">
                <img src="covid-19/virus.png" width="70" style="margin-right: 2em;">
                <a href="covid-19/"><button class="button button3">ACEITE NOSSA AJUDA PARA A COVID-19</button></a>
            </div>

            <div class="col-sm">
                <img src="empresa/img/clock.png" width="70" style="margin-right: 2em; margin-left: 3em;">
                <a href="ponto/"><button class="button button3">REGISTRE SEU PONTO</button></a>
                <br><small class="text">Para usuários cadastros</small>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="login.php"><button class="button button1">JÁ USA O STAFFAST? CLIQUE PARA ENTRAR</button></a>
            </div>
            <div class="col-sm">
                <a href="blog/"><button class="button button2">BLOG DO STAFFAST</button></a>
            </div>
            <div class="col-sm">
                <a href="planos.php"><button class="button button2">CONHEÇA OS PLANOS DO STAFFAST</button></a>
            </div>
        </div>

        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4 high-text">Nossa missão é fazer você cumprir a <i>sua</i></h1>
                <p class="lead">No século onde é difícil cuidar de nós mesmos, porque tem que ser tão complicado cuidar da sua equipe? 
                <br> O Staffast oferece tudo o que você precisa para focar no que realmente te interessa: fazer sua empresa voar</p>
                <div class="row">
                    <div class="col-sm">
                        <a href="https://www.instagram.com/staffast_/" target="_blank"><img src="empresa/img/instagram.png" width="40"></a>
                    </div>
                    <div class="col-sm">
                        <a href="https://www.facebook.com/Staffast-103640134626397/" target="_blank"><img src="empresa/img/facebook.png" width="40"></a>
                    </div>
                    <div class="col-sm">
                        <a href="https://www.linkedin.com/company/65253483" target="_blank"><img src="empresa/img/linkedin.png" width="40"></a>
                    </div>
                    <div class="col-sm">
                        <a href="https://play.google.com/store/apps/details?id=com.staffast" target="_blank"><img src="empresa/img/google-play.png" width="40"></a>
                    </div>
                    <div class="col-sm">
                        <a href="#" onclick="alert('Em breve!')"><img src="empresa/img/app.png" width="40"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/prints/home.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/ponto.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/grafico_gestor.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/modelo_avaliacao.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/painel_avaliacao.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/reunioes.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/documentos.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/processo_seletivo.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/evento.PNG" class="d-block w-100">
                </div>

                <div class="carousel-item">
                    <img src="img/prints/feedback.PNG" class="d-block w-100">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev" style="background-color: grey; width: 5%">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next" style="background-color: grey; width: 5%">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Próximo</span>
            </a>
        </div>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h1><img src="empresa/img/heart.png" width="70"> Os <b>dois corações</b> do Staffast <img src="empresa/img/heart.png" width="70"></h1>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/clock.png" width="170" style="margin-left: 35%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Controle de Ponto</b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Disponível no app </span><img src="empresa/img/app.png" width="20">
                        <p class="card-text" style="font-size: 1.2em;">Pra quem está procurando uma forma de controlar os horários da equipe ou pra quem quer <b>arrancar a máquina 
                        feia da parede</b>. 
                        <br>O Staffast faz o registro eletrônico coletando o <b>horário e localização</b> do colaborador. Depois, joga tudo isso 
                        em relatórios, tornando o processo transparente e <b>reduzindo o trabalho manual</b> do RH.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/checklist.png" width="170" style="margin-left: 35%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Avaliações por Competência</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Uma empresa que não conhece e acompanha as habilidades dos seus colaboradores pode 
                        estar <b>desperdiçando talentos</b>.
                        <br>O Staffast usa como base a teoria de Gestão de Pessoas por Competência, unindo 4 tipos de avaliações, além de modelos 
                        personalizados. Já passou da hora de você sentir que <b>conhece</b> a sua empresa completamente.</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;" id="solucoes">
            <div class="col-sm">
                <h1><img src="empresa/img/google-play.png" width="70"> ... o que está no nosso <b>app</b> <img src="empresa/img/app.png" width="70"></h1>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/clock.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Controle de Ponto</b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Disponível no app </span><img src="empresa/img/app.png" width="20">
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/file.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Documentos</b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Disponível no app </span><img src="empresa/img/app.png" width="20">
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/feedback.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b><i>Feedbacks</i></b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Em breve no app </span><img src="empresa/img/app.png" width="20">
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="high-text">Baixe agora no seu celular ou tablet</h4>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href='https://play.google.com/store/apps/details?id=com.staffast&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Disponível no Google Play' src='https://play.google.com/intl/en_us/badges/static/images/badges/pt-br_badge_web_generic.png' width="200"/></a>
            </div>
        </div>
        <hr class="hr-divide">

        <div class="row" style="text-align: center;" id="solucoes">
            <div class="col-sm">
                <h1><img src="empresa/img/enterprise.png" width="70"> ... e diversas outras <b>soluções</b> <img src="empresa/img/enterprise.png" width="70"></h1>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/file.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Documentos</b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Disponível no app </span><img src="empresa/img/app.png" width="20">
                        <p class="card-text" style="font-size: 1.2em;">Chega de receber uma visita no RH de alguém querendo o holerite de 1999.
                        <br>Enviando os documentos por aqui, o colaborador <b>salva no próprio Google Drive*</b> e tem uma cópia</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/feedback.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b><i>Feedbacks</i></b></p>
                        <img src="empresa/img/google-play.png" width="20"><span style="font-size: 0.9em;"> Em breve no app </span><img src="empresa/img/app.png" width="20">
                        <p class="card-text" style="font-size: 1.2em;">A gente quer acabar com as fofocas. 
                        <br>Por isso aqui, todos podem enviar <b>feedbacks instantâneos</b> e ajudar a equipe a melhorar e reconhecer suas habilidades</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/interview.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Processos Seletivos</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Receba o currículo, dados pessoais e faça quantas perguntas quiser para os seus candidatos. 
                        <br> E ainda é possível relacionar as perguntas com as <b>competências da sua empresa</b>.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/goal.png" width="170" style="margin-left: 35%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Metas OKR</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Deixe bem claro quais são as metas da sua empresa e use o mesmo método que a <b>Google usou pra se tornar gigante</b>. 
                        <br>E conte com instruções nossas para te ajudar a entender as OKRs.</p>
                    </div>
                </div>
            </div>
            <!-- <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/iteration.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Projetos com SCRUM</b></p>
                        <p class="card-text" style="font-size: 1.2em;">A metodologia favorita da gente s2 <br>O SCRUM vem tomando conta do setor de desenvolvimento, mas a gente acredita que ele pode funcionar 
                        nos <b>projetos da sua empresa</b> também.</p>
                    </div>
                </div>
            </div> -->
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/pdi.png" width="170" style="margin-left: 35%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>PDIs</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Não basta sua empresa ter metas e planos. 
                        <br><b>Sua equipe também.</b> 
                        <br>No Staffast, todos podem ter seus próprios Planos de Desenvolvimento Individual e mostrar como estão crescendo.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/calendar.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Eventos</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Sabe aquele grupo do WhatsApp que você sabe que só vai encher de figurinha? 
                        <br>Livre-se dele usando o <b>gerenciamento de eventos</b> do Staffast</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/round-table.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Reuniões</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Agende e gerencie os integrantes de reuniões, com confirmações de presença e <b>atas de encerramento</b>. 
                        <br>Ninguém mais poderá dizer que não se lembra do que foi dito naquela reunião importante.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/report.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Relatórios</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Com taaaanta informação assim no Staffast, a gente monta alguns relatórios com <b>vários <i>insights</i></b> pra te ajudar 
                        sempre mais no conhecimento e decisões da sua empresa.</p>
                    </div>
                </div>
            </div>
        </div>

        <small class="text">* O colaborador precisa possuir uma conta Google e conceder acesso à ela.</small>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;" id="usuarios">
            <div class="col-sm">
                <h1><img src="empresa/img/meeting.png" width="70"> Para <b>todos</b> usarem <img src="empresa/img/meeting.png" width="70"></h1>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="margin-bottom: 3em;">
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/team-1.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Gestores Administrativos</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Os Gestores Administrativos, geralmente <b>gestores do setor de RH</b>, 
                        são os que têm acesso total ao Staffast, gerenciando todos os dados de todos os colaboradores.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/leadership.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Gestores Operacionais</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Os Gestores Operacionais, podendo ser de qualquer setor e cargo, são 
                        os que possuem <b>acesso mais limitado</b>, podendo gerenciar e visualizar informações apenas dos colaboradores dos setores dos quais 
                        são responsáveis.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="text-align: center">
                    <img src="empresa/img/group.png" width="170" style="margin-left: 25%; margin-top: 1em;" alt="Controle de Ponto">
                    <div class="card-body">
                        <p class="card-text" style="font-size: 1.7em;"><b>Colaboradores</b></p>
                        <p class="card-text" style="font-size: 1.2em;">Os Colaboradores têm acesso <b>apenas às suas informações</b> no Staffast, como avalições, 
                        autoavaliações, documentos, reuniões, PDIs, etc.</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h1><img src="empresa/img/conversation.png" width="70"> Vem <b>falar</b> com a gente <img src="empresa/img/conversation.png" width="70"></h1>
                <p class="text">Nós estamos ansiosos para oferecer 15 dias de teste grátis para sua empresa *</p>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="jumbotron jumbotron-fluid" style="padding: 1em;">
            <div class="row">
                <div class="col-sm">
                <form action="index.php?enviarMensagem=true" method="POST" id="fale-conosco">
                    <input type="text" class="all-input" name="nome" id="nome" placeholder="Nome" required>
                </div>
                <div class="col-sm">
                    <input type="email" class="all-input" name="email" id="email" placeholder="E-mail" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <input type="text" class="all-input" name="telefone" id="telefone" placeholder="Telefone (opcional)">
                </div>
                <div class="col-sm">
                    <input type="text" class="all-input" name="empresa" id="empresa" placeholder="Empresa que trabalha">
                </div>
                <div class="col-sm">
                    <select class="all-input" name="plano" id="plano">
                        <option value="Selecione um plano" disabled selected>- Selecione o plano que deseja experimentar -</option>
                        <option value="Staffast Ponto">Staffast Ponto</option>
                        <option value="Staffast Avaliação">Staffast Avaliação</option>
                        <option value="Staffast Revolução">Staffast Revolução</option>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 1em;">
                <div class="col-sm">
                    <textarea name="mensagem" id="mensagem" class="all-input" placeholder="Quer perguntar ou nos dizer algo?"></textarea>
                </div>
            </div>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <input type="submit" value="Enviar mensagem" class="button button1">
                </div>
                </form>
            </div>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <small class="text">* O teste grátis será válido para empresas que nunca usaram o Staffast. Se está em dúvida de qual plano escolher, conheça as funcionalidades 
                    que cada um oferece <a href="planos.php" target="_blank">clicando aqui</a></small>
                </div>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h1><img src="empresa/img/newspaper.png" width="70"> Quer receber <b>novidades</b> sobre gestão? <img src="empresa/img/newspaper.png" width="70"></h1>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="jumbotron jumbotron-fluid" style="padding: 1em; text-align: center;">
            <div class="row">
                <div class="col-sm-4 offset-sm-4">
                <form action="index.php?assinarNewsletter=true" method="POST" id="newsletter">
                    <input type="email" class="all-input" name="email" id="email" placeholder="Insira seu e-mail" required>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <input type="submit" value="Enviar" class="button button1">
                </div>
                </form>
            </div>
        </div>

        <hr class="hr-divide">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <small class="text">Adsumus Sistemas - <?php echo date('Y'); ?></small>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <small class="text"><a href="mailto:contato@sistemastaffast.com">contato@sistemastaffast.com</a></small>
            </div>
        </div>

    </div>
</body>