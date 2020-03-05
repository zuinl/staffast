<?php

    class Avaliacao {

        private $ID;
        private $dataCriacao;
        private $dataLiberacao;
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
        private $cpfGestor;
        private $cpfColaborador;

        function cadastrar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_avaliacao (ava_data_liberacao, ava_sessao_um, ava_sessao_um_obs, 
            ava_sessao_dois, ava_sessao_dois_obs, 
            ava_sessao_tres, ava_sessao_tres_obs, ava_sessao_quatro, ava_sessao_quatro_obs, 
            ava_sessao_cinco, ava_sessao_cinco_obs, ava_sessao_seis, ava_sessao_seis_obs,
            ava_sessao_sete, ava_sessao_sete_obs, ava_sessao_oito, ava_sessao_oito_obs,
            ava_sessao_nove, ava_sessao_nove_obs, ava_sessao_dez, ava_sessao_dez_obs,
            ava_sessao_onze, ava_sessao_onze_obs, ava_sessao_doze, ava_sessao_doze_obs,
            ava_sessao_treze, ava_sessao_treze_obs, ava_sessao_quatorze, ava_sessao_quatorze_obs,
            ava_sessao_quinze, ava_sessao_quinze_obs, ava_sessao_dezesseis, ava_sessao_dezesseis_obs,
            ava_sessao_dezessete, ava_sessao_dezessete_obs, ava_sessao_dezoito, ava_sessao_dezoito_obs,
            ava_sessao_dezenove, ava_sessao_dezenove_obs, ava_sessao_vinte, ava_sessao_vinte_obs,
            ges_cpf, col_cpf) VALUES 
            (DATE_ADD(NOW(), INTERVAL 30 DAY), '$this->sessaoUm', '$this->sessaoUmObs', '$this->sessaoDois', 
            '$this->sessaoDoisObs', '$this->sessaoTres', '$this->sessaoTresObs', '$this->sessaoQuatro', '$this->sessaoQuatroObs',
            '$this->sessaoCinco', '$this->sessaoCincoObs', '$this->sessaoSeis', '$this->sessaoSeisObs',
            '$this->sessaoSete', '$this->sessaoSeteObs', '$this->sessaoOito', '$this->sessaoOitoObs',
            '$this->sessaoNove', '$this->sessaoNoveObs', '$this->sessaoDez', '$this->sessaoDezObs',
            '$this->sessaoOnze', '$this->sessaoOnzeObs', '$this->sessaoDoze', '$this->sessaoDozeObs',
            '$this->sessaoTreze', '$this->sessaoTrezeObs', '$this->sessaoQuatorze', '$this->sessaoQuatorzeObs',
            '$this->sessaoQuinze', '$this->sessaoQuinzeObs', '$this->sessaoDezesseis', '$this->sessaoDezesseisObs',
            '$this->sessaoDezessete', '$this->sessaoDezesseteObs', '$this->sessaoDezoito', '$this->sessaoDezoitoObs',
            '$this->sessaoDezenove', '$this->sessaoDezenoveObs', '$this->sessaoVinte', '$this->sessaoVinteObs',
            '$this->cpfGestor', '$this->cpfColaborador')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function liberarAgora($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_avaliacao SET ava_data_liberacao = NOW() WHERE col_cpf = '$this->cpfColaborador'";

            if($helper->update($update)) return true;
            else return false;

        }

        function setarVisualizada($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_avaliacao SET ava_visualizada = 1 WHERE ava_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        function isLiberada($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ava_id as id FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND 
            ava_data_liberacao > NOW()";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return true;
            else return false;

        }

        function retornarAvaliacao($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ava_id as id, DATE_FORMAT(ava_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao, 
            DATE_FORMAT(ava_data_liberacao, '%d/%m/%Y %H:%i:%s') as liberacao, 
            ava_sessao_um as s1, ava_sessao_um_obs as s1_obs, 
            ava_sessao_dois as s2, ava_sessao_dois_obs as s2_obs,
            ava_sessao_tres as s3, ava_sessao_tres_obs as s3_obs, 
            ava_sessao_quatro as s4, ava_sessao_quatro_obs as s4_obs, 
            ava_sessao_cinco as s5, ava_sessao_cinco_obs as s5_obs, 
            ava_sessao_seis as s6, ava_sessao_seis_obs as s6_obs, 
            ava_sessao_sete as s7, ava_sessao_sete_obs as s7_obs, 
            ava_sessao_oito as s8, ava_sessao_oito_obs as s8_obs, 
            ava_sessao_nove as s9, ava_sessao_nove_obs as s9_obs, 
            ava_sessao_dez as s10, ava_sessao_dez_obs as s10_obs, 
            ava_sessao_onze as s11, ava_sessao_onze_obs as s11_obs, 
            ava_sessao_doze as s12, ava_sessao_doze_obs as s12_obs, 
            ava_sessao_treze as s13, ava_sessao_treze_obs as s13_obs, 
            ava_sessao_quatorze as s14, ava_sessao_quatorze_obs as s14_obs, 
            ava_sessao_quinze as s15, ava_sessao_quinze_obs as s15_obs, 
            ava_sessao_dezesseis as s16, ava_sessao_dezesseis_obs as s16_obs, 
            ava_sessao_dezessete as s17, ava_sessao_dezessete_obs as s17_obs, 
            ava_sessao_dezoito as s18, ava_sessao_dezoito_obs as s18_obs, 
            ava_sessao_dezenove as s19, ava_sessao_dezenove_obs as s19_obs, 
            ava_sessao_vinte as s20, ava_sessao_vinte_obs as s20_obs, 
            ges_cpf as ges, col_cpf as col FROM tbl_avaliacao WHERE ava_id = '$this->ID'";

            $fetch = $helper->select($select, 2);
            $ava = new Avaliacao();
            $ava->setID($fetch['id']);
            $ava->setDataCriacao($fetch['criacao']);
            $ava->setDataLiberacao($fetch['liberacao']);
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
            $ava->setCpfGestor($fetch['ges']);
            $ava->setCpfColaborador($fetch['col']);

            return $ava;

        }

        function retornarUltimaComGestor($database_empresa){

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT DATE_FORMAT(t1.ava_data_criacao, '%d/%m/%Y %H:%i') as criacao, t2.ges_primeiro_nome 
            as gestor FROM tbl_avaliacao t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf WHERE t1.col_cpf = 
            '$this->cpfColaborador' AND t1.ava_data_liberacao <= NOW() ORDER BY t1.ava_data_criacao DESC LIMIT 1";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return 'Não há avaliações';

            $fetch = mysqli_fetch_assoc($query);

            $data = $fetch['criacao'];
            $gestor = $fetch['gestor'];

            return $data.' por '.$gestor;

        }

        function retornarUltimaLiberada($database_empresa){

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT DATE_FORMAT(ava_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao
            FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao < NOW()
            ORDER BY ava_data_criacao DESC LIMIT 1";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) return 'Não há avaliações';

            $fetch = mysqli_fetch_assoc($query);

            $data = $fetch['criacao'];

            return $data;

        }

        function quantidadeAvaliacoesLiberadas($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT COUNT(ava_id) as total FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' 
            AND ava_data_liberacao <= NOW()";

            $fetch = $helper->select($select, 2);

            return $fetch['total'];

        }

        function isAutorizado($database_empresa, $ges_cpf, $col_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT * FROM tbl_gestor_funcionario WHERE ges_cpf = '$ges_cpf' AND col_cpf = '$col_cpf'";
                $query = $helper->select($select, 1);
    
                if(mysqli_num_rows($query) == 0) return false;
                else return true;
    
            }
        
        function retornarRanking($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE ava_data_liberacao <= NOW()";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMedias($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn); 

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW()";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasCurtoPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasMedioPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 90 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasCurtoMedioPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 180 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasLongoPrazo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 365 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasQuinzena($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 15 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

                return $avgs;

        }

        function calcularMediasSemana($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT ROUND(AVG(ava_sessao_um), 1) as um, ROUND(AVG(ava_sessao_dois), 1) as dois,
                ROUND(AVG(ava_sessao_tres), 1) as tres, ROUND(AVG(ava_sessao_quatro), 1) as quatro,
                ROUND(AVG(ava_sessao_cinco), 1) as cinco, ROUND(AVG(ava_sessao_seis), 1) as seis,
                ROUND(AVG(ava_sessao_sete), 1) as sete, ROUND(AVG(ava_sessao_oito), 1) as oito,
                ROUND(AVG(ava_sessao_nove), 1) as nove, ROUND(AVG(ava_sessao_dez), 1) as dez,
                ROUND(AVG(ava_sessao_onze), 1) as onze, ROUND(AVG(ava_sessao_doze), 1) as doze,
                ROUND(AVG(ava_sessao_treze), 1) as treze, ROUND(AVG(ava_sessao_quatorze), 1) as quatorze,
                ROUND(AVG(ava_sessao_quinze), 1) as quinze, ROUND(AVG(ava_sessao_dezesseis), 1) as dezesseis,
                ROUND(AVG(ava_sessao_dezessete), 1) as dezessete, ROUND(AVG(ava_sessao_dezoito), 1) as dezoito,
                ROUND(AVG(ava_sessao_dezenove), 1) as dezenove, ROUND(AVG(ava_sessao_vinte), 1) as vinte
                FROM tbl_avaliacao WHERE col_cpf = '$this->cpfColaborador' AND ava_data_liberacao <= NOW() 
                AND ava_data_criacao >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

                $query = $helper->select($select, 1);

                $avgs = array(); //REGRAS: POSIÇÃO 0 SEMPRE SERÁ 0, AS POSIÇÃO REPRESENTEM AS SESSÕES (1 A 20)
                $avgs[0] = 0.0; //NUNCA USAR

                $avgs[1] = 0;
                $avgs[2] = 0;
                $avgs[3] = 0;
                $avgs[4] = 0;
                $avgs[5] = 0;
                $avgs[6] = 0;
                $avgs[7] = 0;
                $avgs[8] = 0;
                $avgs[9] = 0;
                $avgs[10] = 0;
                $avgs[11] = 0;
                $avgs[12] = 0;
                $avgs[13] = 0;
                $avgs[14] = 0;
                $avgs[15] = 0;
                $avgs[16] = 0;
                $avgs[17] = 0;
                $avgs[18] = 0;
                $avgs[19] = 0;
                $avgs[20] = 0;

                if(mysqli_num_rows($query) > 0) {
                        $fetch = mysqli_fetch_assoc($query);
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
                }

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

        function setDataLiberacao($dataLiberacao) {
            $this->dataLiberacao = $dataLiberacao;
        }

        function getDataLiberacao() {
            return $this->dataLiberacao;
        }

        function setVisualizada($visualizada) {
            $this->visualizada = $visualizada;
        }

        function getVisualizada() {
            return $this->visualizada;
        }

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

    }

?>