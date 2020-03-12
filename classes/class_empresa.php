<?php

    class Empresa {

        private $ID;
        private $razao;
        private $database;
        private $endereco;
        private $telefone;
        private $linkedin;
        private $website;
        private $dataCadastro;
        private $responsavel;
        private $emailResponsavel;
        private $logotipo;

        function retornarEmpresa() {

            require_once("class_conexao_padrao.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoPadrao();
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT t1.emp_id as id, t1.emp_razao_social as razao, t1.emp_database as db, t1.emp_telefone as telefone, 
            t1.emp_linkedin as linkedin, DATE_FORMAT(t1.emp_data_cadastro, '%d/%m/%Y') as cadastro, t1.emp_logotipo as logotipo,
            t2.res_nome as responsavel, t2.res_email as email_responsavel, t1.emp_website as website, t1.emp_endereco as endereco
            FROM tbl_empresa t1 INNER JOIN tbl_empresa_responsavel t2 ON t2.emp_id = t1.emp_id WHERE t1.emp_id = '$this->ID' AND t2.res_ativo = 1";
            
            $fetch = $helper->select($select, 2);

            $empresa = new Empresa();
            $empresa->setID($fetch['id']);
            $empresa->setRazao($fetch['razao']);
            $empresa->setDatabase($fetch['db']);
            $empresa->setEndereco($fetch['endereco']);
                if($fetch['telefone'] == '') $fetch['telefone'] = 'Não informado';
            $empresa->setTelefone($fetch['telefone']);
                if($fetch['linkedin'] == '') $fetch['linkedin'] = 'Não informado';
            $empresa->setLinkedin($fetch['linkedin']);
                if($fetch['website'] == '') $fetch['website'] = 'Não informado';
            $empresa->setWebsite($fetch['website']);
            $empresa->setDataCadastro($fetch['cadastro']);
            $empresa->setResponsavel($fetch['responsavel']);
            $empresa->setEmailResponsavel($fetch['email_responsavel']);
            $empresa->setLogotipo($fetch['logotipo']);

            return $empresa;

        }

        function popularSelect() {

            require_once('class_conexao_padrao.php');
            require_once('class_queryHelper.php');
    
            $conexao = new ConexaoPadrao();
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);
    
            $select = "SELECT emp_id as id, emp_razao_social as nome FROM tbl_empresa ORDER BY emp_razao_social ASC";
    
            $query = $helper->select($select, 1);
    
            while($f = mysqli_fetch_assoc($query)) {
                echo '<option value='.$f['id'].'>'.$f['nome'].'</option>';
            }
    
        }

        function setID($ID) {
            $this->ID = $ID;
        }

        function getID() {
            return $this->ID;
        }

        function setRazao($razao) {
            $this->razao = $razao;
        }

        function getRazao() {
            return $this->razao;
        }

        function setDatabase($database) {
            $this->database = $database;
        }

        function getDatabase() {
            return $this->database;
        }

        function setTelefone($telefone) {
            $this->telefone = $telefone;
        }

        function getTelefone() {
            return $this->telefone;
        }

        function setLinkedin($linkedin) {
            $this->linkedin = $linkedin;
        }

        function getLinkedin() {
            return $this->linkedin;
        }

        function setDataCadastro($dataCadastro) {
            $this->dataCadastro = $dataCadastro;
        }

        function getDataCadastro() {
            return $this->dataCadastro;
        }


        /**
         * Get the value of responsavel
         */ 
        public function getResponsavel()
        {
                return $this->responsavel;
        }

        /**
         * Set the value of responsavel
         *
         * @return  self
         */ 
        public function setResponsavel($responsavel)
        {
                $this->responsavel = $responsavel;

                return $this;
        }

        /**
         * Get the value of emailResponsavel
         */ 
        public function getEmailResponsavel()
        {
                return $this->emailResponsavel;
        }

        /**
         * Set the value of emailResponsavel
         *
         * @return  self
         */ 
        public function setEmailResponsavel($emailResponsavel)
        {
                $this->emailResponsavel = $emailResponsavel;

                return $this;
        }

        /**
         * Get the value of website
         */ 
        public function getWebsite()
        {
                return $this->website;
        }

        /**
         * Set the value of website
         *
         * @return  self
         */ 
        public function setWebsite($website)
        {
                $this->website = $website;

                return $this;
        }

        /**
         * Get the value of endereco
         */ 
        public function getEndereco()
        {
                return $this->endereco;
        }

        /**
         * Set the value of endereco
         *
         * @return  self
         */ 
        public function setEndereco($endereco)
        {
                $this->endereco = $endereco;

                return $this;
        }

        /**
         * Get the value of logotipo
         */ 
        public function getLogotipo()
        {
                return $this->logotipo;
        }

        /**
         * Set the value of logotipo
         *
         * @return  self
         */ 
        public function setLogotipo($logotipo)
        {
                $this->logotipo = $logotipo;

                return $this;
        }
    }

?>