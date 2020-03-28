<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    
    if($_SESSION['user']['permissao'] != 'GESTOR-1') {
        include('../include/acessoNegado.php');
        die();
    }

    if(!isset($_GET['funcionario']) || !isset($_GET['data'])) {
        header('Location: historicoPontos.php');
        die();
    }

    $cpf = base64_decode($_GET['funcionario']);
    $data = $_GET['data'];
    $nome = base64_decode($_GET['nome']);

    if(isset($_POST['id_entrada'])) {
        $data = $_POST['data'];
        $cpf_gestor = $_SESSION['user']['cpf'];
        $motivo = addslashes($_POST['motivo']);

        $data_entrada = $data.' '.$_POST['entrada'].':00';
        if($_POST['id_entrada'] != "") {
            //Update
            $id_entrada = $_POST['id_entrada'];

            $helper->update("UPDATE tbl_ponto SET data = '$data_entrada', editado = 1, cpf_edicao = '$cpf_gestor', 
            data_edicao = CURRENT_TIMESTAMP, motivo_edicao = '$motivo' WHERE id = $id_entrada");
        } else if ($_POST['entrada'] != "" && $_POST['entrada'] != "00:00") {
            //Insert
            $insert = "INSERT INTO tbl_ponto (cpf, data, tipo, editado, cpf_edicao, data_edicao, motivo_edicao) 
            VALUES 
            ('$cpf', '$data_entrada', 1, 1, '$cpf_gestor', CURRENT_TIMESTAMP, '$motivo')";
            $helper->insert($insert);
        }

        $data_pausa = $data.' '.$_POST['pausa'].':00';
        if($_POST['id_pausa'] != "") {
            //Update
            $id_pausa = $_POST['id_pausa'];
            
            $helper->update("UPDATE tbl_ponto SET data = '$data_pausa', editado = 1, cpf_edicao = '$cpf_gestor', 
            data_edicao = CURRENT_TIMESTAMP, motivo_edicao = '$motivo' WHERE id = $id_pausa");
        } else if ($_POST['pausa'] != "" && $_POST['pausa'] != "00:00") {
            //Insert
            $insert = "INSERT INTO tbl_ponto (cpf, data, tipo, editado, cpf_edicao, data_edicao, motivo_edicao) 
            VALUES 
            ('$cpf', '$data_pausa', 2, 1, '$cpf_gestor', CURRENT_TIMESTAMP, '$motivo')";
            $helper->insert($insert);
        }

        $data_retorno = $data.' '.$_POST['retorno'].':00';
        if($_POST['id_retorno'] != "") {
            //Update
            $id_retorno = $_POST['id_retorno'];
            
            $helper->update("UPDATE tbl_ponto SET data = '$data_retorno', editado = 1, cpf_edicao = '$cpf_gestor', 
            data_edicao = CURRENT_TIMESTAMP, motivo_edicao = '$motivo' WHERE id = $id_retorno");
        } else if ($_POST['retorno'] != "" && $_POST['retorno'] != "00:00") {
            //Insert
            $insert = "INSERT INTO tbl_ponto (cpf, data, tipo, editado, cpf_edicao, data_edicao, motivo_edicao) 
            VALUES 
            ('$cpf', '$data_retorno', 3, 1, '$cpf_gestor', CURRENT_TIMESTAMP, '$motivo')";
            $helper->insert($insert);
        }

        $data_saida = $data.' '.$_POST['saida'].':00';
        if($_POST['id_saida'] != "") {
            //Update
            $id_saida = $_POST['id_saida'];
            
            $helper->update("UPDATE tbl_ponto SET data = '$data_saida', editado = 1, cpf_edicao = '$cpf_gestor', 
            data_edicao = CURRENT_TIMESTAMP, motivo_edicao = '$motivo' WHERE id = $id_saida");
        } else if ($_POST['saida'] != "" && $_POST['saida'] != "00:00") {
            //Insert
            $insert = "INSERT INTO tbl_ponto (cpf, data, tipo, editado, cpf_edicao, data_edicao, motivo_edicao) 
            VALUES 
            ('$cpf', '$data_saida', 4, 1, '$cpf_gestor', CURRENT_TIMESTAMP, '$motivo')";
            $helper->insert($insert);
        }

        $_SESSION['msg'] = 'Alterações feitas com sucesso';
        header('Location: editarPonto.php?data='.$data.'&funcionario='.base64_encode($cpf).'&nome='.base64_encode($nome));
        die();
    }

    //Entrada
    $select = "SELECT 
                DATE_FORMAT(data, '%H:%i') as hora,
                id 
               FROM tbl_ponto 
               WHERE cpf = '$cpf' 
               AND DATE_FORMAT(data, '%Y-%m-%d') = '$data' 
               AND tipo = 1";
    $fetch = $helper->select($select, 2);
    $hora_entrada = $fetch['hora'];
    $id_entrada = $fetch['id'];

    //Pausa
    $select = "SELECT 
                DATE_FORMAT(data, '%H:%i') as hora,
                id 
               FROM tbl_ponto 
               WHERE cpf = '$cpf' 
               AND DATE_FORMAT(data, '%Y-%m-%d') = '$data' 
               AND tipo = 2";
    $fetch = $helper->select($select, 2);
    $hora_pausa = $fetch['hora'];
    $id_pausa = $fetch['id'];

    //Retorno
    $select = "SELECT 
                DATE_FORMAT(data, '%H:%i') as hora,
                id 
               FROM tbl_ponto 
               WHERE cpf = '$cpf' 
               AND DATE_FORMAT(data, '%Y-%m-%d') = '$data' 
               AND tipo = 3";
    $fetch = $helper->select($select, 2);
    $hora_retorno = $fetch['hora'];
    $id_retorno = $fetch['id'];

    //Saída
    $select = "SELECT 
                DATE_FORMAT(data, '%H:%i') as hora,
                id 
               FROM tbl_ponto 
               WHERE cpf = '$cpf' 
               AND DATE_FORMAT(data, '%Y-%m-%d') = '$data' 
               AND tipo = 4";
    $fetch = $helper->select($select, 2);
    $hora_saida = $fetch['hora'];
    $id_saida = $fetch['id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar ponto</title>
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
            <li class="breadcrumb-item"><a href="historicoPontos.php">Histórico de Pontos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edição de Ponto</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Edição de ponto</h2>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h6 class="text">Funcionário: <?php echo $nome; ?></h6>
        </div>
        <div class="col-sm">
            <h6 class="text">Data: <?php echo substr($data, -2).'/'.substr($data, 5, 2).'/'.substr($data, 0, 4); ?></h6>
        </div>
    </div>

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    <br><a href="historicoPontos.php"><- Voltar</a>
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

    <div class="row">
        <div class="col-sm">
            <label class="text">Entrada</label>
            <form method="POST" action="editarPonto.php?salvaredicao=true&data=<?php echo $data; ?>&funcionario=<?php echo base64_encode($cpf); ?>&nome=<?php echo base64_encode($nome); ?>">
            <input type="time" name="entrada" id="entrada" value="<?php echo $hora_entrada; ?>" class="all-input">
            <input type="hidden" name="id_entrada" id="id_entrada" value="<?php echo $id_entrada; ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa</label>
            <input type="time" name="pausa" id="pausa" value="<?php echo $hora_pausa; ?>" class="all-input">
            <input type="hidden" name="id_pausa" id="id_pausa" value="<?php echo $id_pausa; ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno</label>
            <input type="time" name="retorno" id="retorno" value="<?php echo $hora_retorno; ?>" class="all-input">
            <input type="hidden" name="id_retorno" id="id_retorno" value="<?php echo $id_retorno; ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida" id="saida" value="<?php echo $hora_saida; ?>" class="all-input">
            <input type="hidden" name="id_saida" id="id_saida" value="<?php echo $id_saida; ?>">
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <textarea name="motivo" id="motivo" class="all-input" placeholder="Descreva brevemente o motivo da edição" required minlength="30"></textarea>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="hidden" name="data" id="data" value="<?php echo $data; ?>">
            <input type="submit" class="button button1" value="Salvar alterações">
            </form>
        </div>
    </div>
    
</div>
</body>
</html>