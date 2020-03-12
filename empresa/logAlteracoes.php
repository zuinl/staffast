<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_conexao_padrao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_usuario.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include("../include/acessoNegado.php");
        die();
    }

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $conexao_p = new ConexaoPadrao();
    $conn = $conexao_p->conecta();

    $emp_id = $_SESSION['empresa']['emp_id'];
    $server_database = $conexao_p->getDatabase();
    $empresa_database = $_SESSION['empresa']['database'];

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
    WHERE t1.emp_id = '$emp_id' 
        ORDER BY t2.alt_timestamp DESC LIMIT 100";

    $query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Alterações</title>
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

            if(dataI == "" || dataF == "") { 
                alert("Por favor, insira a data inicial e final. São os filtros mínimos para a pesquisa");
                return;
            }

            var xmlreq = CriaRequest();
            resposta.innerHTML = '<h5 class="text">Buscando dados...</h5>';
            xmlreq.open("GET", "ajax/logAlteracao.php?dataI=" + dataI + "&dataF=" + dataF + "&gestor=" + gestor, true);
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
            <li class="breadcrumb-item active" aria-current="page">Relatório de ações</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Relatório de <span class="destaque-text">ações</span></h2>
        </div>
    </div>

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm-6">
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

    <div class="row">
        <div class="col-sm">
            <label class="text">Data inicial</label>
            <input type="date" name="dataI" id="dataI" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Data final</label>
            <input type="date" name="dataF" id="dataF" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Gestor</label>
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

    <hr class="hr-divide-light">

    <div id="resposta"></div>

    <div class="row">
        <div class="col-sm">
            <h4 class="high-text">Últimos 100 registros</h4>
        </div>
    </div>

    <?php
    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Nada foi alterado ainda.</h4>
            </div>
         </div>
        <?php
        } else { 
    ?>
    <small class="text">Atenção: apenas gestores administrativos têm acesso à esta página. Se forem necessárias maiores informações sobre uma alteração específica, por gentileza, entre em contato com o <a href="../suporte/" target="blank_">suporte</a></small>
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
 <?php } ?>
</div>
</body>
</html>