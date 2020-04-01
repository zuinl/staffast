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

        public function dispararNewsletter() {
                require_once('class_conexao_padrao.php');
                require_once('class_queryHelper.php');
                include('../src/meta.php');

                $conexao = new ConexaoPadrao();
                $conn = $conexao->conecta();
                $helper = new QueryHelper($conn);

                $select = "SELECT DISTINCT email as email FROM tbl_newsletter_assinantes";
                $query = $helper->select($select, 1);

                while($f = mysqli_fetch_assoc($query)) {
                        $this->setEmailTo($f['email']);
                        $this->addUnsubscribe();
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
                if($emailFrom == 0) $this->emailFrom = "contato@sistemastaffast.com";
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
                $this->mensagem = '
                <html>
                    <head>
                        <title></title>
                        <style>
                        @import url("https://fonts.googleapis.com/css?family=Rubik&display=swap");

                        body {
                        margin-top: 4.5em;
                        margin-bottom: 2em;
                        font-family: "Rubik";
                        }
                        
                        .high-text {
                        color: #093f2f;
                        }
                        
                        .destaque-text {
                        color: rgb(0, 0, 0);
                        }
                        
                        .low-text {
                        color: #70757c;
                        }
                        
                        .big-text {
                        font-size: 3.5em;
                        }
                        
                        .button {
                        background-color: #4CAF50;
                        border-radius: 8px;
                        border: none;
                        color: white;
                        padding: 4px 8px;        
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 0.8em;
                        margin: 4px 2px;
                        -webkit-transition-duration: 0.4s;
                        transition-duration: 0.4s;
                        cursor: pointer;
                        font-family: "Rubik";
                        }
                        
                        .button1 {
                                background-color: #13a378;
                                color: white; 
                                border: 2px solid #13a378;
                                }
                        
                        .button1:hover {
                                background-color: white;
                                color: #13a378;
                                border: 2px solid #13a378;
                                }
                        
                        .button2 {
                                background-color: #13A330;
                                color: white; 
                                border: 2px solid #13A330;
                                }
                        
                        .button2:hover {
                                background-color: white;
                                color: #13A330;
                                border: 2px solid #13A330;
                                }  
                        
                        .button3 {
                                background-color: #1386A3;
                                color: white; 
                                border: 2px solid #1386A3;
                                }
                        
                        .button3:hover {
                                background-color: white;
                                color: #1386A3;
                                border: 2px solid #1386A3;
                                }
                        
                                .all-input {
                                        width: 100%;
                                        padding: 0.2em;
                                        font-size: 0.9em;
                                        border: 1px solid #13a378;
                                        border-radius: 6px;
                                }
                        
                        .fixed-div {
                        position: fixed;
                        left: 0;
                        bottom: 0;
                        width: 100%;
                        }
                        
                        .table-site {
                        margin-top: 1em;
                        font-family: "Rubik";
                        text-align: center;
                        border-collapse: collapse;
                        width: 100%;
                        }
                        
                        .table-site td, .table-sites th {
                        border: 1px solid #ddd;
                        padding: 0.5em;
                        }
                        
                        .table-site tr:nth-child(even){background-color:#d4fae8;}
                        
                        .table-site th {
                        padding-top: 0.6em;
                        padding-bottom: 0.6em;
                        padding-left: 0.8em;
                        padding-right: 0.8em;
                        text-align: center;
                        background-color:#13a378;
                        color: white;
                        }
                        
                        .hr-divide {
                        border-width: 0.1em; 
                        border-color: #13a378;
                        }
                        
                        .hr-divide-light {
                        border-width: 0.1em; 
                        border-color: rgb(59, 55, 55);
                        }
                        
                        .hr-divide-super-light {
                                border-width: 0.1em; 
                                border-color: #70757c;
                                }
                        
                        .hr-vertical-pequena {
                                height: 150px;/*Altura da linha*/
                                border-left: 2px solid;
                          }
                        
                          .radioMy {
                                width: 18px; height: 20px;
                        }
                        
                          .div-checkboxes {
                                height:14em; 
                                overflow: auto; 
                                border: 1px solid #13a378;
                                border-radius: 6px;
                                padding: 0.7em;
                          }
                        
                          .img-perfil {
                                margin-right: 1em; 
                                border-radius: 30px;
                                height: 80px;
                          }
                        
                        </style>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
                    </head>
                    <body style="text-align: center; font-family: Arial">
                    <div>
                        <img src="http://sistemastaffast.com/staffast/img/logo_staffast.png" width="200">
                    </div>
                    <hr class="hr-divide-super-light">
                    '.$mensagem.'
                    </body>
                </html>';

                return $this;
        }

        public function addUnsubscribe() {
                $this->mensagem .= '
                        <div class="row" style="text-align: center; font-size: 0.7em; margin-top: 2em;">
                            <div class="col-sm">
                                <a href="https://sistemastaffast.com/staffast/newsletter/unsubscribe.php?email='.$this->emailTo.'" target="_blank">Não receber mais e-mails assim</a>
                            </div>
                        </div>';
        }
    }

?>