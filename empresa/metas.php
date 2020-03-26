<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_key_result.php');
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

    $metas = array();
    $i = 0;

    //OKRs definidas como visível para todos - GESTORES
    $select = "SELECT
                DISTINCT t1.okr_id as id
               FROM tbl_okr_gestor t1
               INNER JOIN tbl_okr t2 
                ON t2.okr_id = t1.okr_id AND t2.okr_arquivada = 0 AND (t2.okr_visivel = 1 OR t2.okr_visivel = 3)
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
                ON t2.okr_id = t1.okr_id AND t2.okr_arquivada = 0 AND t2.okr_visivel = 1
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
               FROM tbl_okr WHERE ges_cpf = '$cpf' AND okr_visivel = 2 AND okr_arquivada = 0"; 
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
            <li class="breadcrumb-item active" aria-current="page">Metas OKR</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/pie-chart.png" width="60">
        </div>
        <div class="col-sm-6">
            <h2 class="high-text">Metas OKR</h2>
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
        <div class="col-sm">
            <a href="novaOKR.php"><input type="button" class="button button1" value="Criar meta OKR"></a>
        </div>
        <div class="col-sm">
            <a href="metasArquivadas.php"><input type="button" class="button button1" value="Metas arquivadas"></a>
        </div>
        <?php } ?>
        <div class="col-sm">
            <input type="button" class="button button3" data-toggle="modal" data-target="#modal" value="Conheça as OKRs">       
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
                <h4 class="text">Sem metas por enquanto.</h4>
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

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Metas <i>Objective Key Result</i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Quem pode criar uma meta OKR?</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">O Staffast está configurado para permitir que Gestores Administrativos e Operacionais possam 
                criar metas OKR a qualquer momento. Os Colaboradores, porém, não conseguem criar OKRs.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Quem visualizará as metas OKR?</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">No momento da criação da meta OKR, o gestor deve selecionar para quem a mesma estará visível. Se ele, por exemplo, selecionar "Apenas eu" e atribuir diversos colaboradores à meta, estes colaboradores <b>não conseguirão</b> acessar a meta. 
                Os gestores Administrativos, no entanto, <b>conseguem acessar todas as metas OKR, independente da permissão no momento da criação</b>. A lista de metas, porém, é visível para todos na empresa.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Como atualizar o andamento de uma meta?</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Apenas o <b>gestor que criou a meta pode atualizar seu andamento</b>, clicando em "Atualizar", ao lado de cada <i>Key Result</i> na página da meta.</p>
            </div>
        </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>