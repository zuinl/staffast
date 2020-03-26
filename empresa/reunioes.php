<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_reuniao.php');
    require_once('../classes/class_gestor.php');
    date_default_timezone_set('America/Sao_Paulo');

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

    $hoje = date('Y-m-d');
    $hora = date('H:i:s');
    if($_SESSION['user']['permissao'] == 'GESTOR-1') {
        $select = "SELECT DISTINCT reu_id as id FROM tbl_reuniao WHERE (reu_data >= '$hoje' OR (reu_data = '$hoje' AND reu_hora < '$hora')) AND reu_concluida = 0 ORDER BY reu_data ASC";
    } else {
        $select = "SELECT DISTINCT t1.reu_id as id FROM tbl_reuniao_integrante t1 INNER JOIN tbl_reuniao t2 
        ON t2.reu_id = t1.reu_id WHERE t1.cpf = '$cpf' AND (t2.reu_data >= '$hoje' OR (t2.reu_data = '$hoje' AND t2.reu_hora >= '$hora')) AND reu_concluida = 0 ORDER BY t2.reu_data ASC";
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
    <title>Próximas reuniões</title>
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
            xmlreq.open("GET", "ajax/reunioes.php?dataI=" + dataI + "&dataF=" + dataF + "&gestor=" + gestor, true);
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
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reuniões</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm-1">
            <img src="img/round-table.png" width="60">
        </div>
        <div class="col-sm">
            <h2 class="high-text">Próximas reuniões</h2>
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
        <div class="col-sm">
            <a href="novaReuniao.php"><input type="button" class="button button1" value="Criar reunião"></a>
        </div>
        <div class="col-sm">
            <a href="reunioesPassadas.php"><input type="button" class="button button1" value="Reuniões realizadas"></a>
        </div>
        <?php } ?>
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
            <label class="text">Reuniões entre esta data...</label>
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
    if(sizeof($reunioes) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/round-table.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem próximas reuniões por enquanto.</h4>
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
 <?php } ?>
</div>
</body>
</html>