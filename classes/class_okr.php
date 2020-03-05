<?php

    class OKR {

        private $ID;
        private $titulo;
        private $descricao;
        private $tipo;
        private $visivel;
        private $goalMoney;
        private $goalNumber;
        private $prazo;
        private $dataCriacao;
        private $cpfGestor;

        function salvar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_okr (okr_titulo, okr_descricao, okr_tipo, okr_visivel, okr_goal_money, 
            okr_goal_number, okr_prazo, ges_cpf) VALUES ('$this->titulo', '$this->descricao', '$this->tipo', '$this->visivel', 
            '$this->goalMoney', '$this->goalNumber', '$this->prazo', '$this->cpfGestor')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function atualizar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_okr SET okr_titulo = '$this->titulo', okr_descricao = '$this->descricao',
            okr_tipo = '$this->tipo', okr_visivel = '$this->visivel', okr_goal_money = '$this->goalMoney', 
            okr_goal_number = '$this->goalNumber', okr_prazo = '$this->prazo' WHERE okr_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        function retornarOKR($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT okr_id as id, okr_titulo as titulo, okr_descricao as descricao, okr_tipo as tipo,
            okr_visivel as visivel, okr_goal_money as money, okr_goal_number as number, DATE_FORMAT(okr_prazo, '%d/%m/%Y %H:%i:%s') as prazo, ges_cpf as gestor, 
            DATE_FORMAT(okr_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao FROM tbl_okr 
            WHERE okr_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $okr = new OKR();
            $okr->setID($fetch['id']);
            $okr->setTitulo($fetch['titulo']);
            $okr->setDescricao($fetch['descricao']);
            $okr->setTipo($fetch['tipo']);
            $okr->setVisivel($fetch['visivel']);
            $okr->setGoalMoney($fetch['money']);
            $okr->setGoalNumber($fetch['number']);
            $okr->setPrazo($fetch['prazo']);
            $okr->setDataCriacao($fetch['criacao']);
            $okr->setCpfGestor($fetch['gestor']);
            
            return $okr;

        }

        public function retornarUltima($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT okr_id as id FROM tbl_okr ORDER BY okr_id DESC LIMIT 1";
    
                $fetch = $helper->select($select, 2);
    
                return $fetch['id'];
    
            }

            public function isAutorizado($database_empresa, $cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT col_cpf FROM tbl_okr_colaborador WHERE col_cpf = '$cpf'";
    
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) > 0) return true;

                $select = "SELECT ges_cpf FROM tbl_okr_gestor WHERE ges_cpf = '$cpf'";
    
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) > 0) return true;
    
                return false;
    
            }

            function popularSelectMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT okr_id as id, okr_titulo as titulo FROM tbl_okr WHERE okr_concluida = 0 ORDER BY okr_data_criacao DESC";
        
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                        echo '<br>Sem metas';
                        return;
                }
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="metas[]" name="metas[]" value='.$f['id'].'> '.$f['titulo'].'<br>';
                }
        
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
        function getTitulo()
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
         * Get the value of descricao
         */ 
        public function getDescricao()
        {
                return $this->descricao;
        }

        /**
         * Set the value of descricao
         *
         * @return  self
         */ 
        public function setDescricao($descricao)
        {
                $this->descricao = $descricao;

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
         * Get the value of visivel
         */ 
        public function getVisivel()
        {
                return $this->visivel;
        }

        /**
         * Set the value of visivel
         *
         * @return  self
         */ 
        public function setVisivel($visivel)
        {
                $this->visivel = $visivel;

                return $this;
        }

        /**
         * Get the value of goalMoney
         */ 
        public function getGoalMoney()
        {
                return $this->goalMoney;
        }

        /**
         * Set the value of goalMoney
         *
         * @return  self
         */ 
        public function setGoalMoney($goalMoney)
        {
                $this->goalMoney = $goalMoney;

                return $this;
        }

        /**
         * Get the value of goalNumber
         */ 
        public function getGoalNumber()
        {
                return $this->goalNumber;
        }

        /**
         * Set the value of goalNumber
         *
         * @return  self
         */ 
        public function setGoalNumber($goalNumber)
        {
                $this->goalNumber = $goalNumber;

                return $this;
        }

        /**
         * Get the value of prazo
         */ 
        public function getPrazo()
        {
                return $this->prazo;
        }

        /**
         * Set the value of prazo
         *
         * @return  self
         */ 
        public function setPrazo($prazo)
        {
                $this->prazo = $prazo;

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
         * Get the value of cpfGestor
         */ 
        public function getCpfGestor()
        {
                return $this->cpfGestor;
        }

        /**
         * Set the value of cpfGestor
         *
         * @return  self
         */ 
        public function setCpfGestor($cpfGestor)
        {
                $this->cpfGestor = $cpfGestor;

                return $this;
        }

    }

?>