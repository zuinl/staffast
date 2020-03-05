<?php 
session_start();
ob_start();
require_once('../classes/class_conexao_padrao.php');
require_once("../classes/class_queryHelper.php");
include('../src/meta.php');
require_once('../classes/class_email.php');

$conn = new ConexaoPadrao();
    $conn = $conn->conecta();
$helper = new QueryHelper($conn);

$email = addslashes($_POST['email']);

$select = $helper->select("SELECT t1.usu_id as id, t2.emp_razao_social as nome FROM tbl_usuario t1 INNER JOIN 
tbl_empresa t2 ON t2.emp_id = t1.emp_id WHERE t1.usu_email = '$email'", 1);

if(mysqli_num_rows($select) == 0) {
    $_SESSION['msg'] .= 'Seu e-mail não foi encontrado na base de dados do Staffast';
    header('Location: ../recuperarSenha.php');
    die();
} else {
    $row = mysqli_fetch_assoc($select);
    $empresa = $row['nome'];
    $usu_id = $row['id'];

    $newSenha = rand(100000, 999999);
    $newSenha_hash = password_hash($newSenha, PASSWORD_DEFAULT);
    $now = date('Y-m-d H:i:s');
    $helper->update("UPDATE tbl_usuario SET usu_senha = '$newSenha_hash', usu_ultima_alteracao_senha = '$now' 
    WHERE usu_id = '$usu_id'");

    $obj_email = new Email();
    $obj_email->setAssunto("Recuperação de senha do Staffast");
    $obj_email->setEmailFrom(0);
    $obj_email->setEmailTo($email);

    $txt = '<h3 class="high-text">Recuperação de acesso do Staffast</h3>
        <p class="text">Olá, '.$email. '.</p>
        <p class="text">Voce solicitou recuperação de acesso ao Staffast para a empresa '.$empresa.'</p>
		<p class="text"><b>Novos dados de acesso</b></p>
		<p class="text">E-mail: '.$email.'</p>
		<p class="text">Senha: '.$newSenha.'</p>
        <p class="text">Você deve alterar a senha no seu próximo acesso, ok?</p>
        <p class="text"><a href="https://sistemastaffast.com/staffast" target="blank_">Acessar agora</a></p>
        <h6 class="text">Por enquanto é só, até mais :)</h6>
        <small>Suporte da equipe Staffast</small>';    
    $obj_email->setMensagem($txt);
    $obj_email->enviar();

    $_SESSION['msg'] .= 'Foi enviada uma nova senha para <b>'.$email.'</b>.';
    header('Location: ../recuperarSenha.php');
}
?>