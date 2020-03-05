<?php

    include('../../include/auth.php');
    include('../../src/meta.php');
    require_once('../../classes/class_conexao_empresa.php');
    require_once('../../classes/class_queryHelper.php');
    require_once('../../classes/class_reuniao.php');
    require_once('../../classes/class_gestor.php');

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $condicao = "";

    if($_GET['dataI'] != "" && $_GET['dataF'] != "") {
        $dataI = $_GET['dataI'];
        $dataF = $_GET['dataF'];
        $condicao .= " AND (reu_data >= '$dataI' AND reu_data <= '$dataF')";
    }

    if($_GET['gestor'] != "") {
        $gestor = $_GET['gestor'];
        $condicao .= " AND ges_cpf = '$gestor'";
    }

    $select = "SELECT DISTINCT reu_id as id FROM tbl_reuniao WHERE 1".$condicao." ORDER BY reu_data ASC";

    $query = $helper->select($select, 1);
    $reunioes = array();
    $i = 0;
    while($f = mysqli_fetch_assoc($query)) {
        $reunioes[$i] = $f['id'];
        $i++;
    }
    ?>
    <div class="row">
        <div class="col-sm">
            <h4 class="high-text">Resultados dos filtros (inclui reuniões já realizadas)</h4>
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
            <th>Pauta</th>
            <th>Objetivo</th>
            <th>Quando</th>
            <th>Onde</th>
            <th>Presença</th>
            <th>Ver</th>
        </tr>
        <?php
            for($a = 0; $a < sizeof($reunioes); $a++) {

                $reuniao = new Reuniao();
                $reuniao->setID($reunioes[$a]);
                $reuniao = $reuniao->retornarReuniao($_SESSION['empresa']['database']);

                $gestor = new Gestor();
                $gestor->setCpf($reuniao->getCpfGestor());
                $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
                
                $quando = $reuniao->getData()." às ".$reuniao->getHora();
                $reu_id = $reuniao->getID();
                $select = "SELECT confirmado FROM tbl_reuniao_integrante WHERE reu_id = '$reu_id' AND cpf = '".$_SESSION['user']['cpf']."'";
                $query = $helper->select($select, 1);
                $fetch = mysqli_fetch_assoc($query);
                if($fetch['confirmado'] == 1) $confirmado = 1;
                else if (mysqli_num_rows($query) == 0) $confirmado = 2;
                else $confirmado = 0;
        ?>
        <tr>
            <td><b><?php echo $reuniao->getPauta(); ?></b></td>
            <td><?php echo $reuniao->getObjetivo(); ?></td>
            <td><?php echo $quando; ?></td>
            <td><?php echo $reuniao->getLocal(); ?></td>
            <?php if($confirmado == 0) { ?>
                <td><a href="../database/reuniao.php?confirmar=true&id=<?php echo $reuniao->getID(); ?>"><input type="button" class="button button3" value="Confirmar presença"></a></td>
            <?php } else if ($confirmado == 1) { ?>
                <td>
                    Você confirmou presença
                    <br><a href="../database/reuniao.php?desconfirmar=true&id=<?php echo $reuniao->getID(); ?>"><input type="button" class="button button3" value="Reverter confirmação"></a></td>
                </td>
            <?php } else if ($confirmado == 2) { ?>
            <td>-</td>
            <?php } ?>
            <td><a href="verReuniao.php?id=<?php echo $reuniao->getID(); ?>"><input type="button" class="button button2" value="Ver"></a></td>
    <?php } ?>   
    </table>

    <hr class="hr-divide-super-light">
 <?php } ?>