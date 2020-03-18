<?php

include('../include/auth.php');
include('../src/functions.php');
require_once('../classes/class_gestor.php');
require_once('../classes/class_colaborador.php');
require_once('../classes/class_usuario.php');
require_once('../classes/class_email.php');
require_once('../classes/class_log_alteracao.php');

    if($_SESSION['user']['permissao'] == "COLABORADOR") {
        include('../include/acessoNegado.php');
        die();
    }
    
$_SESSION['msg'] = "";

if(isset($_GET['novoGestor'])) {

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
    $dataNascimento = $_POST['dataNascimento'];
    $sexo = $_POST['sexo'];
    $cpf = $_POST['cpf'];
    $cargo = addslashes($_POST['cargo']);
    $linkedin = addslashes($_POST['linkedin']);
    $telefone = addslashes($_POST['telefone']);
    $telefoneProfissional = addslashes($_POST['telefoneP']);
    $ramal = addslashes($_POST['ramal']);
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
    $alergias = addslashes($_POST['alergias']);
    $medicamentos = addslashes($_POST['medicamentos']);
    $cartaoSus = $_POST['sus'];
        isset($_POST['diabetico']) ? $diabetico = 1 : $diabetico = 0;
        isset($_POST['hipertenso']) ? $hipertenso = 1 : $hipertenso = 0;
    $tipoSanguineo = $_POST['tipoSanguineo'];
    $formacao = addslashes($_POST['formacao']);
    $apresentacao = addslashes($_POST['apresentacao']);
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    $permissao = $_POST['permissao'];
    $idInterno = $_POST['idInterno'];

    if($_FILES['foto']['name'] != "") {

        $img_nome = $_FILES['foto']['name'];
        $img_tmp = $_FILES['foto']['tmp_name'];
        $img_tipo = strtolower(pathinfo($img_nome,PATHINFO_EXTENSION));
        if($img_tipo == 'jpg' || $img_tipo == 'jpeg') {
            $nome_foto = (string)'gestor_'.date('Y-m-d').'_'.date('H').'_'.date('m').'_'.date('s').'_'.$img_nome;
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

        $gestor = new Gestor();
        $gestor->setCpf($cpf);
        $gestor->setPrimeiroNome($primeiroNome);
        $gestor->setNomeCompleto($nomeCompleto);
        $gestor->setDataNascimento($dataNascimento);
        $gestor->setSexo($sexo);
        $gestor->setCargo($cargo);
        $gestor->setLinkedin($linkedin);
        $gestor->setTelefone($telefone);
        $gestor->setTelefoneProfissional($telefoneProfissional);
        $gestor->setRamal($ramal);
        $gestor->setCep($cep);
        $gestor->setEndereco($endereco);
        $gestor->setNumero($numero);
        $gestor->setBairro($bairro);
        $gestor->setCidade($cidade);
        $gestor->setNis($nis);
        $gestor->setCtps($ctps);
        $gestor->setDeficiencia($deficiencia);
        $gestor->setTipoSanguineo($tipoSanguineo);
        $gestor->setPlanoMedico($planoMedico);
        $gestor->setMedicamentos($medicamentos);
        $gestor->setAlergias($alergias);
        $gestor->setCartaoSus($cartaoSus);
        $gestor->setDiabetico($diabetico);
        $gestor->setHipertenso($hipertenso);
        $gestor->setRg($rg);
        $gestor->setCnh($cnh);
        $gestor->setTipoCnh($tipoCnh);
        $gestor->setEstadoCivil($estadoCivil);
        $gestor->setFilhos($filhos);
        $gestor->setFormacao($formacao);
        $gestor->setApresentacao($apresentacao);
        $gestor->setTipo($permissao);
        $gestor->setIDInterno($idInterno);
        $gestor->setFoto($nome_foto);
        $gestor->setIDUser($usuario->retornarUltimoUsuario());

        if($gestor->cadastrar($_SESSION['empresa']['database'])) {
            $sendTo = $email;
            $email = new Email();
            $email->setEmailFrom(0);
            $email->setEmailTo($sendTo);
            $email->setAssunto("Bem-vindo ao Staffast!");
            $msg = '
                        <h1 class="high-text">Bem-vindo à '.$_SESSION['empresa']['nome'].' :D</h1>
                        <h2 class="high-text">Você acaba de ser cadastrado como gestor no Staffast</h2>
                        <h3 class="text">Para acessar a plataforma, basta usar este e-mail ('.$sendTo.') 
                        e a senha cadastrada. Se você não souber a senha, recupere-a abaixo.</h3>
                        <a href="https://sistemastaffast.com/staffast/recuperarSenha.php"><button class="button button3">Recuperar senha</button></a>
                        <a href="https://sistemastaffast.com/staffast/"><button class="button button1">Ir ao Staffast</button></a>
                        <h2 class="destaque-text">Por agora é só :D</h2>
                        <h5 class="text">Equipe do Staffast</h5>';
            $email->setMensagem($msg);
            $email->enviar();

            $log = new LogAlteracao();
            $log->setDescricao("Cadastrou gestor ".$nomeCompleto);
            $log->setIDUser($_SESSION['user']['usu_id']);
            $log->salvar(); 

            $_SESSION['empresa']['disponivel'] = $_SESSION['empresa']['disponivel'] - 1;
            $_SESSION['msg'] = 'Gestor cadastrado com sucesso, juntamente com seu e-mail e senha inseridos';
        } else {
            unlink($img_caminho);
            $usuario->setID($usuario->retornarUltimoUsuario());
            $usuario->deletar();
            $_SESSION['msg'] = 'Houve algum erro ao cadastrar o gestor. Confira se não existe um gestor com este CPF cadastrado ou tente mais tarde';
        }

        if(isset($_POST['isCol']) && $_POST['isCol'] == "1") {
            require_once("../classes/class_colaborador.php");

            $colaborador = new Colaborador();
            $colaborador->importarGestor($_SESSION['empresa']['database'], $cpf);
        }

    } else {
        $_SESSION['msg'] = 'Houve um erro ao cadastrar o usuário. Talvez já exista uma conta no Staffast utilizando e-mail inserido';
    }

        header('Location: ../empresa/perfilGestor.php?id='.base64_encode($cpf));
        
} else if (isset($_GET['atualiza'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1" && ($_SESSION['user']['permissao'] == "GESTOR-2" && $_SESSION['user']['cpf'] != $_POST['id'])) {
        include('../include/acessoNegado.php');
        die();
    }

    $cpf = $_POST['id'];
    $primeiroNome = addslashes($_POST['primeiroNome']);
    $nomeCompleto = addslashes($_POST['sobrenome']);
    $dataNascimento = $_POST['dataNascimento'];
    $sexo = $_POST['sexo'];
    $cargo = addslashes($_POST['cargo']);
    $linkedin = addslashes($_POST['linkedin']);
    $telefone = addslashes($_POST['telefone']);
    $telefoneProfissional = addslashes($_POST['telefoneP']);
    $ramal = addslashes($_POST['ramal']);
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
    $alergias = addslashes($_POST['alergias']);
    $medicamentos = addslashes($_POST['medicamentos']);
    $cartaoSus = $_POST['sus'];
        isset($_POST['diabetico']) ? $diabetico = 1 : $diabetico = 0;
        isset($_POST['hipertenso']) ? $hipertenso = 1 : $hipertenso = 0;
    $tipoSanguineo = $_POST['tipoSanguineo'];
    $formacao = addslashes($_POST['formacao']);
    $apresentacao = addslashes($_POST['apresentacao']);
    $permissao = $_POST['permissao'];
    $idInterno = $_POST['idInterno'];

    if($_FILES['foto']['name'] != "") {

        $img_nome = $_FILES['foto']['name'];
        $img_tmp = $_FILES['foto']['tmp_name'];
        $img_tipo = strtolower(pathinfo($img_nome,PATHINFO_EXTENSION));
        if($img_tipo == 'jpg' || $img_tipo == 'jpeg') {
            $nome_foto = (string)'gestor_'.date('Y-m-d').'_'.date('H').'_'.date('m').'_'.date('s').'_'.$img_nome;
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

    $gestor = new Gestor();
    $gestor->setCpf($cpf);
    $gestor->setprimeiroNome($primeiroNome);
    $gestor->setNomeCompleto($nomeCompleto);
    $gestor->setDataNascimento($dataNascimento);
    $gestor->setSexo($sexo);
    $gestor->setCargo($cargo);
    $gestor->setLinkedin($linkedin);
    $gestor->setTelefone($telefone);
    $gestor->setTelefoneProfissional($telefoneProfissional);
    $gestor->setRamal($ramal);
    $gestor->setRamal($ramal);
    $gestor->setCep($cep);
    $gestor->setEndereco($endereco);
    $gestor->setNumero($numero);
    $gestor->setBairro($bairro);
    $gestor->setCidade($cidade);
    $gestor->setNis($nis);
    $gestor->setCtps($ctps);
    $gestor->setDeficiencia($deficiencia);
    $gestor->setTipoSanguineo($tipoSanguineo);
    $gestor->setPlanoMedico($planoMedico);
    $gestor->setMedicamentos($medicamentos);
    $gestor->setAlergias($alergias);
    $gestor->setCartaoSus($cartaoSus);
    $gestor->setDiabetico($diabetico);
    $gestor->setHipertenso($hipertenso);
    $gestor->setRg($rg);
    $gestor->setCnh($cnh);
    $gestor->setTipoCnh($tipoCnh);
    $gestor->setEstadoCivil($estadoCivil);
    $gestor->setFilhos($filhos);
    $gestor->setFormacao($formacao);
    $gestor->setApresentacao($apresentacao);
    $gestor->setTipo($permissao);
    $gestor->setIDInterno($idInterno);
    $gestor->setFoto($nome_foto);

    if($gestor->atualizar($_SESSION['empresa']['database'])) {

        $log = new LogAlteracao();
        $log->setDescricao("Atualizou gestor ".$nomeCompleto);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $log = new LogAlteracao();
        $log->setDescricao("A permissão de ".$nomeCompleto." é ".$permissao);
        $log->setIDUser($_SESSION['user']['usu_id']);
        $log->salvar();

        $_SESSION['msg'] = 'Gestor atualizado com sucesso';
    } else {
        $_SESSION['msg'] = 'Erro ao atualizar o gestor';
    }

    header('Location: ../empresa/perfilGestor.php?id='.base64_encode($cpf));
    die();

} else if (isset($_GET['desativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $ges_cpf = base64_decode($_GET['id']);

    $gestor = new Gestor();

    $gestor->setCpf($ges_cpf);

    $gestor->desativarGestor($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Desativou gestor ".$ges_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Gestor desativado com sucesso';

    header('Location: ../empresa/perfilGestor.php?id='.base64_encode($ges_cpf));
    die();

} else if (isset($_GET['reativa'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $ges_cpf = base64_decode($_GET['id']);

    $gestor = new Gestor();

    $gestor->setCpf($ges_cpf);

    $gestor->reativarGestor($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Reativou gestor ".$ges_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Gestor reativo com sucesso';

    header('Location: ../empresa/perfilGestor.php?id='.base64_encode($ges_cpf));
    die();

} else if (isset($_GET['importar'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $ges_cpf = base64_decode($_GET['id']);

    $colaborador = new Colaborador();

    $colaborador->importarGestor($_SESSION['empresa']['database'], $ges_cpf);

    $log = new LogAlteracao();
    $log->setDescricao("Importou gestor ".$ges_cpf." para colaborador");
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Gestor importado com sucesso e agora é também um colaborador';

    header('Location: ../empresa/perfilGestor.php?id='.base64_encode($ges_cpf));
    die();

} else if (isset($_GET['excluir'])) {

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include('../include/acessoNegado.php');
        die();
    }

    $ges_cpf = base64_decode($_GET['id']);

    $gestor = new Gestor();

    $gestor->setCpf($ges_cpf);

    $gestor->deletar($_SESSION['empresa']['database']);

    $log = new LogAlteracao();
    $log->setDescricao("Deletou gestor ".$ges_cpf);
    $log->setIDUser($_SESSION['user']['usu_id']);
    $log->salvar();

    $_SESSION['msg'] = 'Gestor deletado com sucesso';

    header('Location: ../empresa/gestoresDesativados.php');
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