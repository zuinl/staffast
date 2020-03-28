<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $ava = new Autoavaliacao();
    $ava->setCpfColaborador($_SESSION['user']['cpf']);

    if(!$ava->checarLiberada($_SESSION['empresa']['database'])) {
        include('../include/navbar.php');
        ?>
        <body>
        <div class="container" style="text-align: center;">

            <div class="row">
                <div class="col-sm">
                    <img src="../img/logo_staffast.png" width="220">
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row">
                <div class="col-sm">
                    <h2 class="text">Desculpe, <?php echo $_SESSION['user']['primeiro_nome']; ?>, 
                    mas ainda não há autoavaliações liberadas pelos gestores para você</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <h4 class="text">Lembrete: apenas Colaboradores ou Gestores que tenham cadastro duplicado, ou seja, que também sejam colaboradores 
                    podem fazer uma autoavaliação. Se você se enquadra nesse perfil e ainda não consegue se autoavaliar, pode ser que os seus gestores não 
                    liberaram nenhuma autoavaliação pra você.</h4>
                </div>
            </div>
        </div>
    </body>
        <?php
        die();
    }

    $ata_id = $ava->retornarLiberada($_SESSION['empresa']['database']);

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $cpf = $_SESSION['user']['cpf'];
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova autoavaliação</title>
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

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nova autoavaliação</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Nova autoavaliação</h2>
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
    <?php
    $select = "SELECT DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y') as criacao, t2.ges_nome_completo 
    as nome FROM tbl_avaliacao t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
    WHERE t1.col_cpf = '$cpf' ORDER BY t1.ava_data_criacao DESC LIMIT 1";

    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <h5 class="text">A gestão ainda não te avaliou. Prossiga com sua autoavaliação ;D</h5>
            </div>
        </div>
        <?php
    } else {
        $fetch = $helper->select($select, 2);
        ?>
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <h5 class="text">Última avaliação realizada em <?php echo $fetch['criacao'] ?> por <?php echo $fetch['nome']; ?></h5>
            </div>
        </div>
        <?php
    }

    $select_ata = "SELECT DATE_FORMAT(ata_data_preenchida, '%d/%m/%Y') as preenchimento 
    FROM tbl_autoavaliacao WHERE col_cpf = '$cpf' AND ata_preenchida = 1 ORDER BY ata_data_preenchida DESC LIMIT 1";

    $query_ata = $helper->select($select_ata, 1);

    if(mysqli_num_rows($query_ata) == 0) {
        ?>
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <h5 class="text">Você ainda não fez nenhuma autoavaliação, siga em frente com sua primeira!</h5>
            </div>
        </div>
        <?php
    } else {
        $fetch = $helper->select($select_ata, 2);
        ?>
        <div class="row">
            <div class="col-sm" style="text-align: center;">
                <h5 class="text">Última autoavaliação realizada em <?php echo $fetch['preenchimento'] ?></h5>
            </div>
        </div>
        <?php
    }
    ?>
    
    <form method="POST" action="../database/autoavaliacao.php?nova=true" id="form">
    <input type="hidden" name="ata_id" value="<?php echo $ata_id; ?>">
    <table class="table-site">
        <tr>
            <th>Competência avaliada</th>
            <th>Nota</th>
            <th>Observação</th>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_um']; ?></td>
            <td>
                <input type="radio" name="compet_um" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_um" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_um" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_um" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_um" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_um_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dois']; ?></td>
            <td>
                <input type="radio" name="compet_dois" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dois" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dois" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dois" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dois" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dois_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_tres']; ?></td>
            <td>
                <input type="radio" name="compet_tres" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_tres" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_tres" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_tres" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_tres" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_tres_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quatro']; ?></td>
            <td>
                <input type="radio" name="compet_quatro" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatro" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatro" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatro" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatro" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatro_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_cinco']; ?></td>
            <td>
                <input type="radio" name="compet_cinco" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_cinco" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_cinco" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_cinco" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_cinco" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_cinco_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_seis']; ?></td>
            <td>
                <input type="radio" name="compet_seis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_seis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_seis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_seis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_seis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_seis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_sete']; ?></td>
            <td>
                <input type="radio" name="compet_sete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_sete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_sete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_sete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_sete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_sete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_oito']; ?></td>
            <td>
                <input type="radio" name="compet_oito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_oito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_oito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_oito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_oito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_oito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_nove']; ?></td>
            <td>
                <input type="radio" name="compet_nove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_nove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_nove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_nove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_nove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_nove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dez']; ?></td>
            <td>
                <input type="radio" name="compet_dez" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dez" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dez" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dez" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dez" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dez_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_onze']; ?></td>
            <td>
                <input type="radio" name="compet_onze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_onze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_onze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_onze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_onze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_onze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_doze']; ?></td>
            <td>
                <input type="radio" name="compet_doze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_doze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_doze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_doze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_doze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_doze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_treze']; ?></td>
            <td>
                <input type="radio" name="compet_treze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_treze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_treze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_treze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_treze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_treze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quatorze']; ?></td>
            <td>
                <input type="radio" name="compet_quatorze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatorze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatorze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatorze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatorze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatorze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quinze']; ?></td>
            <td>
                <input type="radio" name="compet_quinze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quinze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quinze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quinze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quinze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quinze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></td>
            <td>
                <input type="radio" name="compet_dezesseis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezesseis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezesseis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezesseis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezesseis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezesseis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezessete']; ?></td>
            <td>
                <input type="radio" name="compet_dezessete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezessete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezessete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezessete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezessete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezessete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezoito']; ?></td>
            <td>
                <input type="radio" name="compet_dezoito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezoito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezoito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezoito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezoito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezoito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezenove']; ?></td>
            <td>
                <input type="radio" name="compet_dezenove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezenove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezenove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezenove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezenove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezenove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_vinte']; ?></td>
            <td>
                <input type="radio" name="compet_vinte" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_vinte" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_vinte" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_vinte" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_vinte" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_vinte_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>

    <hr class="hr-divide-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="hidden" value="<?php echo $ata_id; ?>" name="ata_id">
            <input type="submit" value="Cadastrar" class="button button2" onclick="">
        </div>
        <div class="col-sm">
            <input type="reset" value="Limpar" class="button button2" onclick="">
        </div>
    </div>
    </form>
</div>
</body>
</html>