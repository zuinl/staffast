<?php

include('../../include/auth.php');
include('../../src/meta.php');
require_once('../../classes/class_conexao_empresa.php');
require_once('../../classes/class_conexao_padrao.php');
require_once('../../classes/class_queryHelper.php');
require_once('../../classes/class_colaborador.php');
require_once('../../classes/class_gestor.php');
require_once('../../classes/class_usuario.php');

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $conexao_p = new ConexaoPadrao();
    $conn = $conexao_p->conecta();

    $emp_id = $_SESSION['empresa']['emp_id'];
    $server_database = $conexao_p->getDatabase();
    $empresa_database = $_SESSION['empresa']['database'];

    $condicao = "";

    if($_GET['dataI'] != "") {
        $dataI = $_GET['dataI']." 00:00:00";
        $condicao .= " AND t2.alt_timestamp >= '$dataI'";
    }

    if($_GET['dataF'] != "") {
        $dataF = $_GET['dataF']." 23:59:59";
        $condicao .= " AND t2.alt_timestamp <= '$dataF'";
    }

    if($_GET['gestor'] != "") {
        $gestor = $_GET['gestor'];
        $condicao .= " AND t3.ges_cpf = '$gestor'";
    }

    $select = "SELECT 
    t2.alt_descricao as descricao, 
    DATE_FORMAT(t2.alt_timestamp, '%d/%m/%Y às %H:%i') as hora,
    CASE
        WHEN t3.ges_nome_completo IS NOT NULL THEN t3.ges_nome_completo
        WHEN t4.col_nome_completo IS NOT NULL THEN t4.col_nome_completo
        ELSE 'Não identificado'
    END as nome
    FROM $server_database.tbl_usuario t1 
        INNER JOIN $server_database.tbl_log_alteracao t2 
            ON t2.usu_id = t1.usu_id 
        INNER JOIN $empresa_database.tbl_gestor t3
            ON t3.usu_id = t1.usu_id
        INNER JOIN $empresa_database.tbl_colaborador t4
            ON t4.usu_id = t1.usu_id  
    WHERE t1.emp_id = '$emp_id'".$condicao." 
        ORDER BY t2.alt_timestamp DESC";

    $query = $helper->select($select, 1);
    ?>
    <div class="row">
        <div class="col-sm">
            <h4 class="high-text">Resultados dos filtros</h4>
        </div>
    </div>
    <?php

    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Nada encontrado para os filtros inseridos.</h4>
            </div>
         </div>
        <?php
        } else { 
    ?>
    <table class="table-site">
        <tr>
            <th>Data e hora</th>
            <th>Alteração realizada</th>
            <th>Usuário atuante</th>
        </tr>
        <?php
            while($f = mysqli_fetch_assoc($query)) {       
        ?>
        <tr>
            <td><b><?php echo $f['hora']; ?></b></td>
            <td><?php echo $f['descricao']; ?></td>
            <td><?php echo $f['nome']; ?></td>
        <?php } ?>   
    </table>

    <hr class="hr-divide-super-light">
 <?php } ?>