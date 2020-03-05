<?php
session_start();
ini_set('default_charset','UTF-8');
$conn = mysqli_connect('localhost', 'root', '', 'db_staffast');
//------------- ESTA PÁGINA É ABERTA ------------------//

$_SESSION['msg'] = "";

if(isset($_GET['novaEmpresa'])) {

    $razaoSocial = addslashes($_POST['razaoSocial']);
    $database = $_POST['razaoSocial'];
    $database = strtolower($database);
    $database = str_replace(' ', '', $database);
    $database = str_replace('.', '', $database);
    $database = str_replace(',', '', $database);
    $database = str_replace('é', 'e', $database);
    $database = str_replace('ê', 'e', $database);
    $database = str_replace('è', 'e', $database);
    $database = str_replace('á', 'a', $database);
    $database = str_replace('à', 'a', $database);
    $database = str_replace('ã', 'a', $database);
    $database = str_replace('í', 'i', $database);
    $database = str_replace('ì', 'i', $database);
    $database = str_replace('ñ', 'n', $database);
    $database = str_replace('î', 'i', $database);
    $database = str_replace('â', 'a', $database);
    $database = str_replace('ó', 'o', $database);
    $database = str_replace('ò', 'o', $database);
    $database = str_replace('ô', 'o', $database); 
    

    $telefone = addslashes($_POST['telefone']);
    $linkedin = addslashes($_POST['linkedin']);

    $competencia1 = addslashes($_POST['competencia1']);
    $competencia2 = addslashes($_POST['competencia2']);
    $competencia3 = addslashes($_POST['competencia3']);
    $competencia4 = addslashes($_POST['competencia4']);
    $competencia5 = addslashes($_POST['competencia5']);
    $competencia6 = addslashes($_POST['competencia6']);
    $competencia7 = addslashes($_POST['competencia7']);
    $competencia8 = addslashes($_POST['competencia8']);
    $competencia9 = addslashes($_POST['competencia9']);
    $competencia10 = addslashes($_POST['competencia10']);
    $competencia11 = addslashes($_POST['competencia11']);
    $competencia12 = addslashes($_POST['competencia12']);
    $competencia13 = addslashes($_POST['competencia13']);
    $competencia14 = addslashes($_POST['competencia14']);
    $competencia15 = addslashes($_POST['competencia15']);
    $competencia16 = addslashes($_POST['competencia16']);
    $competencia17 = addslashes($_POST['competencia17']);
    $competencia18 = addslashes($_POST['competencia18']);
    $competencia19 = addslashes($_POST['competencia19']);
    $competencia20 = addslashes($_POST['competencia20']);

    $avg1 = addslashes($_POST['avg1']);
    $avg2 = addslashes($_POST['avg2']);
    $avg3 = addslashes($_POST['avg3']);
    $avg4 = addslashes($_POST['avg4']);
    $avg5 = addslashes($_POST['avg5']);
    $avg6 = addslashes($_POST['avg6']);
    $avg7 = addslashes($_POST['avg7']);
    $avg8 = addslashes($_POST['avg8']);
    $avg9 = addslashes($_POST['avg9']);
    $avg10 = addslashes($_POST['avg10']);

    // $arqNome = "";
    // if($_FILES["logo"]["error"] == 0){
    //     $diretorio = "../empresa/logotipo/";
    //     $logo = $_FILES['logo'];
    
    //         if(!file_exists($diretorio)){
    //             mkdir($diretorio);
    //         }

    //     $arqNome = $diretorio.$logo['name'];
    //     move_uploaded_file($logo['tmp_name'], $arqNome);
    
    // }

    $insert = mysqli_query($conn, "INSERT INTO tbl_empresa (emp_razao_social, emp_database, emp_telefone, 
        emp_linkedin) VALUES ('$razaoSocial', '$database', '$telefone', '$linkedin')");

        if(!$insert) {
            $_SESSION['msg'] .= 'Houve um erro ao cadastrar uma nova empresa'.mysqli_error($conn);
            mysqli_close($conn);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $select = mysqli_query($conn, "SELECT LAST_INSERT_ID() as id FROM tbl_empresa");
        $row = mysqli_fetch_assoc($select);
        $emp_id = $row['id'];

        if(!$select || $emp_id == 0) {
            $_SESSION['msg'] .= 'Houve um erro ao consultar a empresa cadastrada: '.mysqli_error($conn);
            mysqli_close($conn);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $insert = mysqli_query($conn, "INSERT INTO tbl_competencia_empresa (emp_id, compet_um, 
        compet_dois, compet_tres, compet_quatro, compet_cinco, compet_seis, compet_sete,
        compet_oito, compet_nove, compet_dez, compet_onze, compet_doze, compet_treze,
        compet_quatorze, compet_quinze, compet_dezesseis, compet_dezessete,
        compet_dezoito, compet_dezenove, compet_vinte) VALUES ('$emp_id', '$competencia1', 
        '$competencia2', '$competencia3', '$competencia4', '$competencia5', '$competencia6',
        '$competencia7', '$competencia8', '$competencia9', '$competencia10', '$competencia11',
        '$competencia12', '$competencia13', '$competencia14', '$competencia15',
        '$competencia16', '$competencia17', '$competencia18', '$competencia19',
        '$competencia20')");

        if(!$insert) {
            $_SESSION['msg'] .= 'Houve um problema ao salvar as competências da empresa: '.mysqli_error($conn);
            mysqli_close($conn);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $insert = mysqli_query($conn, "INSERT INTO tbl_campos_avaliacao_gestao (emp_id, um, dois,
        tres, quatro, cinco, seis, sete, oito, nove, dez) VALUES ('$emp_id', '$avg1', 
        '$avg2', '$avg3', '$avg4', '$avg5', '$avg6', '$avg7', '$avg8', '$avg9', '$avg10')");

        if(!$insert) {
            $_SESSION['msg'] .= 'Houve um problema ao salvar as competências da avaliação da gestão: '.mysqli_error($conn);
            mysqli_close($conn);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $senha = password_hash('welcomeStaffast', PASSWORD_DEFAULT);
        $mail = $database.'.welcome'.$emp_id.'@staffast.com';
        $insert = mysqli_query($conn, "INSERT INTO tbl_usuario (usu_email, usu_senha, emp_id) 
        VALUES('$mail', '$senha', '$emp_id')");

        $select = mysqli_query($conn, "SELECT LAST_INSERT_ID() as id FROM tbl_usuario");
        $row = mysqli_fetch_assoc($select);
        $usu_id = $row['id'];

        $create = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS db_staffast_".$database);

        if(!$create) {
            $_SESSION['msg'] .= 'Houve um erro na criação do bando de dados '.$database.': '.mysqli_error($conn);
            mysqli_close($conn);
            header('Location: ../novaEmpresa.php');
            die();
        }
        
        mysqli_close($conn);

        $conexao = mysqli_connect('localhost', 'root', '', 'db_staffast_'.$database);
          
        $create = mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS `tbl_colaborador` (
            `col_cpf` VARCHAR(11) NOT NULL UNIQUE,
            `col_primeiro_nome` VARCHAR(20) NOT NULL,
            `col_nome_completo` VARCHAR(80) NOT NULL,
            `col_cargo` VARCHAR(40) NOT NULL,
            `col_telefone` VARCHAR(15) NULL,
            `col_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `col_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            `usu_id` INT NOT NULL,
            `ges_importado` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`col_cpf`));");

            if(!$create) {
                $_SESSION['msg'] .= 'Erro ao criar tabela TBL_COLABORADOR: '.mysqli_error($conexao);
                mysqli_close($conexao);
                header('Location: ../novaEmpresa.php');
                die();
            }
              
        $create = mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS `tbl_gestor` (
            `ges_cpf` VARCHAR(11) NOT NULL UNIQUE,
            `ges_primeiro_nome` VARCHAR(20) NOT NULL,
            `ges_nome_completo` VARCHAR(80) NOT NULL,
            `ges_cargo` VARCHAR(40) NOT NULL,
            `ges_linkedin` VARCHAR(120) NULL,
            `ges_telefone` VARCHAR(15) NULL,
            `ges_ramal` VARCHAR(6) NULL,
            `ges_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `ges_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            `ges_ativo` INT NOT NULL DEFAULT 1,
            `usu_id` INT NOT NULL,
            PRIMARY KEY (`ges_cpf`));");

            if(!$create) {
                $_SESSION['msg'] .= 'Erro ao criar tabela TBL_GESTOR: '.mysqli_error($conexao);
                mysqli_close($conexao);
                header('Location: ../novaEmpresa.php');
                die();
            }
              
        $create = mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS `tbl_setor` (
            `set_id` INT NOT NULL AUTO_INCREMENT,
            `set_nome` VARCHAR(50) NOT NULL,
            `set_local` VARCHAR(80) NULL,
            `set_descricao` VARCHAR(150) NULL,
            `set_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `set_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            PRIMARY KEY (`set_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_SETOR: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $create = mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS tbl_setor_funcionario (
            set_id INT NOT NULL, 
            col_cpf VARCHAR(11) NOT NULL,
            ges_cpf VARCHAR(11) NOT NULL);");

    if(!$create) {
        $_SESSION['msg'] .= 'Erro ao criar tabela TBL_SETOR_FUNCIONARIO: '.mysqli_error($conexao);
        mysqli_close($conexao);
        header('Location: ../novaEmpresa.php');
        die();
    }
          
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_candidato` (
            `can_id` INT NOT NULL AUTO_INCREMENT,
            `can_nome` VARCHAR(80) NOT NULL,
            `can_linkedin` VARCHAR(120) NULL,
            `can_email` VARCHAR(120) NOT NULL,
            `can_telefone` VARCHAR(15) NOT NULL,
            `can_apresentacao` VARCHAR(1000) NOT NULL,
            `can_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `can_curriculo` VARCHAR(200),
            `sel_id` INT NOT NULL,
            PRIMARY KEY (`can_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_CANDIDATO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
          
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_avaliacao` (
            `ava_id` INT NOT NULL AUTO_INCREMENT,
            `ava_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `ava_data_liberacao` DATETIME NOT NULL,
            `ava_visualizada` INT NOT NULL DEFAULT 0,
            `ava_sessao_um` INT NOT NULL,
            `ava_sessao_um_obs` VARCHAR(400) NULL,
            `ava_sessao_dois` INT NOT NULL,
            `ava_sessao_dois_obs` VARCHAR(400) NULL,
            `ava_sessao_tres` INT NOT NULL,
            `ava_sessao_tres_obs` VARCHAR(400) NULL,
            `ava_sessao_quatro` INT NOT NULL,
            `ava_sessao_quatro_obs` VARCHAR(400) NULL,
            `ava_sessao_cinco` INT NOT NULL,
            `ava_sessao_cinco_obs` VARCHAR(400) NULL,
            `ava_sessao_seis` INT NOT NULL,
            `ava_sessao_seis_obs` VARCHAR(400) NULL,
            `ava_sessao_sete` INT NOT NULL,
            `ava_sessao_sete_obs` VARCHAR(400) NULL,
            `ava_sessao_oito` INT NOT NULL,
            `ava_sessao_oito_obs` VARCHAR(400) NULL,
            `ava_sessao_nove` INT NOT NULL,
            `ava_sessao_nove_obs` VARCHAR(400) NULL,
            `ava_sessao_dez` INT NOT NULL,
            `ava_sessao_dez_obs` VARCHAR(400) NULL,
            `ava_sessao_onze` INT NOT NULL,
            `ava_sessao_onze_obs` VARCHAR(400) NULL,
            `ava_sessao_doze` INT NOT NULL,
            `ava_sessao_doze_obs` VARCHAR(400) NULL,
            `ava_sessao_treze` INT NOT NULL,
            `ava_sessao_treze_obs` VARCHAR(400) NULL,
            `ava_sessao_quatorze` INT NOT NULL,
            `ava_sessao_quatorze_obs` VARCHAR(400) NULL,
            `ava_sessao_quinze` INT NOT NULL,
            `ava_sessao_quinze_obs` VARCHAR(400) NULL,
            `ava_sessao_dezesseis` INT NOT NULL,
            `ava_sessao_dezesseis_obs` VARCHAR(400) NULL,
            `ava_sessao_dezessete` INT NOT NULL,
            `ava_sessao_dezessete_obs` VARCHAR(400) NULL,
            `ava_sessao_dezoito` INT NOT NULL,
            `ava_sessao_dezoito_obs` VARCHAR(400) NULL,
            `ava_sessao_dezenove` INT NOT NULL,
            `ava_sessao_dezenove_obs` VARCHAR(400) NULL,
            `ava_sessao_vinte` INT NOT NULL,
            `ava_sessao_vinte_obs` VARCHAR(400) NULL,
            `ges_cpf` VARCHAR(11) NOT NULL,
            `col_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`ava_id`),
              FOREIGN KEY (`ges_cpf`) REFERENCES `TBL_GESTOR` (`ges_cpf`),
              FOREIGN KEY (`col_cpf`) REFERENCES `TBL_COLABORADOR` (`col_cpf`));");

        if(!$create) {
            $_SESSION['msg'] .='Erro ao criar tabela TBL_AVALIACAO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
          
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_autoavaliacao` (
            `ata_id` INT NOT NULL AUTO_INCREMENT,
            `ata_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `ata_data_preenchida` DATETIME NOT NULL DEFAULT NOW(),
            `ata_preenchida` INT NOT NULL DEFAULT 0,
            `ata_sessao_um` INT NOT NULL,
            `ata_sessao_um_obs` VARCHAR(400) NULL,
            `ata_sessao_dois` INT NOT NULL,
            `ata_sessao_dois_obs` VARCHAR(400) NULL,
            `ata_sessao_tres` INT NOT NULL,
            `ata_sessao_tres_obs` VARCHAR(400) NULL,
            `ata_sessao_quatro` INT NOT NULL,
            `ata_sessao_quatro_obs` VARCHAR(400) NULL,
            `ata_sessao_cinco` INT NOT NULL,
            `ata_sessao_cinco_obs` VARCHAR(400) NULL,
            `ata_sessao_seis` INT NOT NULL,
            `ata_sessao_seis_obs` VARCHAR(400) NULL,
            `ata_sessao_sete` INT NOT NULL,
            `ata_sessao_sete_obs` VARCHAR(400) NULL,
            `ata_sessao_oito` INT NOT NULL,
            `ata_sessao_oito_obs` VARCHAR(400) NULL,
            `ata_sessao_nove` INT NOT NULL,
            `ata_sessao_nove_obs` VARCHAR(400) NULL,
            `ata_sessao_dez` INT NOT NULL,
            `ata_sessao_dez_obs` VARCHAR(400) NULL,
            `ata_sessao_onze` INT NOT NULL,
            `ata_sessao_onze_obs` VARCHAR(400) NULL,
            `ata_sessao_doze` INT NOT NULL,
            `ata_sessao_doze_obs` VARCHAR(400) NULL,
            `ata_sessao_treze` INT NOT NULL,
            `ata_sessao_treze_obs` VARCHAR(400) NULL,
            `ata_sessao_quatorze` INT NOT NULL,
            `ata_sessao_quatorze_obs` VARCHAR(400) NULL,
            `ata_sessao_quinze` INT NOT NULL,
            `ata_sessao_quinze_obs` VARCHAR(400) NULL,
            `ata_sessao_dezesseis` INT NOT NULL,
            `ata_sessao_dezesseis_obs` VARCHAR(400) NULL,
            `ata_sessao_dezessete` INT NOT NULL,
            `ata_sessao_dezessete_obs` VARCHAR(400) NULL,
            `ata_sessao_dezoito` INT NOT NULL,
            `ata_sessao_dezoito_obs` VARCHAR(400) NULL,
            `ata_sessao_dezenove` INT NOT NULL,
            `ata_sessao_dezenove_obs` VARCHAR(400) NULL,
            `ata_sessao_vinte` INT NOT NULL,
            `ata_sessao_vinte_obs` VARCHAR(400) NULL,
            `col_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`ata_id`),
              FOREIGN KEY (`col_cpf`)
              REFERENCES `TBL_COLABORADOR` (`col_cpf`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_AUTOAVALIACAO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }


        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_avaliacao_gestao` (
            `avg_id` INT NOT NULL AUTO_INCREMENT,
            `avg_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `avg_sessao_um` INT NOT NULL,
            `avg_sessao_um_obs` VARCHAR(400) NULL,
            `avg_sessao_dois` INT NOT NULL,
            `avg_sessao_dois_obs` VARCHAR(400) NULL,
            `avg_sessao_tres` INT NOT NULL,
            `avg_sessao_tres_obs` VARCHAR(400) NULL,
            `avg_sessao_quatro` INT NOT NULL,
            `avg_sessao_quatro_obs` VARCHAR(400) NULL,
            `avg_sessao_cinco` INT NOT NULL,
            `avg_sessao_cinco_obs` VARCHAR(400) NULL,
            `avg_sessao_seis` INT NOT NULL,
            `avg_sessao_seis_obs` VARCHAR(400) NULL,
            `avg_sessao_sete` INT NOT NULL,
            `avg_sessao_sete_obs` VARCHAR(400) NULL,
            `avg_sessao_oito` INT NOT NULL,
            `avg_sessao_oito_obs` VARCHAR(400) NULL,
            `avg_sessao_nove` INT NOT NULL,
            `avg_sessao_nove_obs` VARCHAR(400) NULL,
            `avg_sessao_dez` INT NOT NULL,
            `avg_sessao_dez_obs` VARCHAR(400) NULL,
            `ges_cpf` VARCHAR(11) NULL,
            `set_id` INT(11) NULL,
            PRIMARY KEY (`avg_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_AVALIACAO_GESTAO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
          
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_processo_seletivo` (
            `sel_id` INT NOT NULL AUTO_INCREMENT,
            `sel_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `sel_data_encerramento` DATETIME NOT NULL,
            `sel_titulo` VARCHAR(80) NOT NULL,
            `sel_vagas` INT NOT NULL,
            `sel_descricao` VARCHAR(800) NOT NULL,
            `ges_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`sel_id`),
              FOREIGN KEY (`ges_cpf`) REFERENCES `TBL_GESTOR` (`ges_cpf`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_PROCESSO_SELETIVO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
          
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_pergunta_processo` (
            `per_id` INT NOT NULL AUTO_INCREMENT,
            `per_titulo` VARCHAR(120) NOT NULL,
            `per_descricao` VARCHAR(500) NULL,
            `per_opc_um` VARCHAR(80) NOT NULL,
            `per_opc_um_competencia` VARCHAR(30) NOT NULL,
            `per_opc_dois` VARCHAR(80) NOT NULL,
            `per_opc_dois_competencia` VARCHAR(30) NOT NULL,
            `per_opc_tres` VARCHAR(80) NOT NULL,
            `per_opc_tres_competencia` VARCHAR(30) NOT NULL,
            `per_opc_quatro` VARCHAR(80) NOT NULL,
            `per_opc_quatro_competencia` VARCHAR(30) NOT NULL,
            `sel_id` INT NOT NULL,
            PRIMARY KEY (`per_id`),
              FOREIGN KEY (`sel_id`) REFERENCES `TBL_PROCESSO_SELETIVO` (`sel_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_PERGUNTA_PROCESSO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
        $create = mysqli_query($conexao, "  CREATE TABLE IF NOT EXISTS `tbl_pergunta_resposta` (
            `res_id` INT NOT NULL AUTO_INCREMENT,
            `res_opc_um` INT NOT NULL,
            `res_opc_dois` INT NOT NULL,
            `res_opc_tres` INT NOT NULL,
            `res_opc_quatro` INT NOT NULL,
            `per_id` INT NOT NULL,
            PRIMARY KEY (`res_id`),
              FOREIGN KEY (`per_id`)
              REFERENCES `TBL_PERGUNTA_PROCESSO` (`per_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_PERGUNTA_RESPOSTA: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
              
        $create = mysqli_query($conexao, " CREATE TABLE IF NOT EXISTS `tbl_resposta_candidato` (
            `can_id` INT NOT NULL,
            `res_id` INT NOT NULL);");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_PERGUNTA_CANDIDATO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $create = mysqli_query($conexao, "CREATE TABLE `tbl_mensagem` (
            `men_id` INT NOT NULL AUTO_INCREMENT,
            `men_titulo` VARCHAR(100) NOT NULL,
            `men_texto` LONGTEXT NOT NULL,
            `men_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `men_data_expiracao` DATETIME NOT NULL,
            `ges_cpf` INT NOT NULL,
            PRIMARY KEY (`men_id`));");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_MENSAGEM: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $create = mysqli_query($conexao, "CREATE TABLE `tbl_mensagem_funcionario` (
            `men_id` INT NOT NULL,
            `cpf` VARCHAR(11) NOT NULL);
          ");

        if(!$create) {
            $_SESSION['msg'] .= 'Erro ao criar tabela TBL_MENSAGEM_FUNCIONARIO: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }
        
        $insert = mysqli_query($conexao, "INSERT INTO tbl_gestor (ges_cpf, ges_primeiro_nome, ges_nome_completo, 
        ges_cargo, ges_linkedin, ges_telefone, ges_ramal, usu_id) VALUES('00000000000', 
        'Gestor', 'Gestor Staffast', 'Gestor Master', '', '(12)99999-9999', '000', '$usu_id')");
        
        if(!$insert) {
            $_SESSION['msg'] .= 'Houve um erro ao cadastrar o gestor: '.mysqli_error($conexao);
            mysqli_close($conexao);
            header('Location: ../novaEmpresa.php');
            die();
        }

        $_SESSION['msg'] .= "<h2>Hey, ".$razaoSocial."! O Staffast está prontinho pra você usar</h2><br>
        Foi criado um gestor de administração com o e-mail de login <b>".$mail."</b> 
        e senha de acesso <b>welcomeStaffast</b>. Recomendamos <b>fortemente</b> que você altere esses dados, por 
        segurança, tudo bem?<br>
        <h3><a href='index.php'>Faça login</a></h3>";

        header('Location: ../novaEmpresa.php');
        mysqli_close($conexao);
}


function remover_caracter($string) {
    $string = preg_replace("/[áàâãä]/", "a", $string);
    $string = preg_replace("/[ÁÀÂÃÄ]/", "A", $string);
    $string = preg_replace("/[éèê]/", "e", $string);
    $string = preg_replace("/[ÉÈÊ]/", "E", $string);
    $string = preg_replace("/[íì]/", "i", $string);
    $string = preg_replace("/[ÍÌ]/", "I", $string);
    $string = preg_replace("/[óòôõö]/", "o", $string);
    $string = preg_replace("/[ÓÒÔÕÖ]/", "O", $string);
    $string = preg_replace("/[úùü]/", "u", $string);
    $string = preg_replace("/[ÚÙÜ]/", "U", $string);
    $string = preg_replace("/ç/", "c", $string);
    $string = preg_replace("/Ç/", "C", $string);
    $string = preg_replace("/[][><}{)(:;,!?*%~^`@]/", "", $string);
    $string = preg_replace("/ /", "_", $string);
    return $string;
}

?>