<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_candidato.php');
    require_once('../classes/class_pergunta.php');
    require_once('../classes/class_resposta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_email.php');
    require_once('../classes/class_processo_seletivo.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include('../include/acessoNegado.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);

    if(isset($_GET['enviarEmail'])) {
        $ps = new ProcessoSeletivo();
        $ps->setID($_POST['ps']);
        $ps = $ps->retornarProcessoSeletivo($_SESSION['empresa']['database']);

        if(isset($_POST['mail_todos']) && $_POST['mail_todos'] == 1) {
            $select = "SELECT can_id as id FROM tbl_candidato WHERE sel_id = ".$_POST['ps'];
            $query = $helper->select($select, 1);
            while($f = mysqli_fetch_assoc($query)) {
                $candidato = new Candidato();
                $candidato->setID($f['id']);
                $candidato = $candidato->retornarCandidato($_SESSION['empresa']['database']);

                $email = new Email();
                $email->setAssunto($_POST['assunto']);
                $email->setEmailFrom($_SESSION['user']['email']);
                $email->setEmailTo($candidato->getEmail());
                $msg = '<h1 class="high-text">Nova mensagem do processo seletivo</h1>
                    <h2 class="high-text">Olá, '.$candidato->getNome().'! Você recebeu uma mensagem sobre o processo seletivo '.$ps->getTitulo().'
                    da empresa '.$_SESSION['empresa']['nome'].'</h2>
                    <h3 class="text">Mensagem de: '.$_SESSION['user']['primeiro_nome'].'</h3>
                    <h3 class="text">Empresa: '.$_SESSION['empresa']['nome'].'</h3>
                    <h3 class="text">Telefone da empresa: '.$_SESSION['empresa']['telefone'].'</h3>
                    <h3 class="text">Endereço da empresa: '.$_SESSION['empresa']['endereco'].'</h3>
                    <h3 class="text">Mensagem: '.$_POST['mensagem'].'</h3>
                    <h2 class="destaque-text">Por agora é só :D</h2>
                    <h5 class="text">Equipe do Staffast</h5>';
                $email->setMensagem($msg);
                $email->enviar();
            }
        } else  {
            foreach($_POST['mail_candidatos'] as $candidato) {
                $candidato = new Candidato();
                $candidato->setID($candidato);
                $candidato = $candidato->retornarCandidato($_SESSION['empresa']['database']);

                $email = new Email();
                $email->setAssunto($_POST['assunto']);
                $email->setEmailFrom($_SESSION['user']['email']);
                $email->setEmailTo($candidato->getEmail());
                $msg = '<h1 class="high-text">Nova mensagem do processo seletivo</h1>
                    <h2 class="high-text">Olá, '.$candidato->getNome().'! Você recebeu uma mensagem sobre o processo seletivo '.$ps->getTitulo().'
                    da empresa '.$_SESSION['empresa']['nome'].'</h2>
                    <h3 class="text">Mensagem de: '.$_SESSION['user']['primeiro_nome'].'</h3>
                    <h3 class="text">Empresa: '.$_SESSION['empresa']['nome'].'</h3>
                    <h3 class="text">Telefone da empresa: '.$_SESSION['empresa']['telefone'].'</h3>
                    <h3 class="text">Endereço da empresa: '.$_SESSION['empresa']['endereco'].'</h3>
                    <h3 class="text">Mensagem: '.$_POST['mensagem'].'</h3>
                    <h2 class="destaque-text">Por agora é só :D</h2>
                    <h5 class="text">Equipe do Staffast</h5>';
                $email->setMensagem($msg);
                $email->enviar();
            }
        }
        
        $_SESSION['msg'] = "E-mail enviado ao(s) candidato(s)";
        header('Location: candidaturas.php?ps='.$_POST['ps']);
        die();
    }

    $ps_id = $_GET['ps'];

    $ps = new ProcessoSeletivo();
    $ps->setID($ps_id);
    $ps = $ps->retornarProcessoSeletivo($_SESSION['empresa']['database']);

    $select = "SELECT can_id as id, can_nome as nome, can_email as email FROM tbl_candidato WHERE sel_id = '$ps_id' ORDER BY can_id ASC";

    $queryCandidatos = $helper->select($select, 1);

    $gestor = new Gestor();
    $gestor->setCpf($ps->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Candidaturas</title>
    <script>
        function enviar(email, assunto, mensagem, id, ps = <?php echo $ps->getID(); ?>) {
            if(email == "" || email == "Não informado") {
                return alert("E-mail inválido");
            }
            if(assunto == "") {
                return alert("Insira um assunto");
            }
            if(mensagem == "") {
                return alert("Descreva a mensagem");
            }

            window.location.href = "candidaturas.php?enviarEmail=true&email="+email+"&assunto="+assunto+"&mensagem="+mensagem+"&ps="+ps+"&id="+id;
        }
    </script>
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
            <li class="breadcrumb-item"><a href="processosSeletivos.php">Processos seletivos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Candidaturas de <?php echo $ps->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

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
            <h2 class="high-text"><?php echo $ps->getTitulo(); ?></h2>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="high-text">Descrição: <?php echo $ps->getDescricao(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="high-text">Vagas: <?php echo $ps->getVagas(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="high-text">Criado por <?php echo $gestor->getNomeCompleto(); ?> em <?php echo $ps->getDataCriacao(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="high-text">Encerramento de candidaturas <?php echo $ps->getDataEncerramento(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm">
            <input type="button" class="button button3" data-toggle="modal" data-target="#modal" value="Entrar em contato com candidatos"></a>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">
</div>
<div class="container">

    <?php
        $contador = 1;
        while($fetch = mysqli_fetch_assoc($queryCandidatos)) {

            $candidato = new Candidato();
            $candidato->setID($fetch['id']);
            $candidato = $candidato->retornarCandidato($_SESSION['empresa']['database']);

            ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <h5 class="high-text"><?php echo $contador; ?>. <span class="destaque-text"><?php echo $candidato->getNome(); ?></span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="text"><b>Telefone</b></label>
                    <br class="text"><?php echo $candidato->getTelefone(); ?>
                </div>
                <div class="col-sm">
                    <label class="text"><b>LinkedIn</b></label>
                    <br class="text"><a href="<?php echo $candidato->getLinkedin(); ?>" target="blank_"><?php echo $candidato->getLinkedin(); ?></a>
                </div>
                <div class="col-sm">
                    <label class="text"><b>E-mail</b></label>
                    <br class="text"><a href="mailto:<?php echo $candidato->getEmail(); ?>"><?php echo $candidato->getEmail(); ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="text"><b>Apresentação</b></label>
                    <br class="text"><?php echo $candidato->getApresentacao(); ?>
                </div>
                <div class="col-sm">
                    <label class="text"><b>Data candidatura</b></label>
                    <br class="text"><?php echo $candidato->getDataCadastro(); ?>
                </div>
            </div>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <br class="text"><a href="../processos-seletivos/<?php echo $candidato->getCurriculo(); ?>" target="blank_"><button class="button button2" style="font-size: 0.7em;">Baixar currículo de <?php echo $candidato->getNome(); ?></button></a>
                </div>
            </div>
            <?php
            //DIVIDIR EM DUAS SELECTS
            $sel_id = $ps->getID();
            $select = "SELECT per_id as id FROM tbl_pergunta_processo WHERE sel_id = '$sel_id' ORDER BY per_id ASC";
            $query = $helper->select($select, 1);
            $contadorPer = 1;
            while($f = mysqli_fetch_assoc($query)) {
                $pergunta = new Pergunta();
                $pergunta->setID($f['id']);
                $pergunta = $pergunta->retornarPergunta($_SESSION['empresa']['database']);
                ?>
                <hr class="hr-divide-super-light">

                <div class="row">
                    <div class="col-sm">
                        <h5 class="destaque-text">Pergunta<?php echo ' '.$contadorPer; ?>: <span class="high-text"><?php echo $pergunta->getTitulo(); ?></span></h5>
                        <small class="text"><?php echo $pergunta->getDescricao(); ?></small>
                    </div>
                </div>
                <?php
                $contadorPer++;
                $per_id = $pergunta->getID();
                $select = "SELECT res_id as id FROM tbl_resposta_candidato WHERE can_id = ".$candidato->getID();
                $queryRes = $helper->select($select, 1);

                $r = mysqli_fetch_assoc($queryRes);
                    $resposta = new Resposta();
                    $resposta->setID($r['id']);
                    $resposta = $resposta->retornarResposta($_SESSION['empresa']['database']);

                    echo '<p class="text"><b>Resposta do candidato:</b> ';

                    if ($resposta->getOpcUm() == 1) {
                        echo $pergunta->getOpcUm();
                        if($pergunta->getCompetUm() != '') echo ' - <small>'.$_SESSION['empresa']['nome'].' atribuiu a competência <b>'.$pergunta->getCompetUm().'</b> a esta resposta!</small>';
                        echo '</p>';
                    } else if ($resposta->getOpcDois() == 1) {
                        echo $pergunta->getOpcDois();
                        if($pergunta->getCompetDois() != '') echo '- <small>'.$_SESSION['empresa']['nome'].' atribuiu a competência <b>'.$pergunta->getCompetDois().'</b> a esta resposta!</small>';
                        echo '</small>';
                    } else if ($resposta->getOpcTres() == 1) {
                        echo $pergunta->getOpcTres();
                        if($pergunta->getCompetTres() != '') echo '- <small>'.$_SESSION['empresa']['nome'].' atribuiu a competência <b>'.$pergunta->getCompetTres().'</b> a esta resposta!</small>';
                        echo '</p>';
                    } else if ($resposta->getOpcQuatro() == 1) {
                        echo $pergunta->getOpcQuatro();
                        if($pergunta->getCompetQuatro() != '') echo '- <small>'.$_SESSION['empresa']['nome'].' atribuiu a competência <b>'.$pergunta->getCompetQuatro().'</b> a esta resposta!</small>';
                        echo '</p>';
                    } else {
                        echo 'Não respondeu</p>';
                    }
                
            }
            
            $contador++;

            echo '<hr class="hr-divide-light">';
        } 
        if (mysqli_num_rows($queryCandidatos) == 0) {
            echo '<h1 class="text">Sem candidaturas</h1>';
        }
    ?>

</div>
</body>

<?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
<div class="modal" tabindex="-1" role="dialog" id="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="high-text">Envie e-mails aos candidatos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <form action="candidaturas.php?enviarEmail=true" method="POST">
                <label class="text">Selecione os candidatos que deseja enviar o e-mail</label>
                <select name="mail_candidatos" id="mail_candidatos" size="6" class="all-input" multiple>
                    <?php
                        $select = "SELECT can_id as id, can_nome as nome FROM tbl_candidato WHERE sel_id = '$ps_id' ORDER BY can_nome ASC";
                        $query = $helper->select($select, 1);
                        while($f = mysqli_fetch_assoc($query)) {
                           ?>
                            <option value="<?php echo $f['id']; ?>"><?php echo $f['nome']; ?></option>
                           <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="checkbox" name="mail_todos" id="mail_todos" value="1"> Enviar para todos
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text">Insira o assunto da mensagem</label>
                <input type="text" name="assunto" id="assunto" required class="all-input">
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="text">Insira a mensagem</label>
                <textarea name="mensagem" id="mensagem" required class="all-input"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="hidden" name="ps" value="<?php echo $ps->getID(); ?>">
                <input type="submit" class="button button3" value="Enviar">
                </form>
            </div>
        </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

</html>