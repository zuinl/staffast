<?php

    class Feedback {

        private $ID;
        private $texto;
        private $comecar;
        private $continuar;
        private $parar;
        private $fee_cpf;
        private $ges_cpf;
        private $col_cpf;
        private $dataCriacao;
        private $remetente;
        private $visualizado;


        public function cadastrar($database_empresa, $id_pedido = 0) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_feedback (fee_texto, fee_comecar, fee_continuar, 
            fee_parar, fee_cpf, ges_cpf, col_cpf) 
            VALUES ('$this->texto', '$this->comecar', '$this->continuar', '$this->parar', 
            '$this->fee_cpf', '$this->ges_cpf', '$this->col_cpf')";

            if($helper->insert($insert)) {
                if($id_pedido != 0) $this->atualizarPedido($database_empresa, $id_pedido);
                else return true;
            } else {
                return false;    
            }

        }

        public function atualizarPedido($database_empresa, $id_pedido) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);

                $fee_id = $this->retornarUltimo($database_empresa);
    
                $update = "UPDATE tbl_feedback_pedido SET fee_id = $fee_id WHERE id = $id_pedido";
    
                if($helper->update($update)) return true;
                else return false;
    
            }

        public function retornarFeedback($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT fee_id as id, fee_texto as texto,
            fee_comecar as comecar, fee_continuar as continuar, fee_parar as parar, 
            DATE_FORMAT(fee_criacao, '%d/%m/%Y %H:%i') as criacao,
            fee_cpf as cpf, ges_cpf, col_cpf 
            FROM tbl_feedback WHERE fee_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $feedback = new Feedback();
            $feedback->setID($fetch['id']);
            $feedback->setTexto($fetch['texto']);
            $feedback->setComecar($fetch['comecar']);
            $feedback->setContinuar($fetch['continuar']);
            $feedback->setParar($fetch['parar']);
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

        public function setarVisualizado($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $update = "UPDATE tbl_feedback SET fee_visualizado = 1 WHERE fee_id = $this->ID";
    
                if($fetch = $helper->update($update)) return true;
                else return false;
    
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

        /**
         * Get the value of visualizado
         */ 
        public function getVisualizado()
        {
                return $this->visualizado;
        }

        /**
         * Set the value of visualizado
         *
         * @return  self
         */ 
        public function setVisualizado($visualizado)
        {
                $this->visualizado = $visualizado;

                return $this;
        }

        /**
         * Get the value of comecar
         */ 
        public function getComecar()
        {
                return $this->comecar;
        }

        /**
         * Set the value of comecar
         *
         * @return  self
         */ 
        public function setComecar($comecar)
        {
                $this->comecar = $comecar;

                return $this;
        }

        /**
         * Get the value of continuar
         */ 
        public function getContinuar()
        {
                return $this->continuar;
        }

        /**
         * Set the value of continuar
         *
         * @return  self
         */ 
        public function setContinuar($continuar)
        {
                $this->continuar = $continuar;

                return $this;
        }

        /**
         * Get the value of parar
         */ 
        public function getParar()
        {
                return $this->parar;
        }

        /**
         * Set the value of parar
         *
         * @return  self
         */ 
        public function setParar($parar)
        {
                $this->parar = $parar;

                return $this;
        }
    }

?>