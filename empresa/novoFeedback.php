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

    $cpf = $_SESSION['user']['cpf'];

    //Checando pedidos pendentes de feedbacks
    $select = "SELECT COUNT(id) as total FROM tbl_feedback_pedido WHERE cpf_destinatario = '$cpf' AND fee_id IS NULL";
    $f = $helper->select($select, 2);
    $numPedidos = $f['total'];

    //Checando pedidos solicitados pendentes de feedbacks
    $select = "SELECT COUNT(id) as total FROM tbl_feedback_pedido WHERE cpf_solicitante = '$cpf' AND fee_id IS NULL";
    $f = $helper->select($select, 2);
    $numSolicitados = $f['total'];

    //Checando feedbacks recebidos
    $select = "SELECT COUNT(fee_id) as total FROM tbl_feedback WHERE col_cpf = '$cpf' OR ges_cpf = '$cpf'";
    $f = $helper->select($select, 2);
    $numEnviados = $f['total'];

    //Checando feedbacks recebidos
    $select = "SELECT COUNT(fee_id) as total FROM tbl_feedback WHERE fee_cpf = '$cpf' AND fee_visualizado = 0";
    $f = $helper->select($select, 2);
    $numRecebidos = $f['total'];
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
            $("#nav-solicitados").removeClass("active");
            $("#nav-recebidos").addClass("active");

            $("#feedback-enviados").hide();
            $("#feedback-pedidos").hide();
            $("#feedback-solicitados").hide();
            $("#feedback-recebidos").show();
        });

        $("#nav-enviados").click(function(){
            $("#nav-recebidos").removeClass("active");
            $("#nav-pedidos").removeClass("active");
            $("#nav-solicitados").removeClass("active");
            $("#nav-enviados").addClass("active");

            $("#feedback-recebidos").hide();
            $("#feedback-pedidos").hide();
            $("#feedback-solicitados").hide();
            $("#feedback-enviados").show();
        });

        $("#nav-pedidos").click(function(){
            $("#nav-recebidos").removeClass("active");
            $("#nav-enviados").removeClass("active");
            $("#nav-solicitados").removeClass("active");
            $("#nav-pedidos").addClass("active");

            $("#feedback-recebidos").hide();
            $("#feedback-enviados").hide();
            $("#feedback-solicitados").hide();
            $("#feedback-pedidos").show();
        });

        $("#nav-solicitados").click(function(){
            $("#nav-recebidos").removeClass("active");
            $("#nav-enviados").removeClass("active");
            $("#nav-pedidos").removeClass("active");
            $("#nav-solicitados").addClass("active");

            $("#feedback-recebidos").hide();
            $("#feedback-enviados").hide();
            $("#feedback-pedidos").hide();
            $("#feedback-solicitados").show();
        });
    });
    </script>
    <script>
        function atenderPedido(cpf_solicitante, id_pedido) {
            $('#dono').val(cpf_solicitante);
            $('#id_pedido').val(id_pedido);
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
            <a class="nav-link active" id="nav-recebidos" href="#"><i>Feedbacks</i> recebidos (<?php echo $numRecebidos; ?> não lidos)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-enviados" href="#"><i>Feedbacks</i> enviados (<?php echo $numEnviados; ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-pedidos" href="#">Pedidos de <i>feedbacks</i> (<?php echo $numPedidos; ?> pendentes)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-solicitados" href="#">Pedidos que você fez (<?php echo $numPedidos; ?> pendentes)</a>
        </li>
    </ul>
    <!-- FIM DA NAV DE NAVEGAÇÃO ENTRE ABAS -->

<div id="feedback-recebidos">
        <?php
            $cpf = $_SESSION['user']['cpf'];
            $select = "SELECT t1.fee_texto as texto, 
                        t1.fee_comecar as comecar,
                        t1.fee_continuar as continuar,
                        t1.fee_parar as parar,
                        t1.fee_id as id,
                        t1.fee_visualizado as visualizado,
                        CASE 
                            WHEN t2.ges_nome_completo IS NOT NULL THEN t2.ges_nome_completo
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
            if(mysqli_num_rows($query) == 0) {
                ?>
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <h5 class="text">Você não recebeu nenhum <i>feedback</i> ainda</h5>
                    </div>
                </div>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {       
            ?>
                <div class="row" style="text-align: center;  margin-bottom: 2em;">
                    <div class="col-sm">
                        <h5 class="text"><b>Recebeu de: </b><?php echo $f['remetente']; ?></h5>
                        <small class="text"><?php echo $f['data']; ?></small>
                        <?php if((int)$f['visualizado'] === 0) { ?>
                            <br><small class="text"><a href="../database/feedback.php?visualizado=true&fee_id=<?php echo $f['id']; ?>">Marcar como lido</a></small>
                        <?php } ?>

                        <h6 class="text"><?php echo $f['texto']; ?></h6>

                        <?php if($f['comecar'] != '') { ?>
                            <h6 class="text"><b>Te orientou a começar a fazer: </b><?php echo $f['comecar']; ?></h6>
                        <?php } ?>

                        <?php if($f['continuar'] != '') { ?>
                            <h6 class="text"><b>Te orientou a continuar fazendo: </b><?php echo $f['continuar']; ?></h6>
                        <?php } ?>

                        <?php if($f['parar'] != '') { ?>
                            <h6 class="text"><b>Te orientou a parar de fazer: </b><?php echo $f['parar']; ?></h6>
                        <?php } ?>
                    </div>
                </div>  
            <?php } ?>   
        <?php } ?> 
    </table>
</div>


<div id="feedback-enviados" style="display: none;">
        <?php
            $cpf = $_SESSION['user']['cpf'];
            $select = "SELECT t1.fee_texto as texto,
                        t1.fee_comecar as comecar,
                        t1.fee_continuar as continuar,
                        t1.fee_parar as parar,
                        t1.fee_visualizado as visualizado, 
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
            if(mysqli_num_rows($query) == 0) {
                ?>
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <h5 class="text">Você não enviou nenhum <i>feedback</i> ainda</h5>
                    </div>
                </div>
                <?php
            } else {
                while($f = mysqli_fetch_assoc($query)) {     
                
                if((int)$f['visualizado'] === 0) {  
                    $visualizado = '<span style="color: orange;">O destinatário ainda não visualizou</span>';
                } else {
                    $visualizado = '<span style="color: green;">O destinatário visualizou</span>';
                }
            ?>
                <div class="row" style="text-align: center;  margin-bottom: 2em;">
                    <div class="col-sm">
                        <h5 class="text"><b>Enviou para:</b> <?php echo $f['destinatario']; ?></h5>
                        <small class="text"><?php echo $f['data']; ?></small>
                        <br><small class="text"><?php echo $visualizado; ?></small>

                        <h6 class="text"><?php echo $f['texto']; ?></h6>

                        <?php if($f['comecar'] != '') { ?>
                            <h6 class="text"><b>Você orientou a começar a fazer: </b><?php echo $f['comecar']; ?></h6>
                        <?php } ?>

                        <?php if($f['continuar'] != '') { ?>
                            <h6 class="text"><b>Você orientou a continuar fazendo: </b><?php echo $f['continuar']; ?></h6>
                        <?php } ?>

                        <?php if($f['parar'] != '') { ?>
                            <h6 class="text"><b>Você orientou a parar de fazer: </b><?php echo $f['parar']; ?></h6>
                        <?php } ?>
                    </div>
                </div>  
            <?php } ?>   
        <?php } ?>
    </table>
</div>
</div>


<div id="feedback-pedidos" style="display: none;">
        <?php
        $select = "SELECT
                    t1.id as id_pedido,
                    t1.fee_id as fee_id,
                    DATE_FORMAT(t1.data, '%d/%m/%Y %H:%i') as data,
                    t1.motivo as motivo,
                    t1.cpf_solicitante as cpf_solicitante,
                    CASE
                        WHEN t2.col_nome_completo IS NOT NULL THEN t2.col_nome_completo
                        ELSE t3.ges_nome_completo
                    END as remetente
                   FROM tbl_feedback_pedido t1
                    LEFT JOIN tbl_colaborador t2
                        ON t2.col_cpf = t1.cpf_solicitante
                    LEFT JOIN tbl_gestor t3
                        ON t3.ges_cpf = t1.cpf_solicitante
                   WHERE t1.cpf_destinatario = '$cpf'
                   ORDER BY data DESC";
        $query = $helper->select($select, 1);
        if(mysqli_num_rows($query) == 0) {
            ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <h5 class="text">Você não recebeu nenhum pedido de <i>feedback</i> ainda</h5>
                </div>
            </div>
            <?php
        } else {
            while($f = mysqli_fetch_assoc($query)) {
            $atendido = $f['fee_id'] ? true : false;   
            
            if($atendido) {
                $span_atendido = '<span style="color: green;">Você já enviou um feedback pra esta solicitação</span>';
            } else {
                $span_atendido = '<span style="color: orange;">Você ainda não enviou um feedback pra esta solicitação</span>';
            }
        ?>
            <div class="row" style="text-align: center; margin-bottom: 2em;">
                <div class="col-sm">
                    <h5 class="text"><b>O pedido é de:</b> <?php echo $f['remetente']; ?></h5>
                    <small class="text"><?php echo $span_atendido; ?></small>
                    <br><small class="text"><?php echo $f['data']; ?></small>

                    <h6 class="text"><b>Motivo do pedido: </b><?php echo $f['motivo']; ?></h6>

                    <?php if(!$atendido) { ?>
                        <input type="button" style="font-size: 0.6em;" class="button button2" value="Enviar feedback para <?php echo $f['remetente']; ?>" onclick="atenderPedido('<?php echo $f['cpf_solicitante']; ?>', '<?php echo $f['id_pedido']; ?>');" data-toggle="modal" data-target="#modal-novo">
                    <?php } ?>
                </div>
            </div>  
        <?php } ?>   
    <?php } ?>  
    </table>
</div>


<div id="feedback-solicitados" style="display: none;">
        <?php
        $select = "SELECT
                    t1.id as id_pedido,
                    t1.fee_id as fee_id,
                    DATE_FORMAT(t1.data, '%d/%m/%Y %H:%i') as data,
                    t1.motivo as motivo,
                    CASE
                        WHEN t2.col_nome_completo IS NOT NULL THEN t2.col_nome_completo
                        ELSE t3.ges_nome_completo
                    END as destinatario
                   FROM tbl_feedback_pedido t1
                    LEFT JOIN tbl_colaborador t2
                        ON t2.col_cpf = t1.cpf_destinatario
                    LEFT JOIN tbl_gestor t3
                        ON t3.ges_cpf = t1.cpf_destinatario
                   WHERE t1.cpf_solicitante = '$cpf'
                   ORDER BY data DESC";
        $query = $helper->select($select, 1);
        if(mysqli_num_rows($query) == 0) {
            ?>
            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <h5 class="text">Você não pediu nenhum <i>feedback</i> ainda</h5>
                </div>
            </div>
            <?php
        } else {
            while($f = mysqli_fetch_assoc($query)) {
            $atendido = $f['fee_id'] ? true : false;   
            
            if($atendido) {
                $span_atendido = '<span style="color: green;">Você recebeu um feedback deste pedido</span>';
            } else {
                $span_atendido = '<span style="color: orange;">O destinatário ainda não atendeu ao seu pedido</span>';
            }
        ?>
            <div class="row" style="text-align: center; margin-bottom: 2em;">
                <div class="col-sm">
                    <h5 class="text"><b>Pediu para: </b><?php echo $f['destinatario']; ?></h5>
                    <small class="text"><?php echo $span_atendido; ?></small>
                    <br><small class="text"><?php echo $f['data']; ?></small>
                </div>
            </div>  
        <?php } ?>   
    <?php } ?>  
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
                    <label class="text">Eu te aconselharia a <i>começar</i> a fazer... (opcional)</label>
                    <textarea name="comecar" id="comecar" class="all-input" maxlength="200"></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label class="text">Eu te aconselharia a <i>continuar</i> fazendo... (opcional)</label>
                    <textarea name="continuar" id="continuar" class="all-input" maxlength="200"></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em;">
                <div class="col-sm">
                    <label class="text">Eu te aconselharia a <i>parar</i> de fazer... (opcional)</label>
                    <textarea name="parar" id="parar" class="all-input" maxlength="200"></textarea>
                </div>
            </div>

            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <input type="hidden" name="id_pedido" id="id_pedido" value="0">
                    <input type="submit" value="Enviar feedback" class="button button2">
                    </form>
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
                    <form method="POST" action="../database/feedback.php?pedir=true" id="form">
                    <label for="destinatario" class="text">Eu quero pedir um <i>feedback</i> para *</label>
                    <select name="destinatario" id="destinatario" class="all-input" required>
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
                    <textarea name="motivo" id="motivo" class="all-input" maxlength="200" required placeholder="Porque você participou de um evento, apresentação ou meta..."></textarea>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1.5em; text-align: center;">
                <div class="col-sm">
                    <input type="submit" value="Pedir feedback" class="button button2">
                    </form>
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