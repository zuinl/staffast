<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_documento.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] == "GESTOR-1") {
        $select = "SELECT doc_id as id FROM tbl_documento ORDER BY doc_data_upload DESC";
    } else {
        $select = "SELECT t2.doc_id as id FROM tbl_documento_dono t1 INNER JOIN tbl_documento t2 
        ON t2.doc_id = t1.doc_id WHERE t1.cpf = '$cpf' ORDER BY t2.doc_data_upload DESC";
    }

    $query_doc = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Documentos</title>
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
            <li class="breadcrumb-item active" aria-current="page">Documentos</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-sm-1">
            <img src="img/file.png" width="60">
        </div>
        <div class="col-sm-7">
            <h2 class="high-text">Central de <i><span class="destaque-text">documentos</span></i> de <?php echo $_SESSION['empresa']['nome']; ?></h2>
        </div>
        <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
        <div class="col-sm-2">
            <a href="novoDocumento.php"><button class="button button1">Novo documento</button></a>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">

</div>
<div class="container">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h3 class="text">Documentos de <?php echo $_SESSION['user']['primeiro_nome']; ?></h3>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <p class="text"><b>IMPORTANTE: </b>apenas você e os gestores administrativos (RH) da sua empresa possuem acesso aos documentos direcionados a você. 
            Holerites sempre são direcionados apenas a você. O Staffast recomenda que você envie seus documentos para a nuvem (usando o botão do Google Drive), pois 
            se um dia vier a perder acesso ao Staffast, você não terá mais acesso a eles.</p>
        </div>
    </div>

    <?php
    if(mysqli_num_rows($query_doc) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/file.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem documentos por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>

        <table class="table-site">
                <tr>
                    <th>Nº</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Data de envio</th>
                    <th>Baixar</th>
                </tr>

        <?php while ($f = mysqli_fetch_assoc($query_doc)) { 
            $doc = new Documento();
            $doc->setID($f['id']);
            $doc = $doc->retornarDocumento($_SESSION['empresa']['database']);
        ?>
            <tr>
                <td>
                    <p class="text"><?php echo $doc->getID(); ?></p>
                </td>
                <td>
                    <p class="text"><b><?php echo $doc->getTitulo(); ?></b></p>
                </td>
                <td>
                    <p class="text"><?php echo $doc->getTipo(); ?></p>
                </td>
                <td>
                    <p class="text"><?php echo $doc->getDataUpload(); ?></p>
                </td>
                <td>
                    <a href="documentos/download.php?arquivo=<?php echo $doc->getID(); ?>" target="blank_"><button class="button button2">Fazer download</button></a>
                    <script src="https://apis.google.com/js/platform.js" async defer></script>
                    <div class="g-savetodrive"
                    data-src="documentos/<?php echo $doc->getCaminhoArquivo(); ?>"
                    data-filename="<?php echo $doc->getCaminhoArquivo(); ?>"
                    data-sitename="Staffast">
                    </div>
                </td>
            </tr>
        <?php } ?>
        </table>
    <?php } ?>
    
</div>
</body>
</html>