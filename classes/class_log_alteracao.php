<?php

    class LogAlteracao {

        private $IDUser;
        private $descricao;
        private $data;

        public function salvar() {

            require_once("class_conexao_padrao.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoPadrao();
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_log_alteracao (alt_descricao, usu_id) 
            VALUES ('$this->descricao', '$this->IDUser')";

            $helper->insert($insert);

        }


        public function getIDUser()
        {
                return $this->IDUser;
        }

        public function setIDUser($IDUser)
        {
                $this->IDUser = $IDUser;

                return $this;
        }

        public function getDescricao()
        {
                return $this->descricao;
        }

        public function setDescricao($descricao)
        {
                $this->descricao = $descricao;

                return $this;
        }

        public function getData()
        {
                return $this->data;
        }

        public function setData($data)
        {
                $this->data = date('Y-m-d H:i:s');

                return $this;
        }
    }

?>