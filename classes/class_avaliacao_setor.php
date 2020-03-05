<?php

    class AvaliacaoSetor {

        private $ID;
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
        // private $sessaoSete; //AINDA USA SOMENTE SEIS
        // private $sessaoSeteObs;
        // private $sessaoOito;
        // private $sessaoOitoObs;
        // private $sessaoNove;
        // private $sessaoNoveObs;
        // private $sessaoDez;
        // private $sessaoDezObs;
        private $dataCriacao;
        private $cpf;
        private $IDSetor;

        function salvar($database_empresa) {

                require_once("class_conexao_empresa.php");
                require_once("class_queryHelper.php");

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $insert = "INSERT INTO tbl_avaliacao_setor (
                um, um_obs,
                dois, dois_obs,
                tres, tres_obs,
                quatro, quatro_obs, 
                cinco, cinco_obs,
                seis, seis_obs, 
                cpf, set_id) VALUES 
                ('$this->sessaoUm', '$this->sessaoUmObs', 
                '$this->sessaoDois', '$this->sessaoDoisObs',
                '$this->sessaoTres', '$this->sessaoTresObs', 
                '$this->sessaoQuatro', '$this->sessaoQuatroObs',
                '$this->sessaoCinco', '$this->sessaoCincoObs', 
                '$this->sessaoSeis', '$this->sessaoSeisObs',
                '$this->cpf', '$this->IDSetor')";

                if($helper->insert($insert)) return true;
                else false;

        }

        function retornarAvaliacaoSetor($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT set_id as id, um, dois, tres, quatro, cinco, seis,
            um_obs, dois_obs, tres_obs, quatro_obs, cinco_obs, seis_obs, cpf,
            DATE_FORMAT(avs_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao
            FROM tbl_avaliacao_setor WHERE set_id = '$this->IDSetor'";

            $fetch = $helper->select($select, 2);

            $avaliacao = new AvaliacaoSetor();
            $avaliacao->setIDSetor($fetch['id']);
            $avaliacao->setSessaoUm($fetch['um']);
            $avaliacao->setSessaoDois($fetch['dois']);
            $avaliacao->setSessaoTres($fetch['tres']);
            $avaliacao->setSessaoQuatro($fetch['quatro']);
            $avaliacao->setSessaoCinco($fetch['cinco']);
            $avaliacao->setSessaoSeis($fetch['seis']);
            $avaliacao->setSessaoUmObs($fetch['um_obs']);
            $avaliacao->setSessaoDoisObs($fetch['dois_obs']);
            $avaliacao->setSessaoTresObs($fetch['tres_obs']);
            $avaliacao->setSessaoQuatroObs($fetch['quatro_obs']);
            $avaliacao->setSessaoCincoObs($fetch['cinco_obs']);
            $avaliacao->setSessaoSeisObs($fetch['seis_obs']);
            $avaliacao->setDataCriacao($fetch['criacao']);
            $avaliacao->setCpf($fetch['cpf']);

            return $avaliacao;
        
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

        
        // public function getSessaoSete()
        // {
        //         return $this->sessaoSete;
        // }

        
        // public function setSessaoSete($sessaoSete)
        // {
        //         $this->sessaoSete = $sessaoSete;

        //         return $this;
        // }

         
        // public function getSessaoSeteObs()
        // {
        //         return $this->sessaoSeteObs;
        // }

         
        // public function setSessaoSeteObs($sessaoSeteObs)
        // {
        //         $this->sessaoSeteObs = $sessaoSeteObs;

        //         return $this;
        // }

        
        // public function getSessaoOito()
        // {
        //         return $this->sessaoOito;
        // }

        
        // public function setSessaoOito($sessaoOito)
        // {
        //         $this->sessaoOito = $sessaoOito;

        //         return $this;
        // }

        
        // public function getSessaoOitoObs()
        // {
        //         return $this->sessaoOitoObs;
        // }

        
        // public function setSessaoOitoObs($sessaoOitoObs)
        // {
        //         $this->sessaoOitoObs = $sessaoOitoObs;

        //         return $this;
        // }

        
        // public function getSessaoNove()
        // {
        //         return $this->sessaoNove;
        // }

        
        // public function setSessaoNove($sessaoNove)
        // {
        //         $this->sessaoNove = $sessaoNove;

        //         return $this;
        // }

        
        // public function getSessaoNoveObs()
        // {
        //         return $this->sessaoNoveObs;
        // }

         
        // public function setSessaoNoveObs($sessaoNoveObs)
        // {
        //         $this->sessaoNoveObs = $sessaoNoveObs;

        //         return $this;
        // }

        
        // public function getSessaoDez()
        // {
        //         return $this->sessaoDez;
        // }

        
        // public function setSessaoDez($sessaoDez)
        // {
        //         $this->sessaoDez = $sessaoDez;

        //         return $this;
        // }

        
        // public function getSessaoDezObs()
        // {
        //         return $this->sessaoDezObs;
        // }

        
        // public function setSessaoDezObs($sessaoDezObs)
        // {
        //         $this->sessaoDezObs = $sessaoDezObs;

        //         return $this;
        // }


        /**
         * Get the value of cpf
         */ 
        public function getCpf()
        {
                return $this->cpf;
        }

        /**
         * Set the value of cpf
         *
         * @return  self
         */ 
        public function setCpf($cpf)
        {
                $this->cpf = $cpf;

                return $this;
        }

        /**
         * Get the value of IDSetor
         */ 
        public function getIDSetor()
        {
                return $this->IDSetor;
        }

        /**
         * Set the value of IDSetor
         *
         * @return  self
         */ 
        public function setIDSetor($IDSetor)
        {
                $this->IDSetor = $IDSetor;

                return $this;
        }
    }

?>