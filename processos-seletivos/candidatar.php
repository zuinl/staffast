<?php

session_start();
include('../src/meta.php');
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_processo_seletivo.php");
require_once("../classes/class_gestor.php");
require_once("../classes/class_codigoPS.php");
require_once("../classes/class_empresa.php");

if(!isset($_POST)) die("Erro");

$codigo = new CodigoPS();
$codigo->setCodigo($_POST['codigo']);

if(!$codigo->isValido()) {
    echo '<div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2 class="high-text">Sentimos muito, mas o código informado é inválido</h2>
                    <a href="index.php"><button class="button button3">Tentar de novo</button></a>
                    <a href="../"><button class="button button1">Voltar</button></a>
                </div>
            </div>
         </div>';
    die();
}

$codigo = $codigo->retornarDados();

$empresa = new Empresa();
$empresa->setID($codigo->getEmpID());

$empresa = $empresa->retornarEmpresa();

$ps = new ProcessoSeletivo();
$ps->setID($codigo->getSelID());

$ps = $ps->retornarProcessoSeletivo($empresa->getDatabase());

if($ps->isEncerrado($empresa->getDatabase())) {
    echo '<h2 class="high-text">Sentimos muito, mas o processo seletivo '.$ps->getID().' 
    da empresa '.$empresa->getRazao().' se encerrou em '.$ps->getDataEncerramento().'</h2>';
    die();
}

$conexaoEmpresa = new ConexaoEmpresa($empresa->getDatabase());
$conn = $conexaoEmpresa->conecta();

$helper = new QueryHelper($conn);

$select = "SELECT per_id as id, per_titulo as titulo, per_descricao as descricao, 
per_opc_um as um, per_opc_dois as dois, per_opc_tres as tres, per_opc_quatro as quatro 
FROM tbl_pergunta_processo WHERE sel_id = ".$ps->getID()." ORDER BY per_id ASC";

$queryPerg = $helper->select($select, 1);
$num_perguntas = mysqli_num_rows($queryPerg);

$perguntas = false;
if($num_perguntas == 0) {
    $perguntas = false;
} else {
    $perguntas = true;
    $i = 1;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Candidatura</title>
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
    <div class="row">
        <div class="col-sm">
            <h1 class="high-text">Bem-vindo a <?php echo $empresa->getRazao(); ?></h1>
        </div>
    </div>
</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
            <h6 class="text"><b>Você está se candidatando a: </b><?php echo $ps->getTitulo(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Descrição: <?php echo $ps->getDescricao(); ?></h6>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h6 class="text"><b>Vagas: </b><?php echo $ps->getVagas(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Aberto em: </b><?php echo $ps->getDataCriacao(); ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text"><b>Encerra em: </b><?php echo $ps->getDataEncerramento(); ?></h6>
        </div>
    </div>

    <hr class="hr-divide">

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

    <div class="row">
        <div class="col-sm">
            <form action="db_candidatar.php" method="POST" enctype="multipart/form-data">
            <label class="text">Nome completo *</label>
            <input type="text" class="all-input" name="nome" maxlength="80" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label class="text">LinkedIn</label>
            <input type="text" class="all-input" name="linkedin" placeholder="Link para perfil, se tiver" maxlength="120">
        </div>
        <div class="col-sm">
            <label class="text">Telefone *</label>
            <input type="text" class="all-input" name="telefone" id="telefone" maxlength="14" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label class="text">Apresentação</label>
            <textarea name="apresentacao" class="all-input" maxlength="1000"></textarea>
        </div>
    </div> 
    <div class="row">
        <div class="col-sm">
            <label class="text">E-mail *</label>
            <input type="email" class="all-input" name="email" id="email" maxlength="120" required>
        </div>
        <div class="col-sm">
            <label class="text">Envie seu currículo</label>
            <input type="file" name="cv" class="button button3" required>
            <br><small class="text">Dê preferência para arquivos em PDF ou DOCX</small>
        </div>
    </div>
    <?php
    if($perguntas) {

        while($fetch = mysqli_fetch_assoc($queryPerg)) {

            $id_pergunta = 'id_perg_'.$i;
            $id_alter = 'id_alter_'.$i;
            $i++;

            $perg_id = $fetch['id'];
            $perg_titulo = $fetch['titulo'];
            $perg_descricao = $fetch['descricao'];
            $perg_opc_um = $fetch['um'];
            $perg_opc_dois = $fetch['dois'];
            $perg_opc_tres = $fetch['tres'];
            $perg_opc_quatro = $fetch['quatro'];
            ?>

            <hr class="hr-divide-super-light">

            <div class="row">
                <div class="col-sm">
                    <h4 class="text"><?php echo $perg_titulo; ?></h4>
                    <input type="hidden" name="<?php echo $id_pergunta; ?>" value="<?php echo $perg_id; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <h5 class="text"><?php echo $perg_descricao; ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <input type="radio" name="<?php echo $id_alter; ?>" value="1"> <?php echo $perg_opc_um; ?>
                </div>
                <?php if($perg_opc_dois != "") { ?>
                <div class="col-sm-6">
                    <input type="radio" name="<?php echo $id_alter; ?>" value="2"> <?php echo $perg_opc_dois; ?>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <?php if($perg_opc_tres != "") { ?>
                <div class="col-sm-6">
                    <input type="radio" name="<?php echo $id_alter; ?>" value="3"> <?php echo $perg_opc_tres; ?>
                </div>
                <?php } ?>
                <?php if($perg_opc_quatro != "") { ?>
                <div class="col-sm-6">
                    <input type="radio" name="<?php echo $id_alter; ?>" value="4"> <?php echo $perg_opc_quatro; ?>
                </div>
                <?php } ?>
            </div>

            <?php

        }

    }
    ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="hidden" name="database" value="<?php echo $empresa->getDatabase(); ?>">
            <input type="hidden" name="num_perguntas" value="<?php echo $num_perguntas; ?>">
            <input type="hidden" name="id" value="<?php echo $codigo->getSelID(); ?>">
            <input type="submit" class="button button2" value="Finalizar candidatura">
        </div>
    </div>
    </form>