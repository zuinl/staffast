<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_setor.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $setor = new Setor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ranking</title>
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
        function getRanking() {
            var ranking = document.getElementById("div-ranking");
            var setor = document.getElementById("setor").value;
            var periodo = document.getElementById("periodo").value;

            var xmlreq = CriaRequest();
            ranking.innerHTML = '<div class="row"><div class="col-sm"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div></div>';
            xmlreq.open("GET", "ajax/getRanking.php?setor=" + setor + "&periodo=" + periodo, true);
            xmlreq.onreadystatechange = function(){
                if (xmlreq.readyState == 4) {
                    if (xmlreq.status == 200) {
                        ranking.innerHTML = xmlreq.responseText;
                    }
                    else{
                        ranking.innerHTML = "Erro: " + xmlreq.statusText;
                    }
                }
            };
            xmlreq.send(null);
        }

        function info() {
            alert('O Ranking de Colaboradores calcula dinamicamente (ou seja, em tempo real), as médias das avaliações feitas para os colaboradores pelos gestores. \nCom estas informações, o ranking é montado! Você pode selecionar setores e períodos para filtrar o ranking também. \nATENÇÃO \n\n 1. Apenas colaboradores que estejam inseridos em algum setor aparecem no Ranking \n 2. O Ranking contabiliza apenas as avaliações já liberadas');
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
            <li class="breadcrumb-item active" aria-current="page">Ranking de colaboradores</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center">
        <div class="col-sm">
            <h3 class="high-text"><i>Ranking</i> de colaboradores</h3>
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

    <div class="row">
        <div class="col-sm">
            <label class="text">Selecione um setor</label>
            <select name="setor" id="setor" class="all-input">
                <option value="Todos" selected>Todos</option>
                <?php $setor->popularSelect($_SESSION['empresa']['database'], $_SESSION['user']['permissao'], $_SESSION['user']['cpf']); ?>
            </select>
        </div>
        <div class="col-sm">
            <label class="text">No período de...</label>
            <select name="periodo" id="periodo" class="all-input" required>
                <option value="all" selected>Desde sempre</option>
                <option value="semana">Semana - 7 dias</option>
                <option value="quinzena">Quinzena - 15 dias</option>
                <option value="curto">Curto prazo - 30 dias</option>
                <option value="medio">Médio prazo - 90 dias</option>
                <option value="curto-medio">Curto médio prazo - 180 dias</option>
                <option value="longo">Longo prazo - 365 dias</option>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="button" class="button button1" value="Ver ranking" onClick="getRanking();">
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="button" class="button button3" value="Como funciona?" onClick="info();">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div id="div-ranking" style="text-align: center;">
        
    </div> 

</html>
<script>
    getRanking();
</script>