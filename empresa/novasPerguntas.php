<?php
    include('../include/auth.php');
    include('../src/meta.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    if(!isset($_POST)) die("Erro");

    if(isset($_POST['sem_perguntas']) || $_POST['perguntas'] == 0) {
       require_once("../classes/class_processo_seletivo.php");
       $ps = new ProcessoSeletivo();
       $ps->setTitulo(addslashes($_POST['titulo']));
       $ps->setDescricao(addslashes($_POST['descricao']));
       $ps->setDataEncerramento($_POST['encerramento'].' 23:59:59');
       $ps->setVagas($_POST['vagas']);
       $ps->setCpfGestor($_SESSION['user']['cpf']);

       $ps->cadastrar($_SESSION['empresa']['database'], $_SESSION['empresa']['emp_id']);

       $_SESSION['msg'] = "Processo seletivo cadastrado. Nenhuma pergunta foi atribuída.";
       var_dump($_POST);
       header('Location: processosSeletivos.php');
       die();
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novas perguntas</title>
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
            <li class="breadcrumb-item"><a href="novoProcessoSeletivo.php">Criação de processo seletivo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Novas perguntas para o processo seletivo</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Criação de novas perguntas</h2>
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
</div>
<div class="container">
<table class="table-site">
    <thead>
        <tr>
            <th>Pergunta</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Alternativa 1</th>
            <th>Alternativa 2</th>
            <th>Alternativa 3</th>
            <th>Alternativa 4</th>
        </tr>
    </thead>
    <form action="../database/processo_seletivo.php?novo=true" method="POST">
<?php
for($i = 1; $i <= $_POST['perguntas']; $i++) {
?>
    <tr>
        <td><b><?php echo $i; ?></b></td>
        <td><input name="titulo_<?php echo $i; ?>" type="text" class="all-input" placeholder="Pergunta <?php echo $i; ?>" maxlength="120"></td>
    
        <td><textarea name="descricao_<?php echo $i; ?>" class="all-input" placeholder="Descrição da pergunta <?php echo $i; ?>" maxlength="500"></textarea></td>
        
        <td>
            <input type="text" name="alter_um_<?php echo $i; ?>" class="all-input" placeholder="Alternativa 1" maxlength="80">
            <select name="compet_um_<?php echo $i; ?>" class="all-input" style="margin-top: 0.7em;">
                <option selected disabled>Competência avaliada</option>
                <option value="0">Nenhuma</option>
                <option value="<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="0<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_tres']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option> <?php } ?>
            </select>
        </td>
        <td>
            <input type="text" name="alter_dois_<?php echo $i; ?>" class="all-input" placeholder="Alternativa 2" maxlength="80">
            <select name="compet_dois_<?php echo $i; ?>" class="all-input" style="margin-top: 0.7em;">
                <option selected disabled>Competência avaliada</option>
                <option value="0">Nenhuma</option>
                <option value="<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="0<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_tres']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option> <?php } ?>
            </select>
        </td>
        <td>
            <input type="text" name="alter_tres_<?php echo $i; ?>" class="all-input" placeholder="Alternativa 3" maxlength="80">
            <select name="compet_tres_<?php echo $i; ?>" class="all-input" style="margin-top: 0.7em;">
                <option selected disabled>Competência avaliada</option>
                <option value="0">Nenhuma</option>
                <option value="<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="0<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_tres']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option> <?php } ?>
            </select>
        </td>
        <td>
            <input type="text" name="alter_quatro_<?php echo $i; ?>" class="all-input" placeholder="Alternativa 4" maxlength="80">
            <select name="compet_quatro_<?php echo $i; ?>" class="all-input" style="margin-top: 0.7em;">
                <option selected disabled>Competência avaliada</option>
                <option value="0">Nenhuma</option>
                <option value="<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="0<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_tres']; ?></option>
                <option value="<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option> <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?> <option value="<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option> <?php } ?>
            </select>
        </td>
    </tr>
<?php
}
?>
</table>
<hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-3 offset-sm-3">
            <input type="hidden" name="titulo" value="<?php echo $_POST['titulo']; ?>">
            <input type="hidden" name="descricao" value="<?php echo $_POST['descricao']; ?>">
            <input type="hidden" name="vagas" value="<?php echo $_POST['vagas']; ?>">
            <input type="hidden" name="perguntas" value="<?php echo $_POST['perguntas']; ?>">
            <input type="hidden" name="encerramento" value="<?php echo $_POST['encerramento']; ?>">
            <input type="submit" value="Cadastrar processo seletivo" class="button button2" onclick="">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1" onclick="">
        </div>
    </div>
</form>