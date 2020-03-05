<?php

    class AvaliacaoGestao {

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
        private $sessaoSete;
        private $sessaoSeteObs;
        private $sessaoOito;
        private $sessaoOitoObs;
        private $sessaoNove;
        private $sessaoNoveObs;
        private $sessaoDez;
        private $sessaoDezObs;
        private $dataCriacao;
        private $cpfGestor;
        private $setorID;
        private $userID;
        private $codigo;

        function salvar($database_empresa) {

                require_once("class_conexao_empresa.php");
                require_once("class_queryHelper.php");

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $insert = "INSERT INTO tbl_avaliacao_gestao (avg_sessao_um, avg_sessao_um_obs,
                avg_sessao_dois, avg_sessao_dois_obs, avg_sessao_tres, avg_sessao_tres_obs,
                avg_sessao_quatro, avg_sessao_quatro_obs, avg_sessao_cinco, avg_sessao_cinco_obs,
                avg_sessao_seis, avg_sessao_seis_obs, avg_sessao_sete, avg_sessao_sete_obs,
                avg_sessao_oito, avg_sessao_oito_obs, avg_sessao_nove, avg_sessao_nove_obs,
                avg_sessao_dez, avg_sessao_dez_obs, ges_cpf, set_id, usu_id, cod_string) VALUES 
                ('$this->sessaoUm', '$this->sessaoUmObs', '$this->sessaoDois', '$this->sessaoDoisObs',
                '$this->sessaoTres', '$this->sessaoTresObs', '$this->sessaoQuatro', '$this->sessaoQuatroObs',
                '$this->sessaoCinco', '$this->sessaoCincoObs', '$this->sessaoSeis', '$this->sessaoSeisObs',
                '$this->sessaoSete', '$this->sessaoSeteObs', '$this->sessaoOito', '$this->sessaoOitoObs',
                '$this->sessaoNove', '$this->sessaoNoveObs', '$this->sessaoDez', '$this->sessaoDezObs',
                '$this->cpfGestor', '$this->setorID', '$this->userID', '$this->codigo')";

                if($helper->insert($insert)) return true;
                else false;

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

        public function getCpfGestor()
        {
                return $this->cpfGestor;
        }

        public function setCpfGestor($cpfGestor)
        {
                $this->cpfGestor = $cpfGestor;

                return $this;
        }

        public function getSetorID()
        {
                return $this->setorID;
        }

        public function setSetorID($setorID)
        {
                $this->setorID = $setorID;

                return $this;
        }

        /**
         * Get the value of userID
         */ 
        public function getUserID()
        {
                return $this->userID;
        }

        /**
         * Set the value of userID
         *
         * @return  self
         */ 
        public function setUserID($userID)
        {
                $this->userID = $userID;

                return $this;
        }

        /**
         * Get the value of codigo
         */ 
        public function getCodigo()
        {
                return $this->codigo;
        }

        /**
         * Set the value of codigo
         *
         * @return  self
         */ 
        public function setCodigo($codigo)
        {
                $this->codigo = $codigo;

                return $this;
        }
    }

?>