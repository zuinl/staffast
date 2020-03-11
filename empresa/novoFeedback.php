<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');
    include('../classes/class_conexao_empresa.php');
    include('../classes/class_queryHelper.php');

    $colaborador = new Colaborador();
    $gestor = new Gestor();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo feedback</title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function(){
        $("#nav-recebidos").click(function(){
            $("#nav-enviados").removeClass("active");
            $("#nav-pedidos").removeClass("active");
            $("#nav-recebidos").addClass("active");

            $("#feedback-enviados").hide();
            $("#feedback-pedidos").hide();
            $("#feedback-recebidos").show();
        });

        $("#nav-enviados").click(function(){
            $("#nav-recebidos").removeClass("active");
            $("#nav-pedidos").removeClass("active");
            $("#nav-enviados").addClass("active");

            $("#feedback-recebidos").hide();
            $("#feedback-pedidos").hide();
            $("#feedback-enviados").show();
        });

        $("#nav-pedidos").click(function(){
            $("#nav-recebidos").removeClass("active");
            $("#nav-enviados").removeClass("active");
            $("#nav-pedidos").addClass("active");

            $("#feedback-recebidos").hide();
            $("#feedback-enviados").hide();
            $("#feedback-pedidos").show();
        });
    });
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
            <li class="breadcrumb-item active" aria-current="page">Feedback</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Feedbacks</h2>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal-novo" value="Enviar um feedback">       
        </div>
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal-pedir" value="Pedir um feedback">       
        </div>
    </div>

    <hr class="hr-divide">

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
</div>
<div class="container">

    <!-- NAV DE NAVEGAÇÃO ENTRE ABAS -->
    <ul class="nav nav-tabs" style="margin-bottom: 2.5em; margin-top: 1.5em;">
        <li class="nav-item">
            <a class="nav-link active" id="nav-recebidos" href="#"><i>Feedbacks</i> recebidos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-enviados" href="#"><i>Feedbacks</i> enviados</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-pedidos" href="#">Pedidos de <i>feedbacks</i></a>
        </li>
    </ul>
    <!-- FIM DA NAV DE NAVEGAÇÃO ENTRE ABAS -->

<div id="feedback-recebidos">
        <?php
            $cpf = $_SESSION['user']['cpf'];
            $select = "SELECT t1.fee_texto as texto, 
                        CASE 
                            WHEN t1.ges_cpf IS NOT NULL THEN t2.ges_nome_completo
                            ELSE t3.col_nome_completo END
                        AS remetente,
                        DATE_FORMAT(t1.fee_criacao, '%d/%m/%Y %H:%i') as data
                    FROM tbl_feedback t1
                    LEFT JOIN tbl_gestor t2
                        ON t2.ges_cpf = t1.ges_cpf
                    LEFT JOIN tbl_colaborador t3 
                        ON t3.col_cpf = t1.col_cpf
                    WHERE t1.fee_cpf = '$cpf' ORDER BY t1.fee_criacao DESC";
            $query = $helper->select($select, 1);
            while($f = mysqli_fetch_assoc($query)) {       
        ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h5 class="text"><b><?php echo $f['remetente']; ?></b></h5>
                <small class="text"><?php echo $f['data']; ?></small>

                <h6 class="text"><?php echo $f['texto']; ?></h6>
            </div>
        </div>

        <hr class="hr-divide-super-light">
        <?php } ?>   
    </table>
</div>


<div id="feedback-enviados" style="display: none;">
        <?php
            $cpf = $_SESSION['user']['cpf'];
            $select = "SELECT t1.fee_texto as texto, 
                        CASE 
                            WHEN t2.ges_nome_completo IS NOT NULL THEN t2.ges_nome_completo
                            ELSE t3.col_nome_completo END
                        AS destinatario,
                        DATE_FORMAT(t1.fee_criacao, '%d/%m/%Y %H:%i') as data
                    FROM tbl_feedback t1
                    LEFT JOIN tbl_gestor t2
                        ON t2.ges_cpf = t1.fee_cpf
                    LEFT JOIN tbl_colaborador t3 
                        ON t3.col_cpf = t1.fee_cpf
                    WHERE t1.col_cpf = '$cpf' OR t1.ges_cpf = '$cpf' ORDER BY t1.fee_criacao DESC";
            $query = $helper->select($select, 1);
            while($f = mysqli_fetch_assoc($query)) {       
        ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h5 class="text"><b><?php echo $f['destinatario']; ?></b></h5>
                <small class="text"><?php echo $f['data']; ?></small>

                <h6 class="text"><?php echo $f['texto']; ?></h6>
            </div>
        </div>

        <hr class="hr-divide-super-light">
        <?php } ?>   
    </table>
</div>
</div>


<div id="feedback-pedidos" style="display: none;">
<!-- pendente de tabela de pedidos -->
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h5 class="text"><b>Nome do solicitante</b></h5>
                <small class="text">00/00/0000 00:00</small>

                <h6 class="text">descrição do pedido</h6>
            </div>
        </div>

        <hr class="hr-divide-super-light">   
    </table>
</div>
</div>
</body>

<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-novo" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Enviar feedback</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <form method="POST" action="../database/feedback.php?novo=true" id="form">
                    <label for="dono" class="text">Este <i>feedback</i> é para *</label>
                    <select name="dono" id="dono" class="all-input" required>
                        <option value="">-- Selecione --</option>
                        <option value="" disabled>---- GESTORES ----</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                        ?>
                        <option value="" disabled>---- COLABORADORES ----</option>
                        <?php
                        $colaborador->popularSelect($_SESSION['empresa']['database']);
                        ?>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label for="feedback" class="text"><i>Feedback</i> *</label>
                    <textarea name="feedback" id="feedback" class="all-input" maxlength="200" required=""></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label class="text">Eu te aconselharia a <i>parar</i> de fazer... (opcional)</label>
                    <textarea name="parar" id="parar" class="all-input" maxlength="200" required=""></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label class="text">Eu te aconselharia a <i>continuar</i> fazendo... (opcional)</label>
                    <textarea name="continuar" id="continuar" class="all-input" maxlength="200" required=""></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label class="text">Eu te aconselharia a <i>começar</i> a fazer... (opcional)</label>
                    <textarea name="comecar" id="comecar" class="all-input" maxlength="200" required=""></textarea>
                </div>
            </div>

            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <input type="submit" value="Enviar feedback" class="button button2">
                </div>
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
    <div class="modal" tabindex="-1" role="dialog" id="modal-pedir" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pedir feedback</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <form method="POST" action="../database/feedback.php?novo=true" id="form">
                    <label for="dono" class="text">Eu quero pedir um <i>feedback</i> para *</label>
                    <select name="dono" id="dono" class="all-input" required>
                        <option value="">-- Selecione --</option>
                        <option value="" disabled>---- GESTORES ----</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                        ?>
                        <option value="" disabled>---- COLABORADORES ----</option>
                        <?php
                        $colaborador->popularSelect($_SESSION['empresa']['database']);
                        ?>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label for="motivo" class="text">Porque você quer um <i>feedback</i>? *</label>
                    <textarea name="motivo" id="motivo" class="all-input" maxlength="200" required=""></textarea>
                </div>
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