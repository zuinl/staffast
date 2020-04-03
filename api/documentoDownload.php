<?php

    require_once '../classes/class_documento.php';
    require_once '../classes/class_ponto.php';
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_conexao_empresa.php';
    require_once '../classes/class_queryHelper.php';
    
    require_once '../src/meta.php';

    $doc_id = base64_decode($_REQUEST['id']);
    $token = base64_decode($_REQUEST['token']);
    $data = base64_decode($_REQUEST['data']);

    //Checando se o link é de hoje
    if($data != date('Y-m-d')) {
        echo '1- Desculpe, mas, por medidas de segurança, este link não é mais válido.';
        die();
    }

    $conexaoP = new ConexaoPadrao();
        $connP = $conexaoP->conecta();
    $helperP = new QueryHelper($connP);

    //Checando o token
    $select = "SELECT 
                t1.usu_id as id, 
                t2.emp_id as emp_id,
                t2.usu_email as email 
               FROM tbl_usuario_token t1 
                INNER JOIN tbl_usuario t2 
                    ON t2.usu_id = t1.usu_id 
               WHERE t1.token = '$token'";
    $query = $helperP->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        echo '2- Desculpe, mas houve um problema na autenticação do usuário.';
        die();
    }

    $f = mysqli_fetch_assoc($query);
    $emp_id = $f['emp_id'];
    $email = $f['email'];
    $usu_id = $f['id'];

    $select = "SELECT emp_database as db FROM tbl_empresa WHERE emp_id = $emp_id";
    $fetch = $helperP->select($select, 2);
    $database = $fetch['db'];

    $conexaoE = new ConexaoEmpresa($database);
        $connE = $conexaoE->conecta();
    $helperE = new QueryHelper($connE);

    $ponto = new Ponto();
    $funcionario = $ponto->identificarFuncionario($email);

    $cpf = $funcionario['cpf'];

    //Checando se o documento consta para aquele funcionário
    $select = "SELECT doc_id as id FROM tbl_documento_dono WHERE doc_id = $doc_id AND cpf = '$cpf'";
    $query = $helperE->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        echo 'Desculpe, mas você não tem permissão de acessar este documento';
        die();
    }

    $doc = new Documento();
    $doc->setID($doc_id);
    $doc = $doc->retornarDocumento($database);

?>
<!DOCTYPE html>
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

		<div class="row">
            <div class="col-sm">
            <form action="documentoDownload2.php" method="POST">
                <label class="text" for="senha">Insira sua senha do Staffast</label>
                <input type="password" name="senha" class="all-input" required>
            </div>
		</div>
		<div class="row">
            <div class="col-sm">
                <input type="hidden" name="usu_id" value="<?php echo $usu_id; ?>">
                <input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>"> 
                <input type="hidden" name="database" value="<?php echo $database; ?>"> 
                <input type="submit" value="Acessar documento" class="button button1">
            </div>
            </form>
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
    </div>
</html>