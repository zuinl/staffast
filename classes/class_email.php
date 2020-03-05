<?php

    class Email { 

        private $emailTo; 
        private $emailFrom;
        private $assunto;
        private $mensagem;

        public function enviar() {

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: Staffast <".$this->emailFrom.">";

            if(mail($this->emailTo, $this->assunto, $this->mensagem, $headers)) return true;
            else return false;

        }

        public function dispararTodos($emp_id) {

                require_once('class_conexao_padrao.php');
                require_once('class_queryHelper.php');
                include('../src/meta.php');

                $conexao = new ConexaoPadrao();
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT usu_email FROM tbl_usuario WHERE emp_id = ".$emp_id;

                $query = $helper->select($select, 1);

                $this->setEmailFrom(0);

                while($f = mysqli_fetch_assoc($query)) {
                     $this->setEmailTo($f['usu_email']);
                     $this->enviar();
                }

        }

        public function getEmailTo()
        {
                return $this->emailTo;
        }

        public function setEmailTo($emailTo)
        {
                $this->emailTo = $emailTo;

                return $this;
        }

        public function getEmailFrom()
        {
                return $this->emailFrom;
        }
 
        public function setEmailFrom($emailFrom = 0)
        {
                if($emailFrom == 0) $this->emailFrom = "suporte@sistemastaffast.com";
                else $this->emailFrom = $emailFrom;
        }

        public function getAssunto()
        {
                return $this->assunto;
        }

        public function setAssunto($assunto)
        {
                $this->assunto = $assunto;

                return $this;
        }

        public function getMensagem()
        {
                return $this->mensagem;
        }

        public function setMensagem($mensagem)
        {
                $this->mensagem = '<link rel="stylesheet" type="text/css" href="https://sistemastaffast.com/staffast/src/site.css">'.$mensagem;

                return $this;
        }
    }

?>