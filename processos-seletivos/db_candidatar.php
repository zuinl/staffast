<?php
session_start();
include("../src/meta.php");
require_once("../classes/class_candidato.php");
require_once("../classes/class_resposta.php");
require_once("../classes/class_email.php");


if(!isset($_POST)) die("Erro");

$nome = addslashes($_POST['nome']);
$linkedin = addslashes($_POST['linkedin']);
$email = addslashes($_POST['email']);
$apresentacao = addslashes($_POST['apresentacao']);
$telefone = $_POST['telefone'];

$candidato = new Candidato();
$candidato->setNome($nome);
$candidato->setLinkedin($linkedin);
$candidato->setEmail($email);
$candidato->setTelefone($telefone);
$candidato->setApresentacao($apresentacao);
$candidato->setIDSel($_POST['id']);
$candidato->cadastrar($_POST['database']);

$can_id = $candidato->retornarUltimo($_POST['database']);

$curriculo = $_FILES['cv'];


if($_FILES["cv"]["error"] == 0){
    $arqNome = "";
    $nome_dir = "curriculos/can_id_".$can_id."/";
    $cv = $_FILES['cv'];
    $diretorio = $nome_dir;

        if(!file_exists($diretorio)){
            mkdir($diretorio);
        }
    $arqNome = $diretorio.$cv['name'];
    move_uploaded_file($cv['tmp_name'], $arqNome);

    $candidato->setID($can_id);
    $candidato->setCurriculo($arqNome);

    $candidato->uploadCurriculo($_POST['database']);

    $e_mail = new Email();
    $e_mail->setAssunto("Candidatura confirmada");
    $e_mail->setEmailFrom(0);
    $e_mail->setEmailTo($email);
    $msg = '<h1 class="high-text">Sua candidatura foi confirmada</h1>
        <h2 class="high-text">Você se candidatou a um processo seletivo através do Staffast</h2>
        <h3 class="text">'.$nome.', nós estamos apenas passando para dizer que está tudo certo com a 
        sua candidatura. Aguarde o contato da empresa, ok? Boa sorte!</h3>
        <h2 class="destaque-text">Por agora é só :D</h2>
        <h5 class="text">Equipe do Staffast</h5>';
    $e_mail->setMensagem($msg);
}



if(isset($_POST['id_perg_1'])) {

    for($i = 1; $i <= $_POST['num_perguntas']; $i++) {

        $id_pergunta = 'id_perg_'.$i;
        $id_alter = 'id_alter_'.$i;

        $resposta = new Resposta();
        $resposta->setPerID($_POST[$id_pergunta]);
        $resposta->setCanID($candidato->retornarUltimo($_POST['database']));

        if($_POST[$id_alter] == "1") {
            $resposta->setOpcUm(1);
            $resposta->setOpcDois(0);
            $resposta->setOpcTres(0);
            $resposta->setOpcQuatro(0);
        } else if ($_POST[$id_alter] == "2") {
            $resposta->setOpcUm(0);
            $resposta->setOpcDois(1);
            $resposta->setOpcTres(0);
            $resposta->setOpcQuatro(0);
        } else if ($_POST[$id_alter] == "3") {
            $resposta->setOpcUm(0);
            $resposta->setOpcDois(0);
            $resposta->setOpcTres(1);
            $resposta->setOpcQuatro(0);
        } else if ($_POST[$id_alter] == "4") {
            $resposta->setOpcUm(0);
            $resposta->setOpcDois(0);
            $resposta->setOpcTres(0);
            $resposta->setOpcQuatro(1);
        } else {
            $resposta->setOpcUm(0);
            $resposta->setOpcDois(0);
            $resposta->setOpcTres(0);
            $resposta->setOpcQuatro(0);
        }

        $resposta->cadastrar($_POST['database']);

    }

}

?>
<script>
    var conf = confirm("Sua candidatura foi concluida com sucesso!");
    if(conf == true || conf == false) {
        window.location.href = "index.php";
    }
</script>