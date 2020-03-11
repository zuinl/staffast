<?php
    include('../include/auth.php'); 
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_gestor.php');

    $colaborador = new Colaborador();
    $gestor = new Gestor();

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conexao = $conexao->conecta();

    $helper = new QueryHelper($conexao);

    $select = "SELECT set_id as id FROM tbl_setor ORDER BY set_nome ASC";

    $query = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Relatórios</title>
    <script>
            function direciona() {
                var col = document.getElementById("colaboradores").value;
                var tipo = document.getElementById("relatorio").value;

                if(col == "null") {
                    alert("Selecione uma opção para os colaboradores");
                    return;
                }
                
                if(tipo == "null") {
                    alert("Selecione uma relatório");
                    return;
                } else if (tipo == "1") {
                    //direciona para arquivo
                } else if (tipo == "2") {
                    //direciona para arquivo
                } else if (tipo == "3") {
                    //direciona para arquivo
                } else if (tipo == "4") {
                    //direciona para arquivo
                } else if (tipo.substr(0, 6) == "compet") {
                    //direciona para arquivo
                }
            }
        </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <!-- <div class="col-sm-1">
            <img src="img/report.png" width="60">
        </div> -->
        <div class="col-sm">
            <h2 class="high-text">Relatórios</h2>
        </div>
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

    <hr class="hr-divide">
</div>

