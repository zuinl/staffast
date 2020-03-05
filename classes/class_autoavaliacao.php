<?php

    class Autoavaliacao {

        private $ID;
        private $dataCriacao;
        private $dataPreenchida;
        private $preenchida;
        private $visualizada;
        private $sessaoUm;
        private $sessaoUmObs;
        private $sessaoDois;
        private $sessaoDoisObs;
        private $sessaoTres;
        private $sessaoTresObs;
        private $sessaoQuatro;
        private $sessaoQuatroObs;
        private $sessaoCinco;
        private $sessaoCincoObs;
        private $sessaoSeis;
        private $sessaoSeisObs;
        private $sessaoSete;
        private $sessaoSeteObs;
        private $sessaoOito;
        private $sessaoOitoObs;
        private $sessaoNove;
        private $sessaoNoveObs;
        private $sessaoDez;
        private $sessaoDezObs;
        private $sessaoOnze;
        private $sessaoOnzeObs;
        private $sessaoDoze;
        private $sessaoDozeObs;
        private $sessaoTreze;
        private $sessaoTrezeObs;
        private $sessaoQuatorze;
        private $sessaoQuatorzeObs;
        private $sessaoQuinze;
        private $sessaoQuinzeObs;
        private $sessaoDezesseis;
        private $sessaoDezesseisObs;
        private $sessaoDezessete;
        private $sessaoDezesseteObs;
        private $sessaoDezoito;
        private $sessaoDezoitoObs;
        private $sessaoDezenove;
        private $sessaoDezenoveObs;
        private $sessaoVinte;
        private $sessaoVinteObs;
        private $cpfColaborador;

        function cadastrar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_autoavaliacao (ata_data_preenchida, ata_sessao_um, ata_sessao_um_obs, 
            ata_sessao_dois, ata_sessao_dois_obs, ata_sessao_tres, ata_sessao_tres_obs, 
            ata_sessao_quatro, ata_sessao_quatro_obs, 
            ata_sessao_cinco, ata_sessao_cinco_obs, ata_sessao_seis, ata_sessao_seis_obs,
            ata_sessao_sete, ata_sessao_sete_obs, ata_sessao_oito, ata_sessao_oito_obs,
            ata_sessao_nove, ata_sessao_nove_obs, ata_sessao_dez, ata_sessao_dez_obs,
            ata_sessao_onze, ata_sessao_onze_obs, ata_sessao_doze, ata_sessao_doze_obs,
            ata_sessao_treze, ata_sessao_treze_obs, ata_sessao_quatorze, ata_sessao_quatorze_obs,
            ata_sessao_quinze, ata_sessao_quinze_obs, ata_sessao_dezesseis, ata_sessao_dezesseis_obs,
            ata_sessao_dezessete, ata_sessao_dezessete_obs, ata_sessao_dezoito, ata_sessao_dezoito_obs,
            ata_sessao_dezenove, ata_sessao_dezenove_obs, ata_sessao_vinte, ata_sessao_vinte_obs,
            col_cpf) VALUES 
            (NOW(), '$this->sessaoUm', '$this->sessaoUmObs', '$this->sessaoDois', 
            '$this->sessaoDoisObs', '$this->sessaoTres', '$this->sessaoTresObs', '$this->sessaoQuatro', '$this->sessaoQuatroObs',
            '$this->sessaoCinco', '$this->sessaoCincoObs', '$this->sessaoSeis', '$this->sessaoSeisObs',
            '$this->sessaoSete', '$this->sessaoSeteObs', '$this->sessaoOito', '$this->sessaoOitoObs',
            '$this->sessaoNove', '$this->sessaoNoveObs', '$this->sessaoDez', '$this->sessaoDezObs',
            '$this->sessaoOnze', '$this->sessaoOnzeObs', '$this->sessaoDoze', '$this->sessaoDozeObs',
            '$this->sessaoTreze', '$this->sessaoTrezeObs', '$this->sessaoQuatorze', '$this->sessaoQuatorzeObs',
            '$this->sessaoQuinze', '$this->sessaoQuinzeObs', '$this->sessaoDezesseis', '$this->sessaoDezesseisObs',
            '$this->sessaoDezessete', '$this->sessaoDezesseteObs', '$this->sessaoDezoito', '$this->sessaoDezoitoObs',
            '$this->sessaoDezenove', '$this->sessaoDezenoveObs', '$this->sessaoVinte', '$this->sessaoVinteObs', 
            '$this->cpfColaborador')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function preencher($database_empresa, $ata_id) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_autoavaliacao SET ata_preenchida = 1, ata_data_preenchida = NOW(), 
            ata_sessao_um = '$this->sessaoUm', ata_sessao_um_obs = '$this->sessaoUmObs', 
            ata_sessao_dois = '$this->sessaoDois', ata_sessao_dois_obs = '$this->sessaoDoisObs', 
            ata_sessao_tres = '$this->sessaoTres', ata_sessao_tres_obs = '$this->sessaoTresObs', 
            ata_sessao_quatro = '$this->sessaoQuatro', ata_sessao_quatro_obs = '$this->sessaoQuatroObs', 
            ata_sessao_cinco = '$this->sessaoCinco', ata_sessao_cinco_obs = '$this->sessaoCincoObs',
            ata_sessao_seis = '$this->sessaoSeis', ata_sessao_seis_obs = '$this->sessaoSeisObs',
            ata_sessao_sete = '$this->sessaoSete', ata_sessao_sete_obs = '$this->sessaoSeteObs',
            ata_sessao_oito = '$this->sessaoOito', ata_sessao_oito_obs = '$this->sessaoOitoObs',
            ata_sessao_nove = '$this->sessaoNove', ata_sessao_nove_obs = '$this->sessaoNoveObs',
            ata_sessao_dez = '$this->sessaoDez', ata_sessao_dez_obs = '$this->sessaoDezObs',
            ata_sessao_onze = '$this->sessaoOnze', ata_sessao_onze_obs = '$this->sessaoOnzeObs',
            ata_sessao_doze = '$this->sessaoDoze', ata_sessao_doze_obs = '$this->sessaoDozeObs',
            ata_sessao_treze = '$this->sessaoTreze', ata_sessao_treze_obs = '$this->sessaoTrezeObs',
            ata_sessao_quatorze = '$this->sessaoQuatorze', ata_sessao_quatorze_obs = '$this->sessaoQuatorzeObs',
            ata_sessao_quinze = '$this->sessaoQuinze', ata_sessao_quinze_obs = '$this->sessaoQuinzeObs',
            ata_sessao_dezesseis = '$this->sessaoDezesseis', ata_sessao_dezesseis_obs = '$this->sessaoDezesseisObs',
            ata_sessao_dezessete = '$this->sessaoDezessete', ata_sessao_dezessete_obs = '$this->sessaoDezesseteObs',
            ata_sessao_dezoito = '$this->sessaoDezoito', ata_sessao_dezoito_obs = '$this->sessaoDezoitoObs',
            ata_sessao_dezenove = '$this->sessaoDezenove', ata_sessao_dezenove_obs = '$this->sessaoDezenoveObs',
            ata_sessao_vinte = '$this->sessaoVinte', ata_sessao_vinte_obs = '$this->sessaoVinteObs'
            WHERE ata_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        function liberarAgora($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_autoavaliacao (col_cpf) VALUES ('$this->cpfColaborador')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function checarLiberada($database_empresa) { //SE EXISTE É PQ ESTÁ LIBERADA

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 0";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        }

        function retornarLiberada($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 0";

            $fetch = $helper->select($select, 2);

            $ata_id = $fetch['id'];

            return $ata_id;

        }

         function checarPreenchida($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ata_id as id FROM tbl_autoavaliacao WHERE ata_preenchida = 1 
            AND col_cpf = '$this->cpfColaborador'";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        } 

        function retornaUltima($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT DATE_FORMAT(ata_data_criacao, '%d/%m/%Y %H:%i') as liberacao,
            DATE_FORMAT(ata_data_preenchida, '%d/%m/%Y %H:%i') as preenchida 
            FROM tbl_autoavaliacao WHERE ata_preenchida = 1 
            AND col_cpf = '$this->cpfColaborador' ORDER BY ata_data_preenchida DESC LIMIT 1";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return array("liberacao" => 'não há preenchidas', "preenchida" => 'não há preenchidas', 
                "existe" => false);

            $fetch = mysqli_fetch_assoc($query);

            $datas = array("liberacao" => $fetch['liberacao'], "preenchida" => $fetch['preenchida'], "existe" => true);

            return $datas;

        }
        

        function retornarUltimaLiberada($database_empresa){

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT DATE_FORMAT(ata_data_criacao, '%d/%m/%Y %H:%i:%s') as liberacao
            FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1
            ORDER BY ata_data_criacao DESC LIMIT 1";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return 'Não há avaliações';

            $fetch = mysqli_fetch_assoc($query);

            $data = $fetch['liberacao'];

            return $data;

        }

        function setarPreenchida($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_autoavaliacao SET ata_preenchida = 1 WHERE ata_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        function retornarAutoavaliacao($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ata_id as id, DATE_FORMAT(ata_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao, 
            DATE_FORMAT(ata_data_preenchida, '%d/%m/%Y %H:%i:%s') as data_preencheu, ata_sessao_um as s1, 
            ata_sessao_um_obs as s1_obs, ata_sessao_dois as s2, 
            ata_sessao_dois_obs as s2_obs,
            ata_sessao_tres as s3, ata_sessao_tres_obs as s3_obs, 
            ata_sessao_quatro as s4, ata_sessao_quatro_obs as s4_obs, 
            ata_sessao_cinco as s5, ata_sessao_cinco_obs as s5_obs, 
            ata_sessao_seis as s6, ata_sessao_seis_obs as s6_obs, 
            ata_sessao_sete as s7, ata_sessao_sete_obs as s7_obs, 
            ata_sessao_oito as s8, ata_sessao_oito_obs as s8_obs, 
            ata_sessao_nove as s9, ata_sessao_nove_obs as s9_obs, 
            ata_sessao_dez as s10, ata_sessao_dez_obs as s10_obs, 
            ata_sessao_onze as s11, ata_sessao_onze_obs as s11_obs, 
            ata_sessao_doze as s12, ata_sessao_doze_obs as s12_obs, 
            ata_sessao_treze as s13, ata_sessao_treze_obs as s13_obs, 
            ata_sessao_quatorze as s14, ata_sessao_quatorze_obs as s14_obs, 
            ata_sessao_quinze as s15, ata_sessao_quinze_obs as s15_obs, 
            ata_sessao_dezesseis as s16, ata_sessao_dezesseis_obs as s16_obs, 
            ata_sessao_dezessete as s17, ata_sessao_dezessete_obs as s17_obs, 
            ata_sessao_dezoito as s18, ata_sessao_dezoito_obs as s18_obs, 
            ata_sessao_dezenove as s19, ata_sessao_dezenove_obs as s19_obs, 
            ata_sessao_vinte as s20, ata_sessao_vinte_obs as s20_obs,
            ata_preenchida as preenchida,
            col_cpf as col FROM tbl_autoavaliacao WHERE ata_id = '$this->ID'";

            $fetch = $helper->select($select, 2);
            $ava = new Autoavaliacao();
            $ava->setID($fetch['id']);
            $ava->setDataCriacao($fetch['criacao']);
            $ava->setDataPreenchida($fetch['data_preencheu']);
            $ava->setPreenchida($fetch['preenchida']);
            $ava->setSessaoUm($fetch['s1']);
            $ava->setSessaoDois($fetch['s2']);
            $ava->setSessaoTres($fetch['s3']);
            $ava->setSessaoQuatro($fetch['s4']);
            $ava->setSessaoCinco($fetch['s5']);
            $ava->setSessaoSeis($fetch['s6']);
            $ava->setSessaoSete($fetch['s7']);
            $ava->setSessaoOito($fetch['s8']);
            $ava->setSessaoNove($fetch['s9']);
            $ava->setSessaoDez($fetch['s10']);
            $ava->setSessaoOnze($fetch['s11']);
            $ava->setSessaoDoze($fetch['s12']);
            $ava->setSessaoTreze($fetch['s13']);
            $ava->setSessaoQuatorze($fetch['s14']);
            $ava->setSessaoQuinze($fetch['s15']);
            $ava->setSessaoDezesseis($fetch['s16']);
            $ava->setSessaoDezessete($fetch['s17']);
            $ava->setSessaoDezoito($fetch['s18']);
            $ava->setSessaoDezenove($fetch['s19']);
            $ava->setSessaoVinte($fetch['s20']);
            $ava->setSessaoUmObs($fetch['s1_obs']);
            $ava->setSessaoDoisObs($fetch['s2_obs']);
            $ava->setSessaoTresObs($fetch['s3_obs']);
            $ava->setSessaoQuatroObs($fetch['s4_obs']);
            $ava->setSessaoCincoObs($fetch['s5_obs']);
            $ava->setSessaoSeisObs($fetch['s6_obs']);
            $ava->setSessaoSeteObs($fetch['s7_obs']);
            $ava->setSessaoOitoObs($fetch['s8_obs']);
            $ava->setSessaoNoveObs($fetch['s9_obs']);
            $ava->setSessaoDezObs($fetch['s10_obs']);
            $ava->setSessaoOnzeObs($fetch['s11_obs']);
            $ava->setSessaoDozeObs($fetch['s12_obs']);
            $ava->setSessaoTrezeObs($fetch['s13_obs']);
            $ava->setSessaoQuatorzeObs($fetch['s14_obs']);
            $ava->setSessaoQuinzeObs($fetch['s15_obs']);
            $ava->setSessaoDezesseisObs($fetch['s16_obs']);
            $ava->setSessaoDezesseteObs($fetch['s17_obs']);
            $ava->setSessaoDezoitoObs($fetch['s18_obs']);
            $ava->setSessaoDezenoveObs($fetch['s19_obs']);
            $ava->setSessaoVinteObs($fetch['s20_obs']);
            $ava->setCpfColaborador($fetch['col']);

            return $ava;

        }

        function quantidadeAutoavaliacoesPreenchidas($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT COUNT(ata_id) as total FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' 
            AND ata_preenchida = 1";

            $fetch = $helper->select($select, 2);

            return $fetch['total'];

        }

        function isAutorizado($database_empresa, $ges_cpf, $col_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT * FROM tbl_setor_funcionario WHERE ges_cpf = '$ges_cpf' AND col_cpf = '$col_cpf'";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        }

        function calcularMedias($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ata_sessao_um), 1) as um, ROUND(AVG(ata_sessao_dois), 1) as dois,
                ROUND(AVG(ata_sessao_tres), 1) as tres, ROUND(AVG(ata_sessao_quatro), 1) as quatro,
                ROUND(AVG(ata_sessao_cinco), 1) as cinco, ROUND(AVG(ata_sessao_seis), 1) as seis,
                ROUND(AVG(ata_sessao_sete), 1) as sete, ROUND(AVG(ata_sessao_oito), 1) as oito,
                ROUND(AVG(ata_sessao_nove), 1) as nove, ROUND(AVG(ata_sessao_dez), 1) as dez,
                ROUND(AVG(ata_sessao_onze), 1) as onze, ROUND(AVG(ata_sessao_doze), 1) as doze,
                ROUND(AVG(ata_sessao_treze), 1) as treze, ROUND(AVG(ata_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ata_sessao_quinze), 1) as quinze, ROUND(AVG(ata_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ata_sessao_dezessete), 1) as dezessete, ROUND(AVG(ata_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ata_sessao_dezenove), 1) as dezenove, ROUND(AVG(ata_sessao_vinte), 1) as vinte
                FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1";

                $fetch = $helper->select($select, 2);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR
                $avgs[1] = $fetch['um'];
                $avgs[2] = $fetch['dois'];
                $avgs[3] = $fetch['tres'];
                $avgs[4] = $fetch['quatro'];
                $avgs[5] = $fetch['cinco'];
                $avgs[6] = $fetch['seis'];
                $avgs[7] = $fetch['sete'];
                $avgs[8] = $fetch['oito'];
                $avgs[9] = $fetch['nove'];
                $avgs[10] = $fetch['dez'];
                $avgs[11] = $fetch['onze'];
                $avgs[12] = $fetch['doze'];
                $avgs[13] = $fetch['treze'];
                $avgs[14] = $fetch['quatorze'];
                $avgs[15] = $fetch['quinze'];
                $avgs[16] = $fetch['dezesseis'];
                $avgs[17] = $fetch['dezessete'];
                $avgs[18] = $fetch['dezoito'];
                $avgs[19] = $fetch['dezenove'];
                $avgs[20] = $fetch['vinte'];

                return $avgs;

        }

        function calcularMediasCurtoPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ata_sessao_um), 1) as um, ROUND(AVG(ata_sessao_dois), 1) as dois,
                ROUND(AVG(ata_sessao_tres), 1) as tres, ROUND(AVG(ata_sessao_quatro), 1) as quatro,
                ROUND(AVG(ata_sessao_cinco), 1) as cinco, ROUND(AVG(ata_sessao_seis), 1) as seis,
                ROUND(AVG(ata_sessao_sete), 1) as sete, ROUND(AVG(ata_sessao_oito), 1) as oito,
                ROUND(AVG(ata_sessao_nove), 1) as nove, ROUND(AVG(ata_sessao_dez), 1) as dez,
                ROUND(AVG(ata_sessao_onze), 1) as onze, ROUND(AVG(ata_sessao_doze), 1) as doze,
                ROUND(AVG(ata_sessao_treze), 1) as treze, ROUND(AVG(ata_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ata_sessao_quinze), 1) as quinze, ROUND(AVG(ata_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ata_sessao_dezessete), 1) as dezessete, ROUND(AVG(ata_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ata_sessao_dezenove), 1) as dezenove, ROUND(AVG(ata_sessao_vinte), 1) as vinte
                FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1 
                AND ata_data_preenchida >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

                $fetch = $helper->select($select, 2);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR
                $avgs[1] = $fetch['um'];
                $avgs[2] = $fetch['dois'];
                $avgs[3] = $fetch['tres'];
                $avgs[4] = $fetch['quatro'];
                $avgs[5] = $fetch['cinco'];
                $avgs[6] = $fetch['seis'];
                $avgs[7] = $fetch['sete'];
                $avgs[8] = $fetch['oito'];
                $avgs[9] = $fetch['nove'];
                $avgs[10] = $fetch['dez'];
                $avgs[11] = $fetch['onze'];
                $avgs[12] = $fetch['doze'];
                $avgs[13] = $fetch['treze'];
                $avgs[14] = $fetch['quatorze'];
                $avgs[15] = $fetch['quinze'];
                $avgs[16] = $fetch['dezesseis'];
                $avgs[17] = $fetch['dezessete'];
                $avgs[18] = $fetch['dezoito'];
                $avgs[19] = $fetch['dezenove'];
                $avgs[20] = $fetch['vinte'];

                return $avgs;

        }

        function calcularMediasMedioPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ata_sessao_um), 1) as um, ROUND(AVG(ata_sessao_dois), 1) as dois,
                ROUND(AVG(ata_sessao_tres), 1) as tres, ROUND(AVG(ata_sessao_quatro), 1) as quatro,
                ROUND(AVG(ata_sessao_cinco), 1) as cinco, ROUND(AVG(ata_sessao_seis), 1) as seis,
                ROUND(AVG(ata_sessao_sete), 1) as sete, ROUND(AVG(ata_sessao_oito), 1) as oito,
                ROUND(AVG(ata_sessao_nove), 1) as nove, ROUND(AVG(ata_sessao_dez), 1) as dez,
                ROUND(AVG(ata_sessao_onze), 1) as onze, ROUND(AVG(ata_sessao_doze), 1) as doze,
                ROUND(AVG(ata_sessao_treze), 1) as treze, ROUND(AVG(ata_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ata_sessao_quinze), 1) as quinze, ROUND(AVG(ata_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ata_sessao_dezessete), 1) as dezessete, ROUND(AVG(ata_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ata_sessao_dezenove), 1) as dezenove, ROUND(AVG(ata_sessao_vinte), 1) as vinte
                FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1 
                AND ata_data_preenchida >= DATE_SUB(NOW(), INTERVAL 90 DAY)";

                $fetch = $helper->select($select, 2);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR
                $avgs[1] = $fetch['um'];
                $avgs[2] = $fetch['dois'];
                $avgs[3] = $fetch['tres'];
                $avgs[4] = $fetch['quatro'];
                $avgs[5] = $fetch['cinco'];
                $avgs[6] = $fetch['seis'];
                $avgs[7] = $fetch['sete'];
                $avgs[8] = $fetch['oito'];
                $avgs[9] = $fetch['nove'];
                $avgs[10] = $fetch['dez'];
                $avgs[11] = $fetch['onze'];
                $avgs[12] = $fetch['doze'];
                $avgs[13] = $fetch['treze'];
                $avgs[14] = $fetch['quatorze'];
                $avgs[15] = $fetch['quinze'];
                $avgs[16] = $fetch['dezesseis'];
                $avgs[17] = $fetch['dezessete'];
                $avgs[18] = $fetch['dezoito'];
                $avgs[19] = $fetch['dezenove'];
                $avgs[20] = $fetch['vinte'];

                return $avgs;

        }

        function calcularMediasCurtoMedioPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ata_sessao_um), 1) as um, ROUND(AVG(ata_sessao_dois), 1) as dois,
                ROUND(AVG(ata_sessao_tres), 1) as tres, ROUND(AVG(ata_sessao_quatro), 1) as quatro,
                ROUND(AVG(ata_sessao_cinco), 1) as cinco, ROUND(AVG(ata_sessao_seis), 1) as seis,
                ROUND(AVG(ata_sessao_sete), 1) as sete, ROUND(AVG(ata_sessao_oito), 1) as oito,
                ROUND(AVG(ata_sessao_nove), 1) as nove, ROUND(AVG(ata_sessao_dez), 1) as dez,
                ROUND(AVG(ata_sessao_onze), 1) as onze, ROUND(AVG(ata_sessao_doze), 1) as doze,
                ROUND(AVG(ata_sessao_treze), 1) as treze, ROUND(AVG(ata_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ata_sessao_quinze), 1) as quinze, ROUND(AVG(ata_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ata_sessao_dezessete), 1) as dezessete, ROUND(AVG(ata_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ata_sessao_dezenove), 1) as dezenove, ROUND(AVG(ata_sessao_vinte), 1) as vinte
                FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1 
                AND ata_data_preenchida >= DATE_SUB(NOW(), INTERVAL 180 DAY)";

                $fetch = $helper->select($select, 2);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR
                $avgs[1] = $fetch['um'];
                $avgs[2] = $fetch['dois'];
                $avgs[3] = $fetch['tres'];
                $avgs[4] = $fetch['quatro'];
                $avgs[5] = $fetch['cinco'];
                $avgs[6] = $fetch['seis'];
                $avgs[7] = $fetch['sete'];
                $avgs[8] = $fetch['oito'];
                $avgs[9] = $fetch['nove'];
                $avgs[10] = $fetch['dez'];
                $avgs[11] = $fetch['onze'];
                $avgs[12] = $fetch['doze'];
                $avgs[13] = $fetch['treze'];
                $avgs[14] = $fetch['quatorze'];
                $avgs[15] = $fetch['quinze'];
                $avgs[16] = $fetch['dezesseis'];
                $avgs[17] = $fetch['dezessete'];
                $avgs[18] = $fetch['dezoito'];
                $avgs[19] = $fetch['dezenove'];
                $avgs[20] = $fetch['vinte'];

                return $avgs;

        }

        function calcularMediasLongoPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ata_sessao_um), 1) as um, ROUND(AVG(ata_sessao_dois), 1) as dois,
                ROUND(AVG(ata_sessao_tres), 1) as tres, ROUND(AVG(ata_sessao_quatro), 1) as quatro,
                ROUND(AVG(ata_sessao_cinco), 1) as cinco, ROUND(AVG(ata_sessao_seis), 1) as seis,
                ROUND(AVG(ata_sessao_sete), 1) as sete, ROUND(AVG(ata_sessao_oito), 1) as oito,
                ROUND(AVG(ata_sessao_nove), 1) as nove, ROUND(AVG(ata_sessao_dez), 1) as dez,
                ROUND(AVG(ata_sessao_onze), 1) as onze, ROUND(AVG(ata_sessao_doze), 1) as doze,
                ROUND(AVG(ata_sessao_treze), 1) as treze, ROUND(AVG(ata_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ata_sessao_quinze), 1) as quinze, ROUND(AVG(ata_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ata_sessao_dezessete), 1) as dezessete, ROUND(AVG(ata_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ata_sessao_dezenove), 1) as dezenove, ROUND(AVG(ata_sessao_vinte), 1) as vinte
                FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpfColaborador' AND ata_preenchida = 1 
                AND ata_data_preenchida >= DATE_SUB(NOW(), INTERVAL 365 DAY)";

                $fetch = $helper->select($select, 2);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR
                $avgs[1] = $fetch['um'];
                $avgs[2] = $fetch['dois'];
                $avgs[3] = $fetch['tres'];
                $avgs[4] = $fetch['quatro'];
                $avgs[5] = $fetch['cinco'];
                $avgs[6] = $fetch['seis'];
                $avgs[7] = $fetch['sete'];
                $avgs[8] = $fetch['oito'];
                $avgs[9] = $fetch['nove'];
                $avgs[10] = $fetch['dez'];
                $avgs[11] = $fetch['onze'];
                $avgs[12] = $fetch['doze'];
                $avgs[13] = $fetch['treze'];
                $avgs[14] = $fetch['quatorze'];
                $avgs[15] = $fetch['quinze'];
                $avgs[16] = $fetch['dezesseis'];
                $avgs[17] = $fetch['dezessete'];
                $avgs[18] = $fetch['dezoito'];
                $avgs[19] = $fetch['dezenove'];
                $avgs[20] = $fetch['vinte'];

                return $avgs;

        }

        function setID($ID) {
            $this->ID = $ID;
        }

        function getID() {
            return $this->ID;
        }

        function setDataCriacao($dataCriacao) {
            $this->dataCriacao = $dataCriacao;
        }

        function getDataCriacao() {
            return $this->dataCriacao;
        }

        function setDataPreenchida($dataPreenchida) {
            $this->dataPreenchida = $dataPreenchida;
        }

        function getDataPreenchida() {
            return $this->dataPreenchida;
        }

        function setVisualizada($visualizada) {
            $this->visualizada = $visualizada;
        }

        function getVisualizada() {
            return $this->visualizada;
        }

        //UM

        function setSessaoUm($sessaoUm) {
            $this->sessaoUm = $sessaoUm;
        }

        function getSessaoUm() {
            return $this->sessaoUm;
        }

        function setSessaoUmObs($sessaoUmObs) {
            $this->sessaoUmObs = $sessaoUmObs;
        }

        function getSessaoUmObs() {
            return $this->sessaoUmObs;
        }

        //DOIS
        
        function setSessaoDois($sessaoDois) {
            $this->sessaoDois = $sessaoDois;
        }

        function getSessaoDois() {
            return $this->sessaoDois;
        }

        function setSessaoDoisObs($sessaoDoisObs) {
            $this->sessaoDoisObs = $sessaoDoisObs;
        }

        function getSessaoDoisObs() {
            return $this->sessaoDoisObs;
        }

        //TRES

        function setSessaoTres($sessaoTres) {
            $this->sessaoTres = $sessaoTres;
        }

        function getSessaoTres() {
            return $this->sessaoTres;
        }

        function setSessaoTresObs($sessaoTresObs) {
            $this->sessaoTresObs = $sessaoTresObs;
        }

        function getSessaoTresObs() {
            return $this->sessaoTresObs;
        }

        //QUATRO

        function setSessaoQuatro($sessaoQuatro) {
            $this->sessaoQuatro = $sessaoQuatro;
        }

        function getSessaoQuatro() {
            return $this->sessaoQuatro;
        }

        function setSessaoQuatroObs($sessaoQuatroObs) {
            $this->sessaoQuatroObs = $sessaoQuatroObs;
        }

        function getSessaoQuatroObs() {
            return $this->sessaoQuatroObs;
        }

        //CINCO

        public function getSessaoCinco()
        {
                return $this->sessaoCinco;
        }

        
        public function setSessaoCinco($sessaoCinco)
        {
                $this->sessaoCinco = $sessaoCinco;

                return $this;
        }

        
        public function getSessaoCincoObs()
        {
                return $this->sessaoCincoObs;
        }

        
        public function setSessaoCincoObs($sessaoCincoObs)
        {
                $this->sessaoCincoObs = $sessaoCincoObs;

                return $this;
        }

        //SEIS
        
        public function getSessaoSeis()
        {
                return $this->sessaoSeis;
        }

        
        public function setSessaoSeis($sessaoSeis)
        {
                $this->sessaoSeis = $sessaoSeis;

                return $this;
        }

        
        public function getSessaoSeisObs()
        {
                return $this->sessaoSeisObs;
        }

        
        public function setSessaoSeisObs($sessaoSeisObs)
        {
                $this->sessaoSeisObs = $sessaoSeisObs;

                return $this;
        }

        //SETE
        
        public function getSessaoSete()
        {
                return $this->sessaoSete;
        }

        
        public function setSessaoSete($sessaoSete)
        {
                $this->sessaoSete = $sessaoSete;

                return $this;
        }

         
        public function getSessaoSeteObs()
        {
                return $this->sessaoSeteObs;
        }

         
        public function setSessaoSeteObs($sessaoSeteObs)
        {
                $this->sessaoSeteObs = $sessaoSeteObs;

                return $this;
        }

        //OITO
        
        public function getSessaoOito()
        {
                return $this->sessaoOito;
        }

        
        public function setSessaoOito($sessaoOito)
        {
                $this->sessaoOito = $sessaoOito;

                return $this;
        }

        
        public function getSessaoOitoObs()
        {
                return $this->sessaoOitoObs;
        }

        
        public function setSessaoOitoObs($sessaoOitoObs)
        {
                $this->sessaoOitoObs = $sessaoOitoObs;

                return $this;
        }

        //NOVE

        public function getSessaoNove()
        {
                return $this->sessaoNove;
        }

        
        public function setSessaoNove($sessaoNove)
        {
                $this->sessaoNove = $sessaoNove;

                return $this;
        }

        
        public function getSessaoNoveObs()
        {
                return $this->sessaoNoveObs;
        }

         
        public function setSessaoNoveObs($sessaoNoveObs)
        {
                $this->sessaoNoveObs = $sessaoNoveObs;

                return $this;
        }

        //DEZ

        public function getSessaoDez()
        {
                return $this->sessaoDez;
        }

        
        public function setSessaoDez($sessaoDez)
        {
                $this->sessaoDez = $sessaoDez;

                return $this;
        }

        
        public function getSessaoDezObs()
        {
                return $this->sessaoDezObs;
        }

        
        public function setSessaoDezObs($sessaoDezObs)
        {
                $this->sessaoDezObs = $sessaoDezObs;

                return $this;
        }

        //ONZE
        
        public function getSessaoOnze()
        {
                return $this->sessaoOnze;
        }

        
        public function setSessaoOnze($sessaoOnze)
        {
                $this->sessaoOnze = $sessaoOnze;

                return $this;
        }

         
        public function getSessaoOnzeObs()
        {
                return $this->sessaoOnzeObs;
        }

       
        public function setSessaoOnzeObs($sessaoOnzeObs)
        {
                $this->sessaoOnzeObs = $sessaoOnzeObs;

                return $this;
        }

        //DOZE

        public function getSessaoDoze()
        {
                return $this->sessaoDoze;
        }

        
        public function setSessaoDoze($sessaoDoze)
        {
                $this->sessaoDoze = $sessaoDoze;

                return $this;
        }

        
        public function getSessaoDozeObs()
        {
                return $this->sessaoDozeObs;
        }

        
        public function setSessaoDozeObs($sessaoDozeObs)
        {
                $this->sessaoDozeObs = $sessaoDozeObs;

                return $this;
        }

        //TREZE

        public function getSessaoTreze()
        {
                return $this->sessaoTreze;
        }

        
        public function setSessaoTreze($sessaoTreze)
        {
                $this->sessaoTreze = $sessaoTreze;

                return $this;
        }

        
        public function getSessaoTrezeObs()
        {
                return $this->sessaoTrezeObs;
        }

        
        public function setSessaoTrezeObs($sessaoTrezeObs)
        {
                $this->sessaoTrezeObs = $sessaoTrezeObs;

                return $this;
        }

        //QUATORZE
        
        public function getSessaoQuatorze()
        {
                return $this->sessaoQuatorze;
        }

        
        public function setSessaoQuatorze($sessaoQuatorze)
        {
                $this->sessaoQuatorze = $sessaoQuatorze;

                return $this;
        }

        
        public function getSessaoQuatorzeObs()
        {
                return $this->sessaoQuatorzeObs;
        }

        
        public function setSessaoQuatorzeObs($sessaoQuatorzeObs)
        {
                $this->sessaoQuatorzeObs = $sessaoQuatorzeObs;

                return $this;
        }

        //QUINZE

        public function getSessaoQuinze()
        {
                return $this->sessaoQuinze;
        }

        
        public function setSessaoQuinze($sessaoQuinze)
        {
                $this->sessaoQuinze = $sessaoQuinze;

                return $this;
        }

        
        public function getSessaoQuinzeObs()
        {
                return $this->sessaoQuinzeObs;
        }

       
        public function setSessaoQuinzeObs($sessaoQuinzeObs)
        {
                $this->sessaoQuinzeObs = $sessaoQuinzeObs;

                return $this;
        }

        //DEZESSEIS
        
        public function getSessaoDezesseis()
        {
                return $this->sessaoDezesseis;
        }

        
        public function setSessaoDezesseis($sessaoDezesseis)
        {
                $this->sessaoDezesseis = $sessaoDezesseis;

                return $this;
        }

         
        public function getSessaoDezesseisObs()
        {
                return $this->sessaoDezesseisObs;
        }

        
        public function setSessaoDezesseisObs($sessaoDezesseisObs)
        {
                $this->sessaoDezesseisObs = $sessaoDezesseisObs;

                return $this;
        }

        //DEZESSETE
        
        public function getSessaoDezessete()
        {
                return $this->sessaoDezessete;
        }

        
        public function setSessaoDezessete($sessaoDezessete)
        {
                $this->sessaoDezessete = $sessaoDezessete;

                return $this;
        }

        
        public function getSessaoDezesseteObs()
        {
                return $this->sessaoDezesseteObs;
        }

        
        public function setSessaoDezesseteObs($sessaoDezesseteObs)
        {
                $this->sessaoDezesseteObs = $sessaoDezesseteObs;

                return $this;
        }

        //DEZOITO
        
        public function getSessaoDezoito()
        {
                return $this->sessaoDezoito;
        }

        
        public function setSessaoDezoito($sessaoDezoito)
        {
                $this->sessaoDezoito = $sessaoDezoito;

                return $this;
        }

        public function setSessaoDezoitoObs($sessaoDezoitoObs)
        {
                $this->sessaoDezoitoObs = $sessaoDezoitoObs;

                return $this;
        }

        public function getSessaoDezoitoObs()
        {
                return $this->sessaoDezoitoObs;
        }

        //DEZENOVE

        public function getSessaoDezenove()
        {
                return $this->sessaoDezenove;
        }

         
        public function setSessaoDezenove($sessaoDezenove)
        {
                $this->sessaoDezenove = $sessaoDezenove;

                return $this;
        }
        
        public function getSessaoDezenoveObs()
        {
                return $this->sessaoDezenoveObs;
        }

        
        public function setSessaoDezenoveObs($sessaoDezenoveObs)
        {
                $this->sessaoDezenoveObs = $sessaoDezenoveObs;

                return $this;
        }

        //VINTE
        
        public function getSessaoVinte()
        {
                return $this->sessaoVinte;
        }

         
        public function setSessaoVinte($sessaoVinte)
        {
                $this->sessaoVinte = $sessaoVinte;

                return $this;
        }

        
        public function getSessaoVinteObs()
        {
                return $this->sessaoVinteObs;
        }

        public function setSessaoVinteObs($sessaoVinteObs)
        {
                $this->sessaoVinteObs = $sessaoVinteObs;

                return $this;
        }

        function setCpfGestor($cpfGestor) {
            $this->cpfGestor = $cpfGestor;
        }

        function getCpfGestor() {
            return $this->cpfGestor;
        }

        function setCpfColaborador($cpfColaborador) {
            $this->cpfColaborador = $cpfColaborador;
        }

        function getCpfColaborador() {
            return $this->cpfColaborador;
        }


        /**
         * Get the value of preenchida
         */ 
        public function getPreenchida()
        {
                return $this->preenchida;
        }

        /**
         * Set the value of preenchida
         *
         * @return  self
         */ 
        public function setPreenchida($preenchida)
        {
                $this->preenchida = $preenchida;

                return $this;
        }
    }

?>