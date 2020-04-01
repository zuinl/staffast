<?php
class ConexaoBlog {

		private $host;
		private $user;
		private $password;
		private $database;

		function conecta() {

			if($_SERVER["SERVER_NAME"] == 'localhost') {
				$this->host = "localhost";
				$this->user = "root";
				$this->password = "";
				$this->database = "db_staffast_blog";
			} else {
				$this->host = "108.179.253.15";
				$this->user = "siste002_staffas";
				$this->password = "wanderlustis18";
				$this->database = "siste002_staffast_blog";
			}

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


		/**
		 * Get the value of database
		 */ 
		public function getDatabase()
		{
				return $this->database;
		}

		/**
		 * Set the value of database
		 *
		 * @return  self
		 */ 
		public function setDatabase($database)
		{
				$this->database = $database;

				return $this;
		}
	}
?>