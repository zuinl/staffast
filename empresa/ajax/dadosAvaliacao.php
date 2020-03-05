<?php
include('../../include/auth.php');
include('../../src/meta.php');
require_once('../../classes/class_conexao_empresa.php');
require_once('../../classes/class_queryHelper.php');

if(!isset($_GET['cpf'])) die("Erro.");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
$helper = new QueryHelper($conn);

$cpf = $_GET['cpf'];

$select = "SELECT DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y') as criacao, t2.ges_nome_completo 
as nome FROM tbl_avaliacao t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
WHERE t1.col_cpf = '$cpf' ORDER BY t1.ava_data_criacao DESC LIMIT 1";

$query = $helper->select($select, 1);

?>
<div class="row">
    <div class="col-sm">
        <h5 class="text"><b>Informações relevantes</b></h5>
    </div>
</div>
<?php

if(mysqli_num_rows($query) == 0) {
    ?>
    <div class="row">
        <div class="col-sm">
            <p class="text">Nenhuma avaliação foi feita para este colaborador ainda. Faça a primeira!</p>
        </div>
    </div>
    <?php
} else {
    $fetch = $helper->select($select, 2);
    ?>
    <div class="row">
        <div class="col-sm">
            <p class="text">Última avaliação realizada em <?php echo $fetch['criacao'] ?> por <?php echo $fetch['nome']; ?></p>
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
        <div class="col-sm">
            <p class="text">O colaborador ainda não preencheu nenhuma autoavaliação</p>
        </div>
    </div>
    <?php
} else {
    $fetch = $helper->select($select_ata, 2);
    ?>
    <div class="row">
        <div class="col-sm">
            <p class="text">O colaborador preencheu uma autoavaliação pela última vez em <?php echo $fetch['preenchimento']; ?></p>
        </div>
    </div>
    <?php
}

?>