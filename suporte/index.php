<?php
    session_start();
    include('../src/meta.php');
    include('../classes/class_empresa.php');
    include('../classes/class_email.php');
    include('../classes/class_conexao_padrao.php');
    include('../classes/class_queryHelper.php');

    if($_POST) {

        //ADICIONAR À UMA TABELA DE SUPORTE
        $conexao = new ConexaoPadrao();
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $assunto = addslashes($_POST['assunto']);
        $telefone = addslashes($_POST['telefone']);
        $email = addslashes($_POST['email']);
        $mensagem = addslashes($_POST['mensagem']);
        $emp = $_POST['empresa'];

        $insert = "INSERT INTO tbl_suporte (sup_assunto, sup_telefone, sup_email, sup_mensagem, 
        emp_id) VALUES ('$assunto', '$telefone', '$email', '$mensagem', '$emp')";

        $helper->insert($insert);

        $e_mail = new Email();
        $e_mail->setEmailFrom('suporte@sistemastaffast.com');
        $e_mail->setEmailTo("suporte@sistemastaffast.com");
        $e_mail->setAssunto("Suporte - Novo chamado no Staffast");
        $msg = '<h1>Novo chamado sobre '.$_POST['assunto'].'</h1>
            <h2>E-mail: '.$_POST['email'].'</h2>
            <h2>Telefone: '.$_POST['telefone'].'</h2>
            <h2>Empresa: '.$_POST['empresa'].'</h2>
            <h2>Mensagem: '.$_POST['mensagem'].'</h2>
        ';
        $e_mail->setMensagem($msg);
        if($e_mail->enviar()) {
            $_SESSION['msg'] = "E-mail enviado ao suporte com sucesso. Em breve retornaremos seu contato.";
        } else {
            $_SESSION['msg'] = "Houve uma falha ao enviar o e-mail. Tente novamente e se persistir, entre em contato por telefone.";
        }
    }

    $assuntos = array(
        "Cadastro de Gestor",
        "Cadastro de Colaborador",
        "Cadastro de Setor",
        "Cadastro de Processe Seletivo",
        "Cadastro de Código de Avaliação de Gestão",
        "Visualização de avaliações e/ou autoavaliações",
        "Autoavaliações",
        "Competências cadastradas",
        "Avaliação de colaborador",
        "Trazer minha empresa",
        "Erros no Staffast",
        "Sugestões",
        "Financeiro",
        "Outro"
    );

    $empresa = new Empresa();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Suporte do Staffast</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>  
    <script type="text/javascript">
        $('#telefone').mask('(00) 00000-0000');
    </script>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="../img/logo_staffast.png" width="200">
        </div>
    </div>
        <div class="col-sm">
            <h1 class="high-text">Vem cá, a gente te ajuda</h1>
        </div>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div class="container" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <form action="index.php" method="POST">
            <select class="all-input" name="assunto" id="assunto" required>
                <option value="0">-- Selecione assunto --</option>
                <?php
                if(isset($_GET['trazer_empresa'])) {
                    echo '<option value="Trazer minha empresa" selected><b>Trazer minha empresa pro Staffast</b></option>';
                }
                if(isset($_GET['sugestao'])) {
                    echo '<option value="Sugestões" selected><b>Sugestões de novas funcionalidades</b></option>';
                }
                foreach($assuntos as $a) {
                    echo '<option value="'.$a.'">'.$a.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-sm">
            <input type="email" class="all-input" name="email" id="email" placeholder="E-mail para retorno" required>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <input type="text" class="all-input" name="telefone" id="telefone" placeholder="Telefone para contato (pode ser WhatsApp)" required maxlength="15">
        </div>
        <div class="col-sm">
            <select class="all-input" name="empresa" id="empresa" required>
                <option value="0">-- Selecione sua empresa --</option>
                <option value="Nenhuma">Ainda não faço parte do Staffast</option>
                <?php echo $empresa->popularSelect(); ?>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <textarea name="mensagem" id="mensagem" class="all-input" placeholder="Agora descreva o que você precisa!" required></textarea>
        </div>
    </div>
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <input type="submit" class="button button1" value="Enviar">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <h6 class="text">Se preferir você pode enviar um e-mail diretamente para <a href="mailto:suporte@sistemastaffast.com">suporte@sistemastaffast.com</a></h6>
        </div>
    </div>
    