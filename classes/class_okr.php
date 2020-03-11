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
        private $prazo_format;
        private $dataCriacao;
        private $cpfGestor;
        private $arquivada;

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
            okr_tipo = '$this->tipo', okr_visivel = '$this->visivel', okr_prazo = '$this->prazo' WHERE okr_id = $this->ID";

            if($helper->update($update)) return true;
            else return false;

        }

        function arquivar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_okr SET okr_arquivada = 1 WHERE okr_id = $this->ID";

            if($helper->update($update)) return true;
            else return false;

        }

        function desarquivar($database_empresa) {

            require_once("class_conexao_empresa.php");
            require_once("class_queryHelper.php");

            $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_okr SET okr_arquivada = 0 WHERE okr_id = $this->ID";

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
            okr_visivel as visivel, okr_goal_money as money, okr_goal_number as number, DATE_FORMAT(okr_prazo, '%d/%m/%Y %H:%i') as prazo, 
            ges_cpf as gestor, DATE_FORMAT(okr_prazo, '%Y-%m-%d') as prazo_format, okr_arquivada as arquivada,
            DATE_FORMAT(okr_data_criacao, '%d/%m/%Y %H:%i') as criacao FROM tbl_okr 
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
            $okr->setPrazo_format($fetch['prazo_format']);
            $okr->setDataCriacao($fetch['criacao']);
            $okr->setCpfGestor($fetch['gestor']);
            $okr->setArquivada($fetch['arquivada']);
            
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

            function popularSelectGestoresMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT DISTINCT t2.ges_nome_completo as nome, t1.ges_cpf as cpf 
                FROM tbl_okr_gestor t1 INNER JOIN tbl_gestor t2 ON t2.ges_cpf = t1.ges_cpf 
                WHERE t1.okr_id = $this->ID";
        
                $query = $helper->select($select, 1);
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="gestores[]" name="gestores[]" value='.$f['cpf'].'> '.strtoupper($f['nome']).'<br>';
                }
        
            }

            function popularSelectColaboradoresMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT DISTINCT t2.col_nome_completo as nome, t1.col_cpf as cpf 
                FROM tbl_okr_colaborador t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf 
                WHERE t1.okr_id = $this->ID";
        
                $query = $helper->select($select, 1);
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="colaboradores[]" name="colaboradores[]" value='.$f['cpf'].'> '.strtoupper($f['nome']).'<br>';
                }
        
            }

            
            function popularSelectSetoresMultiple($database_empresa) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT DISTINCT t2.set_nome as nome, t1.set_id as id 
                FROM tbl_okr_setor t1 INNER JOIN tbl_setor t2 ON t2.set_id = t1.set_id
                WHERE t1.okr_id = $this->ID";
        
                $query = $helper->select($select, 1);
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="setores[]" name="setores[]" value='.$f['id'].'> '.strtoupper($f['nome']).'<br>';
                }
        
            }

            function adicionarColaborador($database_empresa, $col_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);

                $this->removerColaborador($database_empresa, $col_cpf);
        
                $insert = "INSERT INTO tbl_okr_colaborador (okr_id, col_cpf) VALUES ($this->ID, '$col_cpf')";
        
                if($helper->insert($insert)) return true;
                else return false;
        
            }

            function adicionarGestor($database_empresa, $ges_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);

                $this->removerGestor($database_empresa, $ges_cpf);
        
                $insert = "INSERT INTO tbl_okr_gestor (okr_id, ges_cpf) VALUES ($this->ID, '$ges_cpf')";
        
                if($helper->insert($insert)) return true;
                else return false;
        
            }

            function adicionarSetor($database_empresa, $set_id) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);

                $this->removerSetor($database_empresa, $set_id);
        
                $insert = "INSERT INTO tbl_okr_setor (okr_id, set_id) VALUES ($this->ID, $set_id)";
        
                if($helper->insert($insert)) return true;
                else return false;
        
            }

            function removerColaborador($database_empresa, $col_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $delete = "DELETE FROM tbl_okr_colaborador WHERE okr_id = $this->ID AND col_cpf = '$col_cpf'";
                if($helper->delete($delete)) return true;
                else return false;
        
            }

            function removerGestor($database_empresa, $ges_cpf) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $delete = "DELETE FROM tbl_okr_gestor WHERE okr_id = $this->ID AND ges_cpf = '$ges_cpf'";
                if($helper->delete($delete)) return true;
                else return false;
        
            }

            function removerSetor($database_empresa, $set_id) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $delete = "DELETE FROM tbl_okr_setor WHERE okr_id = $this->ID AND set_id = $set_id";
                if($helper->delete($delete)) return true;
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

        /**
         * Get the value of prazo_format
         */ 
        public function getPrazo_format()
        {
                return $this->prazo_format;
        }

        /**
         * Set the value of prazo_format
         *
         * @return  self
         */ 
        public function setPrazo_format($prazo_format)
        {
                $this->prazo_format = $prazo_format;

                return $this;
        }


        /**
         * Get the value of arquivada
         */ 
        public function getArquivada()
        {
                return $this->arquivada;
        }

        /**
         * Set the value of arquivada
         *
         * @return  self
         */ 
        public function setArquivada($arquivada)
        {
                $this->arquivada = $arquivada;

                return $this;
        }

    }

?>