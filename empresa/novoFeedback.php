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
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h1 class="high-text">Novo <span class="destaque-text">feedback</span></h1>
        </div>
    </div>

    <hr class="hr-divide">

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
</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
        <form method="POST" action="../database/feedback.php?novo=true" id="form">
            <label for="feedback" class="text"><i>Feedback</i> *</label>
            <textarea name="feedback" id="feedback" class="all-input" maxlength="200" required=""></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="dono" class="text">Este <i>feedback</i> é para</label>
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

    <div class="row">
        <div class="col-sm">
            <h6 class="text">Os <i>feedbacks</i> são uma forma de comentário rápido sobre uma reunião, evento ou simplesmente uma opinião sobre um dos seus colegas.</h6>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="submit" value="Enviar" class="button button2">
        </div>
    </div>
    </form>

    <hr class="hr-divide">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text"><i>Feedbacks</i> recebidos</h4>
        </div>
    </div>

    <table class="table-site">
        <tr>
            <th>Quem te enviou</th>
            <th>Data</th>
            <th><i>Feedback</i></th>
        </tr>
        <?php
            $cpf = $_SESSION['user']['cpf'];
            $select = "SELECT t1.fee_texto as texto, 
                        CASE 
                            WHEN t1.ges_cpf IS NOT NULL THEN t2.ges_primeiro_nome
                            ELSE t3.col_primeiro_nome END
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
        <tr>
            <td><b><?php echo $f['remetente']; ?></b></td>
            <td><?php echo $f['data']; ?></td>
            <td><?php echo $f['texto']; ?></td>
        <?php } ?>   
    </table>
</div>
</body>
</html>