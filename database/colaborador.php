<?php

include('../include/auth.php');
include('../src/functions.php');
include('../src/meta.php');
require_once('../classes/class_colaborador.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_conexao_empresa.php');
require_once('../classes/class_queryHelper.php');
require_once('../classes/class_log_alteracao.php');
require_once('../classes/class_email.php');
    
$_SESSION['msg'] = "";

if(isset($_GET['novoColaborador'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    // if($_SESSION['empresa']['disponivel'] < 1) {
    //     $_SESSION['msg'] = "O limite de funcionários na sua empresa foi atingido. Contate o suporte do Staffast para saber mais.";
    //     header('Location: ../empresa/novoColaborador.php');
    //     die();
    // }

    $primeiroNome = addslashes($_POST['primeiroNome']);
    $nomeCompleto = addslashes($_POST['primeiroNome'].' '.$_POST['sobrenome']);
    $cpf = $_POST['cpf'];

    //TESTANDO SE HÁ O CPF COMO GESTOR
    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_cpf = '$cpf'";

    $query = $helper->select($select, 1);

    if(mysqli_num_rows($query) > 0) {
        $_SESSION['msg'] = 'Já existe um GESTOR cadastrado com este CPF';
        header('Location: ../empresa/novoGestor.php');
        die();
    }


    $cargo = addslashes($_POST['cargo']);
    $telefone = addslashes($_POST['telefone']);
    $dataNascimento = $_POST['dataNascimento'];
    $sexo = $_POST['sexo'];
    $cep = $_POST['cep'];
    $endereco = addslashes($_POST['endereco']);
    $numero = $_POST['numero'];
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $rg = $_POST['rg'];
    $cnh = $_POST['cnh'];
    $tipoCnh = $_POST['tipoCnh'];
    $filhos = $_POST['filhos'];
    $estadoCivil = $_POST['estadoCivil'];
    $ctps = $_POST['ctps'];
    $nis = $_POST['nis'];
    $deficiencia = addslashes($_POST['deficiencia']);
    $planoMedico = addslashes($_POST['planoMedico']);
    $tipoSanguineo = $_POST['tipoSanguineo'];
    $cartaoSus = $_POST['sus'];
    $alergias = addslashes($_POST['alergias']);
    $medicamentos = addslashes($_POST['medicamentos']);
        isset($_POST['diabetico']) ? $diabetico = 1 : $diabetico = 0;
        isset($_POST['hipertenso']) ? $hipertenso = 1 : $hipertenso = 0;
    $formacao = $_POST['formacao'];
    $apresentacao = addslashes($_POST['apresentacao']);
    $idInterno = $_POST['idInterno'];
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);

    if($_FILES['foto']['name'] != "") {

        $img_nome = $_FILES['foto']['name'];
        $img_tmp = $_FILES['foto']['tmp_name'];
        $img_tipo = strtolower(pathinfo($img_nome,PATHINFO_EXTENSION));
        if($img_tipo == 'jpg' || $img_tipo == 'jpeg') {
            $nome_foto = (string)'colaborador_'.date('Y-m-d').'_'.date('H').'_'.date('m').'_'.date('s').'_'.$img_nome;
            $img_caminho = '../empresa/img/fotos/'.$nome_foto;
            if(move_uploaded_file($img_tmp, $img_caminho)) {
                if(redimencionarImagemJPG($img_caminho, 500, 500, '../empresa/img/fotos/N_'.$nome_foto)) {
                    unlink($img_caminho);
                    $nome_foto = 'N_'.$nome_foto;
                } else {
                    $nome_foto = '';
                    $img_caminho = '';
                }
            } else {
                $nome_foto = '';
                $img_caminho = '';
            }
        } else {
            $nome_foto = '';
            $img_caminho = '';
        }
    } else {
        $nome_foto = '';
        $img_caminho = '';
    }

    $usuario = new Usuario();
    $usuario->setEmail($email);
    $usuario->setSenha($senha);
    $usuario->setIDEmpresa($_SESSION['empresa']['emp_id']);


    if($usuario->cadastrar()) {

        $colaborador = new Colaborador();
        $colaborador->setCpf($cpf);
        $colaborador->setPrimeiroNome($primeiroNome);
        $colaborador->setNomeCompleto($nomeCompleto);
        $colaborador->setDataNascimento($dataNascimento);
        $colaborador->setSexo($sexo);
        $colaborador->setCargo($cargo);
        $colaborador->setTelefone($telefone);
        $colaborador->setCep($cep);
        $colaborador->setEndereco($endereco);
        $colaborador->setNumero($numero);
        $colaborador->setBairro($bairro);
        $colaborador->setCidade($cidade);
        $colaborador->setNis($nis);
        $colaborador->setCtps($ctps);
        $colaborador->setDeficiencia($deficiencia);
        $colaborador->setTipoSanguineo($tipoSanguineo);
        $colaborador->setPlanoMedico($planoMedico);
        $colaborador->setMedicamentos($medicamentos);
        $colaborador->setAlergias($alergias);
        $colaborador->setCartaoSus($cartaoSus);
        $colaborador->setDiabetico($diabetico);
        $colaborador->setHipertenso($hipertenso);
        $colaborador->setRg($rg);
        $colaborador->setCnh($cnh);
        $colaborador->setTipoCnh($tipoCnh);
        $colaborador->setEstadoCivil($estadoCivil);
        $colaborador->setFilhos($filhos);
        $colaborador->setFormacao($formacao);
        $colaborador->setApresentacao($apresentacao);
        $colaborador->setIDInterno($idInterno);
        $colaborador->setFoto($nome_foto);
        $colaborador->setIDUser($usuario->retornarUltimoUsuario());

        if($colaborador->cadastrar($_SESSION['empresa']['database'])) {

            $log = new LogAlteracao();
            $log->setDescricao("Cadastrou colaborador ".$nomeCompleto);
            $log->setIDUser($_SESSION['user']['usu_id']);
            $log->salvar();

            $email = new Email();
            $email->setAssunto("Bem-vindo ao Staffast!");
            $email->setEmailFrom(0);
            $email->setEmailTo($usuario->getEmail());

            $msg = '<h1 class="high-text">Oi, '.$primeiroNome.'</h1>
                    <h2 class="high-text">Você acaba de ser cadastrado como um colaborador 
                    em '.$_SESSION['empresa']['nome'].' :D</h2>
                    <h3 class="text">O Staffast é a plataforma de avaliação e gestão de equipe que sua empresa utiliza</h3>
                    <h3 class="text">Para acessar o Staffast, basta usar seu e-mail <b>'.$email->getEmailTo().'</b> e a senha cadastrada</h3>
                    <a href="https://sistemastaffast.com/staffast/" target="blank_"><button class="button button3">Acesse agora</button></a>
                    <a href="https://sistemastaffast.com/staffast/recuperarSenha.php" target="blank_"><button class="button button1">Recupere sua senha</button></a>
                    <h2 class="destaque-text">Por agora é só :D</h2>
                    <h5 class="text">Equipe do Staffast</h5>';
            $email->setMensagem($msg);
            $email->enviar();

            $_SESSION['empresa']['disponivel'] = $_SESSION['empresa']['disponivel'] - 1;
            $_SESSION['msg'] = 'Colaborador cadastrado com sucesso, juntamente com seu e-mail e senha inseridos';
        } else {
            $usuario->setID($usuario->retornarUltimoUsuario());
            $usuario->deletar();

            $_SESSION['msg'] = 'Houve algum erro ao cadastrar o colaborador. Confira se não existe um colaborador com este CPF cadastrado ou tente mais tarde';
        }

    } else {
        $_SESSION['msg'] = 'Houve um erro ao cadastrar o usuário. Talvez já exista uma conta no Staffast utilizando e-mail inserido';
    }
        header('Location: ../empresa/colaboradores.php');
        die();
        
} else if (isset($_GET['atualiza'])) {

    $cpf = $_POST['id'];

    if($_SESSION['user']['cpf'] != $cpf && $_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $primeiroNome = addslashes($_POST['primeiroNome']);
    $nomeCompleto = addslashes($_POST['sobrenome']);
    $cargo = addslashes($_POST['cargo']);
    $telefone = addslashes($_POST['telefone']);
    $dataNascimento = $_POST['dataNascimento'];
    $sexo = $_POST['sexo'];
    $cep = $_POST['cep'];
    $endereco = addslashes($_POST['endereco']);
    $numero = $_POST['numero'];
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $rg = $_POST['rg'];
    $cnh = $_POST['cnh'];
    $tipoCnh = $_POST['tipoCnh'];
    $filhos = $_POST['filhos'];
    $estadoCivil = $_POST['estadoCivil'];
    $ctps = $_POST['ctps'];
    $nis = $_POST['nis'];
    $deficiencia = addslashes($_POST['deficiencia']);
    $planoMedico = addslashes($_POST['planoMedico']);
    $tipoSanguineo = $_POST['tipoSanguineo'];
    $cartaoSus = $_POST['sus'];
    $alergias = addslashes($_POST['alergias']);
    $medicamentos = addslashes($_POST['medicamentos']);
        isset($_POST['diabetico']) ? $diabetico = 1 : $diabetico = 0;
        isset($_POST['hipertenso']) ? $hipertenso = 1 : $hipertenso = 0;
    $formacao = addslashes($_POST['formacao']);
    $apresentacao = addslashes($_POST['apresentacao']);
    $idInterno = $_POST['idInterno'];

    if($_FILES['foto']['name'] != "") {

        $img_nome = $_FILES['foto']['name'];
        $img_tmp = $_FILES['foto']['tmp_name'];
        $img_tipo = strtolower(pathinfo($img_nome,PATHINFO_EXTENSION));
        if($img_tipo == 'jpg' || $img_tipo == 'jpeg') {
            $nome_foto = (string)'colaborador_'.date('Y-m-d').'_'.date('H').'_'.date('m').'_'.date('s').'_'.$img_nome;
            $img_caminho = '../empresa/img/fotos/'.$nome_foto;
            if(move_uploaded_file($img_tmp, $img_caminho)) {
                if(redimencionarImagemJPG($img_caminho, 500, 500, '../empresa/img/fotos/N_'.$nome_foto)) {
                    unlink($img_caminho);
                    $nome_foto = 'N_'.$nome_foto;
                    unlink('../empresa/img/fotos/'.$_POST['foto_atual']);
                } else {
                    $nome_foto = $_POST['foto_atual'];
                    $img_caminho = '';
                }
            } else {
                $nome_foto = $_POST['foto_atual'];
                $img_caminho = '';
            }
        } else {
            $nome_foto = $_POST['foto_atual'];
            $img_caminho = '';
        }
    } else {
        $nome_foto = $_POST['foto_atual'];
        $img_caminho = '';
    }

    $colaborador = new Colaborador();
    $colaborador->setCpf($cpf);
    $colaborador->setprimeiroNome($primeiroNome);
    $colaborador->setNomeCompleto($nomeCompleto);
    $colaborador->setDataNascimento($dataNascimento);
    $colaborador->setSexo($sexo);
    $colaborador->setCargo($cargo);
    $colaborador->setTelefone($telefone);
    $colaborador->setCep($cep);
    $colaborador->setEndereco($endereco);
    $colaborador->setNumero($numero);
    $colaborador->setBairro($bairro);
    $colaborador->setCidade($cidade);
    $colaborador->setNis($nis);
    $colaborador->setCtps($ctps);
    $colaborador->setDeficiencia($deficiencia);
    $colaborador->setTipoSanguineo($tipoSanguineo);
    $colaborador->setPlanoMedico($planoMedico);
    $colaborador->setMedicamentos($medicamentos);
    $colaborador->setAlergias($alergias);
    $colaborador->setCartaoSus($cartaoSus);
    $colaborador->setDiabetico($diabetico);
    $colaborador->setHipertenso($hipertenso);
    $colaborador->setRg($rg);
    $colaborador->setCnh($cnh);
    $colaborador->setTipoCnh($tipoCnh);
    $colaborador->setEstadoCivil($estadoCivil);
    $colaborador->setFilhos($filhos);
    $colaborador->setFormacao($formacao);
    $colaborador->setApresentacao($apresentacao);
    $colaborador->setIDInterno($idInterno);
    $colaborador->setFoto($nome_foto);

    if($colaborador->atualizar($_SESSION['empresa']['database'])) {

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou cadastro de colaborador ".$nomeCompleto);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Colaborador atualizado com sucesso';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar o colaborador';
    }

    header('Location: ../empresa/perfilColaborador.php?id='.base64_encode($cpf));
    die(); 

} else if (isset($_GET['desativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $col_cpf = base64_decode($_GET['id']);

    $colaborador = new Colaborador();

    $colaborador->setCpf($col_cpf);

    $colaborador->desativarColaborador($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Desativou colaborador ".$col_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Colaborador desativado com sucesso';

    header('Location: ../empresa/perfilColaborador.php?id='.base64_encode($col_cpf));
    die();

} else if (isset($_GET['reativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $col_cpf = base64_decode($_GET['id']);

    $colaborador = new Colaborador();

    $colaborador->setCpf($col_cpf);

    $colaborador->reativarColaborador($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Reativou colaborador ".$col_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Colaborador reativo com sucesso';

    header('Location: ../empresa/perfilColaborador.php?id='.base64_encode($col_cpf));
    die();

} else if (isset($_GET['excluir'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $col_cpf = base64_decode($_GET['id']);

    $colaborador = new Colaborador();

    $colaborador->setCpf($col_cpf);

    $colaborador->deletar($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Deletou colaborador ".$col_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Colaborador deletado com sucesso';

    header('Location: ../empresa/colaboradoresDesativados.php');
    die();

}



//FUNÇÕES
function redimencionarImagemJPG($imagem, $largura = 500, $altura = 500, $caminho_novo){
    // Cria um identificador para nova imagem
    $imagem_original = imagecreatefromjpeg($imagem);
    
    // Salva o tamanho antigo da imagem
    list($largura_antiga, $altura_antiga) = getimagesize($imagem);
    
    
    // Cria uma nova imagem com o tamanho indicado
    // Esta imagem servirá de base para a imagem a ser reduzida
    $imagem_tmp = imagecreatetruecolor($largura, $altura);
    
    // Faz a interpolação da imagem base com a imagem original
    imagecopyresampled($imagem_tmp, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_antiga, $altura_antiga);
    
    // Salva a nova imagem
    $resultado = imagejpeg($imagem_tmp, $caminho_novo);
    
    // Libera memoria
    imagedestroy($imagem_original);
    imagedestroy($imagem_tmp);
    
    if($resultado)
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>