<?php
    include("../src/meta.php");
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_empresa.php');
    require_once('../classes/class_avaliacao_gestao.php');
    require_once('../classes/class_conexao_padrao.php');
    require_once('../classes/class_queryHelper.php');

    if(!isset($_POST['codigo'])) die("Erro");

    $codigo = $_POST['codigo'];
    $email = $_POST['email'];

    $conexao = new ConexaoPadrao();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT emp_id FROM tbl_codigo_avaliacao_empresa WHERE cod_string = '$codigo' 
    AND cod_validade > NOW()";

    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        die('<div class="container">
                <h1 class="high-text">Desculpe, mas parece que este código está inválido</h1>
                <a href="index.php"><button class="button button3">Tentar de novo</button></a>
                <a href="../index.php"><button class="button button3">Voltar</button></a>
            </div>
        ');
    }

    $select_usu = "SELECT usu_id as id FROM tbl_usuario WHERE usu_email = '$email'";
    $query_usu = $helper->select($select_usu, 1);

    if(mysqli_num_rows($query_usu) == 0) {
        die('<div class="container">
                <h1 class="high-text">Desculpe, mas parece que o e-mail que você inseriu não está cadastrado no Staffast</h1>
                <a href="index.php"><button class="button button3">Tentar de novo</button></a>
                <a href="../index.php"><button class="button button3">Voltar</button></a>
            </div>
        ');
    }

    $fetch = $helper->select($select, 2);
    $fetch_usu = $helper->select($select_usu, 2);

    $usu_id = $fetch_usu['id'];

    $empresa = new Empresa();
    $empresa->setID($fetch['emp_id']);
    $empresa = $empresa->retornarEmpresa();

    $select = "SELECT * FROM tbl_campos_avaliacao_gestao WHERE emp_id = ".$fetch['emp_id'];
        $row = $helper->select($select, 2);

        $avg1 = $row['um'];
        $avg2 = $row['dois'];
        $avg3 = $row['tres'];
        $avg4 = $row['quatro'];
        $avg5 = $row['cinco'];
        $avg6 = $row['seis'];
        $avg7 = $row['sete'];
        $avg8 = $row['oito'];
        $avg9 = $row['nove'];
        $avg10 = $row['dez'];

    $gestor = new Gestor();
    $setor = new Setor();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Avalie sua empresa</title>
    <script>
        function setValor(span, valor) {
            span.innerHTML = valor;
        }
    </script>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="../img/logo_staffast.png" width="200">
        </div>
    </div>
        <div class="col-sm">
            <h1 class="high-text">Você está avaliando <?php echo $empresa->getRazao(); ?></h1>
        </div>
    </div>

    <hr class="hr-divide">

</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
        <form method="POST" action="db_avaliacao_gestao.php?salvar=true" id="form">
            <label for="setor" class="text">Selecione o setor avaliado *</label>
           <select name="setor" id="setor" class="all-input" required>
                <option value="0" selected>Toda a empresa</option>
                <?php echo $setor->popularSelect($empresa->getDatabase()); ?>
           </select>
        </div>
        <div class="col-sm">
            <label for="gestor" class="text">Selecione o gestor avaliado (opcional)</label>
           <select name="gestor" id="gestor" class="all-input">
                <option value="0" selected>-- Selecione --</option>
                <?php echo $gestor->popularSelect($empresa->getDatabase()); ?>
           </select>
        </div>
    </div>    

    <small class="text">Lembre-se: você poderá avaliar a empresa quantas vezes quiser enquanto o código fornecido estiver válido.</small>

    <table class="table-site">
        <tr>
            <th>Característica avaliada</th>
            <th>Nota</th>
            <th>Observação (opcional)</th>
        </tr>
        <tr>
            <td><?php echo $avg1; ?></td>
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
            <td><?php echo $avg2; ?></td>
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
            <td><?php echo $avg3; ?></td>
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
            <td><?php echo $avg4; ?></td>
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
        <?php if($avg5 != "") { ?>
        <tr>
            <td><?php echo $avg5; ?></td>
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
        <?php if($avg6 != "") { ?>
        <tr>
            <td><?php echo $avg6; ?></td>
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
        <?php if($avg7 != "") { ?>
        <tr>
            <td><?php echo $avg7; ?></td>
            <td>
                <input type="radio" name="compet_sete" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_sete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_sete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_sete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_sete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_sete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($avg8 != "") { ?>
        <tr>
            <td><?php echo $avg8; ?></td>
            <td>
                <input type="radio" name="compet_oito" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_oito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_oito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_oito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_oito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_oito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($avg9 != "") { ?>
        <tr>
            <td><?php echo $avg9; ?></td>
            <td>
                <input type="radio" name="compet_nove" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_nove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_nove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_nove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_nove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_nove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($avg10 != "") { ?>
        <tr>
            <td><?php echo $avg10; ?></td>
            <td>
                <input type="radio" name="compet_dez" value="1" class="radioMy" required> <img src="../empresa/img/unhappy.png" width="30">
                <input type="radio" name="compet_dez" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="../empresa/img/sad.png" width="30">
                <input type="radio" name="compet_dez" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/confused.png" width="30">
                <input type="radio" name="compet_dez" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/smiling.png" width="30">
                <input type="radio" name="compet_dez" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="../empresa/img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dez_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="hidden" value="<?php echo $empresa->getDatabase(); ?>" name="database">
            <input type="hidden" value="<?php echo $codigo; ?>" name="codigo">
            <input type="hidden" value="<?php echo $usu_id; ?>" name="usu_id">
            <input type="submit" value="Avaliar" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>
</html>