<?php

    class Mensagem {

        private $ID;
        private $titulo;
        private $texto;
        private $dataCriacao;
        private $dataExpiracao;
        private $cpf;
        private $remetente;


        public function cadastrar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_mensagem (men_titulo, men_texto, men_data_expiracao, cpf) 
            VALUES ('$this->titulo', '$this->texto', '$this->dataExpiracao', '$this->cpf')";

            $helper->insert($insert);

        }

        public function retornarMensagem($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT men_id as id, men_titulo as titulo, men_texto as texto, 
            DATE_FORMAT(men_data_criacao, '%d/%m/%Y %H:%i') as criacao,
            DATE_FORMAT(men_data_expiracao, '%d/%m/%Y %H:%i:%s') as expiracao, cpf 
            FROM tbl_mensagem WHERE men_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $mensagem = new Mensagem();
            $mensagem->setID($fetch['id']);
            $mensagem->setTitulo($fetch['titulo']);
            $mensagem->setTexto($fetch['texto']);
            $mensagem->setDataCriacao($fetch['criacao']);
            $mensagem->setDataExpiracao($fetch['expiracao']);
            $mensagem->setCpf($fetch['cpf']);
            $cpf = $fetch['cpf'];
            $select = "SELECT ges_nome_completo as nome FROM tbl_gestor WHERE ges_cpf = '$cpf'";
            $query = $helper->select($select, 1);
                if(mysqli_num_rows($query) == 0) {
                        $select = "SELECT col_nome_completo as nome FROM tbl_colaborador WHERE col_cpf = '$cpf'";
                        $fetch = $helper->select($select, 2);
                        $mensagem->setRemetente($fetch['nome']);
                } else {
                        $fetch = mysqli_fetch_assoc($query);
                        $mensagem->setRemetente($fetch['nome']);
                }

            return $mensagem;

        }

        public function retornarUltima($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT men_id as id FROM tbl_mensagem ORDER BY men_id DESC LIMIT 1";

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
         * Get the value of dataExpiracao
         */ 
        public function getDataExpiracao()
        {
                return $this->dataExpiracao;
        }

        /**
         * Set the value of dataExpiracao
         *
         * @return  self
         */ 
        public function setDataExpiracao($dataExpiracao)
        {
                $this->dataExpiracao = $dataExpiracao;

                return $this;
        }

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