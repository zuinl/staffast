<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_key_result.php');
    require_once('../classes/class_gestor.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    $metas = array();
    $i = 0;

    //OKRs definidas como visível para todos - GESTORES
    $select = "SELECT
                DISTINCT t1.okr_id as id
               FROM tbl_okr_gestor t1
               INNER JOIN tbl_okr t2 
                ON t2.okr_id = t1.okr_id AND t2.okr_arquivada = 1 AND (t2.okr_visivel = 1 OR t2.okr_visivel = 3)
               WHERE t1.ges_cpf = '$cpf'"; 
    $query = $helper->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
        $metas[$i] = $f['id'];
        $i++;
    }

     //OKRs definidas como visível para todos - COLABORADORES
     $select = "SELECT
                DISTINCT t1.okr_id as id
                FROM tbl_okr_colaborador t1
                INNER JOIN tbl_okr t2 
                ON t2.okr_id = t1.okr_id AND t2.okr_arquivada = 1 AND t2.okr_visivel = 1
                WHERE t1.col_cpf = '$cpf'"; 
    $query = $helper->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
        $existe = false;
        for($b = 0; $b < sizeof($metas); $b++) {
            if($f['id'] == $metas[$b]) $existe = true;
        }
        if(!$existe) {
            $metas[$i] = $f['id'];
            $i++;
        }
    }

    //OKRs definidas como visível para apenas o criador
    $select = "SELECT
                DISTINCT okr_id as id
               FROM tbl_okr WHERE ges_cpf = '$cpf' AND okr_visivel = 2 AND okr_arquivada = 1"; 
    $query = $helper->select($select, 1);

    while($f = mysqli_fetch_assoc($query)) {
        $existe = false;
        for($b = 0; $b < sizeof($metas); $b++) {
            if($f['id'] == $metas[$b]) $existe = true;
        }
        if(!$existe) {
            $metas[$i] = $f['id'];
            $i++;
        }
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Metas OKR</title>
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
            var prazo = document.getElementById("prazo").value;
            var gestor = document.getElementById("gestor").value;
            var resposta = document.getElementById("resposta");

            if(prazo == "" && gestor == "") {
                alert("Insira ao menos um filtro");
                return;
            }

            var xmlreq = CriaRequest();
            resposta.innerHTML = '<h5 class="text">Buscando dados...</h5>';
            xmlreq.open("GET", "ajax/metas.php?prazo=" + prazo + "&gestor=" + gestor, true);
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
            <li class="breadcrumb-item"><a href="metas.php">Metas OKR</a></li>
            <li class="breadcrumb-item active" aria-current="page">Metas OKR - Arquivadas</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/pie-chart.png" width="60">
        </div>
        <div class="col-sm-6">
            <h2 class="high-text">Metas OKR arquivadas</h2>
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
            <label class="text">Metas com prazo de até...</label>
            <input type="date" name="prazo" id="prazo" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Gestor responsável</label>
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
    if(sizeof($metas) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/goal.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem metas arquivadas por enquanto.</h4>
            </div>
         </div>
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
 <?php } ?>
</div>
</body>

</html>