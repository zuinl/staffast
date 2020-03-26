<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $setor = new Setor();
    $setor->setID($_GET['id']);
    $setor = $setor->retornarSetor($_SESSION['empresa']['database']);

    if(!$setor->isAvaliacaoLiberada($_SESSION['empresa']['database'])) {
        echo '<h1 class="destaque-text">Desculpe, mas parece que as avaliações deste setor não estão liberadas</h1>';
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $cpf = $_SESSION['user']['cpf'];
    $select = "SELECT set_id FROM tbl_setor_funcionario WHERE set_id = ".$setor->getID()." AND (col_cpf = '$cpf' OR ges_cpf = '$cpf')";
    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        echo '<h1 class="destaque-text">Desculpe, mas parece que você não pertence a este setor</h1>';
        die();
    }

    $select = "SELECT um, dois, tres, quatro, cinco, seis FROM tbl_setor_competencia 
    WHERE set_id = ".$setor->getID(); 
    $fetch = $helper->select($select, 2);
    
    if($fetch['um'] == "") {
        echo '<h1 class="high-text">Houve algum erro :[</h1>';
        die();
    }

    $um = $fetch['um'];
    $dois = $fetch['dois'];
    $tres = $fetch['tres'];
    $quatro = $fetch['quatro'];
    $cinco = $fetch['cinco'];
    $seis = $fetch['seis'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova avaliação de setor</title>
    <script>
        function setValor(span, valor) {
            span.innerHTML = valor;
        }
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Nova <span class="destaque-text">avaliação</span> do setor <?php echo $setor->getNome(); ?></h2>
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

    <h6 class="text">LEMBRE-SE: você está avaliando o <b>SETOR <?php echo $setor->getNome(); ?></b> e todas as competências abaixo são relacionadas ao SETOR como um todo. 
    Leve isto em consideração quando atribuir as notas e os comentários. Lembre-se também que está avaliação é anônima, os gestores não saberão quem a fez individualmente. Seja profissional 
    e assertivo. Vamos lá!</h6>

    <form method="POST" action="../database/avaliacaoSetor.php?nova=true" id="form">
    <table class="table-site">
        <tr>
            <th>Competência avaliada</th>
            <th>Nota</th>
            <th>Observação</th>
        </tr>
        <tr>
            <td><?php echo $um; ?></td>
            <td>
                <input type="radio" name="compet_um" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_um" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_um" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_um" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_um" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_um_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $dois; ?></td>
            <td>
                <input type="radio" name="compet_dois" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_dois" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_dois" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_dois" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_dois" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dois_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $tres; ?></td>
            <td>
                <input type="radio" name="compet_tres" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_tres" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_tres" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_tres" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_tres" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_tres_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $quatro; ?></td>
            <td>
                <input type="radio" name="compet_quatro" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_quatro" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_quatro" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_quatro" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_quatro" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatro_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php if($cinco != "") { ?>
        <tr>
            <td><?php echo $cinco; ?></td>
            <td>
                <input type="radio" name="compet_cinco" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_cinco" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_cinco" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_cinco" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_cinco" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_cinco_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($seis != "") { ?>
        <tr>
            <td><?php echo $seis; ?></td>
            <td>
                <input type="radio" name="compet_seis" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_seis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_seis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_seis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_seis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_seis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="hidden" name="setor" value="<?php echo $setor->getID(); ?>">
            <input type="submit" value="Cadastrar" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>
</html>