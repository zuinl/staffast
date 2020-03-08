<?php

    class Ponto {
        private $ID;
        private $cpf;
        private $data;
        private $data_format;
        private $hora;
        private $tipo;
        private $latitude;
        private $longitude;
        private $endereco;
        private $editado;
        private $cpfEdicao;
        private $dataEdicao;
        private $motivoEdicao;
        private $anotacoes;

        //Esta função identifica o funcionário e retorna seu nome, cpf e nome da empresa
        public function identificarFuncionario($email) {
            require_once 'class_conexao_padrao.php';
            require_once 'class_conexao_empresa.php';
            require_once 'class_usuario.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoPadrao();
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT
                        t1.usu_id as id, 
                        t2.emp_database as db,
                        t2.emp_razao_social as empresa 
                       FROM tbl_usuario t1 
                       INNER JOIN tbl_empresa t2
                        ON t2.emp_id = t1.emp_id AND t2.emp_ativo = 1 
                       WHERE t1.usu_email = '$email'";
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) {
                return 2;
            }

            $f = mysqli_fetch_assoc($query);

            $database = $f['db'];
            $empresa = $f['empresa'];
            $usu_id = $f['id'];

            $conexao = new ConexaoEmpresa($database);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT 
                        col_cpf as cpf,
                        col_nome_completo as nome
                       FROM tbl_colaborador 
                       WHERE usu_id = $usu_id 
                       AND col_ativo = 1";
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) {
                $select = "SELECT 
                        ges_cpf as cpf,
                        ges_nome_completo as nome
                       FROM tbl_gestor 
                       WHERE usu_id = $usu_id 
                       AND ges_ativo = 1";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) == 0) {
                    return 3;
                }
            }

            $f = mysqli_fetch_assoc($query);

            $nome = $f['nome'];
            $cpf = $f['cpf'];

            $dados = array(
                "nome" => $nome,
                "cpf" => $cpf,
                "empresa" => $empresa,
                "database" => $database
            );

            $this->cpf = $cpf;

            return $dados;
        }

        //Esta função será a responsável por checar se o funcionário possui alguma divergência de ponto dentro daquele dia
        public function checarRombo($tipo, $data, $cpf, $database_empresa) {
            require_once 'class_conexao_empresa.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            
        }

        //Registrar o ponto
        public function registrarPonto($tipo, $data, $cpf, $database_empresa, $latitude = '', $longitude = '') {
            require_once 'class_conexao_empresa.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            //Verificando se funcionário é noturno
            $select = "SELECT noturno FROM tbl_funcionario_horario WHERE cpf = '$cpf' AND dt_final IS NULL";
            $f = $helper->select($select, 2);
            $noturno = (int)$f['noturno'];

            $data_inicio = substr($data, 0, 10).' 00:00:00';
            $data_fim = substr($data, 0, 10).' 23:59:59';
            switch($tipo) {
                case 1:
                    $select = "SELECT id FROM tbl_ponto 
                                WHERE (data >= '$data_inicio' AND data <= '$data_fim') 
                                AND cpf = '$cpf' 
                                AND tipo = 1";
                    $query = $helper->select($select, 1);
                    if(mysqli_num_rows($query) > 0 && $noturno != 1) { //já existe entrada hoje
                        return 2;
                    } else if ($noturno === 1 && $this->travarSequencia($database_empresa, substr($data, 11, 8), $cpf, $tipo)) {
                        return 10; //existe entrada menos de uma hora atrás
                    }

                    $insert = "INSERT INTO tbl_ponto 
                    (
                    cpf,
                    data,
                    tipo,
                    latitude,
                    longitude
                    ) VALUES (
                    '$cpf',
                    '$data',
                    $tipo,
                    '$latitude',
                    '$longitude'
                    )";
                    if($helper->insert($insert)) return true;
                    else return 3;
                    break;

                case 2:
                    $select = "SELECT id FROM tbl_ponto 
                                WHERE (data >= '$data_inicio' AND data <= '$data_fim') 
                                AND cpf = '$cpf' 
                                AND tipo = 2";
                    $query = $helper->select($select, 1);
                    if(mysqli_num_rows($query) > 0 && $noturno != 1) { //já existe pausa hoje
                        return 4;
                    }

                    $insert = "INSERT INTO tbl_ponto 
                    (
                    cpf,
                    data,
                    tipo,
                    latitude,
                    longitude
                    ) VALUES (
                    '$cpf',
                    '$data',
                    $tipo,
                    '$latitude',
                    '$longitude'
                    )";
                    if($helper->insert($insert)) return true;
                    else return 5;
                    break;

                case 3:
                    $select = "SELECT id FROM tbl_ponto 
                                WHERE (data >= '$data_inicio' AND data <= '$data_fim') 
                                AND cpf = '$cpf' 
                                AND tipo = 3";
                    $query = $helper->select($select, 1);
                    if(mysqli_num_rows($query) > 0 && $noturno != 1) { //já existe pausa hoje
                        return 6;
                    }

                    $insert = "INSERT INTO tbl_ponto 
                    (
                    cpf,
                    data,
                    tipo,
                    latitude,
                    longitude
                    ) VALUES (
                    '$cpf',
                    '$data',
                    $tipo,
                    '$latitude',
                    '$longitude'
                    )";
                    if($helper->insert($insert)) return true;
                    else return 7;
                    break;
                
                case 4:
                    $select = "SELECT id FROM tbl_ponto 
                                WHERE (data >= '$data_inicio' AND data <= '$data_fim') 
                                AND cpf = '$cpf' 
                                AND tipo = 4";
                    $query = $helper->select($select, 1);
                    if(mysqli_num_rows($query) > 0 && $noturno != 1) { //já existe saída hoje
                        return 8;
                    } else if ($noturno === 1 && $this->travarSequencia($database_empresa, substr($data, 11, 8), $cpf, $tipo)) {
                        return 11; //registrou saída menos de uma hora atrás
                    } 

                    $insert = "INSERT INTO tbl_ponto 
                    (
                    cpf,
                    data,
                    tipo,
                    latitude,
                    longitude
                    ) VALUES (
                    '$cpf',
                    '$data',
                    $tipo,
                    '$latitude',
                    '$longitude'
                    )";
                    if($helper->insert($insert)) return true;
                    else return 9;
                    break;
            }
        }

        //Inserir uma anotaçao no ponto mais recente
        public function anotar($cpf, $anotacoes, $database_empresa) {
            require_once 'class_conexao_empresa.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $update = "UPDATE tbl_ponto SET anotacoes = '$anotacoes' WHERE cpf = '$cpf' ORDER BY id DESC LIMIT 1";
            if($helper->update($update)) return true;
            else return false;
        }

        //Retornar ponto
        public function retornarPonto($database_empresa) {
            require_once 'class_conexao_empresa.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT id, 
            DATE_FORMAT(data, '%d/%m/%Y') as dia,
            DATE_FORMAT(data, '%H:%i:%s') as hora,
            DATE_FORMAT(data, '%Y-%m-%d') as data_format,
            tipo,
            latitude,
            longitude,
            endereco
             FROM tbl_ponto WHERE id = $this->ID";
            $f = $helper->select($select, 2);

            $ponto = new Ponto();
            $ponto->setID($f['id']);
            $ponto->setData($f['dia']);
            $ponto->setData_format($f['data_format']);
            $ponto->setHora($f['hora']);
            $ponto->setTipo($f['tipo']);
            $ponto->setLatitude($f['latitude']);
            $ponto->setLongitude($f['longitude']);
            $ponto->setEndereco($f['endereco']);

            return $ponto;
        }

        //Checar se houve registro de entrada ou saída menos de 1 hora atrás
        public function travarSequencia($database_empresa, $hora, $cpf, $tipo) {
                require_once 'class_conexao_empresa.php';
                require_once 'class_queryHelper.php';

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $hora_add = substr($hora, 0, 2);
                $hora_add = (int)$hora_add;
                $hora_add--;

                $data1 = date('Y-m-d').' '.$hora;
                $data2 = date('Y-m-d').' '.$hora_add.substr($hora, 2, 6);

                $select = "SELECT id FROM tbl_ponto WHERE cpf = '$cpf' AND tipo = $tipo 
                AND (data >= '$data2' AND data <= '$data1')";
                $query = $helper->select($select, 1);

                if(mysqli_num_rows($query) > 0) {
                        return true;
                } else {
                        return false;
                }
        }

        //Retornar array com histórico por mês
        public function retornarHistorico($database_empresa, $fechamento, $cpf, $mes = "", $ano = "") {
                require_once 'class_conexao_empresa.php';
                require_once 'class_queryHelper.php';

                $conexao = new ConexaoEmpresa($database_empresa);
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                if($mes == "") $mes = date('m');
                if($ano == "") $ano = date('Y');
                
                //Montando filtros
                 if($mes == "01" || $mes == 1) {
                    $dataInicial = (string)($ano-1).'-12-'.$fechamento;
                    $dataFinal = (string)$ano.'-'.$mes.'-'.$fechamento;
                 } else {
                    $dataInicial = (string)$ano.'-'.($mes-1).'-'.$fechamento;
                    $dataFinal = (string)$ano.'-'.$mes.'-'.$fechamento;    
                 }
                
                $select = "SELECT 
                                DISTINCT DATE_FORMAT(data, '%Y-%m-%d') AS data 
                           FROM tbl_ponto 
                           WHERE cpf = '$cpf' AND 
                                (data >= '$dataInicial' AND data <= '$dataFinal') 
                           ORDER BY data DESC";
                $query = $helper->select($select, 1);

                $array_historico = array();

                if(mysqli_num_rows($query) > 0) {
                    $i = 0;
                    while($f = mysqli_fetch_assoc($query)) {
                        $data_atual = $f['data'];

                        $select = "SELECT 
                                        id 
                                   FROM tbl_ponto 
                                   WHERE cpf = '$cpf' 
                                   AND DATE_FORMAT(data, '%Y-%m-%d') = '$data_atual'";
                        $query_dia = $helper->select($select, 1);

                        $entrada = "Sem registro";
                        $pausa = "Sem registro";
                        $retorno = "Sem registro";
                        $saida = "Sem registro";
                        $data = "Sem registro";

                        $latitude_entrada = "";
                        $latitude_pausa = "";
                        $latitude_retorno = "";
                        $latitude_saida = "";

                        $longitude_entrada = "";
                        $longitude_pausa = "";
                        $longitude_retorno = "";
                        $longitude_saida = "";

                        $anotacao = "";

                        //Percorrendo todos os pontos daquele dia
                        while($f2 = mysqli_fetch_assoc($query_dia)) {
                                $this->setID($f2['id']);
                                $ponto = $this->retornarPonto($database_empresa);
        
                                $data = $ponto->getData().' - '.$this->getWeekday($ponto->getData_format());
                                $anotacao .= $ponto->getAnotacoes().'<br>';
                                
                                $tipo = "";
                                switch($ponto->getTipo()) {
                                case 1: 
                                        $entrada = $ponto->getHora(); 
                                        $latitude_entrada = $ponto->getLatitude();
                                        $longitude_entrada = $ponto->getLongitude();
                                        break;
                                case 2: 
                                        $pausa = $ponto->getHora(); 
                                        $latitude_pausa = $ponto->getLatitude();
                                        $longitude_pausa = $ponto->getLongitude();
                                        break;
                                case 3: 
                                        $retorno = $ponto->getHora(); 
                                        $latitude_retorno = $ponto->getLatitude();
                                        $longitude_retorno = $ponto->getLongitude();
                                        break;
                                case 4: 
                                        $saida = $ponto->getHora(); 
                                        $latitude_saida = $ponto->getLatitude();
                                        $longitude_saida = $ponto->getLongitude();
                                        break;
                                }
                        }

                        //Montando array daquele dia
                        $array_historico[$i] = array(
                                "sucesso" => true,
                                "data" => $data,
                                "entrada" => $entrada,
                                "entrada_latitude" => $latitude_entrada,
                                "entrada_longitude" => $longitude_entrada,
                                "pausa" => $pausa,
                                "pausa_latitude" => $latitude_pausa,
                                "pausa_longitude" => $longitude_pausa,
                                "retorno" => $retorno,
                                "retorno_latitude" => $latitude_retorno,
                                "retorno_longitude" => $longitude_retorno,
                                "saida" => $saida,
                                "saida_latitude" => $latitude_saida,
                                "saida_longitude" => $longitude_saida,
                                "anotacao" => $anotacao
                        );
                        $i++;
                    }
                } else {
                        $array_historico[0] = array(
                                "sucesso" => true,
                                "data" => "Sem registros",
                                "entrada" => "Sem registros",
                                "entrada_latitude" => "Sem registros",
                                "entrada_longitude" => "Sem registros",
                                "pausa" => "Sem registros",
                                "pausa_latitude" => "Sem registros",
                                "pausa_longitude" => "Sem registros",
                                "retorno" => "Sem registros",
                                "retorno_latitude" => "Sem registros",
                                "retorno_longitude" => "Sem registros",
                                "saida" => "Sem registros",
                                "saida_latitude" => "Sem registros",
                                "saida_longitude" => "Sem registros",
                                "anotacao" => ""
                            );
                }
                return $array_historico;
        }

        //Retorna string com descrição de atraso
        public function retornarAtraso($horarioCorreto, $horarioRegistrado, $tolerancia = 0) {
            $entradaCorreta = strtotime($horarioCorreto) + 60 * $tolerancia;
            $entradaRegistrada = strtotime($horarioRegistrado);

            $atraso = abs($entradaRegistrada - $entradaCorreta) / 3600 * 60;
            
            if($atraso >= 1 && $atraso < 60) $atraso = round($atraso, 0);

            if($entradaRegistrada < $entradaCorreta) return 'Sem atraso';

            if ($atraso < 1) {
                return ($atraso * 60).' segundo(s)';
            } else if($atraso == 1) {
                return $atraso.' minuto';
            } else if($atraso > 1 && $atraso < 60) {
                return $atraso.' minutos';
            } else if($atraso >= 60) {
                if(($atraso % 60) == 0) {
                    return ($atraso / 60).' hora(s)';
                } else {
                    $atrasoMinutos = $atraso % 60;
                    $atraso = $atraso / 60;
                    if($atraso < 10) {
                        $atrasoHora = substr($atraso, 0, 1);
                    } else {
                        $atrasoHora = substr($atraso, 0, 2);
                    }
                    return $atrasoHora.' hora(s) e '.$atrasoMinutos.' minuto(s)';
                }  
            }
            return 'Sem atraso';
        }

        //Retorna string com descrição de hora extra
        public function retornarExtra($horarioCorreto, $horarioRegistrado) {
                $entradaCorreta = strtotime($horarioCorreto);
                $entradaRegistrada = strtotime($horarioRegistrado);
    
                $extra = abs($entradaRegistrada - $entradaCorreta) / 3600 * 60;
                
                if($extra >= 1 && $extra < 60) $extra = round($extra, 0);
    
                if($entradaRegistrada < $entradaCorreta) return 'Sem extra';
    
                if ($extra < 1) {
                    return ($extra * 60).' segundo(s)';
                } else if($extra == 1) {
                    return $extra.' minuto';
                } else if($extra > 1 && $extra < 60) {
                    return $extra.' minutos';
                } else if($extra >= 60) {
                    if(($extra % 60) == 0) {
                        return ($extra / 60).' hora(s)';
                    } else {
                        $extraMinutos = $extra % 60;
                        $extra = $extra / 60;
                        if($extra < 10) {
                            $extraHora = substr($extra, 0, 1);
                        } else {
                            $extraHora = substr($extra, 0, 2);
                        }
                        return $extraHora.' hora(s) e '.$extraMinutos.' minuto(s)';
                    }  
                }
                return 'Sem extra';
            }

        //Retorna string com o dia da semana em portugês
        public function getWeekday($day) {
                $dia = date('w', strtotime($day));
                switch($dia) {
                    case 0: return 'Domingo'; break;
                    case 1: return 'Segunda-feira'; break;
                    case 2: return 'Terça-feira'; break;
                    case 3: return 'Quarta-feira'; break;
                    case 4: return 'Quinta-feira'; break;
                    case 5: return 'Sexta-feira'; break;
                    case 6: return 'Sábado'; break;
                    default: return 'Dia da semana'; break;
                }
        }

        //Retorna booleano informando se funcionário é autorizado a bater ponto no site
        public function isAutorizadoSite($database_empresa, $cpf) {
            require_once 'class_conexao_empresa.php';
            require_once 'class_queryHelper.php';

            $conexao = new ConexaoEmpresa($database_empresa);
            $conn = $conexao->conecta();
            $helper = new QueryHelper($conn);

            $select = "SELECT ponto_site FROM tbl_funcionario_horario WHERE cpf = '$cpf' AND dt_final IS NULL";
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) > 0) {
                $f = mysqli_fetch_assoc($query);
                $ponto_site = (int)$f['ponto_site'];

                if($ponto_site === 1) return true;
                else return false;
            } else {
                return false;
            }
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
         * Get the value of latitude
         */ 
        public function getLatitude()
        {
                return $this->latitude;
        }

        /**
         * Set the value of latitude
         *
         * @return  self
         */ 
        public function setLatitude($latitude)
        {
                $this->latitude = $latitude;

                return $this;
        }

        /**
         * Get the value of longitude
         */ 
        public function getLongitude()
        {
                return $this->longitude;
        }

        /**
         * Set the value of longitude
         *
         * @return  self
         */ 
        public function setLongitude($longitude)
        {
                $this->longitude = $longitude;

                return $this;
        }

        /**
         * Get the value of editado
         */ 
        public function getEditado()
        {
                return $this->editado;
        }

        /**
         * Set the value of editado
         *
         * @return  self
         */ 
        public function setEditado($editado)
        {
                $this->editado = $editado;

                return $this;
        }

        /**
         * Get the value of cpfEdicao
         */ 
        public function getCpfEdicao()
        {
                return $this->cpfEdicao;
        }

        /**
         * Set the value of cpfEdicao
         *
         * @return  self
         */ 
        public function setCpfEdicao($cpfEdicao)
        {
                $this->cpfEdicao = $cpfEdicao;

                return $this;
        }

        /**
         * Get the value of dataEdicao
         */ 
        public function getDataEdicao()
        {
                return $this->dataEdicao;
        }

        /**
         * Set the value of dataEdicao
         *
         * @return  self
         */ 
        public function setDataEdicao($dataEdicao)
        {
                $this->dataEdicao = $dataEdicao;

                return $this;
        }

        /**
         * Get the value of motivoEdicao
         */ 
        public function getMotivoEdicao()
        {
                return $this->motivoEdicao;
        }

        /**
         * Set the value of motivoEdicao
         *
         * @return  self
         */ 
        public function setMotivoEdicao($motivoEdicao)
        {
                $this->motivoEdicao = $motivoEdicao;

                return $this;
        }

        /**
         * Get the value of anotacoes
         */ 
        public function getAnotacoes()
        {
                return $this->anotacoes;
        }

        /**
         * Set the value of anotacoes
         *
         * @return  self
         */ 
        public function setAnotacoes($anotacoes)
        {
                $this->anotacoes = $anotacoes;

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
    }

?>