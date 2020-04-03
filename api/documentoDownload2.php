<?php

    require_once '../classes/class_documento.php';
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_queryHelper.php';

    require_once '../src/meta.php';

    $conexaoP = new ConexaoPadrao();
        $connP = $conexaoP->conecta();
    $helperP = new QueryHelper($connP);

    $usu_id = $_REQUEST['usu_id'];
    $doc_id = $_REQUEST['doc_id'];
    $senha = $_REQUEST['senha'];
    $database = $_REQUEST['database'];

    $select = "SELECT usu_senha as senha FROM tbl_usuario WHERE usu_id = $usu_id";
    $fetch = $helperP->select($select, 2);
    $hash = $fetch['senha'];

    if(!password_verify($senha, $hash)) {
        echo 'A senha não confere';
        die();
    }

    $doc = new Documento();
    $doc->setID($doc_id);
    $doc = $doc->retornarDocumento($database);

?>
<html>
<head>
	<title>Baixar documento - Staffast App</title>
</head>
<body style="margin-top: 0em;">
<div class="container" style="text-align: center;">
	<div class="row">
        <div class="col-sm" style="text-align: center;">
            <img src="../img/logo_staffast.png" width="300">
        </div>
    </div>
	<div class="row" style="margin-top: 0.8em;">
		<div class="col-sm" >
			<h2 class="high-text">Documento: <?php echo $doc->getTitulo(); ?></h2>
		</div>
	</div>
	
	<hr class="hr-divide-super-light">

		<div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="../empresa/documentos/<?php echo $doc->getCaminhoArquivo(); ?>"><button class="button button1">Baixar documento</button></a>
            </div>

            <div class="col-sm">
                <script src="https://apis.google.com/js/platform.js" async defer></script>
                    <div class="g-savetodrive"
                    data-src="../empresa/documentos/<?php echo $doc->getCaminhoArquivo(); ?>"
                    data-filename="<?php echo $doc->getTitulo(); ?>"
                    data-sitename="Staffast App">
                    </div>
            </div>
		</div>
		
</div>
</html>