<?php
session_start();
include('../../src/meta.php');
require_once '../../classes/class_conexao_padrao.php';
require_once '../../classes/class_queryHelper.php';

if(!isset($_GET['token'])) {
    die('Sem permissão de acesso à esta página');
}

if($_GET['token'] != "367617f269506e643722f6ce486fb98d") {
    die('Sem permissão de acesso à esta página');
}

$conexao = new ConexaoPadrao();
$conn = $conexao->conecta();
$helper = new QueryHelper($conn);

$select = "SELECT DISTINCT email as email, DATE_FORMAT(data_add, '%d/%m/%Y às %H:%i') as data 
FROM tbl_newsletter_assinantes ORDER BY data_add DESC";
$query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Assinantes da Newsletter</title>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="../../img/logo_staffast.png" width="180">
</nav>

<body>

    <div class="container">

        <?php
        if(isset($_SESSION['msg'])) {
            ?>
            <div class="container-fluid">
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
            </div>
            <?php
        }
        ?>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Assinantes da Newsletter</h3>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text"><b>Lista de e-mails</b></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <?php
                while($f = mysqli_fetch_assoc($query)) {
                    $email = $f['email'];
                    $data = $f['data'];
                    ?>
                    <p class="text">E-mail: <?php echo $email; ?> - Desde <?php echo $data; ?></p>
                    <?php
                }
                ?>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <small class="text">Adsumus Sistemas - <?php echo date('Y'); ?></small>
            </div>
        </div>
    </div>

    </div>
</body>