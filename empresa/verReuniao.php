<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_reuniao.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_mensagem.php');
    require_once('../classes/class_colaborador.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die(); 
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $id = $_GET['id'];

    $reuniao = new Reuniao();
    $reuniao->setID($id);
    $reuniao = $reuniao->retornarReuniao($_SESSION['empresa']['database']);
    
    if($_SESSION['user']['permissao'] != "GESTOR-1" && !$reuniao->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) {
        include('../include/acessoNegado.php');
        die();
    }
    
    $gestor = new Gestor();
    $gestor->setCpf($reuniao->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $quando = $reuniao->getData()." às ".$reuniao->getHora();

    if($reuniao->getAtingido() == 1) {
        $atingido = 'Objetivo atingido';
    } else {
        $atingido = 'Objetivo não atingido';
    }

    if(isset($_GET['enviar_mensagem'])) {
        $mensagem = new Mensagem();
        $mensagem->setTitulo($reuniao->getTitulo());
        $mensagem->setTexto(addslashes($_POST['mensagem']));
        $hoje = date('Y-m-d');
            $date = date_create($hoje);
            date_add($date,date_interval_create_from_date_string("3 days"));
        $mensagem->setDataExpiracao(date_format($date,"Y-m-d").' 23:59:59');
        $mensagem->setCpf($_SESSION['user']['cpf']);
        $mensagem->cadastrar($_SESSION['empresa']['database']);
        $men_id = $mensagem->retornarUltima($_SESSION['empresa']['database']);

        $select = "SELECT DISTINCT cpf FROM tbl_reuniao_integrante WHERE reu_id = ".$reuniao->getID();
        $query = $helper->select($select, 1);
        while($f = mysqli_fetch_assoc($query)) {
            $cpf = $f['cpf'];
            $insert = "INSERT INTO tbl_mensagem_funcionario (men_id, cpf) VALUES ('$men_id', '$cpf')";
            $helper->insert($insert);
        }
        $_SESSION['msg'] = 'Mensagem enviada aos integrantes';
    }
    
    $colaborador = new Colaborador();
    $eventos = new Evento();
    $metas = new OKR();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $reuniao->getPauta(); ?></title>
    <script>
    function mostrarGerenciar() {
        var div = document.getElementById('gerenciamento');
        var btn = document.getElementById('btnGerenciar');

        if(div.style.display == 'none') {
            div.style.display = 'block';
            btn.value = 'Ocultar gerenciamento';
        } else {
            div.style.display = 'none';
            btn.value = 'Gerenciar integrantes';
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
            <li class="breadcrumb-item"><a href="reunioes.php">Reuniões</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nova reunião</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center">
        <div class="col-sm">
            <h3 class="high-text"><?php echo $reuniao->getPauta(); ?></h3>
        </div>
    </div>
    <div class="row" style="text-align: center">
        <div class="col-sm">
            <h6 class="text"><b>Descrição:</b> <?php echo $reuniao->getDescricao(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Quando:</b> <?php echo $quando; ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Onde:</b> <?php echo $reuniao->getLocal(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Criada em <?php echo $reuniao->getDataCriacao(); ?> por <?php echo $gestor->getPrimeiroNome(); ?></h6>
        </div>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
            <h5 class="text"><b><?= $reuniao->getConcluida() == 1 ? '<span class="high-text">Reunião concluída</span>' : '<span style="color:orange;">Reunião pendente</span>'; ?></b></h5>       
        </div>
        <div class="col-sm">
            <h6 class="text"><?php echo $atingido; ?></h6>       
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['cpf'] == $reuniao->getCpfGestor()) { ?>
        <?php if($reuniao->getConcluida() == 0) { ?>
        <div class="col-sm">
            <a href="concluirReuniao.php?concluir=true&id=<?php echo $reuniao->getID(); ?>"><button class="button button2">Concluir reunião</button></a>
        </div>
        <?php } ?>
        <div class="col-sm">
            <a href="novaReuniao.php?editar=true&id=<?php echo $reuniao->getID(); ?>"><button class="button button2">Editar reunião</button></a>
        </div>
        <div class="col-sm">
            <input type="button" class="button button2" value="Gerenciar integrantes" id="btnGerenciar" onclick="mostrarGerenciar();">
        </div>
        <div class="col-sm">
            <input type="button" class="button button2" data-toggle="modal" data-target="#modal" value="Contatar integrantes">       
        </div>
        <?php } ?>
    </div>   

    <hr class="hr-divide-light">

    <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['cpf'] == $reuniao->getCpfGestor()) { ?>

    <div id="gerenciamento" style="display: none;">

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Adicionar colaboradores</h5>
                <form action="../database/reuniao.php?addColaboradores=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Adicionar gestores</h5>
                <form action="../database/reuniao.php?addGestores=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Remover gestores</h5>
                <form action="../database/reuniao.php?rmvGestores=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $reuniao->popularSelectGestoresMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Remover colaboradores</h5>
                <form action="../database/reuniao.php?rmvColaboradores=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $reuniao->popularSelectColaboradoresMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Adicionar metas</h5>
                <form action="../database/reuniao.php?addMetas=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $metas->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Adicionar eventos</h5>
                <form action="../database/reuniao.php?addEventos=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $eventos->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Adicionar" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Remover metas</h5>
                <form action="../database/reuniao.php?rmvMetas=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $reuniao->popularSelectMetasMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
            <div class="col-sm">
                <h5 class="text">Remover eventos</h5>
                <form action="../database/reuniao.php?rmvEventos=true" method="POST">
                <div style="height:7em;; overflow:auto;">
                    <?php $reuniao->popularSelectEventosMultiple($_SESSION['empresa']['database']); ?>
                </div>
                <input type="submit" value="Remover" class="button button3">
                <input type="hidden" name="id" value="<?php echo $reuniao->getID(); ?>">
                </form>
            </div>
        </div>

        <hr class="hr-divide">
    </div>

    <?php } ?>

    <div class="row">
        <div class="col-sm">
            <h4 class="text">Colaboradores participantes</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT(t2.col_nome_completo) as nome, t1.confirmado as confirmado 
            FROM tbl_reuniao_integrante t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf 
            WHERE t1.reu_id = '$id' AND t1.colaborador = 1 ORDER BY t2.col_nome_completo ASC";
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
            <h4 class="text">Gestores participantes</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT(t2.ges_nome_completo) as nome, t1.confirmado as confirmado 
            FROM tbl_reuniao_integrante t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf 
            WHERE t1.reu_id = '$id' AND t1.gestor = 1 ORDER BY t2.ges_nome_completo ASC";
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
            <h4 class="text">Metas relacionadas</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT(t2.okr_titulo) as titulo, t2.okr_id as id 
            FROM tbl_reuniao_okr t1 INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id 
            WHERE t1.reu_id = '$id' ORDER BY t2.okr_titulo ASC";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) {
                ?>
                    <h6 class="text">Nenhuma.</h6>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {
                    ?>
                    <h6 class="text"><a href="verOKR.php?id=<?php echo $f['id'] ?>"><?php echo $f['titulo']; ?></a></h6>
                    <?php
                }
            }
            ?>
        </div>

        <div class="col-sm">
            <h4 class="text">Eventos relacionadas</h4>
            <hr class="hr-divide-super-light">
            <?php
            $select = "SELECT DISTINCT eve_id as id FROM tbl_reuniao_evento WHERE reu_id = '$id'";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) {
                ?>
                    <h6 class="text">Nenhum.</h6>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {
                    $evento = new Evento();
                    $evento->setID($f['id']);
                    $evento = $evento->retornarEvento($_SESSION['empresa']['database']);
                    ?>
                    <h6 class="text"><a href="verEvento.php?id=<?php echo $f['id'] ?>"><?php echo $evento->getTitulo(); ?></a></h6>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Anotações desta reunião  
                        <span><input type="button" style="font-size: 0.7em;" class="button button2" data-toggle="modal" data-target="#modal-note" value="Adicionar anotação"></span>       

                        </button>
                    </h2>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <?php
                            $select = "SELECT 
                                        DATE_FORMAT(t1.data, '%d/%m/%Y às %H:%i') as data, 
                                        t1.anotacao as anotacao,
                                        CASE
                                            WHEN t2.ges_nome_completo IS NOT NULL THEN t2.ges_nome_completo
                                            ELSE t3.col_nome_completo
                                        END as nome 
                                       FROM tbl_reuniao_anotacao t1
                                        LEFT JOIN tbl_gestor t2
                                            ON t2.ges_cpf = t1.cpf
                                        LEFT JOIN tbl_colaborador t3
                                            ON t3.col_cpf = t1.cpf 
                                       WHERE reu_id = ".$reuniao->getID();
                            $query = $helper->select($select, 1);
                            if(mysqli_num_rows($query) == 0) {
                                ?>
                                <p class="text">Nenhuma anotação.</p>
                                <?php
                            } else {
                                while($f = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <p class="text"><b><?php echo $f['data'] ?> - <?php echo $f['nome'] ?>:</b> <?php echo $f['anotacao']; ?></p>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <?php if($reuniao->getConcluida() == 1) { ?>
        <hr class="hr-divide-light">
        
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text">Ata de encerramento</h4>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <p class="text"><?php echo $reuniao->getAta(); ?> </p>
            </div>
        </div>
    <?php } ?>

</div>
</body>

<div class="container">
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
                        <form action="verReuniao.php?enviar_mensagem=true&id=<?php echo $reuniao->getID(); ?>" method="POST">
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
</div>


<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-note" data-target=".bd-example-modal-lg">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar anotação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm">
                        <h5 class="high-text">Anotação</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <form action="../database/reuniao.php?anotacao=true&reu_id=<?php echo $reuniao->getID(); ?>" method="POST">
                        <textarea name="anotacao" id="anotacao" class="all-input" maxlength="1000" required></textarea>
                        <small class="text">A anotação será visível para todos os integrantes</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <input type="submit" class="button button1" value="Salvar anotação">
                        </form>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
            </div>
        </div>
    </div>
</div>

</html>