<?php

include('../../include/auth.php');
include('../../src/meta.php');
require_once('../../classes/class_conexao_empresa.php');
require_once('../../classes/class_queryHelper.php');
require_once('../../classes/class_evento.php');
require_once('../../classes/class_gestor.php');

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $condicao = "";

    if($_GET['dataI'] != "" && $_GET['dataF'] != "") {
        $dataI = $_GET['dataI'];
        $dataF = $_GET['dataF'];
        $condicao .= " AND (eve_data_inicial >= '$dataI' AND eve_data_final <= '$dataF')";
    }

    if($_GET['gestor'] != "") {
        $gestor = $_GET['gestor'];
        $condicao .= " AND ges_cpf = '$gestor'";
    }

    $select = "SELECT DISTINCT eve_id as id FROM tbl_evento WHERE 1".$condicao." ORDER BY eve_data_inicial ASC";

    $query = $helper->select($select, 1);
    $eventos = array();
    $i = 0;
    while($f = mysqli_fetch_assoc($query)) {
        $eventos[$i] = $f['id'];
        $i++;
    }
    ?>
    <div class="row">
        <div class="col-sm">
            <h4 class="high-text">Resultados dos filtros (inclui eventos passados e cancelados)</h4>
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
         <hr class="hr-divide-super-light">
        <?php
        } else { 
    ?>
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Quando</th>
            <th>Onde</th>
            <th>Presença</th>
            <th>Ver</th>
        </tr>
        <?php
            for($a = 0; $a < sizeof($eventos); $a++) {

                $evento = new Evento();
                $evento->setID($eventos[$a]);
                $evento = $evento->retornarEvento($_SESSION['empresa']['database']);

                $gestor = new Gestor();
                $gestor->setCpf($evento->getCpfGestor());
                $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
                
                $quando = $evento->getDataI()." às ".$evento->getHoraI()." até ".$evento->getDataF()." às ".$evento->getHoraF();
                $eve_id = $evento->getID();
                $select = "SELECT confirmado FROM tbl_evento_participante WHERE eve_id = '$eve_id' AND cpf = '".$_SESSION['user']['cpf']."'";
                $query = $helper->select($select, 1);
                $fetch = mysqli_fetch_assoc($query);
                if($fetch['confirmado'] == 1) $confirmado = 1;
                else if (mysqli_num_rows($query) == 0) $confirmado = 2;
                else $confirmado = 0;
        ?>
        <tr>
            <td><b><?php echo $evento->getTitulo(); ?></b></td>
            <td><?php echo $evento->getDescricao(); ?></td>
            <td><?php echo $quando; ?></td>
            <td><?php echo $evento->getLocal(); ?></td>
            <?php if($confirmado == 0) { ?>
                <td><a href="../database/evento.php?confirmar=true&id=<?php echo $evento->getID(); ?>"><input type="button" class="button button3" value="Confirmar presença"></a></td>
            <?php } else if ($confirmado == 1) { ?>
                <td>
                    Você confirmou presença
                    <br><a href="../database/evento.php?desconfirmar=true&id=<?php echo $evento->getID(); ?>"><input type="button" class="button button3" value="Reverter confirmação"></a></td>
                </td>
            <?php } else if ($confirmado == 2) { ?>
            <td>-</td>
            <?php } ?>
            <td><a href="verEvento.php?id=<?php echo $evento->getID(); ?>"><input type="button" class="button button2" value="Ver"></a></td>
    <?php } ?>   
    </table>

    <hr class="hr-divide-super-light">
 <?php } ?>