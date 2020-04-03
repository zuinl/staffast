<?php
//API de uso exclusivo do app Staffast para consulta de listas de documentos
    //Espera-se receber objeto JSON com as informações "email" (string) e "token" (string) 

    //Retornará uma array de objetos com "sucesso" (booleano), "autorizado" (booleano), "titulo" (string), "tipo" (string), 
    // "data" (string), "remetente" (string), "link" (string)

    require_once '../classes/class_gestor.php';
    require_once '../classes/class_documento.php';
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
        retornar(false, false, "O usuário não foi autenticado");
    } else {
        $f = mysqli_fetch_assoc($query);
        $emp_id = $f['emp_id'];

        //Checando se a empresa tem permissão de plano pra documentos
        $select = "SELECT pla_id as id FROM tbl_empresa WHERE emp_id = $emp_id";
        $fetch = $helperP->select($select, 2);

        if((int)$fetch['id'] === 1) { //se for o Staffast Ponto
            retornar(true, false, "Desculpe, mas sua empresa não tem acesso ao módulo Documentos.");
        }

        $ponto = new Ponto();
        $funcionario = $ponto->identificarFuncionario($email);

        if($funcionario === 2) {
            retornar(false, true, "Não foi possível encontrar o usuário");
        } else if ($funcionario === 3) {
            retornar(false, true, "Não foi possível encontrar o cadastro");
        }

        $cpf = $funcionario['cpf'];
        $database = $funcionario['database'];

        $conexaoE = new ConexaoEmpresa($database);
            $connE = $conexaoE->conecta();
        $helperE = new QueryHelper($connE);

        $select = "SELECT
                    DISTINCT doc_id as id
                   FROM tbl_documento_dono
                   WHERE cpf = '$cpf'
                   ORDER BY doc_id DESC";
        $query = $helperE->select($select, 1);

        $i = 0;
        $array_docs = array();

        while($f = mysqli_fetch_assoc($query)) {
            $doc = new Documento();
            $doc->setID($f['id']);
            $doc = $doc->retornarDocumento($database);

            $gestor = new Gestor();
            $gestor->setCpf($doc->getCpfGestor());
            $gestor = $gestor->retornarGestor($database);

            $array_docs[$i] = array(
                "sucesso" => true,
                "autorizado" => true,
                "titulo" => $doc->getTitulo(),
                "tipo" => $doc->getTipo(),
                "data" => $doc->getDataUpload(),
                "remetente" => $gestor->getNomeCompleto(),
                "link" => "https://sistemastaffast.com/staffast/api/documentoDownload.php?id=".base64_encode($doc->getID())."&token=".base64_encode($token)."&data=".base64_encode(date('Y-m-d')),
                "mensagem" => "Documento encontrado com sucesso"
            );

            $i++;
        }

        echo json_encode($array_docs);

    }


    function retornar($sucesso, $autorizado, $mensagem) {
        $array_docs = array();
        $array_docs[0] = array(
            "sucesso" => $sucesso,
            "autorizado" => $autorizado,
            "titulo" => "",
            "tipo" => "",
            "data" => "",
            "remetente" => "",
            "link" => "",
            "mensagem" => $mensagem
        );

        echo json_encode($array_docs);
        die();
    }
?>