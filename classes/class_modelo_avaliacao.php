<?php

    class ModeloAvaliacao {
        private $ID;
        private $cpfCriador;
        private $dataCriacao;
        private $titulo;
        private $um;
        private $dois;
        private $tres;
        private $quatro;
        private $cinco;
        private $seis;
        private $sete;
        private $oito;
        private $nove;
        private $dez;
        private $onze;
        private $doze;
        private $treze;
        private $quatorze;
        private $quinze;
        private $dezesseis;
        private $dezessete;
        private $dezoito;
        private $dezenove;
        private $vinte;
        private $ativo;

        public function cadastrar($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $insert = "INSERT INTO tbl_modelo_avaliacao 
                        (
                            cpf_criador,
                            titulo,
                            um,
                            dois,
                            tres,
                            quatro,
                            cinco,
                            seis, 
                            sete,
                            oito,
                            nove,
                            dez,
                            onze,
                            doze,
                            treze,
                            quatorze,
                            quinze,
                            dezesseis,
                            dezessete,
                            dezoito,
                            dezenove,
                            vinte
                        ) VALUES (
                            '$this->cpfCriador',
                            '$this->titulo',
                            '$this->um',
                            '$this->dois',
                            '$this->tres',
                            '$this->quatro',
                            '$this->cinco',
                            '$this->seis',
                            '$this->sete',
                            '$this->oito',
                            '$this->nove',
                            '$this->dez',
                            '$this->onze',
                            '$this->doze',
                            '$this->treze',
                            '$this->quatorze',
                            '$this->quinze',
                            '$this->dezesseis',
                            '$this->dezessete',
                            '$this->dezoito',
                            '$this->dezenove',
                            '$this->vinte'
                        )";
            if($helper->insert($insert)) return true;
            else return false;
        }

        public function atualizar($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_modelo_avaliacao SET 
                        titulo = '$this->titulo',
                        um = '$this->um',
                        dois = '$this->dois',
                        tres = '$this->tres',
                        quatro = '$this->quatro',
                        cinco = '$this->cinco',
                        seis = '$this->seis',
                        sete = '$this->sete',
                        oito = '$this->oito',
                        nove = '$this->nove',
                        dez = '$this->dez',
                        onze = '$this->onze',
                        doze = '$this->doze',
                        treze = '$this->treze',
                        quatorze = '$this->quatorze',
                        quinze = '$this->quinze',
                        dezesseis = '$this->dezesseis',
                        dezessete = '$this->dezessete',
                        dezoito = '$this->dezoito',
                        dezenove = '$this->dezenove',
                        vinte = '$this->vinte'
                        WHERE id = $this->ID";
            if($helper->update($update)) return true;
            else return false;
        }

        public function ativar($database_empresa) {
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
    
                $conexao_empresa = new ConexaoEmpresa($database_empresa);
                $conn = $conexao_empresa->conecta();
                $helper = new QueryHelper($conn);
    
                $update = "UPDATE tbl_modelo_avaliacao SET ativo = 1 WHERE id = $this->ID";
    
                if($helper->update($update)) return true;
                else return false;
            }

        public function desativar($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_modelo_avaliacao SET ativo = 0 WHERE id = $this->ID";

            if($helper->update($update)) return true;
            else return false;
        }

        public function retornarModeloAvaliacao($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao_empresa = new ConexaoEmpresa($database_empresa);
            $conn = $conexao_empresa->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT * FROM tbl_modelo_avaliacao WHERE id = $this->ID";
            $f = $helper->select($select, 2);

            $modelo = new ModeloAvaliacao();
            $modelo->setID($f['id']);
            $modelo->setTitulo($f['titulo']);
            $modelo->setCpfCriador($f['cpf_criador']);
            $modelo->setUm($f['um']);
            $modelo->setDois($f['dois']);
            $modelo->setTres($f['tres']);
            $modelo->setQuatro($f['quatro']);
            $modelo->setCinco($f['cinco']);
            $modelo->setSeis($f['seis']);
            $modelo->setSete($f['sete']);
            $modelo->setOito($f['oito']);
            $modelo->setNove($f['nove']);
            $modelo->setDez($f['dez']);
            $modelo->setOnze($f['onze']);
            $modelo->setDoze($f['doze']);
            $modelo->setTreze($f['treze']);
            $modelo->setQuatorze($f['quatorze']);
            $modelo->setQuinze($f['quinze']);
            $modelo->setDezesseis($f['dezesseis']);
            $modelo->setDezessete($f['dezessete']);
            $modelo->setDezoito($f['dezoito']);
            $modelo->setDezenove($f['dezenove']);
            $modelo->setVinte($f['vinte']);
            $modelo->setAtivo($f['ativo']);

            return $modelo;
        }

        function popularSelect($database_empresa, $documento = false) {

                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT 
                                DISTINCT t2.col_cpf as cpf,
                                t2.col_nome_completo as nome
                        FROM tbl_colaborador_modelo_avaliacao t1 
                                INNER JOIN tbl_colaborador t2 
                                        ON t2.col_cpf = t1.col_cpf AND t2.col_ativo = 1
                        WHERE t1.modelo_id = $this->ID";        
                $query = $helper->select($select, 1);
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<option value='.$f['cpf'].'>'.strtoupper($f['nome']).'</option>';
                }
        
            }
        
            function popularSelectAtribuidosMultiple($database_empresa) {
        
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $select = "SELECT 
                                DISTINCT t2.col_cpf as cpf,
                                t2.col_nome_completo as nome
                           FROM tbl_colaborador_modelo_avaliacao t1 
                                INNER JOIN tbl_colaborador t2 
                                        ON t2.col_cpf = t1.col_cpf AND t2.col_ativo = 1
                           WHERE t1.modelo_id = $this->ID";
        
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                        echo 'Nenhum encontrado';
                        return false;
                }
        
                while($f = mysqli_fetch_assoc($query)) {
                    echo '<input type="checkbox" id="colaboradores[]" name="colaboradores[]" value='.$f['cpf'].'> '.strtoupper($f['nome']).'<br>';
                }
        
            }

            function atribuirColaborador($database_empresa, $col_cpf) {
        
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);

                $this->retirarColaborador($database_empresa, $col_cpf);
        
                $insert = "INSERT INTO tbl_colaborador_modelo_avaliacao (col_cpf, modelo_id) 
                VALUES ('$col_cpf', $this->ID)";

                if($helper->insert($insert)) return true;
                else return false;
        
            }

            function retirarColaborador($database_empresa, $col_cpf) {
        
                require_once('class_conexao_empresa.php');
                require_once('class_queryHelper.php');
        
                $conexao = new ConexaoEmpresa($database_empresa);
                $conexao = $conexao->conecta();
                $helper = new QueryHelper($conexao);
        
                $delete = "DELETE FROM tbl_colaborador_modelo_avaliacao WHERE modelo_id = $this->ID AND col_cpf = '$col_cpf'";

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
         * Get the value of cpfCriador
         */ 
        public function getCpfCriador()
        {
                return $this->cpfCriador;
        }

        /**
         * Set the value of cpfCriador
         *
         * @return  self
         */ 
        public function setCpfCriador($cpfCriador)
        {
                $this->cpfCriador = $cpfCriador;

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
         * Get the value of um
         */ 
        public function getUm()
        {
                return $this->um;
        }

        /**
         * Set the value of um
         *
         * @return  self
         */ 
        public function setUm($um)
        {
                $this->um = $um;

                return $this;
        }

        /**
         * Get the value of dois
         */ 
        public function getDois()
        {
                return $this->dois;
        }

        /**
         * Set the value of dois
         *
         * @return  self
         */ 
        public function setDois($dois)
        {
                $this->dois = $dois;

                return $this;
        }

        /**
         * Get the value of tres
         */ 
        public function getTres()
        {
                return $this->tres;
        }

        /**
         * Set the value of tres
         *
         * @return  self
         */ 
        public function setTres($tres)
        {
                $this->tres = $tres;

                return $this;
        }

        /**
         * Get the value of quatro
         */ 
        public function getQuatro()
        {
                return $this->quatro;
        }

        /**
         * Set the value of quatro
         *
         * @return  self
         */ 
        public function setQuatro($quatro)
        {
                $this->quatro = $quatro;

                return $this;
        }

        /**
         * Get the value of cinco
         */ 
        public function getCinco()
        {
                return $this->cinco;
        }

        /**
         * Set the value of cinco
         *
         * @return  self
         */ 
        public function setCinco($cinco)
        {
                $this->cinco = $cinco;

                return $this;
        }

        /**
         * Get the value of seis
         */ 
        public function getSeis()
        {
                return $this->seis;
        }

        /**
         * Set the value of seis
         *
         * @return  self
         */ 
        public function setSeis($seis)
        {
                $this->seis = $seis;

                return $this;
        }

        /**
         * Get the value of sete
         */ 
        public function getSete()
        {
                return $this->sete;
        }

        /**
         * Set the value of sete
         *
         * @return  self
         */ 
        public function setSete($sete)
        {
                $this->sete = $sete;

                return $this;
        }

        /**
         * Get the value of oito
         */ 
        public function getOito()
        {
                return $this->oito;
        }

        /**
         * Set the value of oito
         *
         * @return  self
         */ 
        public function setOito($oito)
        {
                $this->oito = $oito;

                return $this;
        }

        /**
         * Get the value of nove
         */ 
        public function getNove()
        {
                return $this->nove;
        }

        /**
         * Set the value of nove
         *
         * @return  self
         */ 
        public function setNove($nove)
        {
                $this->nove = $nove;

                return $this;
        }

        /**
         * Get the value of dez
         */ 
        public function getDez()
        {
                return $this->dez;
        }

        /**
         * Set the value of dez
         *
         * @return  self
         */ 
        public function setDez($dez)
        {
                $this->dez = $dez;

                return $this;
        }

        /**
         * Get the value of onze
         */ 
        public function getOnze()
        {
                return $this->onze;
        }

        /**
         * Set the value of onze
         *
         * @return  self
         */ 
        public function setOnze($onze)
        {
                $this->onze = $onze;

                return $this;
        }

        /**
         * Get the value of doze
         */ 
        public function getDoze()
        {
                return $this->doze;
        }

        /**
         * Set the value of doze
         *
         * @return  self
         */ 
        public function setDoze($doze)
        {
                $this->doze = $doze;

                return $this;
        }

        /**
         * Get the value of treze
         */ 
        public function getTreze()
        {
                return $this->treze;
        }

        /**
         * Set the value of treze
         *
         * @return  self
         */ 
        public function setTreze($treze)
        {
                $this->treze = $treze;

                return $this;
        }

        /**
         * Get the value of quatorze
         */ 
        public function getQuatorze()
        {
                return $this->quatorze;
        }

        /**
         * Set the value of quatorze
         *
         * @return  self
         */ 
        public function setQuatorze($quatorze)
        {
                $this->quatorze = $quatorze;

                return $this;
        }

        /**
         * Get the value of quinze
         */ 
        public function getQuinze()
        {
                return $this->quinze;
        }

        /**
         * Set the value of quinze
         *
         * @return  self
         */ 
        public function setQuinze($quinze)
        {
                $this->quinze = $quinze;

                return $this;
        }

        /**
         * Get the value of dezesseis
         */ 
        public function getDezesseis()
        {
                return $this->dezesseis;
        }

        /**
         * Set the value of dezesseis
         *
         * @return  self
         */ 
        public function setDezesseis($dezesseis)
        {
                $this->dezesseis = $dezesseis;

                return $this;
        }

        /**
         * Get the value of dezessete
         */ 
        public function getDezessete()
        {
                return $this->dezessete;
        }

        /**
         * Set the value of dezessete
         *
         * @return  self
         */ 
        public function setDezessete($dezessete)
        {
                $this->dezessete = $dezessete;

                return $this;
        }

        /**
         * Get the value of dezoito
         */ 
        public function getDezoito()
        {
                return $this->dezoito;
        }

        /**
         * Set the value of dezoito
         *
         * @return  self
         */ 
        public function setDezoito($dezoito)
        {
                $this->dezoito = $dezoito;

                return $this;
        }

        /**
         * Get the value of dezenove
         */ 
        public function getDezenove()
        {
                return $this->dezenove;
        }

        /**
         * Set the value of dezenove
         *
         * @return  self
         */ 
        public function setDezenove($dezenove)
        {
                $this->dezenove = $dezenove;

                return $this;
        }

        /**
         * Get the value of vinte
         */ 
        public function getVinte()
        {
                return $this->vinte;
        }

        /**
         * Set the value of vinte
         *
         * @return  self
         */ 
        public function setVinte($vinte)
        {
                $this->vinte = $vinte;

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
    }

?>