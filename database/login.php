<?php

session_start();
require_once("../classes/class_conexao_padrao.php");
require_once("../classes/class_conexao_empresa.php");
require_once("../classes/class_queryHelper.php");
require_once("../classes/class_log_alteracao.php");

$log = new LogAlteracao();
$con_p = new ConexaoPadrao();
    $con_p = $con_p->conecta();
    $helper_p = new QueryHelper($con_p);

if(!isset($_GET['login'])) { 
    $_SESSION['msg'] .= 'Oooops... você não pode acessar esta página agora';
    header('Location: ../login.php');
    die();
}

    $email = addslashes($_REQUEST['email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = 'Dados inválidos';
        header('Location: ../login.php');
        mysqli_close($conn);
        die();
    }

    if(!isset($_COOKIE['staffast_login_email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    }

include('../include/connect.php');

$select = $helper_p->select("SELECT * FROM tbl_usuario WHERE usu_email = '$email'", 1);

if(mysqli_num_rows($select) == 0) {
    $_SESSION['msg'] = 'O e-mail inserido não consta como usuário do Staffast';
    unset($_COOKIE['staffast_login_email']);
    header('Location: ../login.php');
    die();
} else if (mysqli_num_rows($select) == 1) {
    $row = mysqli_fetch_assoc($select);
    $usu_id = $row['usu_id'];
    $senha_hash = $row['usu_senha'];
    $token = $row['usu_token'];

    if(!password_verify($senha, $senha_hash) && !isset($_COOKIE['staffast_login_email'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $helper_p->insert("INSERT INTO tbl_acesso (acs_ip, acs_sucesso, usu_id) VALUES ('$ip', 0, '$usu_id')");

        $now = date('Y-m-d H:i:s');
        $select = $helper_p->select("SELECT t2.acs_ip FROM tbl_usuario t1 
            INNER JOIN tbl_acesso t2 WHERE t1.usu_email = '$email' 
            AND t2.acs_sucesso = 0 AND (t2.acs_timestamp 
            BETWEEN DATE_SUB('$now', INTERVAL 24 HOUR) AND '$now')", 1);

            if(mysqli_num_rows($select) >= 5 && mysqli_num_rows($select) < 10) {
                $aviso = 'A CONTA DO USUÁRIO '.$email.' FOI ACESSADA DE FORMA ERRÔNEA'.mysqli_num_rows($select).'VEZES NAS ÚLTIMAS 24 HORAS';
                $helper_p->insert("INSERT INTO tbl_aviso (avi_descricao) VALUES ('$aviso')");
                $_SESSION['msg'] = 'Os dados são inválidos. Essa foi a <b>'.mysqli_num_rows($select).'ª</b> tentativa frustrada 
                em 24 horas. Na 10ª vez, uma nova senha será gerada automaticamente e enviada ao seu e-mail';
                header('Location: ../login.php');
                die();
            } else if (mysqli_num_rows($select) >= 10) {
                $newSenha = rand(100000, 999999);
                $newSenha_hash = password_hash($newSenha, PASSWORD_DEFAULT);
                $helper_p->update("UPDATE tbl_usuario SET usu_senha = '$newSenha_hash', 
                usu_ultima_alteracao_senha = '$now' WHERE usu_email = '$email'");
                
                $subject = "Sua conta do Staffast foi bloqueada";
                $mailTo = $email;
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: Staffast <suporte@sistemastaffast.com>";
                $txt = '<h3>Recupere sua conta</h3>
                    <p>Olá, '.$email. '.</p>
                    <p>Sua conta no Staffast foi acessada 10 vezes com a senha incorreta e por isso nós 
                    alteramos a sua senha de forma automática, para sua segurança. Segue abaixo a sua nova senha de acesso.</p>
                    <p><b>Novos dados de acesso</b></p>
                    <p>E-mail: '.$email.'</p>
                    <p>Senha: '.$newSenha.'</p>
                    <p>É muito importante que você crie uma nova senha no próximo acesso, ok?</p>
                    <h6>Por enquanto é só, até mais :)</h6>
                    <small>Suporte da equipe Staffast</small>';
                mail($mailTo, $subject, $txt, $headers);
            }

        $_SESSION['msg'] .= 'Senha inválida';
        header('Location: ../login.php');
        die();
    }

        $usu_id = $row['usu_id'];
        $usu_email = $row['usu_email'];
        $emp_id = $row['emp_id'];

        $row = $helper_p->select("SELECT 
                                    t1.emp_razao_social as nome, 
                                    t1.emp_database as db,
                                    t1.emp_telefone as telefone, 
                                    t1.emp_endereco as endereco, 
                                    t1.emp_data_folha as fechamento, 
                                    t1.emp_tolerancia_atraso as tolerancia,
                                    t1.emp_logotipo as logotipo, 
                                    t2.compet_um as c1, 
                                    t2.compet_dois as c2, 
                                    t2.compet_tres as c3, 
                                    t2.compet_quatro as c4, 
                                    t2.compet_cinco as c5, 
                                    t2.compet_seis as c6, 
                                    t2.compet_sete as c7,
                                    t2.compet_oito as c8, 
                                    t2.compet_nove as c9, 
                                    t2.compet_dez as c10, 
                                    t2.compet_onze as c11,
                                    t2.compet_doze as c12, 
                                    t2.compet_treze as c13, 
                                    t2.compet_quatorze as c14, 
                                    t2.compet_quinze as c15,
                                    t2.compet_dezesseis as c16, 
                                    t2.compet_dezessete as c17, 
                                    t2.compet_dezoito as c18,
                                    t2.compet_dezenove as c19, 
                                    t2.compet_vinte as c20, 
                                    t1.emp_ativo as ativo,
                                    t3.pla_id as pla_id
                                FROM tbl_empresa t1 
                                    INNER JOIN tbl_competencia_empresa t2 
                                        ON t2.emp_id = t1.emp_id  
                                    INNER JOIN tbl_planos t3
                                        ON t3.pla_id = t1.pla_id
                                WHERE t1.emp_id = '$emp_id'", 2);
        $empresa = $row['nome'];
        $telefone = $row['telefone'];
        $endereco = $row['endereco'];
        $database = $row['db'];
        $c1 = $row['c1'];
        $c2 = $row['c2'];
        $c3 = $row['c3'];
        $c4 = $row['c4'];
        $c5 = $row['c5'];
        $c6 = $row['c6'];
        $c7 = $row['c7'];
        $c8 = $row['c8'];
        $c9 = $row['c9'];
        $c10 = $row['c10'];
        $c11= $row['c11'];
        $c12 = $row['c12'];
        $c13 = $row['c13'];
        $c14 = $row['c14'];
        $c15 = $row['c15'];
        $c16 = $row['c16'];
        $c17 = $row['c17'];
        $c18 = $row['c18'];
        $c19 = $row['c19'];
        $c20 = $row['c20'];
        $logotipo = $row['logotipo'];
        $ativo = $row['ativo'];
        $fechamento = $row['fechamento'];
        $tolerancia = $row['tolerancia'];
        $pla_id = $row['pla_id'];

        $plano = "PONTO";
        switch($pla_id) {
            case 1: $plano = "PONTO"; break;
            case 2: $plano = "AVALIACAO"; break;
            case 3: $plano = "REVOLUCAO"; break;
            case 4: $plano = "DOCUMENTO"; break;
        }

        if($ativo == 0) {
            include('../src/meta.php');
            die('
            <div class="container">
                <div class="row">
                    <div class="col-sm" style="text-align: center;">
                        <img src="../img/logo_staffast.png" width="200">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <h3 class="high-text">Desculpe, mas parece que esta empresa está inativa no Staffast. Entre em 
                        contato com o <a href="../suporte/">suporte</a>.</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <h5 class="text">Isto geralmente acontece porque a empresa solicitou o encerramento da parceria com o Staffast ou porque há pendências administrativas. Se você é colaborador, entre em contato com o setor de RH da sua empresa, ok?</h5>
                    </div>
                </div>
            </div>');
        }

        $row = $helper_p->select("SELECT * FROM tbl_campos_avaliacao_gestao WHERE
        emp_id = '$emp_id'", 2);

        $avg1 = $row['um'];
        $avg2 = $row['dois'];
        $avg3 = $row['tres'];
        $avg4 = $row['quatro'];
        $avg5 = $row['cinco'];
        $avg6 = $row['seis'];
        $avg7 = $row['sete'];
        $avg8 = $row['oito'];
        $avg9 = $row['nove'];
        $avg10 = $row['dez'];

        $con_e = new ConexaoEmpresa($database);
            $con_e = $con_e->conecta();
            $helper_e = new QueryHelper($con_e);


        if(!$con_e) {
            $_SESSION['msg'] .= 'Há algo de errado com o banco de dados da empresa '.$empresa.'<br>Por favor, contate o 
            suporte imediatamente';
            header('Location: ../login.php');
            die();
        }

        $select = $helper_e->select("SELECT * FROM tbl_gestor WHERE usu_id = '$usu_id' AND ges_ativo = 1", 1);

        $permissao = "NULL";
        if(mysqli_num_rows($select) == 1) {
            $row = mysqli_fetch_assoc($select);
            $primeiro_nome = $row['ges_primeiro_nome'];
            $nome = $row['ges_nome_completo'];
            $cpf = $row['ges_cpf'];
            if($row['ges_tipo'] == 1) $permissao = "GESTOR-1";
            else $permissao = "GESTOR-2";
        } else {
            $select = $helper_e->select("SELECT * FROM tbl_colaborador WHERE usu_id = '$usu_id' 
            AND col_ativo = 1", 1);
            if(mysqli_num_rows($select) == 1) {
                $row = mysqli_fetch_assoc($select);
                $primeiro_nome = $row['col_primeiro_nome'];
                $nome = $row['col_nome_completo'];
                $cpf = $row['col_cpf'];
                $permissao = "COLABORADOR";
            } else {
                $_SESSION['msg'] = 'Nós não conseguimos encontrar seu cadastro na empresa :( <br>Isso acontece quando 
                o gestor ou colaborador foi desativado no sistema. Procure o responsável pelo RH da sua empresa e, se 
                for necessário, contate o suporte do Staffast';
                header('Location: ../login.php');
                die();
            }
        }

        $query = $helper_p->select("SELECT * FROM tbl_usuario WHERE emp_id = '$emp_id'", 1);
        $usuarios_empresa = mysqli_num_rows($query);

        $_SESSION['login'] = 1;

        $_SESSION['user'] = array(
            'primeiro_nome' => $primeiro_nome,
            'nome_completo' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'usu_id' => $usu_id,
            'permissao' => $permissao);
        
        $_SESSION['empresa'] = array(
            'nome' => $empresa,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'emp_id' => $emp_id,
            'database' => $database,
            'compet_um' => $c1,
            'compet_dois' => $c2,
            'compet_tres' => $c3,
            'compet_quatro' => $c4,
            'compet_cinco' => $c5,
            'compet_seis' => $c6,
            'compet_sete' => $c7,
            'compet_oito' => $c8,
            'compet_nove' => $c9,
            'compet_dez' => $c10,
            'compet_onze' => $c11,
            'compet_doze' => $c12,
            'compet_treze' => $c13,
            'compet_quatorze' => $c14,
            'compet_quinze' => $c15,
            'compet_dezesseis' => $c16,
            'compet_dezessete' => $c17,
            'compet_dezoito' => $c18,
            'compet_dezenove' => $c19,
            'compet_vinte' => $c20,
            'avg_sessao_um' => $avg1,
            'avg_sessao_dois' => $avg2,
            'avg_sessao_tres' => $avg3,
            'avg_sessao_quatro' => $avg4,
            'avg_sessao_cinco' => $avg5,
            'avg_sessao_seis' => $avg6,
            'avg_sessao_sete' => $avg7,
            'avg_sessao_oito' => $avg8,
            'avg_sessao_nove' => $avg9,
            'avg_sessao_dez' => $avg10,
            'fechamento' => $fechamento,
            'tolerancia' => $tolerancia,
            'logotipo'=> $logotipo, 
            'usuarios' => $usuarios_empresa,
            'plano' => $plano);  
            
        $_SESSION['staffast'] = array(
            'logotipo' => 'logo_staffast.png'
        );

        $ip = $_SERVER['REMOTE_ADDR'];
        $helper_p->insert("INSERT INTO tbl_acesso (acs_ip, acs_sucesso, usu_id) 
        VALUES ('$ip', 1, '$usu_id')");

        //Resetar $_COOKIE
        setcookie("staffast_login_email", $email, time()+3600 * 365, "/");

        if(isset($_GET['historicoPonto'])) header('Location: ../empresa/historicoPontos.php');
        else header('Location: ../empresa/home.php');
        die();
    }
?>