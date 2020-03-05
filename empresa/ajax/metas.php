<?php

    include('../../include/auth.php');
    include('../../src/meta.php');
    require_once('../../classes/class_conexao_empresa.php');
    require_once('../../classes/class_queryHelper.php');
    require_once('../../classes/class_okr.php');
    require_once('../../classes/class_key_result.php');
    require_once('../../classes/class_gestor.php');

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $condicao = "";

    if($_GET['prazo'] != "") {
        $prazo = $_GET['prazo'];
        $condicao .= " AND okr_prazo >= '$prazo'";
    }

    if($_GET['gestor'] != "") {
        $gestor = $_GET['gestor'];
        $condicao .= " AND ges_cpf = '$gestor'";
    }

    $select = "SELECT DISTINCT okr_id as id FROM tbl_okr WHERE 1".$condicao." ORDER BY okr_prazo DESC";

    $query = $helper->select($select, 1);
    $metas = array();
    $i = 0;
    while($f = mysqli_fetch_assoc($query)) {
        $metas[$i] = $f['id'];
        $i++;
    }
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
         <hr class="hr-divide-super-light">
        <?php
        } else { 
    ?>
    <table class="table-site">
        <tr>
            <th>Meta</th>
            <th>Categoria</th>
            <th>Prazo</th>
            <th>Criado por</th>
            <th>Ver</th>
        </tr>
        <?php
            for($a = 0; $a < sizeof($metas); $a++) {

                $okr = new OKR();
                $okr->setID($metas[$a]);
                $okr = $okr->retornarOKR($_SESSION['empresa']['database']);

                $gestor = new Gestor();
                $gestor->setCpf($okr->getCpfGestor());
                $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
                
        ?>
        <tr>
            <td><b><?php echo $okr->getTitulo(); ?></b></td>
            <td><?php echo $okr->getTipo(); ?></td>
            <td><?php echo $okr->getPrazo(); ?></td>
            <td><?php echo $gestor->getPrimeiroNome(); ?></td>
            <?php if ($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2" || $okr->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'])) { ?>
                <td><a href="verOKR.php?id=<?php echo $okr->getID(); ?>"><input type="button" class="button button3" value="Ver"></a></td>
            <?php } else { ?>
                <td>Não disponível para você</td>
            <?php } ?>
    <?php } ?>   
    </table>

    <hr class="hr-divide-super-light">
 <?php } ?>