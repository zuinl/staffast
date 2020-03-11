<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_okr.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_key_result.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $id = $_GET['id'];

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        $cpf = $_SESSION['user']['cpf'];
        $select = "SELECT okr_id FROM tbl_okr_gestor WHERE ges_cpf = '$cpf' AND okr_id = '$id'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) {
            $select = "SELECT okr_id FROM tbl_okr_colaborador WHERE col_cpf = '$cpf' AND okr_id = '$id'";
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) {
                include('../include/acessoNegado.php');
                die();
            }
        }
    }

    $okr = new OKR();
    $okr->setID($id);
    $okr = $okr->retornarOKR($_SESSION['empresa']['database']);

    switch($okr->getVisivel()) {
        case 1: break; //Para todos
        case 2: //Apenas o criador
            if($_SESSION['user']['cpf'] != $okr->getCpfGestor()) {
                include('../include/acessoNegado.php'); 
                die();
            }
            break;
        case 3: //Apenas os gestores
            if($_SESSION['user']['permissao'] == "COLABORADOR") { 
                include('../include/acessoNegado.php'); 
                die();
            }
            break;
    }
    
    $gestor = new Gestor();
    $gestor->setCpf($okr->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $select = "SELECT krs_id as id FROM tbl_key_result WHERE okr_id = ".$okr->getID()." ORDER BY krs_id ASC";
    $query_krs = $helper->select($select, 1);
    $num_krs = mysqli_num_rows($query_krs);

    $colaborador = new Colaborador();
    $setor = new Setor();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $okr->getTitulo(); ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        function editarKRS(id, titulo, goal, current, tipo) {
            $('#titulo_krs').val(titulo);
            $('#krs_edit_id').val(id);
            $('#krs_excluir_id').val(id);
            $('#goal_krs').val(goal);
            $('#current_krs').val(current);
            if(tipo == 'Padrão') $('#tipo_krs_number').prop("checked", true);
            else $('#tipo_krs_money').prop("checked", true);
        }

        function addAnotacao(id) {
            $('#krs_id_anotacao').val(id);
        }
    </script>
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

        function editKRS() {
            var titulo = document.getElementById("titulo_krs").value;
            var goal = document.getElementById("goal_krs").value;
            var current = document.getElementById("current_krs").value;

            var tipo_number = document.getElementById("tipo_krs_number");
            var tipo;
            if(tipo_number.checked == true) tipo = 'Meta numérica';
            else tipo = 'Orçamento';

            var id = document.getElementById("krs_edit_id").value;

            var resposta = document.getElementById("resposta_edit_krs");

            if(titulo == "") {
                alert("Insira o título da Key Result");
                return;
            }

            if(goal == "") {
                alert("Insira o objetivo da Key Result");
                return;
            }

            var xmlreq = CriaRequest();
            resposta.innerHTML = '<div class="col-sm"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>';
            xmlreq.open("GET", "ajax/atualizarKRS.php?id=" + id + "&titulo=" + titulo + "&tipo=" + tipo + "&goal=" + goal + "&current=" + current, true);
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
            <li class="breadcrumb-item active" aria-current="page">Meta OKR - <?php echo $okr->getTitulo(); ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="high-text"><?php echo $okr->getTitulo(); ?></h4>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Categoria: <?php echo $okr->getTipo(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Descrição: <?php echo $okr->getDescricao(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Prazo: <?php echo $okr->getPrazo(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Criada em <?php echo $okr->getDataCriacao(); ?> por <?php echo $gestor->getNomeCompleto(); ?></h6>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal"  <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?> value="Ver/Gerenciar participantes" <?php } else { ?> value="Ver participantes" <?php } ?>>       
        </div>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?>
        <div class="col-sm">
            <a href="novaOKR.php?editar=true&id=<?php echo $okr->getID(); ?>"><input type="button" class="button button1" value="Editar meta"></a>
        </div>
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal-new-krs" value="Adicionar uma Key Result">       
        </div>
        <?php if($okr->getArquivada() == 0) { ?>
        <div class="col-sm">
            <a href="../database/okr.php?arquivar=true&id=<?php echo $okr->getID(); ?>"><input type="button" class="button button1" value="Arquivar meta"></a>
        </div>
        <?php } else { ?>
        <div class="col-sm">
            <a href="../database/okr.php?desarquivar=true&id=<?php echo $okr->getID(); ?>"><input type="button" class="button button1" value="Desarquivar meta"></a>
        </div>
        <?php } ?>
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


    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Reuniões que contêm esta meta na pauta</h3>

            <hr class="hr-divide-super-light">

            <?php
                $select = "SELECT DISTINCT t1.reu_id as id, t2.reu_pauta as pauta, 
                CONCAT(DATE_FORMAT(t2.reu_data, '%d/%m/%Y'), ' às ', t2.reu_hora) as data,
                t2.reu_concluida as concluida
                FROM tbl_reuniao_okr t1 INNER JOIN tbl_reuniao t2 ON t2.reu_id = t1.reu_id WHERE t1.okr_id = '$id' 
                ORDER BY t2.reu_data DESC";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    ?>
                    <span class="text">Nenhuma.
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <span class="text"><a href="verReuniao.php?id=<?php echo $f['id'] ?>">
                        <?php echo $f['pauta'] ?></a> - <?php echo $f['data'] ?> (<?= $f['concluida'] == 1 ? "Concluída" : "Pendente" ?>)<br>
                        <?php   
                    }
                }
            ?>
        </div>
    </div>

    <hr class="hr-divide">


    <?php
    $contador = 0;
    while($f = mysqli_fetch_assoc($query_krs)) {
        $contador++;
        $krs = new KeyResult();
        $krs->setID($f['id']);
        $krs = $krs->retornarKeyResult($_SESSION['empresa']['database']);
        $id_grafico = 'grafico_krs_'.$krs->getID();

    

        if($okr->getTipo() == 'Orçamento') { 
            $descricao = 'Valor R$';
            $meta = 'Meta (R$)';
            $andamento = 'Andamento (R$)';
        }
        else if ($okr->getTipo() == 'Meta numérica') { 
            $descricao = 'Número atual';
            $meta = 'Meta';
            $andamento = 'Andamento';
        }
        else { 
            $descricao = 'Valor';
            $meta = 'Meta';
            $andamento = 'Andamento';
        }

        if($krs->getGoal() < $krs->getCurrent()) {
            $maximo =  $krs->getCurrent();
        } else {
            $maximo = $krs->getGoal();
        }
        ?>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Metas", "<?php echo $descricao; ?>", { role: "style" } ],
                ['<?php echo $meta; ?>', <?php echo $krs->getGoal(); ?>, "#42adf5"],
                ['<?php echo $andamento; ?>', <?php echo $krs->getCurrent(); ?>, "orange"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                                sourceColumn: 1,
                                type: "string",
                                role: "annotation" },
                            2]);

            var options = {
                title: "<?php echo $krs->getTitulo(); ?>",
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
                vAxis: {
                    viewWindow: {
                        min: 0,
                        max: <?php echo (int)$maximo; ?>
                    },
                }
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('<?php echo $id_grafico; ?>'));
            chart.draw(view, options);
        }
        </script>

        <div class="row">
            <div class="col-sm">
                <h6 class="text"><b>Key result <?php echo $contador; ?></b>: <span class="text"><?php echo $krs->getTitulo(); ?></span></h6>
            </div>
            <div class="col-sm">
                <h6 class="text"><b>Progresso:</b> <span style="font-size: 1em;"><?php echo $krs->porcentagem(); ?></span></h6>
            </div>
            <div class="col-sm">
                <h6 class="text"><b>Última atualização:</b> <?php echo $krs->getUltimaAtualizacao(); ?></h6>
            </div>
        </div>
        <?php if($_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="atualizarKRS.php?id=<?php echo $krs->getID(); ?>&okr=<?php echo $okr->getID(); ?>"><button class="button button1">Atualizar andamento da Key Result</button></a>
            </div>
            <div class="col-sm">
                <input type="button" class="button button3" data-toggle="modal" data-target="#modal-krs" value="Editar Key Result" onclick="editarKRS('<?php echo $krs->getID(); ?>', '<?php echo $krs->getTitulo(); ?>', '<?php echo $krs->getGoal(); ?>', '<?php echo $krs->getCurrent(); ?>', '<?php echo $krs->getTipo(); ?>');">       
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm" style="margin-left: 20%">
                <div id="<?php echo $id_grafico; ?>" style="width: 100%; height: 410px;"></div>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h5 class="text"><b>Anotações sobre <?php echo $krs->getTitulo(); ?></b></h5>
                <input style="font-size: 0.6em;" type="button" class="button button3" data-toggle="modal" data-target="#modal-anotacao-krs" value="Adicionar anotação" onclick="addAnotacao('<?php echo $krs->getID(); ?>');">       
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-sm">

                <?php
                $select = "SELECT 
                            DATE_FORMAT(t1.data, '%d/%m/%Y') as data, 
                            t1.anotacao,
                            CASE 
                                WHEN t1.ges_cpf IS NOT NULL THEN t2.ges_nome_completo
                                ELSE t3.col_nome_completo
                            END as nome 
                            FROM tbl_krs_anotacao t1
                            LEFT JOIN tbl_gestor t2
                                ON t2.ges_cpf = t1.ges_cpf
                            LEFT JOIN tbl_colaborador t3
                                ON t3.col_cpf = t1.col_cpf
                WHERE t1.krs_id = ".$krs->getID()." ORDER BY t1.data DESC";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    ?>
                    <p class="text">Sem anotações</p>
                    <?php
                } else {
                    while($f = mysqli_fetch_assoc($query)) {
                        ?>
                        <p class="text"><b><?php echo $f['data']; ?> - <?php echo $f['nome']; ?></b>: <?php echo $f['anotacao']; ?></p>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <hr class="hr-divide-light">
        <?php
    }

    ?>
    
</div>
</body>

<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Participantes desta meta</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm">
                    <h4 class="text">Gestores</h4>
                </div>
            </div>
            <?php
                $select = "SELECT DISTINCT t1.ges_cpf as cpf, t2.ges_nome_completo as nome 
                FROM tbl_okr_gestor t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
                WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.ges_nome_completo ASC";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        echo '<p class="text">'.$f['nome'].'</p>';
                    }
                }
            ?>

            <hr class="hr-divide-super-light">

            <div class="row">
                <div class="col-sm">
                    <h4 class="text">Colaboradores</h4>
                </div>
            </div>
            <?php
                $select = "SELECT DISTINCT t1.col_cpf as cpf, t2.col_nome_completo as nome 
                FROM tbl_okr_colaborador t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf 
                WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.col_nome_completo ASC";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        echo '<p class="text">'.$f['nome'].'</p>';
                    }
                }
            ?>

            <hr class="hr-divide-super-light">

            <div class="row">
                <div class="col-sm">
                    <h4 class="text">Setores</h4>
                </div>
            </div>
            <?php
                $select = "SELECT DISTINCT t1.set_id as id, t2.set_nome as nome 
                FROM tbl_okr_setor t1 INNER JOIN tbl_setor t2 ON t2.set_id = t1.set_id 
                WHERE t1.okr_id = ".$okr->getID()." ORDER BY t2.set_nome ASC";
                $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) echo '<p class="text">Nenhum.</p>';
                else {
                    while($f = mysqli_fetch_assoc($query)) {
                        echo '<p class="text">'.$f['nome'].'</p>';
                    }
                }
            ?>

            <hr class="hr-divide-super-light">
            
            <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?>
            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Adicionar colaboradores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?addColaboradores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $colaborador->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Adicionar" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Adicionar gestores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?addGestores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $gestor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Adicionar" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Adicionar setores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?addSetores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $setor->popularSelectMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Adicionar" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Remover gestores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?rmvGestores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $okr->popularSelectGestoresMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Remover" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Remover colaboradores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?rmvColaboradores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $okr->popularSelectColaboradoresMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Remover" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>

            <hr class="hr-divide-super-light">

            <div class="row" style="margin-top: 1.5em;">
                <div class="col-sm">
                    <h4 class="text">Remover setores</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form action="../database/okr.php?rmvSetores=true" method="POST">
                    <div style="height:8em;; overflow:auto;">
                        <?php $okr->popularSelectSetoresMultiple($_SESSION['empresa']['database']); ?>
                    </div>
                    <input type="submit" value="Remover" class="button button3">
                    <input type="hidden" name="id" value="<?php echo $okr->getID(); ?>">
                    </form>
                </div>
            </div>
            <?php } ?>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>


