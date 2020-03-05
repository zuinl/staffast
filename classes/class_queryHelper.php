<?php
class QueryHelper {
		private $conn;

		function __construct($conn) {
			$this->conn = $conn;
		}

		function insert($consulta) {
			$insert = mysqli_query($this->conn, $consulta);
			if(!$insert) echo mysqli_error($this->conn);

			if($insert) {
				return true;
			} else {
				return false;
			}
		}

		function select($consulta, $tipoRetorno = 1) {
			try {
				$query = mysqli_query($this->conn, $consulta);
				if(!$query) echo mysqli_error($this->conn);

				if($tipoRetorno == 1) return $query;

                $fetch = mysqli_fetch_assoc($query);
                
                if($tipoRetorno == 2) return $fetch;
			}
			catch (Exception $e) {
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                
                return false;
			}
		}

		function update($update) {
			try {
                $query = mysqli_query($this->conn, $update);
				if(!$query) echo mysqli_error($this->conn);
				else return true;
			}
			catch (Exception $e) {
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                return false;
			}
		}

		function delete($delete) {
			try {
				$query = mysqli_query($this->conn, $delete);
				if(!$query) echo mysqli_error($this->conn);
				else return true;
			}
			catch (Exception $e) {
                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                
                return false;
			}
		}
	}
?>