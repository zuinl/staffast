<?php
//API de uso exclusivo do app Staffast para registro de ponto eletrônico
    //Espera-se receber objeto JSON com as informações "email" (string) e token (string) 

    //Retornará uma array de objetos com "data" (string), "entrada" (string), "pausa" (string), "retorno" (string),
    // saida (string)

    require_once '../classes/class_ponto.php';
    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_conexao_empresa.php';
    require_once '../classes/class_queryHelper.php';

    //Recebendo objeto JSON do POST
    $json = file_get_contents("php://input");

    //Convertendo para objeto PHP
    $dados = json_decode($json);

    $email = $dados->email;
    $token = $dados->token;

    $conexaoP = new ConexaoPadrao();
        $connP = $conexaoP->conecta();
    $helperP = new QueryHelper($connP);
    
    //Verificar token
    $select = "SELECT 
                t2.token as token,
                t1.emp_id as emp_id 
              FROM tbl_usuario t1 
                INNER JOIN tbl_usuario_token t2 
                    ON t2.usu_id = t1.usu_id 
               WHERE t1.usu_email = '$email' AND t2.token = '$token'";
               
    $query = $helperP->select($select, 1);

    if(mysqli_num_rows($query) == 0) {
        retornar();
    } else {
        $f = mysqli_fetch_assoc($query);
        $emp_id = $f['emp_id'];
        //Coletando data de fechamento de folha
        $select = "SELECT emp_data_folha as data FROM tbl_empresa WHERE emp_id = $emp_id";
        $f = $helperP->select($select, 2);
        $dataFechamento = $f['data'];

        $ponto = new Ponto();
        $funcionario = $ponto->identificarFuncionario($email);

        if($funcionario === 2) {
            retornar();
        } else if ($funcionario === 3) {
            retornar();
        }

        $cpf = $funcionario['cpf'];
        $database = $funcionario['database'];

        $ponto = new Ponto();
        $historico = $ponto->retornarHistorico($database, $dataFechamento, $cpf, date('m'), date('Y'));

        echo json_encode($historico);

    }

    function retornar() {
        $array_historico = array();
        $array_historico[0] = array(
            "sucesso" => false,
            "data" => "Sem registros",
            "entrada" => "Sem registros",
            "pausa" => "Sem registros",
            "retorno" => "Sem registros",
            "saida" => "Sem registros"
        );

        echo json_encode($array_historico);
        die();
    }
?>