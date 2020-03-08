<?php

    class Horario {
        private $ID;
        private $cpf;
        private $dtFinal;
        private $entradaMonday;
        private $pausaMonday;
        private $retornoMonday;
        private $saidaMonday;
        private $entradaTuesday;
        private $pausaTuesday;
        private $retornoTuesday;
        private $saidaTuesday;
        private $entradaWednesday;
        private $pausaWednesday;
        private $retornoWednesday;
        private $saidaWednesday;
        private $entradaThursday;
        private $pausaThursday;
        private $retornoThursday;
        private $saidaThursday;
        private $entradaFriday;
        private $pausaFriday;
        private $retornoFriday;
        private $saidaFriday;
        private $entradaSaturday;
        private $pausaSaturday;
        private $retornoSaturday;
        private $saidaSaturday;
        private $entradaSunday;
        private $pausaSunday;
        private $retornoSunday;
        private $saidaSunday;
        private $noturno;
        private $pausaFlexivel;
        private $horarioFlexivel;
        private $horaExtra;
        private $tolerancia;
        private $pontoSite;

        public function cadastrar($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);

            $insert = "INSERT INTO tbl_funcionario_horario (
                        cpf,
                        entrada_monday,
                        pausa_monday,
                        retorno_monday,
                        saida_monday,
                        entrada_tuesday,
                        pausa_tuesday,
                        retorno_tuesday,
                        saida_tuesday,
                        entrada_wednesday,
                        pausa_wednesday,
                        retorno_wednesday,
                        saida_wednesday,
                        entrada_thursday,
                        pausa_thursday,
                        retorno_thursday,
                        saida_thursday,
                        entrada_friday,
                        pausa_friday,
                        retorno_friday,
                        saida_friday,
                        entrada_saturday,
                        pausa_saturday,
                        retorno_saturday,
                        saida_saturday,
                        entrada_sunday,
                        pausa_sunday,
                        retorno_sunday,
                        saida_sunday,
                        noturno,
                        pausa_flexivel,
                        horario_flexivel,
                        hora_extra,
                        tolerancia,
                        ponto_site) VALUES (
                        '$this->cpf',
                        '$this->entradaMonday',
                        '$this->pausaMonday',
                        '$this->retornoMonday',
                        '$this->saidaMonday',
                        '$this->entradaTuesday',
                        '$this->pausaTuesday',
                        '$this->retornoTuesday',
                        '$this->saidaTuesday',
                        '$this->entradaWednesday',
                        '$this->pausaWednesday',
                        '$this->retornoWednesday',
                        '$this->saidaWednesday',
                        '$this->entradaThursday',
                        '$this->pausaThursday',
                        '$this->retornoThursday',
                        '$this->saidaThursday',
                        '$this->entradaFriday',
                        '$this->pausaFriday',
                        '$this->retornoFriday',
                        '$this->saidaFriday',
                        '$this->entradaSaturday',
                        '$this->pausaSaturday',
                        '$this->retornoSaturday',
                        '$this->saidaSaturday',
                        '$this->entradaSunday',
                        '$this->pausaSunday',
                        '$this->retornoSunday',
                        '$this->saidaSunday',
                        $this->noturno,
                        $this->pausaFlexivel,
                        $this->horarioFlexivel,
                        $this->horaExtra,
                        $this->tolerancia,
                        $this->pontoSite
                        )";
            if($helper->insert($insert)) return true;
            else return false;
        }

        public function retornarHorarioHoje($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);

            date_default_timezone_set('America/Sao_Paulo');
            $dia = date('w');

            switch($dia) {
                case 0: 
                    $campo_dia = '_sunday'; break;
                case 1: 
                    $campo_dia = '_monday'; break;
                case 2: 
                    $campo_dia = '_tuesday'; break;
                case 3: 
                    $campo_dia = '_wednesday'; break;
                case 4: 
                    $campo_dia = '_thursday'; break;
                case 5: 
                    $campo_dia = '_friday'; break;
                case 6: 
                    $campo_dia = '_saturday'; break;
            }

            $select = "SELECT (
                        entrada".$campo_dia." as entrada, 
                        pausa".$campo_dia." as pausa,
                        retorno".$campo_dia." as retorno,
                        saida".$campo_dia." as saida 
                       FROM tbl_funcionario_horario 
                       WHERE cpf = '$this->cpf' AND dt_final IS NULL";
            $fetch = $helper->select($select, 2);

            $entrada = $fetch['entrada'] != "" ? substr($fetch['entrada'], 0, 5) : "Sem registro";
            $pausa = $fetch['pausa'] != "" ? substr($fetch['pausa'], 0, 5) : "Sem registro";
            $retorno = $fetch['retorno'] != "" ? substr($fetch['retorno'], 0, 5) : "Sem registro";
            $saida = $fetch['saida'] != "" ? substr($fetch['saida'], 0, 5) : "Sem registro";

            $array = array(
                "entrada" => $entrada,
                "pausa" => $pausa,
                "retorno" => $retorno,
                "saida" => $saida
            );

            return $array;
        }

        public function retornarHorario($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);

            $select = "SELECT * FROM tbl_funcionario_horario WHERE cpf = '$this->cpf' AND dt_final IS NULL";
            $f = $helper->select($select, 2);

            $horario = new Horario();
            $horario->setID($f['id']);
            $horario->setCpf($f['cpf']);

            $horario->setEntradaMonday($f['entrada_monday']);
            $horario->setPausaMonday($f['pausa_monday']);
            $horario->setRetornoMonday($f['retorno_monday']);
            $horario->setSaidaMonday($f['saida_monday']);

            $horario->setEntradaTuesday($f['entrada_tuesday']);
            $horario->setPausaTuesday($f['pausa_tuesday']);
            $horario->setRetornoTuesday($f['retorno_tuesday']);
            $horario->setSaidaTuesday($f['saida_tuesday']);

            $horario->setEntradaWednesday($f['entrada_wednesday']);
            $horario->setPausaWednesday($f['pausa_wednesday']);
            $horario->setRetornoWednesday($f['retorno_wednesday']);
            $horario->setSaidaWednesday($f['saida_wednesday']);

            $horario->setEntradaThursday($f['entrada_thursday']);
            $horario->setPausaThursday($f['pausa_thursday']);
            $horario->setRetornoThursday($f['retorno_thursday']);
            $horario->setSaidaThursday($f['saida_thursday']);

            $horario->setEntradaFriday($f['entrada_friday']);
            $horario->setPausaFriday($f['pausa_friday']);
            $horario->setRetornoFriday($f['retorno_friday']);
            $horario->setSaidaFriday($f['saida_friday']);

            $horario->setEntradaSaturday($f['entrada_saturday']);
            $horario->setPausaSaturday($f['pausa_saturday']);
            $horario->setRetornoSaturday($f['retorno_saturday']);
            $horario->setSaidaSaturday($f['saida_saturday']);

            $horario->setEntradaSunday($f['entrada_sunday']);
            $horario->setPausaSunday($f['pausa_sunday']);
            $horario->setRetornoSunday($f['retorno_sunday']);
            $horario->setSaidaSunday($f['saida_sunday']);

            $horario->setNoturno($f['noturno']);
            $horario->setPausaFlexivel($f['pausa_flexivel']);
            $horario->setHorarioFlexivel($f['horario_flexivel']);
            $horario->setTolerancia($f['tolerancia']);
            $horario->setHoraExtra($f['hora_extra']);
            $horario->setPontoSite($f['ponto_site']);

            return $horario;
        }

        public function atualizarHorario($database_empresa) {
            require_once('class_conexao_empresa.php');
            require_once('class_queryHelper.php');

            $conexao = new ConexaoEmpresa($database_empresa);
            $conexao = $conexao->conecta();
            $helper = new QueryHelper($conexao);

            $update = "UPDATE tbl_funcionario_horario SET dt_final = CURRENT_TIMESTAMP WHERE cpf = '$this->cpf' 
            ORDER BY id DESC LIMIT 1";
            $helper->update($update);

            if($this->cadastrar($database_empresa)) return true;
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
         * Get the value of dtFinal
         */ 
        public function getDtFinal()
        {
                return $this->dtFinal;
        }

        /**
         * Set the value of dtFinal
         *
         * @return  self
         */ 
        public function setDtFinal($dtFinal)
        {
                $this->dtFinal = $dtFinal;

                return $this;
        }

        /**
         * Get the value of entradaMonday
         */ 
        public function getEntradaMonday()
        {
                return $this->entradaMonday;
        }

        /**
         * Set the value of entradaMonday
         *
         * @return  self
         */ 
        public function setEntradaMonday($entradaMonday)
        {
                $this->entradaMonday = $entradaMonday;

                return $this;
        }

        /**
         * Get the value of pausaMonday
         */ 
        public function getPausaMonday()
        {
                return $this->pausaMonday;
        }

        /**
         * Set the value of pausaMonday
         *
         * @return  self
         */ 
        public function setPausaMonday($pausaMonday)
        {
                $this->pausaMonday = $pausaMonday;

                return $this;
        }

        /**
         * Get the value of retornoMonday
         */ 
        public function getRetornoMonday()
        {
                return $this->retornoMonday;
        }

        /**
         * Set the value of retornoMonday
         *
         * @return  self
         */ 
        public function setRetornoMonday($retornoMonday)
        {
                $this->retornoMonday = $retornoMonday;

                return $this;
        }

        /**
         * Get the value of saidaMonday
         */ 
        public function getSaidaMonday()
        {
                return $this->saidaMonday;
        }

        /**
         * Set the value of saidaMonday
         *
         * @return  self
         */ 
        public function setSaidaMonday($saidaMonday)
        {
                $this->saidaMonday = $saidaMonday;

                return $this;
        }

        /**
         * Get the value of entradaTuesday
         */ 
        public function getEntradaTuesday()
        {
                return $this->entradaTuesday;
        }

        /**
         * Set the value of entradaTuesday
         *
         * @return  self
         */ 
        public function setEntradaTuesday($entradaTuesday)
        {
                $this->entradaTuesday = $entradaTuesday;

                return $this;
        }

        /**
         * Get the value of pausaTuesday
         */ 
        public function getPausaTuesday()
        {
                return $this->pausaTuesday;
        }

        /**
         * Set the value of pausaTuesday
         *
         * @return  self
         */ 
        public function setPausaTuesday($pausaTuesday)
        {
                $this->pausaTuesday = $pausaTuesday;

                return $this;
        }

        /**
         * Get the value of retornoTuesday
         */ 
        public function getRetornoTuesday()
        {
                return $this->retornoTuesday;
        }

        /**
         * Set the value of retornoTuesday
         *
         * @return  self
         */ 
        public function setRetornoTuesday($retornoTuesday)
        {
                $this->retornoTuesday = $retornoTuesday;

                return $this;
        }

        /**
         * Get the value of saidaTuesday
         */ 
        public function getSaidaTuesday()
        {
                return $this->saidaTuesday;
        }

        /**
         * Set the value of saidaTuesday
         *
         * @return  self
         */ 
        public function setSaidaTuesday($saidaTuesday)
        {
                $this->saidaTuesday = $saidaTuesday;

                return $this;
        }

        /**
         * Get the value of entradaWednesday
         */ 
        public function getEntradaWednesday()
        {
                return $this->entradaWednesday;
        }

        /**
         * Set the value of entradaWednesday
         *
         * @return  self
         */ 
        public function setEntradaWednesday($entradaWednesday)
        {
                $this->entradaWednesday = $entradaWednesday;

                return $this;
        }

        /**
         * Get the value of pausaWednesday
         */ 
        public function getPausaWednesday()
        {
                return $this->pausaWednesday;
        }

        /**
         * Set the value of pausaWednesday
         *
         * @return  self
         */ 
        public function setPausaWednesday($pausaWednesday)
        {
                $this->pausaWednesday = $pausaWednesday;

                return $this;
        }

        /**
         * Get the value of retornoWednesday
         */ 
        public function getRetornoWednesday()
        {
                return $this->retornoWednesday;
        }

        /**
         * Set the value of retornoWednesday
         *
         * @return  self
         */ 
        public function setRetornoWednesday($retornoWednesday)
        {
                $this->retornoWednesday = $retornoWednesday;

                return $this;
        }

        /**
         * Get the value of saidaWednesday
         */ 
        public function getSaidaWednesday()
        {
                return $this->saidaWednesday;
        }

        /**
         * Set the value of saidaWednesday
         *
         * @return  self
         */ 
        public function setSaidaWednesday($saidaWednesday)
        {
                $this->saidaWednesday = $saidaWednesday;

                return $this;
        }

        /**
         * Get the value of entradaThursday
         */ 
        public function getEntradaThursday()
        {
                return $this->entradaThursday;
        }

        /**
         * Set the value of entradaThursday
         *
         * @return  self
         */ 
        public function setEntradaThursday($entradaThursday)
        {
                $this->entradaThursday = $entradaThursday;

                return $this;
        }

        /**
         * Get the value of pausaThursday
         */ 
        public function getPausaThursday()
        {
                return $this->pausaThursday;
        }

        /**
         * Set the value of pausaThursday
         *
         * @return  self
         */ 
        public function setPausaThursday($pausaThursday)
        {
                $this->pausaThursday = $pausaThursday;

                return $this;
        }

        /**
         * Get the value of retornoThursday
         */ 
        public function getRetornoThursday()
        {
                return $this->retornoThursday;
        }

        /**
         * Set the value of retornoThursday
         *
         * @return  self
         */ 
        public function setRetornoThursday($retornoThursday)
        {
                $this->retornoThursday = $retornoThursday;

                return $this;
        }

        /**
         * Get the value of saidaThursday
         */ 
        public function getSaidaThursday()
        {
                return $this->saidaThursday;
        }

        /**
         * Set the value of saidaThursday
         *
         * @return  self
         */ 
        public function setSaidaThursday($saidaThursday)
        {
                $this->saidaThursday = $saidaThursday;

                return $this;
        }

        /**
         * Get the value of entradaFriday
         */ 
        public function getEntradaFriday()
        {
                return $this->entradaFriday;
        }

        /**
         * Set the value of entradaFriday
         *
         * @return  self
         */ 
        public function setEntradaFriday($entradaFriday)
        {
                $this->entradaFriday = $entradaFriday;

                return $this;
        }

        /**
         * Get the value of pausaFriday
         */ 
        public function getPausaFriday()
        {
                return $this->pausaFriday;
        }

        /**
         * Set the value of pausaFriday
         *
         * @return  self
         */ 
        public function setPausaFriday($pausaFriday)
        {
                $this->pausaFriday = $pausaFriday;

                return $this;
        }

        /**
         * Get the value of retornoFriday
         */ 
        public function getRetornoFriday()
        {
                return $this->retornoFriday;
        }

        /**
         * Set the value of retornoFriday
         *
         * @return  self
         */ 
        public function setRetornoFriday($retornoFriday)
        {
                $this->retornoFriday = $retornoFriday;

                return $this;
        }

        /**
         * Get the value of saidaFriday
         */ 
        public function getSaidaFriday()
        {
                return $this->saidaFriday;
        }

        /**
         * Set the value of saidaFriday
         *
         * @return  self
         */ 
        public function setSaidaFriday($saidaFriday)
        {
                $this->saidaFriday = $saidaFriday;

                return $this;
        }

        /**
         * Get the value of entradaSaturday
         */ 
        public function getEntradaSaturday()
        {
                return $this->entradaSaturday;
        }

        /**
         * Set the value of entradaSaturday
         *
         * @return  self
         */ 
        public function setEntradaSaturday($entradaSaturday)
        {
                $this->entradaSaturday = $entradaSaturday;

                return $this;
        }

        /**
         * Get the value of pausaSaturday
         */ 
        public function getPausaSaturday()
        {
                return $this->pausaSaturday;
        }

        /**
         * Set the value of pausaSaturday
         *
         * @return  self
         */ 
        public function setPausaSaturday($pausaSaturday)
        {
                $this->pausaSaturday = $pausaSaturday;

                return $this;
        }

        /**
         * Get the value of retornoSaturday
         */ 
        public function getRetornoSaturday()
        {
                return $this->retornoSaturday;
        }

        /**
         * Set the value of retornoSaturday
         *
         * @return  self
         */ 
        public function setRetornoSaturday($retornoSaturday)
        {
                $this->retornoSaturday = $retornoSaturday;

                return $this;
        }

        /**
         * Get the value of saidaSaturday
         */ 
        public function getSaidaSaturday()
        {
                return $this->saidaSaturday;
        }

        /**
         * Set the value of saidaSaturday
         *
         * @return  self
         */ 
        public function setSaidaSaturday($saidaSaturday)
        {
                $this->saidaSaturday = $saidaSaturday;

                return $this;
        }

        /**
         * Get the value of entradaSunday
         */ 
        public function getEntradaSunday()
        {
                return $this->entradaSunday;
        }

        /**
         * Set the value of entradaSunday
         *
         * @return  self
         */ 
        public function setEntradaSunday($entradaSunday)
        {
                $this->entradaSunday = $entradaSunday;

                return $this;
        }

        /**
         * Get the value of pausaSunday
         */ 
        public function getPausaSunday()
        {
                return $this->pausaSunday;
        }

        /**
         * Set the value of pausaSunday
         *
         * @return  self
         */ 
        public function setPausaSunday($pausaSunday)
        {
                $this->pausaSunday = $pausaSunday;

                return $this;
        }

        /**
         * Get the value of retornoSunday
         */ 
        public function getRetornoSunday()
        {
                return $this->retornoSunday;
        }

        /**
         * Set the value of retornoSunday
         *
         * @return  self
         */ 
        public function setRetornoSunday($retornoSunday)
        {
                $this->retornoSunday = $retornoSunday;

                return $this;
        }

        /**
         * Get the value of saidaSunday
         */ 
        public function getSaidaSunday()
        {
                return $this->saidaSunday;
        }

        /**
         * Set the value of saidaSunday
         *
         * @return  self
         */ 
        public function setSaidaSunday($saidaSunday)
        {
                $this->saidaSunday = $saidaSunday;

                return $this;
        }

        /**
         * Get the value of noturno
         */ 
        public function getNoturno()
        {
                return $this->noturno;
        }

        /**
         * Set the value of noturno
         *
         * @return  self
         */ 
        public function setNoturno($noturno)
        {
                $this->noturno = $noturno;

                return $this;
        }

        /**
         * Get the value of pausaFlexivel
         */ 
        public function getPausaFlexivel()
        {
                return $this->pausaFlexivel;
        }

        /**
         * Set the value of pausaFlexivel
         *
         * @return  self
         */ 
        public function setPausaFlexivel($pausaFlexivel)
        {
                $this->pausaFlexivel = $pausaFlexivel;

                return $this;
        }

        /**
         * Get the value of tolerancia
         */ 
        public function getTolerancia()
        {
                return $this->tolerancia;
        }

        /**
         * Set the value of tolerancia
         *
         * @return  self
         */ 
        public function setTolerancia($tolerancia)
        {
                $this->tolerancia = $tolerancia;

                return $this;
        }

        /**
         * Get the value of horarioFlexivel
         */ 
        public function getHorarioFlexivel()
        {
                return $this->horarioFlexivel;
        }

        /**
         * Set the value of horarioFlexivel
         *
         * @return  self
         */ 
        public function setHorarioFlexivel($horarioFlexivel)
        {
                $this->horarioFlexivel = $horarioFlexivel;

                return $this;
        }

        /**
         * Get the value of horaExtra
         */ 
        public function getHoraExtra()
        {
                return $this->horaExtra;
        }

        /**
         * Set the value of horaExtra
         *
         * @return  self
         */ 
        public function setHoraExtra($horaExtra)
        {
                $this->horaExtra = $horaExtra;

                return $this;
        }

        /**
         * Get the value of pontoSite
         */ 
        public function getPontoSite()
        {
                return $this->pontoSite;
        }

        /**
         * Set the value of pontoSite
         *
         * @return  self
         */ 
        public function setPontoSite($pontoSite)
        {
                $this->pontoSite = $pontoSite;

                return $this;
        }
    }

?>