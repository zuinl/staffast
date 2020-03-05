<?php
class ConexaoEmpresa {
		
		private $host;
		private $user;
		private $password;
        private $database;
        
        function __construct($database) {
			$this->database = $database;
			
			if($_SERVER["SERVER_NAME"] == 'localhost') {
				$this->host = "localhost";
				$this->user = "root";
				$this->password = "";
			} else {
				$this->host = "108.179.253.15";
				$this->user = "siste002_staffas";
				$this->password = "wanderlustis18";
			}
        }

		function conecta() {
			try {
				$conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
			}
			catch (Exception $e) {
    			echo 'Exceção capturada: ',  $e->getMessage(), "\n";
			}
			return $conn;
        }
        
        function desconecta($conexao) {
            mysqli_close($conexao);
        }

	}
?>