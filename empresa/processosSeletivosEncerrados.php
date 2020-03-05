<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_processo_seletivo.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_codigoPS.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT sel_id as id FROM tbl_processo_seletivo WHERE sel_data_encerramento < NOW() ORDER BY sel_data_criacao DESC";

    $query = $helper->select($select, 1);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processos seletivos encerrados</title>
    <script>
        function confirmaReabertura(id_ps) {

            var confirma = confirm("Tem certeza de que quer reabrir o processo seletivo " + id_ps + " por mais 3 dias?\n O código de candidatura ficará válido novamente e pessoas poderão se candidatar. \nVocê poderá desativá-lo se quiser.");
            
            if(confirma == true) {
                window.location.href = "../database/processo_seletivo.php?reabrir=true&ps="+id_ps;
            }
            
        }
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1">
            <img src="img/interview.png" width="60">
        </div>
        <div class="col-sm-6">
            <h2 class="high-text">Processos <span class="destaque-text">seletivos</span></h2>
        </div>
        <?php if($_SESSION['empresa']['logotipo'] != "") { ?>
        <div class="col-sm-1">
            <img src="<?php echo $_SESSION['empresa']['logotipo']; ?>" width="100">
        </div>
        <?php } ?>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <div class="col-sm-1">
            <a href="novoProcessoSeletivo.php"><button class="button button1">Criar</button></a>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">

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

    <div class="row">
        <div class="col-sm">
            <p class="text"><b>LEMBRE-SE: </b> o link para candidatura é <a href="https://sistemastaffast.com/staffast/processos-seletivos/" target="blank_">sistemastaffast.com/staffast/processos-seletivos/</a> e os candidatos precisarão dos códigos de cada processo seletivo para se candidatar.
        </div>
    </div>

    <hr class="hr-divide-light">

    <?php
    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/job-seeking.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem processos seletivos encerrados por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
        <table class="table-site">
            <tr>
                <th>Nº</th>
                <th>Situação</th>
                <th>Título e descrição</th>
                <th>Data de encerramento</th>
                <th>Criado por</th>
                <th>Vagas</th>
                <th>Código de candidatura</th>
                <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
                    <th>Candidaturas</th>
                <?php } ?>
                <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
                    <th>Reabrir</th>
                <?php } ?>
            </tr>
            <?php
                while($fetch = mysqli_fetch_assoc($query)) {

                    $ps = new ProcessoSeletivo();
                    $ps->setID($fetch['id']);

                    $ps = $ps->retornarProcessoSeletivo($_SESSION['empresa']['database']);

                    !$ps->isEncerrado($_SESSION['empresa']['database']) ? $status = "Aberto" : $status = "Encerrado";
                    
                    $status == "Encerrado" ? $btn_value = "Já encerrado" : $btn_value = "Encerrar agora";

                    $codigo = new CodigoPS();
                    $codigo->setSelID($ps->getID());
                    $codigo->setEmpID($_SESSION['empresa']['emp_id']);

                    $codigo = $codigo->retornarCodigo();
                    
                    $gestor = new Gestor();
                    $gestor->setCpf($ps->getCpfGestor());

                    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);
                    
            ?>
            <tr>
                <td><?php echo $ps->getID(); ?></td>
                <td><?php echo $status; ?></td>
                <td>
                    <?php echo $ps->getTitulo(); ?>
                    <small class="text"><?php echo $ps->getDescricao(); ?></small>
                </td>
                <td><?php echo $ps->getDataEncerramento(); ?></td>
                <td><a href="perfilGestor.php?id=<?php echo base64_encode($gestor->getCpf()); ?>" target="blank_"><?php echo $gestor->getNomeCompleto(); ?></a></td>
                <td><?php echo $ps->getVagas(); ?></td>
                <td><?php echo $codigo; ?></td>
                <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
                <td><a href="candidaturas.php?ps=<?php echo $ps->getID(); ?>"><button class="button button2">Ver</button></a></td>
                <?php } ?>
                <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
                <td><button class="button button3" onclick="confirmaReabertura(<?php echo $ps->getID(); ?>);">Reabrir por 3 dias</button></td>
                <?php } ?>  
        <?php } ?>   
        </table>
    <?php } ?>
</div>
</body>
</html>