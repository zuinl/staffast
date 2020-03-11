<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_mensagem.php');
    require_once('../classes/class_colaborador.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $id = $_GET['id'];

    $evento = new Evento();
    $evento->setID($id);
    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['cpf'] != $evento->getCpfGestor()) {
        $cpf = $_SESSION['user']['cpf'];
        $select = "SELECT * FROM tbl_evento_participante WHERE eve_id = '$id' AND cpf = '$cpf'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) {
            include('../include/acessoNegado.php');
            die();
        }
    }

    $gestor = new Gestor();
    $gestor->setCpf($evento->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $quando = $evento->getDataI()." às ".$evento->getHoraI()." até ".$evento->getDataF()." às ".$evento->getHoraF();

    if($evento->getIsNaEmpresa() == 0) {
        $isNaEmpresa = 'Evento ocorrendo <b>fora</b> de '.$_SESSION['empresa']['nome'];
    } else {
        $isNaEmpresa = 'Evento ocorrendo <b>dentro</b> de '.$_SESSION['empresa']['nome'];
    }

    if(isset($_GET['enviar_mensagem'])) {
        $mensagem = new Mensagem();
        $mensagem->setTitulo($evento->getTitulo());
        $mensagem->setTexto(addslashes($_POST['mensagem']));
        $hoje = date('Y-m-d');
            $date = date_create($hoje);
            date_add($date,date_interval_create_from_date_string("3 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);
        $mensagem->cadastrar($_SESSION['empresa']['database']);
        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $select = "SELECT DISTINCT cpf FROM tbl_evento_participante WHERE eve_id = ".$evento->getID();
        $query = $helper->select($select, 1);
        while($f = mysqli_fetch_assoc($query)) {
            $cpf = $f['cpf'];
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }
        $_SESSION['msg'] = 'Mensagem enviada aos participantes';
    }

    $gestor = new Gestor();
    $colaborador = new Colaborador();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $evento->getTitulo(); ?></title>
    <script>
    function mostrarGerenciar() {
        var div = document.getElementById('gerenciamento');
        var btn = document.getElementById('btnGerenciar');

        if(div.style.display == 'none') {
            div.style.display = 'block';
            btn.value = 'Ocultar gerenciamento';
        } else {
            div.style.display = 'none';
            btn.value = 'Gerenciar participantes';
        }
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
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item"><a href="eventos.php">Eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $evento->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="high-text"><?php echo $evento->getTitulo(); ?></h3>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text"><b>Descrição:</b> <?php echo $evento->getDescricao(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Quando:</b> <?php echo $quando; ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Onde:</b> <?php echo $evento->getLocal(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Criado em <?php echo $evento->getDataCriacao(); ?> por <?php echo $gestor->getPrimeiroNome(); ?></h6>
        </div>
        <div class="col-sm">
        <h6 class="text"><?php echo $isNaEmpresa; ?></h6>       
        </div>
    </div>

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm-6">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            </div>
		</div>
        <?php
    }
    ?>

    <hr class="hr-divide">
</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
            <h5 class="text"><b><?= $evento->getStatus() == 1 ? '<span class="high-text">Evento ativo</span>' : '<span style="color:red;">Evento cancelado</span>'; ?></b></h5>       
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['cpf'] == $evento->getCpfGestor()) { ?>
        <div class="col-sm">
            <a href="../database/evento.php?cancelar=true&id=<?php echo $evento->getID(); ?>"><button class="button button2">Cancelar evento</button></a>
        </div>
        <div class="col-sm">
            <a href="novoEvento.php?editar=true&id=<?php echo $evento->getID(); ?>"><button class="button button2">Editar evento</button></a>
        </div>
        <div class="col-sm-2">
            <input type="button" class="button button2" value="Gerenciar participantes" id="btnGerenciar" onclick="mostrarGerenciar();">
        </div>
        <div class="col-sm">
            <input type="button" class="button button2" data-toggle="modal" data-target="#modal" value="Contatar participantes">       
        </div>
        <?php } ?>
    </div>   

    <hr class="hr-divide-light">

    <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['cpf'] == $evento->getCpfGestor()) { ?>

    <div id="gerenciamento" style="display: none;">
        <div class="row">
            <div class="col-sm">
                <small class="text">Mantenha a tecla CTRL pressionada e clique nos nomes, para selecionar mais de um</small>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="destaque-text">Adicionar colaboradores</h5>
                <form action="../database/evento.php?addColaboradores=true" method="POST">
                <div style="height:8em;; overflow:auto;">
                    <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $evento->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="destaque-text">Adicionar gestores</h5>
                <form action="../database/evento.php?addGestores=true" method="POST">
                <div style="height:8em;; overflow:auto;">
                    <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $evento->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="destaque-text">Remover gestores</h5>
                <form action="../database/evento.php?rmvGestores=true" method="POST">
                <div style="height:8em;; overflow:auto;">
                    <?php $evento->popularSelectMultipleGestores($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $evento->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="destaque-text">Remover colaboradores</h5>
                <form action="../database/evento.php?rmvColaboradores=true" method="POST">
                <div style="height:8em;; overflow:auto;">
                    <?php $evento->popularSelectMultipleColaboradores($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $evento->getID(); ?>">
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Reuniões que contêm este evento na pauta</h3>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
                CONCAT(DATE_FORMAT(t2.reu_data, '%d/%m/%Y'), ' às ', t2.reu_hora) as data,
                t2.reu_concluida as concluida
                FROM tbl_reuniao_evento t1 INNER JOIN tbl_reuniao t2 ON t2.reu_id = t1.reu_id WHERE t1.eve_id = '$id' 
                ORDER BY t2.reu_data DESC";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    ?>
                    <span class="text">Nenhuma.
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <span class="text"><a href="verReuniao.php?id=<?php echo $f['id'] ?>">
                        <?php echo $f['pauta'] ?></a> - <?php echo $f['data'] ?> (<?= $f['concluida'] == 1 ? "Concluída" : "Pendente" ?>)<br>
                        <?php   
                    }
                }
            ?>
        </div>
    </div>

    <hr class="hr-divide">

    <div class="row">
        <div class="col-sm">
            <h4 class="destaque-text">Colaboradores participantes</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT(t2.col_nome_completo) as nome, t1.confirmado as confirmado 
            FROM tbl_evento_participante t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf 
            WHERE t1.eve_id = '$id' AND t1.colaborador = 1 ORDER BY t2.col_nome_completo ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) {
                ?>
                    <h6 class="text">Nenhum.</h6>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {
                    $f['confirmado'] == 1 ? $confirmado = '<span style="color: green;">Confirmado</span>' : $confirmado = '<span style="color: red;">Não confirmado</span>';
                    ?>
                    <h6 class="text"><?php echo "<b>".$f['nome']."</b> - Presença: ".$confirmado; ?></h6>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-sm">
            <h4 class="destaque-text">Gestores participantes</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT(t2.ges_nome_completo) as nome, t1.confirmado as confirmado 
            FROM tbl_evento_participante t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf 
            WHERE t1.eve_id = '$id' AND t1.gestor = 1 ORDER BY t2.ges_nome_completo ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) {
                ?>
                    <h6 class="text">Nenhum.</h6>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {
                    $f['confirmado'] == 1 ? $confirmado = '<span style="color: green;">Confirmado</span>' : $confirmado = '<span style="color: red;">Não confirmado</span>';
                    ?>
                    <h6 class="text"><?php echo "<b>".$f['nome']."</b> - Presença: ".$confirmado; ?></h6>
                    <?php
                }
            }
            ?>
        </div>
    </div>

</div>
</body>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar mensagens aos participantes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <h5 class="high-text">Mensagem</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <form action="verEvento.php?enviar_mensagem=true&id=<?php echo $evento->getID(); ?>" method="POST">
                <textarea name="mensagem" id="mensagem" class="all-input" maxlength="400" required></textarea>
                <small class="text">Uma mensagem será criada e direcionada a todos os participantes</small>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="submit" class="button button1" value="Enviar">
                </form>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>