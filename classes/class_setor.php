<?php

    class Setor {

        private $ID;
        private $nome;
        private $local;
        private $descricao;
        private $ativo;
        private $dataCadastro;
        private $dataCadastroFormat;
        private $dataAlteracao;

        private $sessaoUm;
        private $sessaoDois;
        private $sessaoTres;
        private $sessaoQuatro;
        private $sessaoCinco;
        private $sessaoSeis;

        function cadastrar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $insert = "INSERT INTO tbl_setor (set_nome, set_local, set_descricao) VALUES ('$this->nome', '$this->local', 
            '$this->descricao')";

            $helper->insert($insert);

            $idSetor = $this->retornarUltimoSetor($database_empresa);

            $insert = "INSERT INTO tbl_setor_competencia (set_id, um, dois, tres, quatro, cinco, seis) 
            VALUES ('$idSetor', '$this->sessaoUm', '$this->sessaoDois', '$this->sessaoTres', '$this->sessaoQuatro', 
            '$this->sessaoCinco', '$this->sessaoSeis')";
            
            if($helper->insert($insert)) {
                echo 'Cadastrado com sucesso';
                return true;
            } else {
                echo 'Erro ao cadastrar';
                return false;
            }

        }

        function atualizar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $update = "UPDATE tbl_setor_competencia SET um = '$this->sessaoUm', dois = '$this->sessaoDois', 
            tres = '$this->sessaoTres', quatro = '$this->sessaoQuatro', cinco = '$this->sessaoCinco', 
            seis = '$this->sessaoSeis' WHERE set_id = '$this->ID'";
            $helper->update($update);

            $update = "UPDATE tbl_setor SET set_nome = '$this->nome', set_local = '$this->local', set_descricao = '$this->descricao', 
            set_data_alteracao = NOW() WHERE set_id = '$this->ID'";
            
            if($helper->update($update)) {
                echo 'Atualizado com sucesso';
                return true;
            } else {
                echo 'Erro ao atualizar';
                return false;
            }

        }

        function desativar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $update = "UPDATE tbl_setor SET set_ativo = 0 WHERE set_id = '$this->ID'";
            
            if($helper->update($update)) {
                echo 'Desativado com sucesso';
                return true;
            } else {
                echo 'Erro ao desativar';
                return false;
            }

        }

        function reativar($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $update = "UPDATE tbl_setor SET set_ativo = 1 WHERE set_id = '$this->ID'";
            
            if($helper->update($update)) {
                echo 'Reativado com sucesso';
                return true;
            } else {
                echo 'Erro ao reativar';
                return false;
            }

        }

        function isAvaliacaoLiberada($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $select = "SELECT avs_liberada FROM tbl_setor_competencia WHERE avs_liberada >= NOW() AND set_id = '$this->ID'";
            $query = $helper->select($select, 1);
            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        }

        function calcularGestores($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            
            $helper = new QueryHelper($conexao->conecta());

            $select = "SELECT DISTINCT ges_cpf FROM tbl_setor_funcionario WHERE set_id = '$this->ID'";
            $query = $helper->select($select, 1);
            return mysqli_num_rows($query);

        }

        function retornarUltimoSetor($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);
    
            $select = "SELECT set_id as id FROM tbl_setor ORDER BY set_id DESC LIMIT 1";
    
            $fetch = $helper->select($select, 2);
            
            return $fetch['id'];
    
        }

        function inserirGestor($set_id, $ges_cpf, $database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $insert = "INSERT INTO tbl_setor_funcionario (set_id, ges_cpf, col_cpf) VALUES ('$set_id', '$ges_cpf', '00000000000')";

            $helper->insert($insert);
        }

        function liberarAvaliacao($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);
            $date = date_create(date("Y-m-d"));
            $data = date_add($date, date_interval_create_from_date_string("7 days"));
            $data = date_format($data,"Y-m-d")." 23:59:59";
            
            $update = "UPDATE tbl_setor_competencia SET avs_liberada = '$data' WHERE set_id = ".$this->ID;

            if($helper->update($update)) return true;
            else return false;
        }

        function retornarSetor($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT set_id as id, set_nome as nome, set_descricao as descricao, set_local as local, 
            DATE_FORMAT(set_data_cadastro, '%d/%m/%Y %H:%i:%s') as cadastro, 
            set_data_cadastro as cadastro_format,
            DATE_FORMAT(set_data_alteracao, '%d/%m/%Y %H:%i:%s') as alteracao, set_ativo as ativo 
            FROM tbl_setor WHERE set_id = '$this->ID'";

            $fetch = $helper->select($select, 2);

            $setor = new Setor();

            $setor->setID($fetch['id']);
            $setor->setNome($fetch['nome']);
            $setor->setLocal($fetch['local']);
            $setor->setDescricao($fetch['descricao']);
            $setor->setDataCadastro($fetch['cadastro']);
            $setor->setDataCadastroFormat($fetch['cadastro_format']);
            $setor->setDataAlteracao($fetch['alteracao']);
            $setor->setAtivo($fetch['ativo']);
            
            $select = "SELECT um, dois, tres, quatro, cinco, seis FROM tbl_setor_competencia 
            WHERE set_id = '$this->ID'";
            $fetch = $helper->select($select, 2);

            $setor->setSessaoUm($fetch['um']);
            $setor->setSessaoDois($fetch['dois']);
            $setor->setSessaoTres($fetch['tres']);
            $setor->setSessaoQuatro($fetch['quatro']);
            $setor->setSessaoCinco($fetch['cinco']);
            $setor->setSessaoSeis($fetch['seis']);
            
            return $setor;

        }

        function listarGestores($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);
    
                $select = "SELECT DISTINCT t1.ges_cpf as cpf, t2.ges_nome_completo as nome FROM tbl_setor_funcionario t1 
                INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf WHERE t1.set_id = '$this->ID'";

                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    echo 'Nenhum';
                } else {
                    while($fetch = mysqli_fetch_assoc($query)) {
                        echo $fetch['nome'].'<br>';
                    }
                }

        }

        function listarColaboradores($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT DISTINCT t1.col_cpf as cpf, t2.col_nome_completo as nome FROM tbl_setor_funcionario t1 
            INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf WHERE t1.set_id = '$this->ID'";

            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) {
                echo 'Nenhum';
            } else {
                while($fetch = mysqli_fetch_assoc($query)) {
                    echo $fetch['nome'].'<br>';
                }
            }

    }

        function limparGestores($database_empresa, $ges_cpf = '') {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conn = new ConexaoEmpresa($database_empresa);
                $conexao = $conn->conecta();
                $helper = new QueryHelper($conexao);

                $delete = "DELETE FROM tbl_setor_funcionario WHERE set_id = '$this->ID' AND col_cpf = '00000000000'";
                $helper->delete($delete);

                $delete = "DELETE FROM tbl_gestor_funcionario WHERE ges_cpf = '$ges_cpf'";
                $helper->delete($delete);

        }

        function adicionarGestor($database_empresa, $ges_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $delete = "DELETE FROM tbl_setor_funcionario WHERE set_id = '$this->ID' AND ges_cpf = '$ges_cpf'";
            $helper->delete($delete);

            $insert = "INSERT INTO tbl_setor_funcionario (set_id, ges_cpf, col_cpf) VALUES ('$this->ID', '$ges_cpf', '00000000000')";
            $helper->insert($insert);

            $delete = "DELETE FROM tbl_gestor_funcionario WHERE set_id = '$this->ID' AND ges_cpf = '$ges_cpf'";
            $helper->delete($delete);

            $select = "SELECT DISTINCT col_cpf as cpf FROM tbl_setor_funcionario WHERE set_id = '$this->ID'";
            $query = $helper->select($select, 1);

            while($f = mysqli_fetch_assoc($query)) {
                $col_cpf = $f['cpf'];
                $insert = "INSERT INTO tbl_gestor_funcionario (set_id, ges_cpf, col_cpf) VALUES ('$this->ID', '$ges_cpf', '$col_cpf')";
                $helper->insert($insert);
            }

        }

        function adicionarColaborador($database_empresa, $col_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $delete = "DELETE FROM tbl_setor_funcionario WHERE set_id = '$this->ID' AND col_cpf = '$col_cpf'";
            $helper->delete($delete);

            $insert = "INSERT INTO tbl_setor_funcionario (set_id, ges_cpf, col_cpf) VALUES ('$this->ID', '00000000000', '$col_cpf')";
            $helper->insert($insert);


            $delete = "DELETE FROM tbl_gestor_funcionario WHERE set_id = '$this->ID' AND col_cpf = '$col_cpf'";
            $helper->delete($delete);

            $select = "SELECT DISTINCT ges_cpf as cpf FROM tbl_setor_funcionario WHERE set_id = '$this->ID'";
            $query = $helper->select($select, 1);

            while($f = mysqli_fetch_assoc($query)) {
                $ges_cpf = $f['cpf'];
                $insert = "INSERT INTO tbl_gestor_funcionario (set_id, ges_cpf, col_cpf) VALUES ('$this->ID', '$ges_cpf', '$col_cpf')";
                $helper->insert($insert);
            }

        }

        function removerColaborador($database_empresa, $col_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $delete = "DELETE FROM tbl_setor_funcionario WHERE set_id = '$this->ID' AND col_cpf = '$col_cpf'";
            $helper->delete($delete);

            $delete = "DELETE FROM tbl_gestor_funcionario WHERE set_id = '$this->ID' AND col_cpf = '$col_cpf'";
            $helper->delete($delete);

        }

        function removerGestor($database_empresa, $ges_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $delete = "DELETE FROM tbl_setor_funcionario WHERE set_id = '$this->ID' AND ges_cpf = '$ges_cpf'";
            $helper->delete($delete);

            $delete = "DELETE FROM tbl_gestor_funcionario WHERE set_id = '$this->ID' AND ges_cpf = '$ges_cpf'";
            $helper->delete($delete);

        }

        function isGestorIn($database_empresa, $ges_cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT ges_cpf FROM tbl_setor_funcionario WHERE ges_cpf = '$ges_cpf' AND set_id = '$this->ID'";
            $query = $helper->select($select, 1);
            
            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        }

        function isAutorizado($database_empresa, $cpf) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
        
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT ges_cpf FROM tbl_setor_funcionario WHERE (ges_cpf = '$cpf' OR col_cpf = '$cpf') AND set_id = '$this->ID'";
            $query = $helper->select($select, 1);
            
            if(mysqli_num_rows($query) == 0) return false;
            else return true;

        }

        function popularSelect($database_empresa, $permissao = 0, $cpf = '') {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            if($permissao != 'COLABORADOR') { 
                $select = "SELECT DISTINCT set_id as id, set_nome as nome FROM tbl_setor 
                ORDER BY set_nome ASC";
            } else {
                $select = "SELECT DISTINCT t1.set_id as id, t2.set_nome as nome FROM tbl_setor_funcionario t1 
                INNER JOIN tbl_setor t2 ON t2.set_id = t1.set_id 
                WHERE t2.col_cpf = '$cpf' OR t2.ges_cpf = '$cpf'  
                ORDER BY t2.set_nome ASC";
            }

            var_dump($select);

            $query = $helper->select($select, 1);

            while($fetch = mysqli_fetch_assoc($query)) {
                echo '<option value="'.$fetch['id'].'">'.$fetch['nome'].'</option>';
            }
    
        }

        function popularSelectMultiple($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT DISTINCT set_id as id, set_nome as nome FROM tbl_setor 
            ORDER BY set_nome ASC";

            $query = $helper->select($select, 1);

            while($f = mysqli_fetch_assoc($query)) {
                echo '<input type="checkbox" id="setores[]" name="setores[]" value='.$f['id'].'> '.$f['nome'].'<br>';
            }
    
        }

        function popularSelectColaboradores($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT DISTINCT t1.col_cpf as cpf, t2.col_nome_completo as nome FROM tbl_setor_funcionario t1 
            INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf WHERE t1.set_id = '$this->ID'";

            $query = $helper->select($select, 1);

            while($f = mysqli_fetch_assoc($query)) {
                echo '<input type="checkbox" id="colaboradoresrmv[]" name="colaboradoresrmv[]" value='.$f['cpf'].'> '.$f['nome'].'<br>';
            }
    
        }

        function popularSelectGestores($database_empresa) {

            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');
    
            $conn = new ConexaoEmpresa($database_empresa);
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT DISTINCT t1.ges_cpf as cpf, t2.ges_nome_completo as nome FROM tbl_setor_funcionario t1 
            INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf WHERE t1.set_id = '$this->ID'";

            $query = $helper->select($select, 1);

            while($f = mysqli_fetch_assoc($query)) {
                echo '<input type="checkbox" id="gestoresrmv[]" name="gestoresrmv[]" value='.$f['cpf'].'> '.$f['nome'].'<br>';
            }
    
        }

        function setNome($nome) {
            $this->nome = $nome;
        }

        function getNome() {
            return $this->nome; 
        }

        function setLocal($local) {
            $this->local = $local;
        }

        function getLocal() {
            return $this->local;
        }

        function setDescricao($descricao) {
            $this->descricao = $descricao;
        }

        function getDescricao() {
            return $this->descricao;
        }

        function setDataCadastro($dataCadastro) {
            $this->dataCadastro = $dataCadastro;
        }

        function getDataCadastro() {
            return $this->dataCadastro;
        }

        function setDataAlteracao($dataAlteracao) {
            $this->dataAlteracao = $dataAlteracao;
        }

        function getDataAlteracao() {
            return $this->dataAlteracao;
        }

        function setID($ID) {
            $this->ID = $ID;
        }

        function getID() {
            return $this->ID;
        }


        /**
         * Get the value of sessaoUm
         */ 
        public function getSessaoUm()
        {
                return $this->sessaoUm;
        }

        /**
         * Set the value of sessaoUm
         *
         * @return  self
         */ 
        public function setSessaoUm($sessaoUm)
        {
                $this->sessaoUm = $sessaoUm;

                return $this;
        }

        /**
         * Get the value of sessaoDois
         */ 
        public function getSessaoDois()
        {
                return $this->sessaoDois;
        }

        /**
         * Set the value of sessaoDois
         *
         * @return  self
         */ 
        public function setSessaoDois($sessaoDois)
        {
                $this->sessaoDois = $sessaoDois;

                return $this;
        }

        /**
         * Get the value of sessaoTres
         */ 
        public function getSessaoTres()
        {
                return $this->sessaoTres;
        }

        /**
         * Set the value of sessaoTres
         *
         * @return  self
         */ 
        public function setSessaoTres($sessaoTres)
        {
                $this->sessaoTres = $sessaoTres;

                return $this;
        }

        /**
         * Get the value of sessaoQuatro
         */ 
        public function getSessaoQuatro()
        {
                return $this->sessaoQuatro;
        }

        /**
         * Set the value of sessaoQuatro
         *
         * @return  self
         */ 
        public function setSessaoQuatro($sessaoQuatro)
        {
                $this->sessaoQuatro = $sessaoQuatro;

                return $this;
        }

        /**
         * Get the value of sessaoCinco
         */ 
        public function getSessaoCinco()
        {
                return $this->sessaoCinco;
        }

        /**
         * Set the value of sessaoCinco
         *
         * @return  self
         */ 
        public function setSessaoCinco($sessaoCinco)
        {
                $this->sessaoCinco = $sessaoCinco;

                return $this;
        }

        /**
         * Get the value of sessaoSeis
         */ 
        public function getSessaoSeis()
        {
                return $this->sessaoSeis;
        }

        /**
         * Set the value of sessaoSeis
         *
         * @return  self
         */ 
        public function setSessaoSeis($sessaoSeis)
        {
                $this->sessaoSeis = $sessaoSeis;

                return $this;
        }

        /**
         * Get the value of ativo
         */ 
        public function getAtivo()
        {
                return $this->ativo;
        }

        /**
         * Set the value of ativo
         *
         * @return  self
         */ 
        public function setAtivo($ativo)
        {
                $this->ativo = $ativo;

                return $this;
        }

        /**
         * Get the value of dataCadastroFormat
         */ 
        public function getDataCadastroFormat()
        {
                return $this->dataCadastroFormat;
        }

        /**
         * Set the value of dataCadastroFormat
         *
         * @return  self
         */ 
        public function setDataCadastroFormat($dataCadastroFormat)
        {
                $this->dataCadastroFormat = $dataCadastroFormat;

                return $this;
        }
    }

?>