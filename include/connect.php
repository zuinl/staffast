<?php

    
        $conn = mysqli_connect('108.179.253.15', 'siste002_staffas', 'wanderlustis18', 'siste002_db_staffast');
        //$conn = mysqli_connect('localhost', 'root', '', 'db_staffast'); 
    
        if(!$conn) {
            echo 'Houve um erro ao conectar à base de dados do Staffast';
        }

        if(isset($_SESSION['login'])) {

            $conn_emp = mysqli_connect('108.179.253.15', 'siste002_staffas', 'wanderlustis18', $_SESSION['empresa']['database']); 
    
            if(!$conn_emp) {
                echo 'Houve um erro ao conectar à base de dados da empresa';
            }

        }
    


?>