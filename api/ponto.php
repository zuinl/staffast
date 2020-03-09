<?php
//API de uso exclusivo do app Staffast para registro de ponto eletrônico
    //Espera-se receber objeto JSON com as informações "email" (string), "hora" (formato HH:MM:SS), latitude (string),
    // longitude (String), tipo (int [1=entrada, 2=pausa, 3=retorno da pausa, 4=saida]), anotacao (string)
    // token (string) 

    //Retornará um objeto com "sucesso" (booleano) e "mensagem" (string)

    require_once '../classes/class_ponto.php';
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_queryHelper.php';

    //Recebendo objeto JSON do POST
    $json = file_get_contents("php://input");

    //Convertendo para objeto PHP
    $dados = json_decode($json);

    $email = $dados->email;
    $hora = $dados->hora;
    $latitude = $dados->latitude;
    $longitude = $dados->longitude;
    $tipo = $dados->tipo;
    $anotacao = $dados->anotacao;
    $token = $dados->token;

    $conexaoP = new ConexaoPadrao();
        $connP = $conexaoP->conecta();
    $helperP = new QueryHelper($connP);

    $msg = "";
    $sucesso = false;

    //Verificar token
    $select = "SELECT 
                t2.token as token 
              FROM tbl_usuario t1 
                INNER JOIN tbl_usuario_token t2 
                    ON t2.usu_id = t1.usu_id 
               WHERE t1.usu_email = '$email' AND t2.token = '$token'";
               
    $query = $helperP->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        $sucesso = false;
        $msg = "Houve um erro ao autenticar o usuário";
        retornar($msg, $sucesso);
    } else {
        $ponto = new Ponto();
        $funcionario = $ponto->identificarFuncionario($email);

        if($funcionario === 2) {
            $sucesso = false;
            $msg = "Não foi possível encontrar o usuário";
            retornar($msg, $sucesso);
        } else if ($funcionario === 3) {
            $sucesso = false;
            $msg = "Não foi possível encontrar o cadastro";
            retornar($msg, $sucesso);
        }

        $cpf = $funcionario['cpf'];
        $database = $funcionario['database'];

        date_default_timezone_set('America/Sao_Paulo');
        $data = date('Y-m-d').' '.$hora;

        $ret = $ponto->registrarPonto($tipo, $data, $cpf, $database, $latitude, $longitude, 1);

        if($ret === true) {
            if(trim($anotacao) != "") $ponto->anotar($cpf, $anotacao, $database);

            $sucesso = true;
            $msg = "Beleza! Seu registro de ponto foi salvo com sucesso";
            retornar($msg, $sucesso);
        } else {
            switch($ret) {
                case 2:
                    $sucesso = false;
                    $msg = "Já existe uma entrada registrada no dia de hoje";
                    retornar($msg, $sucesso);
                case 3:
                    $sucesso = false;
                    $msg = "Houve um erro ao registrar sua entrada";
                    retornar($msg, $sucesso);
                case 4:
                    $sucesso = false;
                    $msg = "Já existe uma saída para pausa registrada para o dia de hoje";
                    retornar($msg, $sucesso);
                case 5:
                    $sucesso = false;
                    $msg = "Houve um erro ao registrar sua saída para pausa";
                    retornar($msg, $sucesso);
                case 6:
                    $sucesso = false;
                    $msg = "Já existe um retorno de pausa registrado para o dia de hoje";
                    retornar($msg, $sucesso);
                case 7:
                    $sucesso = false;
                    $msg = "Houve um erro ao registrar seu retorno da pausa";
                    retornar($msg, $sucesso);
                case 8:
                    $sucesso = false;
                    $msg = "Já existe uma saída registrada no dia de hoje";
                    retornar($msg, $sucesso);
                case 9:
                    $sucesso = false;
                    $msg = "Houve um erro ao registrar sua saída";
                    retornar($msg, $sucesso);
                case 10:
                    $sucesso = false;
                    $msg = "Existe um registro de entrada menos de 1 hora atrás";
                    retornar($msg, $sucesso);
                case 11:
                    $sucesso = false;
                    $msg = "Existe um registro de saída menos de 1 hora atrás";
                    retornar($msg, $sucesso);
            }
        }
    }

    function retornar($msg, $sucesso) {
        $data = array(
            "sucesso" => $sucesso,
            "mensagem" => $msg
        );

        echo json_encode($data);
        die();
    }
?>