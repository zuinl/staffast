<?php

    class KeyResult {

        private $ID;
        private $titulo;
        private $tipo;
        private $goal;
        private $current;
        private $ultimaAtualizacao;
        private $dataCriacao;
        private $IDOKR;

        function salvar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_key_result (krs_tipo, krs_titulo, krs_goal, okr_id)
            VALUES ('$this->tipo', '$this->titulo', '$this->goal', '$this->IDOKR')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function atualizar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_key_result SET krs_titulo = '$this->titulo', krs_tipo = '$this->tipo', 
            krs_goal = '$this->goal', krs_current = '$this->current', krs_ultima_atualizacao = NOW() 
            WHERE krs_id = $this->ID";

            if($helper->update($update)) return true;
            else return false;

        }

        function excluir($database_empresa) {

                require_once("class_conexao_empresa.php");
                require_once("class_queryHelper.php");
    
                $conexao = new ConexaoEmpresa($database_empresa);
                    $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);
    
                $update = "DELETE FROM tbl_key_result WHERE krs_id = $this->ID";
    
                if($helper->update($update)) return true;
                else return false;
    
            }

        function retornarKeyResult($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT krs_id as id, krs_titulo as titulo, krs_tipo as tipo,
            krs_goal as goal, krs_current as current, okr_id as okr,  
            DATE_FORMAT(krs_data_criacao, '%d/%m/%Y %H:%i') as criacao,
            DATE_FORMAT(krs_ultima_atualizacao, '%d/%m/%Y %H:%i') as atualizacao 
            FROM tbl_key_result WHERE krs_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $this->setID($fetch['id']);
            $this->setTitulo($fetch['titulo']);
            $this->setTipo($fetch['tipo']);
            $this->setCurrent($fetch['current']);
            $this->setGoal($fetch['goal']);
            $this->setIDOKR($fetch['okr']);
            $this->setUltimaAtualizacao($fetch['atualizacao']);
            $this->setDataCriacao($fetch['criacao']);
            
            return $this;

        }


        function upgrade($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_key_result SET krs_current = (krs_current + '$this->current') WHERE krs_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        // Função de porcentagem: N é X% de N
        function porcentagem () {
                $porcentagem = (($this->current * 100 ) / $this->goal);

                if($porcentagem < 20) {
                        $msg = '<span style="color: red">Ainda um pouco longe</span>';
                } else if ($porcentagem >= 20 && $porcentagem < 40) {
                        $msg = '<span style="color: orange">Está avançando</span>';
                } else if ($porcentagem >= 40 && $porcentagem < 60) {
                        $msg = '<span style="color: blue">Meio caminho andado!</span>';
                } else if ($porcentagem >= 60 && $porcentagem < 100) {
                        $msg = '<span style="color: green">A gente vai conseguir!</span>';
                } else if ($porcentagem >= 100) {
                        $msg = '<span style="color: green">Yeeeey! Arrasamos!</span>';
                }

                $porcentagem = number_format($porcentagem, 1, ',', '');

                return $porcentagem.'% '.$msg;
                
        }


        /**
         * Get the value of ID
         */ 
        public function getID()
        {
                return $this->ID;
        }

        /**
         * Set the value of ID
         *
         * @return  self
         */ 
        public function setID($ID)
        {
                $this->ID = $ID;

                return $this;
        }

        /**
         * Get the value of titulo
         */ 
        public function getTitulo()
        {
                return $this->titulo;
        }

        /**
         * Set the value of titulo
         *
         * @return  self
         */ 
        public function setTitulo($titulo)
        {
                $this->titulo = $titulo;

                return $this;
        }

        /**
         * Get the value of tipo
         */ 
        public function getTipo()
        {
                return $this->tipo;
        }

        /**
         * Set the value of tipo
         *
         * @return  self
         */ 
        public function setTipo($tipo)
        {
                $this->tipo = $tipo;

                return $this;
        }
        /**
         * Get the value of dataCriacao
         */ 
        public function getDataCriacao()
        {
                return $this->dataCriacao;
        }

        /**
         * Set the value of dataCriacao
         *
         * @return  self
         */ 
        public function setDataCriacao($dataCriacao)
        {
                $this->dataCriacao = $dataCriacao;

                return $this;
        }

        /**
         * Get the value of goal
         */ 
        public function getGoal()
        {
                return $this->goal;
        }

        /**
         * Set the value of goal
         *
         * @return  self
         */ 
        public function setGoal($goal)
        {
                $this->goal = $goal;

                return $this;
        }

        /**
         * Get the value of current
         */ 
        public function getCurrent()
        {
                return $this->current;
        }

        /**
         * Set the value of current
         *
         * @return  self
         */ 
        public function setCurrent($current)
        {
                $this->current = $current;

                return $this;
        }

        /**
         * Get the value of ultimaAtualizacao
         */ 
        public function getUltimaAtualizacao()
        {
                return $this->ultimaAtualizacao;
        }

        /**
         * Set the value of ultimaAtualizacao
         *
         * @return  self
         */ 
        public function setUltimaAtualizacao($ultimaAtualizacao)
        {
                $this->ultimaAtualizacao = $ultimaAtualizacao;

                return $this;
        }

        /**
         * Get the value of IDOKR
         */ 
        public function getIDOKR()
        {
                return $this->IDOKR;
        }

        /**
         * Set the value of IDOKR
         *
         * @return  self
         */ 
        public function setIDOKR($IDOKR)
        {
                $this->IDOKR = $IDOKR;

                return $this;
        }
    }

?>