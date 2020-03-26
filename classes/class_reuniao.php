<?php

    class Reuniao {

        private $ID;
        private $pauta;
        private $descricao;
        private $local;
        private $objetivo;
        private $atingido;
        private $ata;
        private $data;
          private $data_format;
        private $hora;
        private $concluida;
        private $dataCriacao;
        private $cpfGestor;

        function salvar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_reuniao (reu_pauta, reu_descricao, reu_local, reu_data, 
            reu_hora, reu_objetivo, ges_cpf) VALUES ('$this->pauta', '$this->descricao',
            '$this->local', '$this->data', '$this->hora', '$this->objetivo', '$this->cpfGestor')";

            if($helper->insert($insert)) return true;
            else return false;

        }

        function atualizar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE 
                        tbl_reuniao 
                       SET reu_pauta = '$this->pauta', 
                        reu_descricao = '$this->descricao',
                        reu_local = '$this->local', 
                        reu_data = '$this->data', 
                        reu_hora = '$this->hora',
                        reu_objetivo = '$this->objetivo', 
                        reu_objetivo_atingido = $this->atingido, 
                        reu_data_atualizacao = NOW() 
                       WHERE reu_id = $this->ID";

            if($helper->update($update)) return true;
            else return false;

        }

        function retornarReuniao($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT reu_id as id, reu_pauta as pauta, reu_descricao as descricao,
            reu_local as local, reu_objetivo as objetivo,
            reu_concluida as concluida, reu_ata as ata,
            reu_objetivo_atingido as atingido,
            DATE_FORMAT(reu_data, '%d/%m/%Y') as data,
            DATE_FORMAT(reu_hora, '%H:%i') as hora,
            DATE_FORMAT(reu_data_criacao, '%d/%m/%Y %H:%i') as criacao,
            ges_cpf as gestor,
            reu_data as data_format
            FROM tbl_reuniao WHERE reu_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $reu = new Reuniao();
            $reu->setID($fetch['id']);
            $reu->setPauta($fetch['pauta']);
            $reu->setDescricao($fetch['descricao']);
            $reu->setHora($fetch['hora']);
            $reu->setData_format($fetch['data_format']);
            $reu->setData($fetch['data']);
            $reu->setLocal($fetch['local']);
            $reu->setConcluida($fetch['concluida']);
            $reu->setAta($fetch['ata']);
            $reu->setObjetivo($fetch['objetivo']);
            $reu->setAtingido($fetch['atingido']);
            $reu->setDataCriacao($fetch['criacao']);
            $reu->setCpfGestor($fetch['gestor']);
            
            return $reu;

        }

        public function adicionarAnotacao($database_empresa, $anotacao, $cpf) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_reuniao_anotacao (reu_id, cpf, anotacao) VALUES ($this->ID, '$cpf', '$anotacao')";
            if($helper->insert($insert)) return true;
            else return false;
        }

        public function retornarUltima($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT reu_id as id FROM tbl_reuniao ORDER BY reu_id DESC LIMIT 1";
    
                $fetch = $helper->select($select, 2);
    
                return $fetch['id'];
    
            }

            public function isAutorizado($database_empresa, $cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $select = "SELECT cpf FROM tbl_reuniao_integrante WHERE cpf = '$cpf' AND reu_id = '$this->ID'";
    
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
    
                $select = "SELECT DISTINCT cpf, t2.col_nome_completo as nome FROM tbl_reuniao_integrante t1 
                INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf WHERE t1.reu_id = '$this->ID' AND t1.colaborador = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function popularSelectColaboradoresMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.col_nome_completo as nome FROM tbl_reuniao_integrante t1 
                INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.cpf WHERE t1.reu_id = '$this->ID' AND t1.colaborador = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" name="colaboradores[]" id="colaboradores[]" value="'.$fetch['cpf'].'"> '.$fetch['nome'].'<br>';
                }
        
            }
    
            function popularSelectGestores($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.ges_nome_completo as nome FROM tbl_reuniao_integrante t1 
                INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf WHERE t1.reu_id = '$this->ID' AND t1.gestor = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function popularSelectGestoresMultiple($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT cpf, t2.ges_nome_completo as nome FROM tbl_reuniao_integrante t1 
                INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.cpf WHERE t1.reu_id = '$this->ID' AND t1.gestor = 1";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" name="gestores[]" id="gestores[]" value="'.$fetch['cpf'].'"> '.$fetch['nome'].'<br>';
                }
        
            }

            function popularSelectMetasMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT t1.okr_id as id, t2.okr_titulo as titulo FROM tbl_reuniao_okr t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE t1.reu_id = '$this->ID'";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" name="metas[]" id="metas[]" value="'.$fetch['id'].'"> '.$fetch['titulo'].'<br>';
                }
        
            }
    
            function popularSelectMetas($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT t1.okr_id as id, t2.okr_titulo as titulo FROM tbl_reuniao_okr t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE t1.reu_id = '$this->ID'";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function popularSelectEventosMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT t1.eve_id as id, t2.eve_titulo as titulo FROM tbl_reuniao_evento t1 
                INNER JOIN tbl_evento t2 ON t2.eve_id = t1.eve_id WHERE t1.reu_id = '$this->ID'";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" name="eventos[]" id="eventos[]" value="'.$fetch['id'].'"> '.$fetch['titulo'].'<br>';
                }
        
            }
    
            function popularSelectEventos($database_empresa) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT t1.okr_id as id, t2.okr_titulo as titulo FROM tbl_reuniao_okr t1 
                INNER JOIN tbl_okr t2 ON t2.okr_id = t1.okr_id WHERE t1.reu_id = '$this->ID'";
    
                $query = $helper->select($select, 1);
    
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo '<option value="'.$fetch['cpf'].'">'.$fetch['nome'].'</option>';
                }
        
            }

            function notificarIntegrantes($database_empresa, $assunto, $mensagem) {
                require_once('class_conexao_empresa.php');
                require_once('class_conexao_padrao.php');
                require_once('class_email.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);

                $connP = new ConexaoPadrao();
                $conexaoP = $connP->conecta();
                $helperP = new QueryHelper($conexaoP);

                $reuniao = $this->retornarReuniao($database_empresa);

                $select = "SELECT DISTINCT cpf as cpf FROM tbl_reuniao_integrante WHERE reu_id = $this->ID";
                $query = $helper->select($select, 1);

                while($f = mysqli_fetch_assoc($query)) {
                    $cpf = $f['cpf'];

                    $select2 = "SELECT usu_id as usu_id FROM tbl_colaborador WHERE col_cpf = '$cpf'";
                    $query = $helper->select($select2, 1);
                    if(mysqli_num_rows($query) == 0) {
                        $select2 = "SELECT usu_id as usu_id FROM tbl_gestor WHERE ges_cpf = '$cpf'";
                        $query = $helper->select($select2, 1);
                    }
                    $fetch = $helper->select($select2, 2);
                    $usu_id = $fetch['usu_id'];
                    
                    $select = "SELECT usu_email as email FROM tbl_usuario WHERE usu_id = $usu_id";
                    $fetch_email = $helperP->select($select, 2);
                    $email = $fetch_email['email'];

                    $mail = new Email();
                    $mail->setEmailTo($email);
                    $mail->setEmailFrom(0);
                    $mail->setAssunto($assunto);
                    $mail->setMensagem($mensagem);
                    $mail->enviar();
                }
            }

            function adicionarGestor($database_empresa, $ges_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, gestor) VALUES ('$this->ID', '$ges_cpf', 1)";
    
                $helper->insert($insert);
    
            }
    
            function adicionarColaborador($database_empresa, $col_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $insert = "INSERT INTO tbl_reuniao_integrante (reu_id, cpf, colaborador) VALUES ('$this->ID', '$col_cpf', 1)";
    
                $helper->insert($insert);
    
            }
    
            function removerColaborador($database_empresa, $col_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_reuniao_integrante WHERE reu_id = '$this->ID' AND cpf = '$col_cpf' 
                AND colaborador = 1";
    
                $helper->delete($delete);
    
            }
    
            function removerGestor($database_empresa, $ges_cpf) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_reuniao_integrante WHERE reu_id = '$this->ID' AND cpf = '$ges_cpf' 
                AND gestor = 1";
    
                $helper->delete($delete);
    
            }

            function adicionarMeta($database_empresa, $okr_id) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);

                $delete = "DELETE FROM tbl_reuniao_okr WHERE reu_id = '$this->ID' AND okr_id = '$okr_id'";
                $helper->delete($delete);
    
                $insert = "INSERT INTO tbl_reuniao_okr (reu_id, okr_id) VALUES ('$this->ID', '$okr_id')";
                $helper->insert($insert);
    
            }
    
            function removerMeta($database_empresa, $okr_id) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_reuniao_okr WHERE reu_id = '$this->ID' AND okr_id = '$okr_id'";
                $helper->delete($delete);
    
            }

            function adicionarEvento($database_empresa, $eve_id) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);

                $delete = "DELETE FROM tbl_reuniao_evento WHERE eve_id = '$this->ID' AND eve_id = '$eve_id'";
                $helper->delete($delete);
    
                $insert = "INSERT INTO tbl_reuniao_evento (reu_id, eve_id) VALUES ('$this->ID', '$eve_id')";
                $helper->insert($insert);
    
            }
    
            function removerEvento($database_empresa, $eve_id) {
    
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
            
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $delete = "DELETE FROM tbl_reuniao_evento WHERE reu_id = '$this->ID' AND eve_id = '$eve_id'";
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
         * Get the value of pauta
         */ 
        public function getPauta()
        {
                return $this->pauta;
        }

        /**
         * Set the value of pauta
         *
         * @return  self
         */ 
        public function setPauta($pauta)
        {
                $this->pauta = $pauta;

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
         * Get the value of objetivo
         */ 
        public function getObjetivo()
        {
                return $this->objetivo;
        }

        /**
         * Set the value of objetivo
         *
         * @return  self
         */ 
        public function setObjetivo($objetivo)
        {
                $this->objetivo = $objetivo;

                return $this;
        }

        /**
         * Get the value of atingido
         */ 
        public function getAtingido()
        {
                return $this->atingido;
        }

        /**
         * Set the value of atingido
         *
         * @return  self
         */ 
        public function setAtingido($atingido)
        {
                $this->atingido = $atingido;

                return $this;
        }

        /**
         * Get the value of data
         */ 
        public function getData()
        {
                return $this->data;
        }

        /**
         * Set the value of data
         *
         * @return  self
         */ 
        public function setData($data)
        {
                $this->data = $data;

                return $this;
        }

          /**
           * Get the value of data_format
           */ 
          public function getData_format()
          {
                    return $this->data_format;
          }

          /**
           * Set the value of data_format
           *
           * @return  self
           */ 
          public function setData_format($data_format)
          {
                    $this->data_format = $data_format;

                    return $this;
          }

        /**
         * Get the value of hora
         */ 
        public function getHora()
        {
                return $this->hora;
        }

        /**
         * Set the value of hora
         *
         * @return  self
         */ 
        public function setHora($hora)
        {
                $this->hora = $hora;

                return $this;
        }

        /**
         * Get the value of concluida
         */ 
        public function getConcluida()
        {
                return $this->concluida;
        }

        /**
         * Set the value of concluida
         *
         * @return  self
         */ 
        public function setConcluida($concluida)
        {
                $this->concluida = $concluida;

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
         * Get the value of ata
         */ 
        public function getAta()
        {
                return $this->ata;
        }

        /**
         * Set the value of ata
         *
         * @return  self
         */ 
        public function setAta($ata)
        {
                $this->ata = $ata;

                return $this;
        }
    }

?>