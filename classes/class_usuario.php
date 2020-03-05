<?php

class Usuario {

    private $email;
    private $senha;
    private $ID;
    private $IDEmpresa;
    private $ultimaAlteracao;

    function cadastrar() {
        
        if(!$this->usuarioExiste($this->email)) {

            require_once('class_conexao_padrao.php');
            require_once('class_queryHelper.php');

            $conn = new ConexaoPadrao();
            $conexao = $conn->conecta();
            $helper = new QueryHelper($conexao);

            $this->senha = password_hash($this->senha, PASSWORD_DEFAULT); 
            $insert = "INSERT INTO tbl_usuario (usu_email, usu_senha, emp_id) VALUES ('$this->email', '$this->senha', '$this->IDEmpresa')";

            if($helper->insert($insert)) {
                echo '<br>Cadastrado com sucesso';
                return true;
            } else {
                echo '<br>Erro ao cadastrar';
                return false;
            }

        } else {
            echo '<br>Já existe um cadastro com esse e-mail';
            return false;
        }

    }

    function deletar() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $delete = "DELETE FROM tbl_usuario WHERE usu_id = '$this->ID'";

        if($helper->delete($delete)) {
            echo 'Deletado com sucesso';
            return true;
        } else {
            echo 'Erro ao deletar usuário';
            return false;
        }

    }

    function atualizarSenha() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);

        $update = "UPDATE tbl_usuario SET usu_senha = '$this->senha', 
        usu_ultima_alteracao_senha = NOW() WHERE usu_id = '$this->ID'";

        if($helper->update($update)) {
            echo 'Senha atualizada com sucesso';
            return true;
        } else {
            echo 'Erro ao atualizar senha';
            return false;
        }

    }

    function atualizarEmail() {

        if($this->usuarioExiste($this->email)) {
            echo 'E-mail já cadastrado';
            return false;
        }

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_usuario SET usu_email = '$this->email' WHERE usu_id = '$this->ID'";

        if($helper->update($update)) {
            echo 'E-mail atualizado com sucesso';
            return true;
        } else {
            echo 'Erro ao atualizar e-mail';
            return false;
        }

    }

    function retornarUsuario() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao();
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT usu_id as id, usu_email as email, usu_senha as senha, emp_id as id_emp, 
        DATE_FORMAT(usu_ultima_alteracao_senha, '%d/%m/%Y %H:%i:%s') as alteracao FROM tbl_usuario WHERE usu_id = '$this->ID'";

        $fetch = $helper->select($select, 2);

        $usuario = new Usuario();
        $usuario->setID($fetch['id']);
        $usuario->setEmail($fetch['email']);
        $usuario->setSenha($fetch['senha']);
        $usuario->setIDEmpresa($fetch['id_emp']);
        $usuario->setUltimaAlteracao($fetch['alteracao']);

        return $usuario;

    }

    function conferirSenhaAtual($senha_atual) {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT usu_id, usu_senha FROM tbl_usuario WHERE usu_id = '$this->ID'";
        
        $fetch = $helper->select($select, 2);

        if(!password_verify($senha_atual, $fetch['usu_senha'])) {
            echo 'A senha atual não confere';
            return false;
        } else {
            return true;
        }

    }

    function usuarioExiste($email) {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT usu_email FROM tbl_usuario WHERE usu_email = '$email'";
        $query = $helper->select($select, 1);
        if(mysqli_num_rows($query) != 0) return true;
        else return false;
    }

    function retornarUltimoUsuario() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conn = new ConexaoPadrao();
        $conexao = $conn->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT usu_id as id FROM tbl_usuario ORDER BY usu_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);
        
        return $fetch['id'];

    }

    function setEmail($email) {
        $this->email = $email;
    } 

    function getEmail() {
        return $this->email;
    } 

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function getSenha() {
        return $this->senha;
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function getID() {
        return $this->ID;
    }

    function setIDEmpresa($IDEmpresa) {
        $this->IDEmpresa = $IDEmpresa;
    }

    function getIDEmpresa() {
        return $this->IDEmpresa;
    }

    function setUltimaAlteracao($ultimaAlteracao) {
        $this->ultimaAlteracao = $ultimaAlteracao;
    }

    function getUltimaAlteracao() {
        return $this->ultimaAlteracao;
    }

}

?>