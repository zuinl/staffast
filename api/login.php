<?php
//API de uso exclusivo do app Staffast para autenticação de usuário
    //Espera-se receber objeto JSON com as informações "email" e "senha"

    //Retornará um objeto com "sucesso" (booleano), "nome" (string), "empresa" (string)
    //"cpf" (string), "email" (string), "token" (string) e "mensagem" (string)

    require_once '../classes/class_conexao_padrao.php';
    require_once '../classes/class_conexao_empresa.php';
    require_once '../classes/class_queryHelper.php';

    //Recebendo objeto JSON do POST
    $json = file_get_contents("php://input");

    //Convertendo para objeto PHP
    $dados = json_decode($json);

    $email = $dados->email;
    $senha = $dados->senha;

    $conexaoP = new ConexaoPadrao();
        $connP = $conexaoP->conecta();
    $helperP = new QueryHelper($connP);

    $msg = "";
    $nome = "";
    $empresa = "";
    $cpf = "";
    $sucesso = false;
    $token = "";

    //Buscando o usuário e coletando hash de senha
    $select = "SELECT 
                t1.usu_id as usu_id, 
                t1.usu_email as email, 
                t1.usu_senha as senha,
                t2.emp_razao_social as empresa,
                t2.emp_database as db
              FROM tbl_usuario t1
                INNER JOIN tbl_empresa t2 
                    ON t2.emp_id = t1.emp_id AND t2.emp_ativo = 1
              WHERE t1.usu_email = '$email'";
    $query = $helperP->select($select, 1);

    if(mysqli_num_rows($query) == 1) {
        $f = mysqli_fetch_assoc($query);

        $hash = $f['senha'];
        $usu_id = $f['usu_id'];
        $empresa = $f['empresa'];

        if(password_verify($senha, $hash)) {
            $conexaoE = new ConexaoEmpresa($f['db']);
                $connE = $conexaoE->conecta();
            $helperE = new QueryHelper($connE);

            $select = "SELECT 
                        col_nome_completo as nome,
                        col_cpf as cpf
                       FROM tbl_colaborador
                       WHERE usu_id = $usu_id AND col_ativo = 1";
            $query = $helperE->select($select, 1);

            if(mysqli_num_rows($query) == 1) {
                $f_col = mysqli_fetch_assoc($query);

                $nome = $f_col['nome'];
                $cpf = $f_col['cpf'];

                //Checar se já existe token
                $select = "SELECT token FROM tbl_usuario_token WHERE usu_id = $usu_id";
                $query = $helperP->select($select, 1);

                $token = "";
                if(mysqli_num_rows($query) == 0) { //se não existe
                    $token = md5(date('Y-m-d H:i:s'));

                    $insert = "INSERT INTO tbl_usuario_token (usu_id, token) VALUES ($usu_id, '$token')";
                    $helperP->insert($insert);
                } else { //se existe token
                    $fetch = $helperP->select($select, 2);
                    $token = $fetch['token'];
                }

                

                $sucesso = true;
                $msg = "Colaborador encontrado";
                retornar($msg, $nome, $empresa, $cpf, $sucesso, $token);
            } else {
                $select = "SELECT 
                        ges_nome_completo as nome,
                        ges_cpf as cpf
                       FROM tbl_gestor
                       WHERE usu_id = $usu_id AND ges_ativo = 1";
                $query = $helperE->select($select, 1);

                if(mysqli_num_rows($query) == 1) {
                    $f_ges = mysqli_fetch_assoc($query);

                    $nome = $f_ges['nome'];
                    $cpf = $f_ges['cpf'];

                    $token = md5(date('Y-m-d H:i:s'));

                    $delete = "DELETE FROM tbl_usuario_token WHERE usu_id = $usu_id";
                    $helperP->delete($delete);

                    $insert = "INSERT INTO tbl_usuario_token (usu_id, token) VALUES ($usu_id, '$token')";
                    $helperP->insert($insert);

                    $sucesso = true;
                    $msg = "Gestor encontrado";
                    retornar($msg, $nome, $empresa, $cpf, $sucesso, $token);
                } else {
                    $msg = "Não foi possível encontrar o cadastro do funcionário";
                    retornar($msg, $nome, $empresa, $cpf, $sucesso, $token);
                }
            }
        } else {
            $msg = "A senha está incorreta";
            retornar($msg, $nome, $empresa, $cpf, $sucesso, $token);
        }
    } else {
        $msg = "O e-mail inserido não consta no sistema";
        retornar($msg, $nome, $empresa, $cpf, $sucesso, $token);
    }

    function retornar($msg, $nome, $empresa, $cpf, $sucesso, $token) {
        $data = array(
            "sucesso" => $sucesso,
            "nome" => $nome,
            "empresa" => $empresa,
            "cpf" => $cpf,
            "token" => $token,
            "mensagem" => $msg
        );

        echo json_encode($data);
        die();
    }
?>