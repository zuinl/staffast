<?php

    include('../../include/auth.php');
    require_once('../../classes/class_documento.php');
    require_once('../../classes/class_conexao_empresa.php');
    require_once('../../classes/class_queryHelper.php');

    if(!isset($_GET['arquivo'])) {
        include('../../include/acessoNegado.php');
        die();
    }

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $id = $_GET['arquivo'];
        $cpf = $_SESSION['user']['cpf'];
        $select = "SELECT doc_id FROM tbl_documento_dono WHERE doc_id = '$id' AND cpf = '$cpf'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) {
            include('../../include/acessoNegado.php');
            die();
        }
    }

    $doc = new Documento();
    $doc->setID($_GET['arquivo']);
    $doc = $doc->retornarDocumento($_SESSION['empresa']['database']);
    header('Location: '.$doc->getCaminhoArquivo());
    die();

?>