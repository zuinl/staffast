<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_empresa.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();
    $helper = new QueryHelper($conexao);

    $empresa = new Empresa();
    $empresa->setID($_SESSION['empresa']['emp_id']); 
    $empresa = $empresa->retornarEmpresa();
    
    $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_ativo = 1";
    $query = $helper->select($select, 1);
    $colaboradores = mysqli_num_rows($query);

    $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_ativo = 1";
    $query = $helper->select($select, 1);
    $gestores = mysqli_num_rows($query);

    $select = "SELECT set_id FROM tbl_setor";
    $query = $helper->select($select, 1);
    $setores = mysqli_num_rows($query);

    $select = "SELECT sel_id FROM tbl_processo_seletivo";
    $query = $helper->select($select, 1);
    $processos = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sua empresa - Staffast</title>
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
            <li class="breadcrumb-item active" aria-current="page">Minha empresa</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

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
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text"><?php echo $empresa->getRazao(); ?></h2>
        </div>
    </div>

    <hr class="hr-divide">

</div>
<div class="container">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Estes são os dados da sua empresa no Staffast</h4>
        </div>
    </div>

    <div class="row" style="text-align: center; margin-bottom: 1em;">
        <div class="col-sm">
            <h5 class="text">Nome</h5>
            <h6 class="text"><?php echo $empresa->getRazao(); ?></h6>
        </div>
        <div class="col-sm">
            <h5 class="text">Telefone</h5>
            <h6 class="text"><?php echo $empresa->getTelefone(); ?></h6>        
        </div>
        <div class="col-sm">
            <h5 class="text">LinkedIn</h5>
            <h6 class="text"><?php echo $empresa->getLinkedin(); ?></h6>        
        </div>
    </div>
    <div class="row" style="text-align: center; margin-bottom: 1em;">
        <div class="col-sm">
            <h5 class="text">Website</h5>
            <h6 class="text"><a href="<?php echo $empresa->getWebsite(); ?>" target="blank_"><?php echo $empresa->getWebsite(); ?></a></h6>        
        </div>
        <div class="col-sm">
            <h5 class="text">Endereço</h5>
            <h6 class="text"><?php echo $empresa->getEndereco(); ?></h6>        
        </div>
        <div class="col-sm">
            <h5 class="text">Data de adesão ao Staffast</h5>
            <h6 class="text"><?php echo $empresa->getDataCadastro(); ?></h6>        
        </div>
    </div>

    <div class="row" style="text-align: center; margin-bottom: 1em;">
        <div class="col-sm">
            <h5 class="text">Responsável (pelo Staffast)</h5>
            <h6 class="text"><?php echo $empresa->getResponsavel(); ?></h6>
        </div>
        <div class="col-sm">
            <h5 class="text">E-mail do responsável</h5>
            <h6 class="text"><?php echo $empresa->getEmailResponsavel(); ?></h6>        
        </div>
    </div>
    <div class="row" style="text-align: center; margin-bottom: 1em;">
        <div class="col-sm">
            <h5 class="text">Orientações</h5>
            <h6 class="text">Qualquer solicitação de alteração, correção, atualização e/ou negociações 
            relacionadas ao Staffast só serão tratadas com o responsável cadastrado. O <a href="../suporte/" target="blank_">suporte técnico</a> 
            apenas trata de assuntos técnicos, dúvidas, sugestões e esclarecimentos.</h6>        
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="text">Gestores ativos</h5>
            <h6 class="text"><?php echo $gestores; ?></h6>
        </div>
        <div class="col-sm">
            <h5 class="text">Colaboradores ativos</h5>
            <h6 class="text"><?php echo $colaboradores; ?></h6>        
        </div>
        <div class="col-sm">
            <h5 class="text">Processos seletivos já realizados</h5>
            <h6 class="text"><?php echo $processos; ?></h6>        
        </div>
        <div class="col-sm">
            <h5 class="text">Setores ativos</h5>
            <h6 class="text"><?php echo $setores; ?></h6>        
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Competências das avaliações dos colaboradores</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">

            <ul>
                <li><b>Competência 1</b>: <?php echo $_SESSION['empresa']['compet_um']; ?> </li>
                <li><b>Competência 2</b>: <?php echo $_SESSION['empresa']['compet_dois']; ?> </li>
                <li><b>Competência 3</b>: <?php echo $_SESSION['empresa']['compet_tres']; ?> </li>
                <li><b>Competência 4</b>: <?php echo $_SESSION['empresa']['compet_quatro']; ?> </li>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                    <li><b>Competência 5</b>: <?php echo $_SESSION['empresa']['compet_cinco']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 5</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                    <li><b>Competência 6</b>: <?php echo $_SESSION['empresa']['compet_seis']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 6</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                    <li><b>Competência 7</b>: <?php echo $_SESSION['empresa']['compet_sete']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 7</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                    <li><b>Competência 8</b>: <?php echo $_SESSION['empresa']['compet_oito']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 8</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                    <li><b>Competência 9</b>: <?php echo $_SESSION['empresa']['compet_nove']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 9</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                    <li><b>Competência 10</b>: <?php echo $_SESSION['empresa']['compet_dez']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 10</b>: não utilizada. </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-sm">
            <ul>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                    <li><b>Competência 11</b>: <?php echo $_SESSION['empresa']['compet_onze']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 11</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                    <li><b>Competência 12</b>: <?php echo $_SESSION['empresa']['compet_doze']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 12</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                    <li><b>Competência 13</b>: <?php echo $_SESSION['empresa']['compet_treze']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 13</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                    <li><b>Competência 14</b>: <?php echo $_SESSION['empresa']['compet_quatorze']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 14</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                    <li><b>Competência 15</b>: <?php echo $_SESSION['empresa']['compet_quinze']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 15</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                    <li><b>Competência 16</b>: <?php echo $_SESSION['empresa']['compet_dezesseis']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 16</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                    <li><b>Competência 17</b>: <?php echo $_SESSION['empresa']['compet_dezessete']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 17</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                    <li><b>Competência 18</b>: <?php echo $_SESSION['empresa']['compet_dezoito']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 18</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                    <li><b>Competência 19</b>: <?php echo $_SESSION['empresa']['compet_dezenove']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 19</b>: não utilizada. </li>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                    <li><b>Competência 20</b>: <?php echo $_SESSION['empresa']['compet_vinte']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 20</b>: não utilizada. </li>
                <?php } ?>
            </div>
        </div>


    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Competências das avaliações da empresa</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">

            <ul>
                <li><b>Competência 1</b>: <?php echo $_SESSION['empresa']['avg_sessao_um']; ?> </li>
                <li><b>Competência 2</b>: <?php echo $_SESSION['empresa']['avg_sessao_dois']; ?> </li>
                <li><b>Competência 3</b>: <?php echo $_SESSION['empresa']['avg_sessao_tres']; ?> </li>
                <li><b>Competência 4</b>: <?php echo $_SESSION['empresa']['avg_sessao_quatro']; ?> </li>
                <?php if($_SESSION['empresa']['avg_sessao_cinco'] != "") { ?>
                    <li><b>Competência 5</b>: <?php echo $_SESSION['empresa']['avg_sessao_cinco']; ?> </li>
                <?php } else { ?>
                    <li><b>Competência 5</b>: não utilizada. </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-sm">
            <ul>
            <?php if($_SESSION['empresa']['avg_sessao_seis'] != "") { ?>
                <li><b>Competência 6</b>: <?php echo $_SESSION['empresa']['avg_sessao_seis']; ?> </li>
            <?php } else { ?>
                <li><b>Competência 6</b>: não utilizada. </li>
            <?php } ?>
            <?php if($_SESSION['empresa']['avg_sessao_sete'] != "") { ?>
                <li><b>Competência 7</b>: <?php echo $_SESSION['empresa']['avg_sessao_sete']; ?> </li>
            <?php } else { ?>
                <li><b>Competência 7</b>: não utilizada. </li>
            <?php } ?>
            <?php if($_SESSION['empresa']['avg_sessao_oito'] != "") { ?>
                <li><b>Competência 8</b>: <?php echo $_SESSION['empresa']['avg_sessao_oito']; ?> </li>
            <?php } else { ?>
                <li><b>Competência 8</b>: não utilizada. </li>
            <?php } ?>
            <?php if($_SESSION['empresa']['avg_sessao_nove'] != "") { ?>
                <li><b>Competência 9</b>: <?php echo $_SESSION['empresa']['avg_sessao_nove']; ?> </li>
            <?php } else { ?>
                <li><b>Competência 9</b>: não utilizada. </li>
            <?php } ?>
            <?php if($_SESSION['empresa']['avg_sessao_dez'] != "") { ?>
                <li><b>Competência 10</b>: <?php echo $_SESSION['empresa']['avg_sessao_dez']; ?> </li>
            <?php } else { ?>
                <li><b>Competência 10</b>: não utilizada. </li>
            <?php } ?>
            </div>
        </div>
    </div>
    
</div>
</body>
</html>