<?php if($_SESSION['user']['cpf'] == $okr->getCpfGestor()) { ?>
<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-krs" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Editar Key Result</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text"><i>Key Result</i></label>
                    <input type="titulo_krs" id="titulo_krs" class="all-input" value="">
                </div>
            </div>

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text">Tipo</label>
                    <br><input type="radio" name="tipo_krs" id="tipo_krs_number" value="Meta numérica"> Metá numérica
                    <input style="margin-left: 3em;" type="radio" name="tipo_krs" id="tipo_krs_money" value="Orçamento"> Orçamento
                </div>
            </div>

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text">Meta</label>
                    <input type="goal_krs" id="goal_krs" class="all-input" value="">
                </div>
            </div>

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text">Andamento</label>
                    <input type="current_krs" id="current_krs" class="all-input" value="">
                </div>
            </div>

            <div id="resposta_edit_krs" class="row" style="margin-bottom: 1em; text-align: center;">
            
            </div>

            <div class="row" style="margin-bottom: 1em; text-align: center;">
                <div class="col-sm">
                    <input type="hidden" name="krs_edit_id" id="krs_edit_id" value="">
                    <input type="button" name="atualizarKRS" id="atualizarKRS" class="button button1" onclick="editKRS();" value="Atualizar Key Result">
                </div>
                <div class="col-sm">
                    <form action="../database/okr.php?excluirKRS=true&okr_id=<?php echo $okr->getID(); ?>" method="POST">
                    <input type="hidden" name="krs_excluir_id" id="krs_excluir_id" value="">
                    <input type="submit" class="button button3" style="font-size: 0.6em;" value="Excluir Key Result">
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>


<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-new-krs" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Adicionar Key Result</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <form action="../database/okr.php?adicionarKRS=true" method="POST">
                    <label class="text"><i>Key Result</i></label>
                    <input type="text" name="titulo_new_krs" id="titulo_new_krs" class="all-input" required>
                </div>
            </div>

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text">Tipo</label>
                    <br><input type="radio" name="tipo_new_krs" value="Meta numérica" required> Metá numérica
                    <input style="margin-left: 3em;" type="radio" name="tipo_new_krs" value="Orçamento" required> Orçamento
                </div>
            </div>

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <label class="text">Meta</label>
                    <input type="text" name="goal_new_krs" id="goal_new_krs" class="all-input" required>
                </div>
            </div>            

            <div class="row" style="margin-bottom: 1em; text-align: center;">
                <div class="col-sm">
                    <input type="hidden" name="okr_id" id="okr_id" value="<?php echo $okr->getID(); ?>">
                    <input type="submit" class="button button1" value="Criar Key Result">
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>




<div class="container">
    <div class="modal" tabindex="-1" role="dialog" id="modal-anotacao-krs" data-target=".bd-example-modal-lg">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Adicionar anotação</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="row" style="margin-bottom: 1em;">
                <div class="col-sm">
                    <form action="../database/okr.php?adicionarAnotacao=true&okr_id=<?php echo $okr->getID(); ?>" method="POST">
                    <label class="text">Anotação</label>
                    <textarea name="anotacao" id="anotacao" class="all-input" required></textarea>
                </div>
            </div>          

            <div class="row" style="margin-bottom: 1em; text-align: center;">
                <div class="col-sm">
                    <input type="hidden" name="krs_id" id="krs_id_anotacao" value="">
                    <input type="submit" class="button button1" value="Adicionar anotação">
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</div>
<?php } ?>

</html>