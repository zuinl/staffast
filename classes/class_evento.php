<?php

    class Evento {

        private $ID;
        private $titulo;
        private $descricao;
        private $local;
        private $isNaEmpresa;
        private $dataI;
          private $dataI_format;
        private $horaI;
        private $dataF;
          private $dataF_format;
        private $horaF;
        private $status;
        private $dataCriacao;
        private $cpfGestor;

        function salvar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_evento (eve_titulo, eve_descricao, eve_local, eve_na_empresa, eve_data_inicial, 
            eve_hora_inicial, eve_data_final, eve_hora_final, ges_cpf) VALUES ('$this->titulo', '$this->descricao',
            '$this->local', '$this->isNaEmpresa', '$this->dataI', '$this->horaI', '$this->dataF', '$this->horaF', '$this->cpfGestor')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function atualizar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_evento SET eve_titulo = '$this->titulo', eve_descricao = '$this->descricao',
            eve_local = '$this->local', eve_na_empresa = '$this->isNaEmpresa', eve_data_inicial = '$this->dataI',
            eve_hora_inicial = '$this->horaI', eve_data_final = '$this->dataF', eve_hora_final = '$this->horaF',
            eve_data_atualizacao = NOW() WHERE eve_id = '$this->ID'";

            if($helper->update($update)) return true;
            else return false;

        }

        function retornarEvento($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT eve_id as id, eve_titulo as titulo, eve_descricao as descricao,
            eve_local as local, eve_na_empresa as isNaEmpresa,
            eve_status as status,
            DATE_FORMAT(eve_data_inicial, '%d/%m/%Y') as dataI,
            DATE_FORMAT(eve_data_final, '%d/%m/%Y') as dataF,
            DATE_FORMAT(eve_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao,
            eve_hora_inicial as horaI, eve_hora_final as horaF, ges_cpf as gestor,
            eve_data_inicial as dataI_format, eve_data_final as dataF_format
            FROM tbl_evento WHERE eve_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $eve = new Evento();
            $eve->setID($fetch['id']);
            $eve->setTitulo($fetch['titulo']);
            $eve->setDescricao($fetch['descricao']);
            $eve->setHoraI($fetch['horaI']);
            $eve->setHoraF($fetch['horaF']);
            $eve->setDataI_format($fetch['dataI_format']);
            $eve->setDataF_format($fetch['dataF_format']);
            $eve->setDataI($fetch['dataI']);
            $eve->setDataF($fetch['dataF']);
            $eve->setLocal($fetch['local']);
            $eve->setStatus($fetch['status']);
            $eve->setIsNaEmpresa($fetch['isNaEmpresa']);
            $eve->setDataCriacao($fetch['criacao']);
            $eve->setCpfGestor($fetch['gestor']);
            
            return $eve;

        }

        public function retornarUltimo($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT eve_id as id FROM tbl_evento ORDER BY eve_id DESC LIMIT 1";
    
                $fetch = $helper->select($select, 2);
    
                return $fetch['id'];
    
            }

            public function isAutorizado($database_empresa, $cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT cpf FROM tbl_evento_participante WHERE cpf = '$cpf' AND eve_id = '$this->ID'";
    
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) > 0) return true;
                else return false;
    
            }

            function popularSelectColaboradores($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.col_nome_completo as nome FROM tbl_evento_participante t1 
                INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf WHERE t1.eve_id = '$this->ID' AND t1.colaborador = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function popularSelectMultipleColaboradores($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.col_nome_completo as nome FROM tbl_evento_participante t1 
                INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf WHERE t1.eve_id = '$this->ID' AND t1.colaborador = 1";
    
                $query = $helper->select($select, 1);
    
                while($f = mysqli_fetch_assoc($query)) {
                   echo '<input type="checkbox" id="colaboradores[]" name="colaboradores[]" value='.$f['cpf'].'> '.$f['nome'].'<br>';
                }
        
            }
    
            function popularSelectGestores($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.ges_nome_completo as nome FROM tbl_evento_participante t1 
                INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf WHERE t1.eve_id = '$this->ID' AND t1.gestor = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function popularSelectMultipleGestores($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.ges_nome_completo as nome FROM tbl_evento_participante t1 
                INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf WHERE t1.eve_id = '$this->ID' AND t1.gestor = 1";
    
                $query = $helper->select($select, 1);
    
                while($f = mysqli_fetch_assoc($query)) {
                        echo '<input type="checkbox" id="gestores[]" name="gestores[]" value='.$f['cpf'].'> '.$f['nome'].'<br>';
                }
        
            }

            function popularSelectMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT eve_id as id, eve_titulo as titulo FROM tbl_evento WHERE eve_data_final >= NOW() ORDER BY eve_data_inicial DESC";
        
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                        echo '<br>Sem eventos';
                        return;
                }
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="eventos[]" name="eventos[]" value='.$f['id'].'> '.$f['titulo'].'<br>';
                }
        
            }

            function adicionarGestor($database_empresa, $ges_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, gestor) VALUES ('$this->ID', '$ges_cpf', 1)";
    
                $helper->insert($insert);
    
            }
    
            function adicionarColaborador($database_empresa, $col_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $insert = "INSERT INTO tbl_evento_participante (eve_id, cpf, colaborador) VALUES ('$this->ID', '$col_cpf', 1)";
    
                $helper->insert($insert);
    
            }
    
            function removerColaborador($database_empresa, $col_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_evento_participante WHERE eve_id = '$this->ID' AND cpf = '$col_cpf' 
                AND colaborador = 1";
    
                $helper->delete($delete);
    
            }
    
            function removerGestor($database_empresa, $ges_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_evento_participante WHERE eve_id = '$this->ID' AND cpf = '$ges_cpf' 
                AND gestor = 1";
    
                $helper->delete($delete);
    
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


        /**
         * Get the value of local
         */ 
        public function getLocal()
        {
                return $this->local;
        }

        /**
         * Set the value of local
         *
         * @return  self
         */ 
        public function setLocal($local)
        {
                $this->local = $local;

                return $this;
        }

        /**
         * Get the value of isNaEmpresa
         */ 
        public function getIsNaEmpresa()
        {
                return $this->isNaEmpresa;
        }

        /**
         * Set the value of isNaEmpresa
         *
         * @return  self
         */ 
        public function setIsNaEmpresa($isNaEmpresa)
        {
                $this->isNaEmpresa = $isNaEmpresa;

                return $this;
        }

        /**
         * Get the value of dataI
         */ 
        public function getDataI()
        {
                return $this->dataI;
        }

        /**
         * Set the value of dataI
         *
         * @return  self
         */ 
        public function setDataI($dataI)
        {
                $this->dataI = $dataI;

                return $this;
        }

        /**
         * Get the value of horaI
         */ 
        public function getHoraI()
        {
                return $this->horaI;
        }

        /**
         * Set the value of horaI
         *
         * @return  self
         */ 
        public function setHoraI($horaI)
        {
                $this->horaI = $horaI;

                return $this;
        }

        /**
         * Get the value of dataF
         */ 
        public function getDataF()
        {
                return $this->dataF;
        }

        /**
         * Set the value of dataF
         *
         * @return  self
         */ 
        public function setDataF($dataF)
        {
                $this->dataF = $dataF;

                return $this;
        }

        /**
         * Get the value of horaF
         */ 
        public function getHoraF()
        {
                return $this->horaF;
        }

        /**
         * Set the value of horaF
         *
         * @return  self
         */ 
        public function setHoraF($horaF)
        {
                $this->horaF = $horaF;

                return $this;
        }

        /**
         * Get the value of status
         */ 
        public function getStatus()
        {
                return $this->status;
        }

        /**
         * Set the value of status
         *
         * @return  self
         */ 
        public function setStatus($status)
        {
                $this->status = $status;

                return $this;
        }

          /**
           * Get the value of dataI_format
           */ 
          public function getDataI_format()
          {
                    return $this->dataI_format;
          }

          /**
           * Set the value of dataI_format
           *
           * @return  self
           */ 
          public function setDataI_format($dataI_format)
          {
                    $this->dataI_format = $dataI_format;

                    return $this;
          }

          /**
           * Get the value of dataF_format
           */ 
          public function getDataF_format()
          {
                    return $this->dataF_format;
          }

          /**
           * Set the value of dataF_format
           *
           * @return  self
           */ 
          public function setDataF_format($dataF_format)
          {
                    $this->dataF_format = $dataF_format;

                    return $this;
          }
    }

?>