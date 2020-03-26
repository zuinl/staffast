<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_evento.php');
    require_once('../classes/class_gestor.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] == 'GESTOR-1') { 
        $select = "SELECT DISTINCT eve_id as id FROM tbl_evento WHERE eve_data_inicial >= NOW() AND eve_status = 1 ORDER BY eve_data_inicial ASC";
    } else {
        $select = "SELECT DISTINCT t1.eve_id as id FROM tbl_evento_participante t1 INNER JOIN tbl_evento t2 
        ON t2.eve_id = t1.eve_id WHERE t1.cpf = '$cpf' AND t2.eve_data_inicial >= NOW() AND eve_status = 1 ORDER BY t2.eve_data_inicial ASC";
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
    <title>Próximos eventos</title>
    <script>
    function CriaRequest() {
            try{
                request = new XMLHttpRequest();        
            }
            catch (IEAtual) {
                try{
                    request = new ActiveXObject("Msxml2.XMLHTTP");       
                }
                catch(IEAntigo){
                    try{
                        request = new ActiveXObject("Microsoft.XMLHTTP");          
                    }   
                    catch(falha){
                    request = false;
                    }
                }
            }
      
            if (!request) 
                alert("Seu Navegador não suporta Ajax!");
            else
                return request;
        }

        function getDados() {
            var dataI = document.getElementById("dataI").value;
            var dataF = document.getElementById("dataF").value;
            var gestor = document.getElementById("gestor").value;
            var resposta = document.getElementById("resposta");

            if(dataI == "" && dataF == "" && gestor == "") {
                alert("Insira ao menos um filtro");
                return;
            }

            if((dataI != "" && dataF == "") || (dataI == "" && dataF != "")) { 
                alert("Se você inserir uma das datas, precisa preencher a outra ou deixar ambas vazias.");
                return;
            }

            var xmlreq = CriaRequest();
            resposta.innerHTML = '<h5 class="text">Buscando dados...</h5>';
            xmlreq.open("GET", "ajax/eventos.php?dataI=" + dataI + "&dataF=" + dataF + "&gestor=" + gestor, true);
            xmlreq.onreadystatechange = function(){
                if (xmlreq.readyState == 4) {
                    if (xmlreq.status == 200) {
                        resposta.innerHTML = xmlreq.responseText;
                    }
                    else{
                        resposta.innerHTML = "Erro: " + xmlreq.statusText;
                    }
                }
            };
            xmlreq.send(null);
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
            <li class="breadcrumb-item active" aria-current="page">Próximos eventos</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/calendar.png" width="60">
        </div>
        <div class="col-sm-5">
            <h2 class="high-text">Próximos <span class="destaque-text">eventos</span></h2>
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
        <div class="col-sm">
            <a href="novoEvento.php"><input type="button" class="button button1" value="Criar evento"></a>
        </div>
        <div class="col-sm">
            <a href="eventosEncerrados.php"><input type="button" class="button button1" value="Eventos cancelados"></a>
        </div>
        <?php } ?>
        <div class="col-sm">
            <a href="eventosPassados.php"><input type="button" class="button button1" value="Eventos passados"></a>
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

    <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
    <div class="row">
        <div class="col-sm">
            <label class="text">Eventos entre esta data...</label>
            <input type="date" name="dataI" id="dataI" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">... e esta</label>
            <input type="date" name="dataF" id="dataF" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Gestor organizador</label>
            <select id="gestor" name="gestor" class="all-input">
                <option value="" disabled selected>-- Selecione --</option>
                <?php
                    $gestor = new Gestor();
                    $gestor->popularSelect($_SESSION['empresa']['database']);
                ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 1em;">
            <input type="button" class="button button1" value="Filtrar" onclick="getDados();">
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-super-light">

    <div id="resposta"></div>

    <?php
    if(sizeof($eventos) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/goal.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem próximos eventos por enquanto.</h4>
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
 <?php } ?>
</div>
</body>
</html>