<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_gestor.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include("../include/acessoNegado.php");
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] == 'GESTOR-1') {
        $select = "SELECT DISTINCT eve_id as id FROM tbl_evento WHERE eve_status = 0 ORDER BY eve_data_inicial ASC";
    } else {
        $select = "SELECT DISTINCT t1.eve_id as id FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
        ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_status = 0 ORDER BY t2.eve_data_inicial ASC";
    }
    
    $query = $helper->select($select, 1);  
    $eventos = array();
    $i = 0;
    while($f = mysqli_fetch_assoc($query)) {
        $eventos[$i] = $f['id'];
        $i++;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eventos cancelados</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1">
            <img src="img/calendar.png" width="60">
        </div>
        <div class="col-sm">
            <h1 class="high-text">Eventos <span class="destaque-text">cancelados</span></h1>
        </div>
        <div class="col-sm">
                <a href="eventos.php"><input type="button" class="button button1" value="Eventos próximos"></a>
        </div>
        <div class="col-sm">
            <a href="eventosPassados.php"><input type="button" class="button button1" value="Eventos passados"></a>
        </div>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">
    <?php
    if(sizeof($eventos) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/goal.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem eventos cancelados por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Quando</th>
            <th>Onde</th>
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

                $select = "SELECT confirmado FROM tbl_evento_participante WHERE cpf = '".$_SESSION['user']['cpf']."'";
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
            <td><a href="verEvento.php?id=<?php echo $evento->getID(); ?>"><input type="button" class="button button2" value="Ver"></a></td>
    <?php } ?>   
    </table>
 <?php } ?>
</div>
</body>
</html>