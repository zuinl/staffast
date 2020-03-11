<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_reuniao.php');
    require_once('../classes/class_gestor.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    $hoje = date('Y-m-d');
    if($_SESSION['user']['permissao'] == 'GESTOR-1') {
        $select = "SELECT DISTINCT reu_id as id FROM tbl_reuniao WHERE reu_data > '$hoje' ORDER BY reu_data ASC";
    } else {
        $select = "SELECT DISTINCT t1.reu_id as id FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
        ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND t2.reu_data < '$hoje' ORDER BY t2.reu_data ASC";
    }
    
    $query = $helper->select($select, 1);  
    $reunioes = array();
    $i = 0;
    while($f = mysqli_fetch_assoc($query)) {
        $reunioes[$i] = $f['id'];
        $i++;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reuniões passadas</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reuniões passadas</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/round-table.png" width="60">
        </div>
        <div class="col-sm">
            <h2 class="high-text">Reuniões passadas</h2>
        </div>
        <div class="col-sm">
            <a href="reunioes.php"><input type="button" class="button button1" value="Próximas reuniões"></a>
        </div>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">
    <?php
    if(sizeof($reunioes) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/round-table.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem reuniões passadas por enquanto.</h4>
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
            <th>Concluída?</th>
            <th>Atingiu o objetivo?</th>
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
                
                if($reuniao->getConcluida() == 1) {
                    $concluida = 'Concluída';
                } else {
                    $concluida = 'Em aberto';
                }

                if($reuniao->getAtingido() == 0) {
                    $atingida = 'Não :(';
                } else {
                    $atingida = 'Sim :D';
                }
        ?>
        <tr>
            <td><b><?php echo $reuniao->getPauta(); ?></b></td>
            <td><?php echo $reuniao->getObjetivo(); ?></td>
            <td><?php echo $quando; ?></td>
            <td><?php echo $concluida; ?></td>
            <td><?php echo $atingida; ?></td>
            <td><a href="verReuniao.php?id=<?php echo $reuniao->getID(); ?>"><input type="button" class="button button2" value="Ver"></a></td>
    <?php } ?>   
    </table>
 <?php } ?>
</div>
</body>
</html>