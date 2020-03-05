<?php

    class Feedback {

        private $ID;
        private $texto;
        private $fee_cpf;
        private $ges_cpf;
        private $col_cpf;
        private $dataCriacao;
        private $remetente;


        public function cadastrar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_feedback (fee_texto, fee_cpf, ges_cpf, col_cpf) 
            VALUES ('$this->texto', '$this->fee_cpf', '$this->ges_cpf', '$this->col_cpf')";

            $helper->insert($insert);

        }

        public function retornarFeedback($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT fee_id as id, fee_texto as texto, 
            DATE_FORMAT(fee_criacao, '%d/%m/%Y %H:%i') as criacao,
            fee_cpf as cpf, ges_cpf, col_cpf 
            FROM tbl_feedback WHERE fee_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $feedback = new Feedback();
            $feedback->setID($fetch['id']);
            $feedback->setTexto($fetch['texto']);
            $feedback->setDataCriacao($fetch['criacao']);
            $feedback->setFee_cpf($fetch['cpf']);
            $feedback->setGes_cpf($fetch['ges_cpf']);
            $feedback->setCol_cpf($fetch['col_cpf']);
            $ges_cpf = $fetch['ges_cpf'];
            $col_cpf = $fetch['col_cpf'];
            $select = "SELECT ges_nome_completo as nome FROM tbl_gestor WHERE ges_cpf = '$ges_cpf'";
            $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) {
                        $select = "SELECT col_nome_completo as nome FROM tbl_colaborador WHERE col_cpf = '$col_cpf'";
                        $fetch = $helper->select($select, 2);
                        $feedback->setRemetente($fetch['nome']);
                } else {
                        $fetch = mysqli_fetch_assoc($query);
                        $feedback->setRemetente($fetch['nome']);
                }

            return $feedback;

        }

        public function retornarUltimo($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT fee_id as id FROM tbl_feedback ORDER BY fee_id DESC LIMIT 1";

            $fetch = $helper->select($select, 2);

            return $fetch['id'];

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
         * Get the value of texto
         */ 
        public function getTexto()
        {
                return $this->texto;
        }

        /**
         * Set the value of texto
         *
         * @return  self
         */ 
        public function setTexto($texto)
        {
                $this->texto = $texto;

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
         * Get the value of fee_cpf
         */ 
        public function getFee_cpf()
        {
                return $this->fee_cpf;
        }

        /**
         * Set the value of fee_cpf
         *
         * @return  self
         */ 
        public function setFee_cpf($fee_cpf)
        {
                $this->fee_cpf = $fee_cpf;

                return $this;
        }

        /**
         * Get the value of ges_cpf
         */ 
        public function getGes_cpf()
        {
                return $this->ges_cpf;
        }

        /**
         * Set the value of ges_cpf
         *
         * @return  self
         */ 
        public function setGes_cpf($ges_cpf)
        {
                $this->ges_cpf = $ges_cpf;

                return $this;
        }

        /**
         * Get the value of col_cpf
         */ 
        public function getCol_cpf()
        {
                return $this->col_cpf;
        }

        /**
         * Set the value of col_cpf
         *
         * @return  self
         */ 
        public function setCol_cpf($col_cpf)
        {
                $this->col_cpf = $col_cpf;

                return $this;
        }

        /**
         * Get the value of remetente
         */ 
        public function getRemetente()
        {
                return $this->remetente;
        }

        /**
         * Set the value of remetente
         *
         * @return  self
         */ 
        public function setRemetente($remetente)
        {
                $this->remetente = $remetente;

                return $this;
        }
    }

?>