<div class="container">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="text">Avaliação por competência</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <form action="relatorios/relatorio_avaliacao_competencia.php" method="POST">
            <?php if($_SESSION['user']['permissao'] != 'COLABORADOR') { ?>
            <label class="text">Colaborador</label>
            <select name="colaborador" id="colaborador" class="all-input" required>
                <option value="">-- Selecione colaborador --</option>
                <?php echo $colaborador->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
            <?php } else if ($_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
                <label class="text">Colaborador</label>
                <select name="colaborador" id="colaborador" class="all-input" required>
                    <option value="">-- Selecione colaborador --</option>
                    <?php echo $colaborador->popularSelectAvaliacao($_SESSION['empresa']['database'], $_SESSION['user']['cpf']); ?>
                </select>
            <?php } else { ?>
                <input type="hidden" name="colaborador" id="colaborador" value="<?php echo $_SESSION['user']['cpf']; ?>">
            <?php } ?>
        </div>
        <div class="col-sm">
            <label class="text">Gestor (opcional)</label>
            <select name="gestor" id="gestor" class="all-input">
                <option value="">-- Selecione gestor --</option>
                <?php echo $gestor->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
        </div>
        <div class="col-sm">
            <label class="text">Data inicial (opcional)</label>
            <input type="date" name="dataI" id="dataI" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Data final (opcional)</label>
            <input type="date" name="dataF" id="dataF" class="all-input">
        </div>
        <div class="col-sm">
            <label class="text">Competência</label>
            <select name="compet" id="compet" class="all-input" required>
                <option value="">-- Selecione --</option>
                <option value="ava_sessao_um|<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="ava_sessao_dois|<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="ava_sessao_tres|<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <option value="ava_sessao_quatro|<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                <option value="ava_sessao_cinco|<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                <option value="ava_sessao_seis|<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                <option value="ava_sessao_sete|<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                <option value="ava_sessao_oito|<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                <option value="ava_sessao_nove|<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                <option value="ava_sessao_dez|<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                <option value="ava_sessao_onze|<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                <option value="ava_sessao_doze|<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                <option value="ava_sessao_treze|<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                <option value="ava_sessao_quatorze|<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                <option value="ava_sessao_quinze|<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                <option value="ava_sessao_dezesseis|<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                <option value="ava_sessao_dezessete|<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                <option value="ava_sessao_dezoito|<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                <option value="ava_sessao_dezenove|<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                <option value="ava_sessao_vinte|<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 1.5em;">
            <input type="submit" class="button button1" value="Gerar relatório">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm"  style="text-align: center;">
            <h5 class="text">Autoavaliação por competência</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <form action="relatorios/relatorio_autoavaliacao_competencia.php" method="POST">
            <?php if($_SESSION['user']['permissao'] != 'COLABORADOR') { ?>
            <label class="text">Colaborador</label>
            <select name="colaborador" id="colaborador" class="all-input" required>
                <option value="">-- Selecione colaborador --</option>
                <?php echo $colaborador->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
            <?php } else if ($_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
                <label class="text">Colaborador</label>
                <select name="colaborador" id="colaborador" class="all-input" required>
                    <option value="">-- Selecione colaborador --</option>
                    <?php echo $colaborador->popularSelectAvaliacao($_SESSION['empresa']['database'], $_SESSION['user']['cpf']); ?>
                </select>
            <?php } else { ?>
                <input type="hidden" name="colaborador" id="colaborador" value="<?php echo $_SESSION['user']['cpf']; ?>">
            <?php } ?>
        </div>
        <div class="col-sm-2">
            <label class="text">Data inicial (opcional)</label>
            <input type="date" name="dataI" id="dataI" class="all-input">
        </div>
        <div class="col-sm-2">
            <label class="text">Data final (opcional)</label>
            <input type="date" name="dataF" id="dataF" class="all-input">
        </div>
        <div class="col-sm-2">
            <label class="text">Competência</label>
            <select name="compet" id="compet" class="all-input" required>
                <option value="">-- Selecione --</option>
                <option value="ata_sessao_um|<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="ata_sessao_dois|<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="ata_sessao_tres|<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <option value="ata_sessao_quatro|<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                <option value="ata_sessao_cinco|<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                <option value="ata_sessao_seis|<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                <option value="ata_sessao_sete|<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                <option value="ata_sessao_oito|<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                <option value="ata_sessao_nove|<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                <option value="ata_sessao_dez|<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                <option value="ata_sessao_onze|<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                <option value="ata_sessao_doze|<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                <option value="ata_sessao_treze|<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                <option value="ata_sessao_quatorze|<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                <option value="ata_sessao_quinze|<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                <option value="ata_sessao_dezesseis|<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                <option value="ata_sessao_dezessete|<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                <option value="ata_sessao_dezoito|<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                <option value="ata_sessao_dezenove|<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                <option value="ata_sessao_vinte|<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 1.5em;">
            <input type="submit" class="button button1" value="Gerar relatório">
            </form>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <!-- <div class="row">
        <div class="col-sm">
            <h5 class="text">Melhores colaboradores por competência</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <form action="relatorios/relatorio_colaborador_competencia.php" method="POST">
            <label class="text">Competência</label>
            <select name="compet" id="compet" class="all-input" required>
                <option value="">-- Selecione --</option>
                <option value="ata_sessao_um|<?php echo $_SESSION['empresa']['compet_um']; ?>"><?php echo $_SESSION['empresa']['compet_um']; ?></option>
                <option value="ata_sessao_dois|<?php echo $_SESSION['empresa']['compet_dois']; ?>"><?php echo $_SESSION['empresa']['compet_dois']; ?></option>
                <option value="ata_sessao_tres|<?php echo $_SESSION['empresa']['compet_tres']; ?>"><?php echo $_SESSION['empresa']['compet_quatro']; ?></option>
                <option value="ata_sessao_quatro|<?php echo $_SESSION['empresa']['compet_quatro']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                <option value="ata_sessao_cinco|<?php echo $_SESSION['empresa']['compet_cinco']; ?>"><?php echo $_SESSION['empresa']['compet_cinco']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                <option value="ata_sessao_seis|<?php echo $_SESSION['empresa']['compet_seis']; ?>"><?php echo $_SESSION['empresa']['compet_seis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                <option value="ata_sessao_sete|<?php echo $_SESSION['empresa']['compet_sete']; ?>"><?php echo $_SESSION['empresa']['compet_sete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                <option value="ata_sessao_oito|<?php echo $_SESSION['empresa']['compet_oito']; ?>"><?php echo $_SESSION['empresa']['compet_oito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                <option value="ata_sessao_nove|<?php echo $_SESSION['empresa']['compet_nove']; ?>"><?php echo $_SESSION['empresa']['compet_nove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                <option value="ata_sessao_dez|<?php echo $_SESSION['empresa']['compet_dez']; ?>"><?php echo $_SESSION['empresa']['compet_dez']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                <option value="ata_sessao_onze|<?php echo $_SESSION['empresa']['compet_onze']; ?>"><?php echo $_SESSION['empresa']['compet_onze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                <option value="ata_sessao_doze|<?php echo $_SESSION['empresa']['compet_doze']; ?>"><?php echo $_SESSION['empresa']['compet_doze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                <option value="ata_sessao_treze|<?php echo $_SESSION['empresa']['compet_treze']; ?>"><?php echo $_SESSION['empresa']['compet_treze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                <option value="ata_sessao_quatorze|<?php echo $_SESSION['empresa']['compet_quatorze']; ?>"><?php echo $_SESSION['empresa']['compet_quatorze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                <option value="ata_sessao_quinze|<?php echo $_SESSION['empresa']['compet_quinze']; ?>"><?php echo $_SESSION['empresa']['compet_quinze']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                <option value="ata_sessao_dezesseis|<?php echo $_SESSION['empresa']['compet_dezesseis']; ?>"><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                <option value="ata_sessao_dezessete|<?php echo $_SESSION['empresa']['compet_dezessete']; ?>"><?php echo $_SESSION['empresa']['compet_dezessete']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                <option value="ata_sessao_dezoito|<?php echo $_SESSION['empresa']['compet_dezoito']; ?>"><?php echo $_SESSION['empresa']['compet_dezoito']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                <option value="ata_sessao_dezenove|<?php echo $_SESSION['empresa']['compet_dezenove']; ?>"><?php echo $_SESSION['empresa']['compet_dezenove']; ?></option>
                <?php } ?>
                <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                <option value="ata_sessao_vinte|<?php echo $_SESSION['empresa']['compet_vinte']; ?>"><?php echo $_SESSION['empresa']['compet_vinte']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm">
            <input type="submit" class="button button1" value="Gerar relatório">
            </form>
        </div>
    </div> -->
</div>
</body>
</html>